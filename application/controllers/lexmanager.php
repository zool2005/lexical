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

class Lexmanager extends CI_Controller {


	public function __construct()
	{
        parent::__construct();

        if (!$this->session->userdata('logged_in'))
        {
        	redirect('login/logout', 'refresh');
        }
	}


	function index($lex_ID = NULL)
	{

		if ($lex_ID == NULL)
		{
			redirect('lexmanager/view_all', 'refresh');
		}
		else
		{
			$lex_ID = check_int($lex_ID);
			redirect('lexmanager/view_lexicon/'.$lex_ID, 'refresh');
		}

	}

	function view_lexicon($lex_ID = NULL)
	{

		$this->load->model('mdl_lexinfo');
		$lex_ID = check_int($lex_ID);
		if ($lex_ID > 0 && $this->mdl_lexinfo->get_by_id($lex_ID))
		{

			$lexicon = $this->mdl_lexinfo->get_by_id($lex_ID);

			$data['page_title'] = $lexicon->Name.' Lexicon';
			$data['headline'] = $lexicon->Name.' Lexicon';
			$data['lex_ID'] = $lex_ID;
			$data['alphabet'] = $lexicon->Alphabet;
			$data['topbar'] = 'alphabet_topbar';
			$data['content'] = 'lex/view_lexicon';
			$this->load->view('template', $data);			
		}
		else
		{
			redirect('lexmanager/view_all', 'refresh');
		}

	}



	function view_all()
	{
		$this->load->model('mdl_lexinfo');
		$data['lexicons'] = $this->mdl_lexinfo->order_by('Name')->get()->result();

		$data['page_title'] = $this->lang->line('title_view_all');
		$data['headline'] = $this->lang->line('headline_view_all');
		$data['topbar'] = 'empty_topbar';
		$data['content'] = 'lex/view_all';
		$this->load->view('template', $data);		
	}



	function view_word($lex_ID, $entry_index)
	{	// get[i] and get[e] respectively.

		// Ensure mandatory GET inputs are set, else end execution
		if(isset($lex_ID) && isset($entry_index)) 
		{
			$lexIndex = check_int($lex_ID);
			$entryIndex = check_int($entry_index);
		}
		else
		{
			redirect('lexmanager/index', 'refresh');
		}

		$this->load->model('mdl_lexinfo');
		$lexicon_data = $this->mdl_lexinfo->get_by_id($lex_ID);

		// Retrieve table structure and create two parallel arrays containing field labels and field types
		$lang = $lexicon_data->Name;
		$field_label_array = explode("\n", $lexicon_data->FieldLabels);
	    $field_type_array = explode("\n", $lexicon_data->FieldTypes);

		// Retrieve the appropriate lexicon entry from the database and assemble it into a FLAT array
		$lex_data = $this->db->query("SELECT * FROM `" . $lang . "` WHERE `Index_ID`='" . $entryIndex . "';")->row_array();

		foreach ($lex_data as $key => $value)
		{
			$lex_data_array[] = $value;
		}

		// Retrieve the proper formatting information
		$lex_styles = $this->db->query("SELECT * FROM `" . $lang . "-styles`;")->result_array();
		
		// Iterate over each field and display the entry
		$displayBuf = "";
		foreach($field_label_array as $key => $field_label) 
		{

			$clean_field_label = str_replace(' ', '', $field_label);
			switch($field_type_array[$key]) 
			{
				case 'id':
				case 'hidden':
					// If an ID or hidden field, do nothing
					break;
				case 'text':
					// If a text field, display the contents
					$displayBuf .= "<br><span class=\"" . $clean_field_label . "\">" . (($lex_styles[$key]['Label'] == '1') ? $field_label . ": " : "") . $lex_data_array[$key] . "</span>\n";
					break;
				case 'rich':
					// If a rich text field, create a new paragraph and display the formatted contents
					$field_value = $lex_data_array[$key];
					// Set up an array of conversions between LexManager markup and HTML
					$formatters = array(array("\n", "<br>", "<br>"),
									    array("''", "<b>", "</b>"),
										array("//", "<i>", "</i>"),
										array("__", "<u>", "</u>"));
					// Format rich text
					foreach($formatters as $formatter) {
						$counter = 0;
						while(strpos($field_value, $formatter[0]) !== FALSE) {
							if($counter % 2 == 0) {
								$tmp = explode($formatter[0], $field_value, 2);
								$field_value = implode($formatter[1], $tmp);
							} else {
								$tmp = explode($formatter[0], $field_value, 2);
								$field_value = implode($formatter[2], $tmp);
							}
							$counter++;
						}
					}
					
					// Format links
					while(strpos($field_value, "[[") !== FALSE) {
						$tmp = explode("[[", $field_value, 2);
						$target = substr($tmp[1], 0, strpos($tmp[1], "|"));
						if(is_numeric($target)) {
							$field_value = $tmp[0] . "<a class=\"entrylink\" href=\"view.php?i=" . $lex_ID . "&e=" . $target . "\">" . substr($tmp[1], strlen($target) + 1);;
						} else {
							$field_value = $tmp[0] . "<a class=\"entrylink external\" href=\"" . $target . "\">" . substr($tmp[1], strlen($target) + 1);
						}
					}
					$field_value = str_replace("]]", "</a>", $field_value);
					
					$displayBuf .= "<p class=\"" . $clean_field_label . "\">" . (($lex_styles[$key]['Label'] == '1') ? $field_label . ": " : "") . $field_value . "</p>\n";
					break;
				case 'list':
					// If a list text field, format the contents and generate an HTML list
					$field_value = $lex_data_array[$key];
					// Set up an array of conversions between LexManager markup and HTML
					$formatters = array(array("''", "<b>", "</b>"),
										array("//", "<i>", "</i>"),
										array("__", "<u>", "</u>"));
					// Format rich text
					foreach($formatters as $formatter) {
						$counter = 0;
						while(strpos($field_value, $formatter[0]) !== FALSE) {
							if($counter % 2 == 0) {
								$tmp = explode($formatter[0], $field_value, 2);
								$field_value = implode($formatter[1], $tmp);
							} else {
								$tmp = explode($formatter[0], $field_value, 2);
								$field_value = implode($formatter[2], $tmp);
							}
							$counter++;
						}
					}
					
					// If a list field, generate an HTML list
					$field_value_array = explode("\n", $field_value);
					$displayBuf .= "<ol class=\"" . $clean_field_label . "\">";
					foreach($field_value_array as $def) {
						$displayBuf .= "<li>" . $def . "</li>";
					}
					$displayBuf .= "</ol>\n";
					break;
			}
		}
		echo($displayBuf);
		
		// Print Formatting CSS
		$displayBuf = "<style type=\"text/css\">\n";
		foreach($field_label_array as $key => $field_label) {
			$clean_field_label = str_replace(' ', '', $field_label);
			switch($field_type_array[$key]) {
				case 'id':
				case 'hidden':
					break;
				case 'text':
				case 'rich':
					$displayBuf .= "." . $clean_field_label . "{\n";
					$displayBuf .= "font-family: " . $lex_styles[$key]['FontFamily'] . ";\n";
					$displayBuf .= "font-size: " . $lex_styles[$key]['FontSize'] . ";\n";
					$displayBuf .= "color: " . $lex_styles[$key]['FontColor'] . ";\n";
					$displayBuf .= (($lex_styles[$key]['Bold'] == '1') ? "font-weight: bold;\n" : "font-weight: normal;\n");
					$displayBuf .= (($lex_styles[$key]['Italic'] == '1') ? "font-style: italic;\n" : "font-style: normal;\n");
					$displayBuf .= (($lex_styles[$key]['Underline'] == '1') ? "text-decoration: underline;\n" : "text-decoration: none;\n");
					$displayBuf .= (($lex_styles[$key]['SmallCaps'] == '1') ? "font-variant: small-caps;\n" : "font-variant: normal;\n");
					$displayBuf .= "}\n\n";
					break;
				case 'list':
					$displayBuf .= "." . $clean_field_label . "{\n";
					$displayBuf .= "font-family: " . $lex_styles[$key]['FontFamily'] . ";\n";
					$displayBuf .= "font-size: " . $lex_styles[$key]['FontSize'] . ";\n";
					$displayBuf .= "color: " . $lex_styles[$key]['FontColor'] . ";\n";
					$displayBuf .= "list-style-type: " . $lex_styles[$key]['BulletType'] . ";\n";
					$displayBuf .= "}\n\n";
					break;
			}
		}
		$displayBuf .= "</style>\n";
		echo($displayBuf);
		
		// Add necessary JavaScript events to any links
		echo("<script type=\"text/javascript\">wordLookup();</script>\n");

	}




}