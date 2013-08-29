<?php if(!defined('BASEPATH')) exit('No direct access is allowed');

class Login extends CI_Controller {

	var $default_login_redirect;

	public function __construct()
	{
        parent::__construct();

        $this->load->model('mdl_lex_userinfo');
        $this->default_login_redirect = 'lexmanager/index';
	}


	public function index()
	{
		if ($this->session->userdata('status') == 1)
		{	// 1 = ADMINISTRATOR
			redirect('adm_lexmanager/index', 'refresh');
		}
		elseif ($this->session->userdata('status') == 2)
		{	// 2 = NORMAL USER
			redirect('lexmanager/index', 'refresh');				
		}
		else
		{
			$this->show_login(false);				
		}

	}


	public function show_login($show_error = false)
	{
		if ($show_error)
		{
			$data['error_message'] = '<p class="merror">'.$this->lang->line('authentication_error').'</p>';
		}

		$data['page_title'] = $this->lang->line('title_login');
		$data['headline'] = $this->lang->line('headline_login');
		$data['topbar'] = 'empty_topbar';
		$data['content'] = 'users/login';
		$this->load->view('template', $data);
	}


	public function login_user()
	{
		$email_address = clean_string($this->input->post('email_address'));
		$password = clean_string($this->input->post('password'));

		if ($email_address && $password && $this->mdl_lex_userinfo->validate_user($email_address, $password))
		{
			if ($this->session->userdata('status') == 1)
			{	// 1 = ADMINISTRATOR
				redirect('adm_lexmanager/index', 'refresh');
			}
			elseif ($this->session->userdata('status') == 2)
			{	// 2 = NORMAL USER
				redirect('lexmanager/index', 'refresh');				
			}
			else
			{
				// do nothing				
			}
		}
		else
		{
			$this->show_login(true);
		}


	}


	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login/index', 'refresh');
	}


}	// end Login class
