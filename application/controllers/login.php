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
			redirect('lex_admin/index', 'refresh');
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
		$data['topbar'] = 'basic_topbar';
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
				redirect('lex_admin/index', 'refresh');
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


	function about()
	{
		$str = <<<EOD
<div id="about" style="font-size: 11px;">
<h2>Lexical</h2>
<p><b>Lexical</b>, a web-based dictionary management ported to the <a href="http://www.codeigniter.com" target="_blank">CodeIgniter</a> framework from <i>LexManager</i>, created by Martin Posthumus.</p>

<p><a href="http://www.veche.net/programming/lexmanager.html" target="_blank">LexManager Author Website</a><br />
<a href="https://github.com/voikya/LexManager" target="_blank">LexManager Source Code on GitHub</a></p>

<p><b>Lexical</b> is free and open-source. The source code may be redistributed and/or modified freely under the terms of the GNU General Public License (GPL) as published by the Free Software Foundation,
either version 3 of the license or any later version. The source code relating to modifications and derivatives must be made available to end-users.</p>
<p><b>Lexical</b> comes with no warranty for loss of data, as per the GPL3 license.</p>
<p><a href="https://github.com/zool2005/Lexical" target="_blank">Lexical Source Code on GitHub</a></p> 
<p><b>Lexical</b> &copy; Phil FRARY 2013</p>
</div>
EOD;
	echo $str;
	}


}	// end Login class
