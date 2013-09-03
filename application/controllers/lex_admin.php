<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

class Lex_admin extends CI_Controller {


	public function __construct()
	{
        parent::__construct();

        if ($this->session->userdata('status') != 1)
        {
        	redirect('login/logout', 'refresh');
        }


	}


	function index()
	{
		redirect('lex_admin/manager', 'refresh');
	}

	function manager()
	{

		$this->load->model('mdl_lexinfo');
		$data['lexicons'] = $this->mdl_lexinfo->order_by('Name')->get()->result();

		$data['page_title'] = $this->lang->line('title_lex_admin');
		$data['headline'] = $this->lang->line('headline_lex_admin');
		$data['topbar'] = 'adm_nav';
		$data['content'] = 'adm/manager';
		$this->load->view('template', $data);
	}



	function adm_view_lexicon($lang_ID)
	{
		$this->load->model('mdl_lexinfo');
		$data['lexicons'] = $this->mdl_lexinfo->order_by('Name')->get()->result();
		$data['current_lexicon'] = $this->mdl_lexinfo->get_by_id($lang_ID);
		$data['lang_ID'] = $lang_ID; // make the $lang_ID variable available for the topbar

		$data['page_title'] = $this->lang->line('title_lex_admin');
		$data['headline'] = $this->lang->line('headline_lex_admin');
		$data['topbar'] = 'adm_lexicon_topbar';
		$data['content'] = 'adm/adm_view_lexicon';
		$this->load->view('template', $data);
	}

// To work on
	function adm_lex_viewall($lang_ID, $start_from = 1, $max_entries_displayed = 50)
	{
       	$this->load->library('table');

		$this->load->model('mdl_lexinfo');
		$data['lexicons'] = $this->mdl_lexinfo->order_by('Name')->get()->result();
		$current_lexicon = $this->mdl_lexinfo->get_by_id($lang_ID);
		$data['current_lexicon'] = $current_lexicon;

		$data['page_title'] = $this->lang->line('title_view_all_entries');
		$data['headline'] = $this->lang->line('headline_view_all_entries');

		$data['max_entries_displayed'] = $max_entries_displayed;
		$data['start_from'] = $start_from;
		$data['word_array'] = $this->db->query("SELECT * FROM `" . $current_lexicon->Name . "` LIMIT " . ($start_from - 1) . ", " . $max_entries_displayed . ";");

		$data['lang_ID'] = $lang_ID; // make the $lang_ID variable available for the topbar
		$data['topbar'] = 'adm_lexicon_topbar';
		$data['content'] = 'adm/adm_lex_viewall';
		$this->load->view('template', $data);		
	}

	function adm_lex_newentry($lang_ID)
	{
		$this->load->model('mdl_lexinfo');
		$current_lexicon = $this->mdl_lexinfo->get_by_id($lang_ID);

       	$field_label_array = explode("\n", $current_lexicon->FieldLabels);
		$field_type_array = explode("\n", $current_lexicon->FieldTypes);

		if ($this->input->post('submit')) 
		{
			if ($this->input->post('Word'))
			{ // Check that AT LEAST the Word field has been completed

				$key = array();

				foreach($field_label_array as $key => $field_label)
				{
					if ($field_label == 'Index_ID')
					{
						$data[$field_label] = '';
					}
					else
					{
						$data[$field_label] = $_POST[$field_label];					
					}

				}

				if ($this->db->insert($current_lexicon->Name, $data))
				{
					$query_string = "UPDATE `lexinfo` SET `DateChanged`=NOW(), `Count`=Count+1 WHERE `Index_ID`=".$lang_ID;
					$this->db->query($query_string);
					$this->session->set_flashdata('message', '<p class="msuccess">New entry successfully added to lexicon</p>');
					redirect('lex_admin/adm_lex_viewall/'.$lang_ID, 'refresh');
				}
				else
				{
					$this->session->set_flashdata('message', '<p class="merror">Error : database insert failed.</p>');
					redirect('lex_admin/adm_lex_viewall/'.$lang_ID, 'refresh');
				}

			}
			else
			{
				$data['validation_error'] = 'Error, no dictionary word has been provided. Please enter a new dictionary word with optional definition(s) and example(s).';
			}

		}

		$data['lexicons'] = $this->mdl_lexinfo->order_by('Name')->get()->result();

		$data['current_lexicon'] = $current_lexicon;
		
		$this->load->model('mdl_lexicon_entries');
		// Required to show next supposed database ID for this record
		$data['max_entry_ID'] = $this->mdl_lexicon_entries->get_max_ID($current_lexicon->Name);

		$data['page_title'] = $this->lang->line('title_lex_newentry');
		$data['headline'] = $this->lang->line('headline_lex_newentry');
		$data['lang_ID'] = $lang_ID; // make the $lang_ID variable available for the topbar
		$data['topbar'] = 'adm_lexicon_topbar';
		$data['content'] = 'adm/adm_lex_newentry';
		$this->load->view('template', $data);		

	}


	function adm_lex_deleteentry($lang_ID, $entry_Index_ID)
	{
		$lang_ID = check_int($lang_ID);
		$entry_Index_ID = check_int($entry_Index_ID);

		if ($lang_ID && $entry_Index_ID) 
		{		
			$this->load->model('mdl_lexinfo');
			$current_lexicon = $this->mdl_lexinfo->get_by_id($lang_ID);	// needed to retrieve relevant table name from lexicon ID
			if ($this->db->delete($current_lexicon->Name, array('Index_ID' => $entry_Index_ID)))
			{
				$query_string = "UPDATE `lexinfo` SET `DateChanged`=NOW(), `Count`=Count-1 WHERE `Index_ID`=".$lang_ID;
				$this->db->query($query_string);	
			}

			$this->session->set_flashdata('message', '<p class="msuccess">Lexicon word deleted successfully</p>');
			redirect('lex_admin/adm_lex_viewall/'.$lang_ID, 'refresh');
		}

	}

}