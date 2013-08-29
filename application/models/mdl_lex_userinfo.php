<?php if(!defined('BASEPATH')) exit('No direct access is allowed');

class Mdl_lex_userinfo extends MY_Model {

    public $table               = 'lex_userinfo';
    public $primary_key         = 'uid';
    public $user_credentials    = array();

	function __construct()
    {
        parent::__construct();
	}


    public function validation_rules()
    {
        return array(
            'first_name'      => array(
                'field' => 'first_name',
                'label' => $this->lang->line('first_name'),
                'rules' => 'trim|required|strip_tags|min_length[3]|max_length[15]'
            ),
            'last_name'      => array(
                'field' => 'last_name',
                'label' => $this->lang->line('last_name'),
                'rules' => 'trim|required|strip_tags|strtoupper|min_length[3]|max_length[20]'
            ),
            'email'      => array(
                'field' => 'email',
                'label' => $this->lang->line('email'),
                'rules' => 'trim|required|valid_email'
            ),
            'password'      => array(
                'field' => 'password',
                'label' => $this->lang->line('password'),
                'rules' => 'trim|required|strip_tags|min_length[7]|matches[passconf]|sha1'
            )

		);

	}


    public function validate_user($email_address, $password)
    { // used with login/index
        $authenticated = FALSE;

        $query = $this->db
                ->select('uid, first_name, last_name, email_address, password, status')
                ->where('email_address', $email_address)
                ->where('password', sha1($password))
                ->where('status >', 0)
                ->get($this->table);

        if ($query->num_rows() == 1)
        {
            $this->user_credentials = $query->row_array();

            $this->set_session();

            $authenticated = TRUE;
        }

        return $authenticated;
    }


    function set_session()
    { // used with usermodel->validate_user
        $this->session->set_userdata(array( 
                'user_id' => $this->user_credentials['uid'],
                'first_name' => $this->user_credentials['first_name'],
                'last_name' => $this->user_credentials['last_name'],
                'email_address' => $this->user_credentials['email_address'],
                'status' => $this->user_credentials['status'],
                'logged_in' => TRUE
            ));
    }

/*
    function user_list() 
    {
        $this->db
            ->select('uid, first_name, last_name, initials, sitename, username, email, telephone, status, roleName')
            ->join('roles', 'users.status = roles.ID')
            ->join('sites', 'sites.siteid = users.site_id')
            ->where('site_id', $this->session->userdata('site_id'))
            ->order_by('status', 'asc');
        // This prevents anyone other than SuperUser and Administrator from viewing and/or modifying high level accounts
        if ($this->authorize->userRole >= 4)    // SM or below
        {
            $this->db->where('status >=', 4);
        }
        return $this;
    }


    function users_by_status($roles_to_retrieve)
    { // used with student/form function

        $this->db
            ->where_in('status', $roles_to_retrieve)
            ->where('site_id', $this->session->userdata('site_id'));
        return $this;
    }
*/

}
