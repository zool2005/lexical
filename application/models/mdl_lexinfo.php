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
class Mdl_lexinfo extends MY_Model {

    public $table               = 'lexinfo';
    public $primary_key         = 'index_ID';

	function __construct()
    {
        parent::__construct();
	}


	function create_lexinfo_table()
	{
		$query = "CREATE TABLE `lexinfo` (`Index_ID` int(6) NOT NULL AUTO_INCREMENT, `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `Alphabet` text COLLATE utf8_unicode_ci NOT NULL, `Collation` text COLLATE utf8_unicode_ci NOT NULL, `Count` int(6) NOT NULL, `FieldTypes` text COLLATE utf8_unicode_ci NOT NULL, `FieldLabels` text COLLATE utf8_unicode_ci NOT NULL, `SearchableFields` text COLLATE utf8_unicode_ci NOT NULL, `DateCreated` datetime NOT NULL, `DateChanged` datetime NOT NULL, PRIMARY KEY (`Index_ID`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$this->db->query($query);

	}

}