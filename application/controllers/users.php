<?php if(!defined('BASEPATH')) exit('No direct access is allowed');

/*
+-----------------------------------------------------------------------------------------------+
| Lexical, a web-based dictionary management ported to the CodeIgniter framework from 			|
| LexManager, created by Martin Posthumus														|
| Original Website : http://www.veche.net/programming/lexmanager.html 							|
| Original Source Code on GitHub : https://github.com/voikya/LexManager                         |
|                                                                                               |
| Lexical is free and open-source. You may redistribute and/or modify Lexical under the terms | 
| of the GNU General Public  License (GPL) as published by the Free Software Foundation, 		|
| either version 3 of the license or any later version. 										|
|                                                                                               |
| Lexical comes with no warranty for loss of data, as per the GPL3 license.    					|
+-----------------------------------------------------------------------------------------------+
*/

class Users extends CI_Controller {

	public function __construct()
	{
        parent::__construct();

        if ($this->session->userdata('status') != 1)
        {
        	redirect('login/logout', 'refresh');
        }
		
	}


	public function index()
	{
		redirect('users/view', 'refresh');
	}


	public function adm_view_users() 
	{
		$this->load->library('table');
		
		$t_head = array($this->lang->line('val_id'), $this->lang->line('val_first_name'), $this->lang->line('val_last_name'), $this->lang->line('val_email'), $this->lang->line('val_status'), $this->lang->line('action'));
		$this->table->set_heading($t_head);

		$this->load->model('mdl_lex_userinfo');
		$user_list = $this->mdl_lex_userinfo->filter_user_select()->order_by('status', 'asc')->get()->result_array();

		for ($i = 0; $i < count($user_list); $i++)
		{

			if ($user_list[$i]['status'] == 1)
			{
				$user_list[$i]['status'] = $this->lang->line('administrator');
			}
			else 
			{
			 	$user_list[$i]['status'] = $this->lang->line('user');
			}

			$user_list[$i]['action'] = anchor('users/adm_delete_user/'.$user_list[$i]['uid'].'/', $this->lang->line('delete'), 'class="buttonlink"');
		}

		$data['t_body'] = $this->table->generate($user_list);
		$data['page_title'] = $this->lang->line('title_manage_users');
		$data['headline'] = $this->lang->line('headline_manage_users');
		$data['topbar'] = 'adm_nav';
		$data['content'] = 'adm/adm_view_users';
		$this->load->view('template', $data);
		
	}


	public function adm_register_user()
	{
		$this->load->model('mdl_lex_userinfo');
        if ($this->mdl_lex_userinfo->run_validation())
        {
			$this->mdl_lex_userinfo->save();
			$this->session->set_flashdata('message', '<p class="msuccess">'.$this->lang->line('record_create_success').'</p>');
			redirect('users/adm_view_users', 'refresh');
        }

		$data['page_title'] = $this->lang->line('title_register_user');
		$data['headline'] = $this->lang->line('headline_register_user');
		$data['topbar'] = 'adm_nav';
		$data['content'] = 'adm/adm_register_user';
		$this->load->view('template', $data);
	}



	public function adm_delete_user($user_id)
	{
		$user_id = check_int($user_id);

		if ($user_id > 0)
		{
			$this->load->model('mdl_lex_userinfo');

			// 1. Get the status of this user (administrator or user)
			$user_data = $this->mdl_lex_userinfo->get_by_id($user_id);
			// 2. Get number of adminstrators
			$nb_admin = $this->mdl_lex_userinfo->where('status', 1)->get()->num_rows();
			// 3. If this is the primary (only) administrator account, refuse account deletion and display appropriate error message
			if ($user_data->status == 1 && $nb_admin == 1)
			{
				$this->session->set_flashdata('message', '<p class="merror">'.$this->lang->line('cannot_delete_primary_account').'</p>');
				redirect('users/adm_view_users', 'refresh');				
			}
			else
			{
				$this->mdl_lex_userinfo->delete($user_id);
				$this->session->set_flashdata('message', '<p class="msuccess">'.$this->lang->line('record_delete_success').'</p>');
				redirect('users/adm_view_users', 'refresh');
			}

		}

	}


}


