<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

if ( ! function_exists('clean_string'))
{
	function clean_string($data)
	{
		$data = trim($data);
		$data = strip_tags($data);
		$data = mysql_real_escape_string($data);

		return $data;
	}
}



if ( ! function_exists('check_int'))
{
	function check_int($data)
	{

		$data = trim($data);
		$data = strip_tags($data);
		$data = mysql_real_escape_string($data);

		if ( (int)$data == $data && (int)$data > 0 )
		{	
			return $data;
		}
		else
		{
			$data = '';
			return $data;
		}

	}
}




if ( ! function_exists('is_positive_integer'))
{
	function is_positive_integer($data)
	{

		$data = trim($data);
		$data = strip_tags($data);
		$data = mysql_real_escape_string($data);

		if ( (int)$data == $data && (int)$data > 0 )
		{	
			return TRUE;
		}
		else
		{
			return FALSE;
		}

	}
}


if ( ! function_exists('clean_string'))
{
	function clean_string($data)
	{
		$data = trim($data);
		$data = strip_tags($data);
		$data = mysql_real_escape_string($data);

		return $data;
	}
}