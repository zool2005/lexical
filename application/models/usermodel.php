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
class Usermodel extends CI_Model {

	var $user_credentials;
    public $table               = 'users';
    public $primary_key         = 'users.uid';
    public $default_methods		= array('site', 'order');

	function __construct()
    {
        parent::__construct();

        $this->table = 'users';
        $this->primary_key = 'uid';	
	}


    function set_defaults()
    {
    	$default_methods = $this->default_methods;

    	foreach ($default_methods as $default_method)
    	{
    		$default_method = 'default_' . $default_method;

    		if (method_exists($this, $default_method))
    		{
    			$this->$default_method();
    		}
    	}
    }


    function default_site()
    {
    	$this->db->where('site_id', $this->session->userdata('site_id'));
    	return $this;
    }


    function default_order()
    {
    	$this->db->order_by('status', 'asc');
    	return $this;
    }


	function users_by_status($roles_to_retrieve)
	{ // used with student/addStudent function
		// supercedes getUserDropdown function

		$this->set_defaults();
		return $this->db
			->where_in('status', $roles_to_retrieve)
			->get($this->table);
	}


	public function validate_user($email, $password)
	{ // used with login/index
		$authenticated = FALSE;

		$query = $this->db
				->select('uid, first_name, last_name, email, password, status')
				->where('email', $email)
				->where('password', sha1($password))
				->where('status >', 0)
				->get('users');

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
				'email' => $this->user_credentials['email'],
				'status' => $this->user_credentials['status'],
				'logged_in' => TRUE
			));
	}


// ----------------- NEW ABOVE HERE -------------------------


	function getUsersDropdown($roleID = '')
	{ // needed for ffdates/booking user filter
		$users =  array('' => 'Select One :');
		$this->db->select('uid, first_name, last_name');
		$this->db->where('site_id', $this->session->userdata('site_id'));
		if ($roleID > 0)
		{
			$this->db->where('status', $roleID);
		}
		else
		{
			$this->db->where('status !=', 11);
		}
		$this->db->where('status >', 1);
		$this->db->order_by('status', 'asc');
		$query = $this->db->get('users');

		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$users[$row->uid] = fullname($row->first_name, $row->last_name);
			}
			return $users;
		}
		
	}


	function getUserDataArray()
	{ // needed for ffdates/scheduling for manual SELECT menu creation (jQuery compatibility)
		$this->db->select('uid, first_name, last_name, initials');
		$this->db->where('site_id', $this->session->userdata('site_id'));
		$this->db->where('status !=', 11); // exclude deactivated users
		$this->db->where('status >', 1); // exclude superuser account (00001)
		$this->db->order_by('status', 'asc');
		$query = $this->db->get('users');
		if ($query->num_rows() > 0)
		{
			return $query;
		}

	}




	function getUsersForSort() {	// use to sort most recent follow ups by teacher
		
		$site_id = $this->session->userdata('site_id');
	
		$this->db->select('uid, first_name, last_name');
		$this->db->join('roles', 'users.status = roles.ID', 'left');
		$this->db->where('site_id', $site_id);
		$this->db->where('roleName !=', 'deactivated');
		$this->db->order_by('uid', 'asc');
		$query = $this->db->get('users');
		
		return $query->result_array();
	
	}


	function getUserlist() {
	
		$site_id = $this->session->userdata('site_id');
	
		$this->db->select('uid, first_name, last_name, initials, sitename, username, email, telephone, status, roleName');
		$this->db->join('roles', 'users.status = roles.ID');
		$this->db->join('sites', 'sites.siteid = users.site_id');
		$this->db->where('site_id', $this->session->userdata('site_id'));
		// This prevents anyone other than SuperUser and Administrator from viewing and/or modifying high level accounts
		if ($this->authorize->userRole >= 4)	// SM or below
		{
			$this->db->where('status >=', 4);
		}
		$this->db->order_by('status', 'asc');
		$query = $this->db->get('users');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
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
			
			return $ul;
		}


		return $query->result();

	}	// end get_userlist function


	function registeruser() {
	
		// RECOVER POST DATA FROM FORM SUBMISSION
		$data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => strtoupper($this->input->post('last_name')),
			'initials' => strtoupper($this->input->post('initials')),
			'site_id' => $this->input->post('site_id'),
			'username' => $this->input->post('username'),
			'password' => sha1($this->input->post('password')),
			'telephone' => $this->input->post('telephone'),
			'email' => $this->input->post('email'),
			'status' => $this->input->post('status'),
			);
			
		$this->db->insert('users', $data);

	}


	function modifyUserData() {

		$data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => strtoupper($this->input->post('last_name')),
			'initials' => strtoupper($this->input->post('initials')),
			'site_id' => $this->input->post('site_id'),
			'username' => $this->input->post('username'),
			'telephone' => $this->input->post('telephone'),
			'email' => $this->input->post('email'),
			'status' => $this->input->post('status'),
			);
	
		$this->db->where('uid', $this->input->post('uid'));
		$this->db->update('users', $data);
	
	}



	function getUserData($uid) {	// for data edit
	
		$this->db->where('uid', $uid);
		$query = $this->db->get('users');
		return $query->result();
	
	}
	
	
	function getConsultantDropdown() {

		$consultantArray = array('' => 'Select One :');	
		$this->db->select('uid, first_name, last_name');
		$this->db->join('roles', 'users.status =  roles.ID', 'left');
		$this->db->where('site_id', $this->session->userdata('site_id'));
		$this->db->where("(roleName = 'Consultant' OR roleName = 'Center Manager')");
		
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$consultantArray[$row->uid] = $row->first_name. ' ' .$row->last_name;		
			}				
		}
		return $consultantArray;
	}


// -----------------------------------------------------------------------------
// THIS IS A ONE-OFF FUNCTION TO UPDATE USERS TABLE TO NEW SHA1 PASSWORD HASHING
// -----------------------------------------------------------------------------
/*
	function passwordsToSha() {
	
		$this->db->select('uid, password');
		$query1 = $this->db->get('users');
		
		foreach ($query1->result() as $key) {
		
			$uid[] = $key->uid;
			$password[] = $this->encrypt->decode($key->password);
		
		}
		
		
		for($i=0; $i< count($uid); $i++) {
			$data = array(
				'password' => sha1($password[$i])
				);
			$this->db->where('uid', $uid[$i]);
			$this->db->update('users', $data);
		}
	}
*/
// ---------------------------------------------------------------------------------

	
	function changepw() {
	
	$result = FALSE;

	
	// Retrieve the submitted values via POST
	$curpw = sha1($this->input->post('curpw'));
	
	// Query the database to check that 'curpw' matches password in database
	$this->db->select('password');
	$this->db->where('uid', $this->authorize->userID);
	$query = $this->db->get('users');
		
		if($query->num_rows() == 1) {
		
			$row = $query->row();
			
			if ($curpw == $row->password) {
			
				$newpwdata = array(
					'password' => sha1($this->input->post('newpw')),
				);
		
				$this->db->where('uid', $this->authorize->userID);		
				$this->db->update('users', $newpwdata);			
				$result = TRUE;			

			}
		
		}	
	return $result;
	
	} // end changepw function
	
	
	
/*
|	Start working on user roles and permissions to limit user access to certain functions depending on their access level.
|	The functions provided below are used in conjunction with the authorize library that will be called in each controller
|	to check user permissions for each function and redirect to an error page if the user does not have sufficient
|	permissions.
*/
	
	function addRoleName() {
	
		// Check that the name does not already exist in the db
		$this->db->like('roleName', $this->input->post('roleName'));
		$rolecheck = $this->db->get('roles');
		
		if ($rolecheck->num_rows() > 0) {
			$this->session->set_flashdata(info_msg('merror', 'This Role already exists'));
			redirect('permissions/rolesManager', 'refresh');
		} else {
		// If the user does not exist, add it to the db
			$data = array(
				'roleName' => $this->input->post('roleName')
				);
			$this->db->insert('roles', $data);
		}
		
	}
	
	function getAllRoles() {
		
		$this->db->order_by('ID', 'asc');
		$query = $this->db->get('roles');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$gar[] = array(
					$row->ID,
					$row->roleName,
					anchor("permissions/manageRolePerms/".$row->ID."/","Manage Role Permissions")
					);
			}
		
			return $gar;
		}
		
	}
	
/*	
	function getRolesDropdown() {
		
		$rolearray = array('' => 'Select One :');
		$this->db->select('ID, roleName');
		if ($this->authorize->userRole >= 4)
		{
			$this->db->where('ID >=', 4);
		}
		$this->db->order_by('ID');
		$query = $this->db->get('roles');
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $key) {
					$rolearray[$key->ID] = $key->roleName;				
				}
			
			}
		return $rolearray;
	}
*/

	function addNewPermission() {
	
		$this->db->like('permKey', $this->input->post('permKey'));
		$permcheck = $this->db->get('permissions');
		
		if ($permcheck->num_rows() > 0) {
			$this->session->set_flashdata(info_msg('merror', 'This Permission already exists'));
			redirect('permissions/permissionsManager');
		} else {
			$data = array(
				'permKey' => $this->input->post('permKey'),
				'permName' => $this->input->post('permName')
				);
			$this->db->insert('permissions', $data);
		}
	
	}
	

	function getAllPermissions() {
	
		$this->db->order_by('ID', 'asc');
		$query = $this->db->get('permissions');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$gap[] = array(
					$row->ID,
					$row->permKey,
					$row->permName,
					anchor("permissions/modifyPermission/".$row->ID."/","Modify Permission")
					);
			}
		
			return $gap;
		}
	
	}
	
	
	function setRolePermissions() {

	$permID = $this->input->post('permID');
	$roleID = $this->input->post('roleID');

		// Reset previous permissions to ZERO
		$this->db->where('roleID', $roleID);
		$this->db->delete('role_perms');

		// Then add NEW permissions
             for($i=0; $i< count($this->input->post('permID')); $i++)
             {
			$data = array(
				'roleID' => $roleID,
				'permID' => $permID[$i]
				);
			$this->db->insert('role_perms', $data);

		//	$strSQL = sprintf("REPLACE INTO `role_perms` SET `roleID` = %u AND `permID` = %u", $roleID, $permID[$i]);  
		//	mysql_query($strSQL);		
             }


	

	}



}	// end Usermodel class
