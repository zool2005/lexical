<?php if(!defined('BASEPATH')) exit('No direct access is allowed');

class Users extends CI_Controller {

	public $default_redirect	=	'users/view';

	public function __construct()
	{
        parent::__construct();
        // Checked
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="fvalerror">', '</p>');
        $this->load->model('mdl_users');


        // Replace
		$this->load->model('usermodel');
		$this->load->model('sitesmodel');
		
	}


	public function index()
	{	// v10
		redirect($this->default_redirect, 'refresh');
	}


    public function form($id = NULL)
    {	// v10
        $this->authorize->permission_check('create_user');

		if ($this->input->post('btn_cancel'))
		{
			redirect($this->default_redirect, 'refresh');
		}

        if ($this->input->post('btn_submit') && $this->mdl_users->run_validation())
        {
			$this->mdl_users->save();
			$this->session->set_flashdata(info_msg('msuccess', $this->lang->line('record_create_success')));
			redirect($this->default_redirect, 'refresh');
        }

        if ($this->input->post('btn_update') && $this->mdl_users->run_validation('validation_rules_for_update'))
        {
			$this->mdl_users->save($id);
			$this->session->set_flashdata(info_msg('msuccess', $this->lang->line('record_update_success')));
			redirect($this->default_redirect, 'refresh');
        }

        $data['form_open'] = 'users/form/'.$id;
        $data['btn_type'] = 'btn_submit';

		$data['page_title'] = $this->lang->line('title_register_user');
		$data['headline'] = $this->lang->line('headline_register_user');

        if ($id and !$this->input->post('btn_submit'))
        {
            $this->mdl_users->prep_form($id);

	        $data['btn_type'] = 'btn_update';

			$data['page_title'] = $this->lang->line('title_update_user');
			$data['headline'] = $this->lang->line('headline_update_user');
        }

		$this->load->model('mdl_roles');
		$roles = $this->mdl_roles->roles_dropdown()->get();
		$data['roles_dropdown'] = create_key_value_pairs($roles, array('ID', 'roleName'), TRUE);

		$this->load->model('mdl_sites');
		$sites = $this->mdl_sites->sites_dropdown()->get();
		$data['sites_dropdown'] = create_key_value_pairs($sites, array('siteid', 'sitename'), FALSE);

		$data['include'] = '/users/userform';
		$this->load->view('/template', $data);

    }


	function view() 
	{ // v10
		$this->authorize->permission_check('create_user'); 

		$this->load->library('table');
		
		$t_head = array('Role','Site','First Name','Last Name','Username','Email','Telephone','Action');
		$this->table->set_heading($t_head);

		$user_list = $this->mdl_users->user_list()->get()->result();

		foreach ($user_list as $row)
        {
			$ul[] = array(
				$row->roleName,
				$row->sitename,
				$row->first_name,
				$row->last_name,
				$row->username,
				safe_mailto($row->email, $row->email),
				$row->telephone,
				anchor("users/form/".$row->uid."/", "Edit", array('class' => 'anchor'))
				);                  
		}

		$data['t_body'] = $this->table->generate($ul);		
		$data['page_title'] = $this->lang->line('title_list_users');
		$data['headline'] = $this->lang->line('headline_list_users');
		$data['include'] = '/users/userlist';		
		$this->load->view('template', $data);
		
	}
	
 


	function launchpad()
	{
		$this->authorize->permission_check('access_site');

		$this->load->library('table');

		$this->load->model('contractsmodel');
		$this->load->model('followupmodel');
		$this->load->model('ffdatesmodel');
		

		$user_id = $this->session->userdata('user_id');
		$user_role = $this->authorize->userRole;

// DAILY CLASS LIST
		$today = date('Y-m-d');
		$classesByDate = array();

		$slots = $this->ffdatesmodel->getClassesForDate($today, $user_id)->result_array();

		if (!empty($slots))
		{
			foreach ($slots as $row)
			{
				$classesByDate[$row['asid']] = array(
							'slot' => $row,
							'reservation' => $this->ffdatesmodel->getReservationsBySlot($row['asid'])
							);
			}
				
		}

		$data['classList'] = $classesByDate;



// END DAILY CLASS LIST



// OUTSTANDING REPORTS FOR SELECTED TEACHER

		$t_head = array(
				'Date & Time',
				'Duration',
				'Activity',
				'Action'
				);

		$outstandingByActivity = array();
		$activityTypes = $this->contractsmodel->listActivityTypes();

			foreach($activityTypes as $row)
			{
				if (is_array($this->followupmodel->getOutstanding($row['actID'], $user_id)))
				{
					$this->table->set_heading($t_head);			
					$outstandingByActivity[] = array (
						'activitytypes_array' => $row,
						'liststudents_array' => $this->table->generate($this->followupmodel->getOutstanding($row['actID'], $user_id))
						);
					$this->table->clear();
				}
	    	}

		$data['outstanding'] = $outstandingByActivity;

// END OUTSTANDING REPORTS

		$data['ug_message'] = ug_message('minfo', $this->lang->line('ug_user_launchpad'));
		$data['page_title'] = $this->lang->line('title_launchpad');
		$data['headline'] = $this->lang->line('headline_launchpad');
		$data['include'] = 'users/launchpad';
		$this->load->view('template', $data);


	}



	
	function iveBeenBlocked() {

		if (!$this->authorize->hasPermission('access_site')) {
			$this->session->set_flashdata('message', $this->authorize->authErrorMessage);
			redirect($this->authorize->authRedirectLocation, 'refresh');
		} 

		$data['page_title'] = 'Ooops - Insufficient Permissions';
		$this->load->view('users/ivebeenblocked', $data);
	
	}

	
	function logout() {
		$this->session->unset_userdata('defaultstu');	
		$this->session->sess_destroy();
		redirect('login/index', 'refresh');
	}	// end logout function









 
	
	function changepw() {

		if (!$this->authorize->hasPermission('user_account_admin')) {
			$this->session->set_flashdata('message', $this->authorize->authErrorMessage);
			redirect($this->authorize->authRedirectLocation, 'refresh');
		} 
		
		if($this->form_validation->run()) {
		
				if($this->usermodel->changepw() == TRUE) {
					$this->session->set_flashdata(info_msg('msuccess', 'Your password has been updated'));
					redirect('/users/changepw');
				} else {
					$this->session->set_flashdata(info_msg('merror', 'Your current password was incorrect, please try again'));
					redirect('/users/changepw');
				}
			
		} else {		
	
			$this->load->view('/users/changepw');
		
		}
		
	}
	
	
	
	function pwreset() {

		// No permission check is necessary for this function but user must supply a valid email address
		
		if ($this->form_validation->run() == FALSE) {
		
			$data['page_title'] = "Password Reminder";
			$this->load->view('/users/pwreset', $data);
			
		} else {

			$this->db->select('uid, first_name, email');
			$this->db->where('email', $this->input->post('email'));
			$this->db->where('username', $this->input->post('username'));
			$this->db->limit(1);
			$query = $this->db->get('users');
			
			// If a valid email address was provided...
			if ($query->num_rows() == 1) {
						
				$row = $query->row();			
				// Retrieve first name and email from result array...
				$first_name = $row->first_name;
				$email = $row->email;

				// Generate new random password for user and assign to variable...
				$newPassword = $this->authorize->generatePassword();
			
				// Hash new password and add to db
				$data = array(
					'password' => sha1($newPassword),
					);
				$this->db->where('uid', $row->uid);
				$this->db->where('username', $this->input->post('username'));
				$this->db->where('email', $this->input->post('email'));
				$this->db->update('users', $data);
				
				// Send new password to user
				$this->load->library('email');
			
				$this->email->from('admin@vsdb.fr', 'VSDB Administrator');
				$this->email->to($email);
				$this->email->subject('VSDB Password Reminder');
				$this->email->message("Your new VSDB Password is $newPassword. You can change your password from the control panel.");

				if (!$this->email->send()) {
					echo $this->email->print_debugger();
				} else {
					redirect('/users/logout', 'redirect');
				}
		
			} else {
				$this->session->set_flashdata(info_msg('merror', 'Unknown User'));
				redirect('/users/pwreset', 'redirect');
			}
		
		}
	
	
	}




}	// end Usercontroller class
