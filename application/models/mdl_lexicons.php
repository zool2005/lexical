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
class Mdl_lexicons extends CI_Model {

	function __construct()
    {
        parent::__construct();
	}


	public function create_lexicon_table($lang, $exploded_field_types, $exploded_field_labels)
	{

		// Create a SQL create table command by iterating over each field and its corresponding field type
		$table_structure_string = "";

		foreach($exploded_field_types as $key => $value) 
		{
			switch($value) 
			{
				case 'id':
					$table_structure_string = "`" . mysql_real_escape_string($exploded_field_labels[$key]) . "` int(6) unsigned NOT NULL AUTO_INCREMENT";
					break;
				case 'text':
				case 'hidden':
					$table_structure_string .= ", `" . mysql_real_escape_string($exploded_field_labels[$key]) . "` varchar(255) COLLATE utf8_unicode_ci NOT NULL";
					break;
				case 'rich':
				case 'list':
					$table_structure_string .= ", `" . mysql_real_escape_string($exploded_field_labels[$key]) . "` text COLLATE utf8_unicode_ci NOT NULL";
					break;
			}
		}
		$this->db->query("CREATE TABLE `" . $lang . "` (" . $table_structure_string . ", PRIMARY KEY (`Index_ID`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	}




	public function create_lexicon_formatting_table($lang, $exploded_field_types, $exploded_field_labels)
	{
		// Create a formatting table for the new lexicon to store CSS information and fill it with default values
		// CSS styles not applicable for a given field are left NULL
		$this->db->query("CREATE TABLE `" . $lang . "-styles` (`Index_ID` int(3) NOT NULL AUTO_INCREMENT, `Name` varchar(255), `FontFamily` varchar(64), `FontSize` varchar(64), `FontColor` varchar(64), `Bold` bool, `Italic` bool, `Underline` bool, `SmallCaps` bool, `Label` bool, `BulletType` varchar(64), PRIMARY KEY(`Index_ID`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		
		foreach($exploded_field_types as $key => $value) 
		{
			switch($value) 
			{
				case 'id':
				case 'hidden':
					$this->db->query("INSERT INTO `" . $lang . "-styles` (`Name`) VALUES ('" . mysql_real_escape_string($exploded_field_labels[$key]) . "');");
					break;
				case 'text':
				case 'rich':
					$this->db->query("INSERT INTO `" . $lang . "-styles` (`Name`, `FontFamily`, `FontSize`, `FontColor`, `Bold`, `Italic`, `Underline`, `SmallCaps`, `Label`) VALUES ('" . mysql_real_escape_string($exploded_field_labels[$key]) . "', 'serif', 'medium', '#000000', '0', '0', '0', '0', '0');");
					break;
				case 'list':
					$this->db->query("INSERT INTO `" . $lang . "-styles` (`Name`, `FontFamily`, `FontSize`, `FontColor`, `Label`, `BulletType`) VALUES ('" . mysql_real_escape_string($exploded_field_labels[$key]) . "', 'serif', 'medium', '#000000', '0', 'decimal');");
					break;
			}

		}

	}



}