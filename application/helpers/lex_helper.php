<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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