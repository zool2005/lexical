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
		$lex_ID = check_int($lex_ID);
		if ($lex_ID > 0)
		{
			$this->load->model('mdl_lexinfo');
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

	//////
	// view.php
	// 
	// Purpose: Display a single lexicon entry, properly-formatted
	// Inputs: 
	//     'i' (GET, mandatory): the index of the lexicon in the "lexinfo" table
	//     'e' (GET, mandatory): the index of the entry within the lexicon's table
	//
	// This PHP file does not produce a complete HTML page. It is intended to be loaded within another HTML page using AJAX
	//////

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
	$query_reply = $this->mdl_lexinfo->get_by_id($lex_ID);

	// Retrieve table structure and create two parallel arrays containing field labels and field types
	$lang = mysql_result($queryReply, 0, 'Name');
	$fieldLabelArray = explode("\n", mysql_result($queryReply, 0, 'FieldLabels'));
    $fieldTypeArray = explode("\n", mysql_result($queryReply, 0, 'FieldTypes'));

	// Retrieve the appropriate lexicon entry from the database and assemble it into an array
	$queryReply = mysql_query("SELECT * FROM `" . $lang . "` WHERE `Index_ID`='" . $entryIndex . "';");
	for($i = 0; $i < count($fieldLabelArray); $i++) {
		$lexDataArray[$i] = mysql_result($queryReply, 0, $i);
	}
	
	// Retrieve the proper formatting information
	$queryReply = mysql_query("SELECT * FROM `" . $lang . "-styles`;");
	
	// Iterate over each field and display the entry
	$displayBuf = "";
	foreach($fieldLabelArray as $key => $fieldLabel) {
		$cleanFieldLabel = str_replace(' ', '', $fieldLabel);
		switch($fieldTypeArray[$key]) {
			case 'id':
			case 'hidden':
				// If an ID or hidden field, do nothing
				break;
			case 'text':
				// If a text field, display the contents
				$displayBuf .= "<br><span class=\"" . $cleanFieldLabel . "\">" . ((mysql_result($queryReply, $key, 'Label') == '1') ? $fieldLabel . ": " : "") . $lexDataArray[$key] . "</span>\n";
				break;
			case 'rich':
				// If a rich text field, create a new paragraph and display the formatted contents
				$fieldValue = $lexDataArray[$key];
				// Set up an array of conversions between LexManager markup and HTML
				$formatters = array(array("\n", "<br>", "<br>"),
								    array("''", "<b>", "</b>"),
									array("//", "<i>", "</i>"),
									array("__", "<u>", "</u>"));
				// Format rich text
				foreach($formatters as $formatter) {
					$counter = 0;
					while(strpos($fieldValue, $formatter[0]) !== FALSE) {
						if($counter % 2 == 0) {
							$tmp = explode($formatter[0], $fieldValue, 2);
							$fieldValue = implode($formatter[1], $tmp);
						} else {
							$tmp = explode($formatter[0], $fieldValue, 2);
							$fieldValue = implode($formatter[2], $tmp);
						}
						$counter++;
					}
				}
				
				// Format links
				while(strpos($fieldValue, "[[") !== FALSE) {
					$tmp = explode("[[", $fieldValue, 2);
					$target = substr($tmp[1], 0, strpos($tmp[1], "|"));
					if(is_numeric($target)) {
						$fieldValue = $tmp[0] . "<a class=\"entrylink\" href=\"view.php?i=" . $lexIndex . "&e=" . $target . "\">" . substr($tmp[1], strlen($target) + 1);;
					} else {
						$fieldValue = $tmp[0] . "<a class=\"entrylink external\" href=\"" . $target . "\">" . substr($tmp[1], strlen($target) + 1);
					}
				}
				$fieldValue = str_replace("]]", "</a>", $fieldValue);
				
				$displayBuf .= "<p class=\"" . $cleanFieldLabel . "\">" . ((mysql_result($queryReply, $key, 'Label') == '1') ? $fieldLabel . ": " : "") . $fieldValue . "</p>\n";
				break;
			case 'list':
				// If a list text field, format the contents and generate an HTML list
				$fieldValue = $lexDataArray[$key];
				// Set up an array of conversions between LexManager markup and HTML
				$formatters = array(array("''", "<b>", "</b>"),
									array("//", "<i>", "</i>"),
									array("__", "<u>", "</u>"));
				// Format rich text
				foreach($formatters as $formatter) {
					$counter = 0;
					while(strpos($fieldValue, $formatter[0]) !== FALSE) {
						if($counter % 2 == 0) {
							$tmp = explode($formatter[0], $fieldValue, 2);
							$fieldValue = implode($formatter[1], $tmp);
						} else {
							$tmp = explode($formatter[0], $fieldValue, 2);
							$fieldValue = implode($formatter[2], $tmp);
						}
						$counter++;
					}
				}
				
				// If a list field, generate an HTML list
				$fieldValueArray = explode("\n", $fieldValue);
				$displayBuf .= "<ol class=\"" . $cleanFieldLabel . "\">";
				foreach($fieldValueArray as $def) {
					$displayBuf .= "<li>" . $def . "</li>";
				}
				$displayBuf .= "</ol>\n";
				break;
		}
	}
	echo($displayBuf);
	
	// Print Formatting CSS
	$displayBuf = "<style type=\"text/css\">\n";
	foreach($fieldLabelArray as $key => $fieldLabel) {
		$cleanFieldLabel = str_replace(' ', '', $fieldLabel);
		switch($fieldTypeArray[$key]) {
			case 'id':
			case 'hidden':
				break;
			case 'text':
			case 'rich':
				$displayBuf .= "." . $cleanFieldLabel . "{\n";
				$displayBuf .= "font-family: " . mysql_result($queryReply, $key, 'FontFamily') . ";\n";
				$displayBuf .= "font-size: " . mysql_result($queryReply, $key, 'FontSize') . ";\n";
				$displayBuf .= "color: " . mysql_result($queryReply, $key, 'FontColor') . ";\n";
				$displayBuf .= ((mysql_result($queryReply, $key, 'Bold') == '1') ? "font-weight: bold;\n" : "font-weight: normal;\n");
				$displayBuf .= ((mysql_result($queryReply, $key, 'Italic') == '1') ? "font-style: italic;\n" : "font-style: normal;\n");
				$displayBuf .= ((mysql_result($queryReply, $key, 'Underline') == '1') ? "text-decoration: underline;\n" : "text-decoration: none;\n");
				$displayBuf .= ((mysql_result($queryReply, $key, 'SmallCaps') == '1') ? "font-variant: small-caps;\n" : "font-variant: normal;\n");
				$displayBuf .= "}\n\n";
				break;
			case 'list':
				$displayBuf .= "." . $cleanFieldLabel . "{\n";
				$displayBuf .= "font-family: " . mysql_result($queryReply, $key, 'FontFamily') . ";\n";
				$displayBuf .= "font-size: " . mysql_result($queryReply, $key, 'FontSize') . ";\n";
				$displayBuf .= "color: " . mysql_result($queryReply, $key, 'FontColor') . ";\n";
				$displayBuf .= "list-style-type: " . mysql_result($queryReply, $key, 'BulletType') . ";\n";
				$displayBuf .= "}\n\n";
				break;
		}
	}
	$displayBuf .= "</style>\n";
	echo($displayBuf);
	
	// Add necessary JavaScript events to any links
	echo("<script type=\"text/javascript\">wordLookup();</script>\n");

	// Close database connection
	@mysql_close($dbLink);		
	}




}