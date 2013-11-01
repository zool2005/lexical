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



	function adm_new_lexicon()
	{
		if($this->input->post('submit')) 
		{
			// Retrieve submitted configuration fields (GLOBAL XSS FILTERING ENABLED)
			$lang = $this->input->post('lang');
 			$field_types = str_replace("\r", "", $_POST['fieldTypes']);			// Virtual Page Two (Field Types & Field Labels - array of data prepared by jQuery)
			$field_labels = str_replace("\r", "", $_POST['fieldLabels']);		// Virtual Page Two (Field Types & Field Labels - array of data prepared by jQuery)
			
			// Explode the field type and label variables to create two parallel arrays
			$exploded_field_types = explode("\n", $field_types);
			$exploded_field_labels = explode("\n", $field_labels);

			// Check if a 'lexinfo' table has been created (to keep track of all lexicons stored in the database), if not create it
			if (!$this->db->table_exists('lexinfo'))
			{ 
				$this->load->model('mdl_lexinfo');
				$this->mdl_lexinfo->create_lexinfo_table();
			} 
		
			// Insert data about the new lexicon in the lexinfo table
			$lexinfo_data = array(
						'Name' => $lang,
						'Alphabet' => $this->input->post('alphabet'),
						'Collation' => $this->input->post('collation'),
						'Count' => 0,
						'FieldTypes' => $field_types,
						'FieldLabels' => $field_labels,
						'SearchableFields' => 'Word',
						'DateCreated' => date('Y-m-d H:i:i:s'),
						'DateChanged' => date('Y-m-d H:i:i:s')
						);
			// Insert data			
			$this->db->insert('lexinfo', $lexinfo_data);

			$this->load->model('mdl_lexicons');
			// Now create the lexicon table with the specified name to store the words and definitions
			$this->mdl_lexicons->create_lexicon_table($lang, $exploded_field_types, $exploded_field_labels);
			// Create the formatting table for this lexicon
			$this->mdl_lexicons->create_lexicon_formatting_table($lang, $exploded_field_types, $exploded_field_labels);

			$this->session->set_flashdata('message', '<p class="msuccess">'.$this->lang->line('record_create_success').'</p>');
			redirect('lex_admin/manager', 'refresh');

		}


		$this->load->model('mdl_lexinfo');
		$data['lexicons'] = $this->mdl_lexinfo->order_by('Name')->get()->result();

		$data['page_title'] = $this->lang->line('title_new_lexicon');
		$data['headline'] = $this->lang->line('headline_new_lexicon');
		$data['topbar'] = 'adm_nav';
		$data['content'] = 'adm/adm_new_lexicon';
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
		// Note : the INSERT query in this function does not use the CI db insert format as the field names are generated dynamically in the view and global XSS filtering of $_POST data may interfere with the field names when doing an array-based insert with CI.
		// For example, the "Part of Speech" field is filtered to "PartofSpeech" during $_POST data filtering (whitespace removed) and is therefore not recognised as a valid DB field causing the insert query to fail.
		// Using the manual SQL query, the data is dumped into the database and relies on the data being formatted correctly beforehand for data consistency.
		// *** This could be improved in the future by using str_replace() to convert spaces to dashes or underscores for DB compatibility but would need to be implemented at the time of new lexicon creation with updates to all related code.
		
		$this->load->model('mdl_lexinfo');
		$current_lexicon = $this->mdl_lexinfo->get_by_id($lang_ID);

       	$field_label_array = explode("\n", $current_lexicon->FieldLabels);
		$field_type_array = explode("\n", $current_lexicon->FieldTypes);

		if ($this->input->post('submit')) 
		{
			if ($this->input->post('Word'))
			{ // Check that AT LEAST the Word field has been completed

				$query_string = "INSERT INTO `" . $current_lexicon->Name . "` VALUES (";
				// Iterate over submitted fields by referencing the field label array, and create a SQL insert command
				foreach($field_label_array as $key => $field_label) 
				{
					$field_label = str_replace(' ', '', $field_label);

					switch($field_type_array[$key]) 
					{
						case 'id':
							// If an ID field, insert nothing; ID values will be handled by MySQL's AUTO_INCREMENT
							$query_string .= "''";
							break;

						default:
							// Otherwise, add the data to the SQL insert
							$val = str_replace("\r", "", $_POST[$field_label]);
							$query_string .= ", '" . mysql_real_escape_string($val) . "'";

							break;
					}
				}
				$query_string .= ");";

				if ($this->db->query($query_string))
				{
					$query_string = "UPDATE `lexinfo` SET `DateChanged`=NOW(), `Count`=Count+1 WHERE `Index_ID`=".$lang_ID;
					$this->db->query($query_string);
					$this->session->set_flashdata('message', '<p class="msuccess">'.$this->lang->line('record_create_success').'</p>');
					redirect('lex_admin/adm_lex_viewall/'.$lang_ID, 'refresh');
				}
				else
				{
					$this->session->set_flashdata('message', '<p class="merror">'.$this->lang->line('record_create_failure').'</p>');
					redirect('lex_admin/adm_lex_viewall/'.$lang_ID, 'refresh');
				}

			}
			else
			{
				$data['validation_error'] = $this->lang->line('no_dictionary_word_error');
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


	function adm_lex_editentry($lang_ID, $entry_Index_ID)
	{
		// Note : similarly to the adm_lex_newentry function, it was necessary to create the SQL query manually for the UPDATE command due to dynamically generated form fields and XSS filtering of $_POST data.

		$lang_ID = check_int($lang_ID);
		$entry_Index_ID = check_int($entry_Index_ID);

		$this->load->model('mdl_lexinfo');
		$current_lexicon = $this->mdl_lexinfo->get_by_id($lang_ID);

       	$field_label_array = explode("\n", $current_lexicon->FieldLabels);
		$field_type_array = explode("\n", $current_lexicon->FieldTypes);


		if ($this->input->post('submit'))
		{

			if ($this->input->post('Word'))
			{
				$query_string = "UPDATE `" . $current_lexicon->Name . "` SET ";
				foreach($field_label_array as $key => $field_label) 
				{
					if($field_label != "Index_ID") 
					{
						$cleaned_field_label = str_replace(' ', '', $field_label);
						$val = str_replace("\r", "", $_POST[$cleaned_field_label]);
						$query_string .= "`" . $field_label . "`='" . mysql_real_escape_string($val) . "', ";
					}
				}

				$query_string = substr($query_string, 0, -2) . " WHERE `Index_ID`=" . $entry_Index_ID . ";";
				
				if ($this->db->query($query_string))
				{
					$query_string = "UPDATE `lexinfo` SET `DateChanged`=NOW() WHERE `Index_ID`=".$lang_ID;
					$this->db->query($query_string);
					$this->session->set_flashdata('message', '<p class="msuccess">'.$this->lang->line('record_update_success').'</p>');
					redirect('lex_admin/adm_lex_viewall/'.$lang_ID, 'refresh');					
				}

			}
			else
			{
				$data['validation_error'] = $this->lang->line('no_dictionary_word_error');
			}

		}

		// Get lexicon data for display in view ("current" and "all")
		$data['current_lexicon'] = $current_lexicon;

		$data['field_label_array'] = $field_label_array;
		$data['field_type_array'] = $field_type_array;

		$data['lexicons'] = $this->mdl_lexinfo->order_by('Name')->get()->result();
		
		// L.E.D (Lexicon Entry Data, single row to retrieve data for edit)
		$data['led'] = $this->db->where('Index_ID', $entry_Index_ID)->get($current_lexicon->Name)->row();

		$data['form_open'] = 'lex_admin/adm_lex_editentry/'.$lang_ID.'/'.$entry_Index_ID.'/';

		$data['page_title'] = $this->lang->line('title_lex_editentry');
		$data['headline'] = $this->lang->line('headline_lex_editentry');
		$data['lang_ID'] = $lang_ID; // make the $lang_ID variable available for the topbar
		$data['topbar'] = 'adm_lexicon_topbar';
		$data['content'] = 'adm/adm_lex_editentry';
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

			$this->session->set_flashdata('message', '<p class="msuccess">'.$this->lang->line('record_delete_success').'</p>');
			redirect('lex_admin/adm_lex_viewall/'.$lang_ID, 'refresh');
		}

	}

}