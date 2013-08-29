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


class Ajax extends CI_Controller {


	public function __construct()
	{
        parent::__construct();
	}


	public function query($lex_ID, $search_term, $search_type)
	{
		// Note : $search_term can be letter of alphabet or a word to search for.
		// The $type variable determines whether alphabet or word ('alphabet', 'word')

// AJAX - get values via $_POST ????

		$lex_ID = check_int($lex_ID);
		$search_term = clean_string($search_term);

		$this->load->model('mdl_lexinfo');
		$lexicon_data = $this->mdl_lexinfo->get_by_id($lex_ID);

		// Retrieve the language name and its collation from 'lexinfo'
		$current_lexicon = $lexicon_data->Name;
		$collation_list = explode(" ", $lexicon_data->Collation);

		$collation_array = array();
		$collation_value_array = array();
		
		// Set encoding for multibyte PHP string functions
		mb_internal_encoding("UTF-8");


		// Based on the new collation array, generate an array of every single character having a collation value and a parallel array with the actual collation values for each character
		// This is mindful of letters that may be composed of multiple glyphs
		$counter = 0;
		foreach($collation_list as $key => $letters) 
		{
			while(mb_strlen($letters) > 0) 
			{
				$curChar = mb_substr($letters, 0, 1);
				if($curChar == "[") 
				{
					$goTo = strpos($letters, "]");
					$curChar = substr($letters, 1, $goTo - 1);
					$letters = substr($letters, $goTo + 1);
				}
				else
				{
					$letters = mb_substr($letters, 1);
				}
				$collation_array[$counter] = $curChar;
				$collation_value_array[$counter] = $key;
				$counter++;
			}
		}


		if (isset($search_term) && $search_type == 'alphabet')
		{
			// If an alphabetical query, retrieve the letter and its collation value
			$letter = $search_term;
			$letter_val = $collation_value_array[array_search($letter, $collation_array, TRUE)];

			// Create an array of characters with the same collation value (i.e., that are considered variants of the same letter)
			$equiv = array_keys($collation_value_array, $letter_val);
			$equiv_letters;
			foreach($equiv as $key => $val) 
			{
				$equiv_letters[$key] = $collation_array[$val];
			}

			// Query the database for words beginning with each letter in the equivalence array
			$query = "SELECT `Index_ID`, `Word` FROM `" . $current_lexicon . "` WHERE (";
			foreach($equiv_letters as $aLetter) 
			{
				$query .= "`Word` LIKE '" . $aLetter . "%' OR ";
			}
			$query = substr($query, 0, -4) . ");";
		    $query_reply = $this->db->query($query);
			$total_entries = $query_reply->num_rows();

			// Iterate through the returned values and add valid values to an array
			$result_array;

			for ($i = 0; $i < $total_entries; $i++) 
			{
				$tmp = $query_reply->result_array();

				if(array_search(mb_substr($tmp[$i]['Word'], 0, 1), $equiv_letters, TRUE) !== FALSE) {
					// If the entry is valid, add it to the results array
					$result_array[$i] = $tmp[$i];
				}
				else 
				{
					// If the entry is invalid, skip it and decrement the variable containing the number of returned results
					// This can happen if two characters that a particular collation considers to be unique are interpreted by MySQL as being the same letter.
					// For instance, MySQL considers the two Cyrillic letters 'ye' and 'yo' to be the variants of the same letter (as they are in Russian). Thus,
					// an query looking for words beginning with 'ye' will also return words beginning with 'yo' and vice versa. This is fine for Russian, but completely
					// incorrect for a language that considers these to be two completely distinct letters.
					$total_entries--;
				}
			}

			// Output the total number of valid returned entries
			echo("<p class=\"count\">" . $total_entries . " match" . (($total_entries == 1) ? "" : "es") . " returned.</p>\n");

			// If the results array is non-zero, sort it and output the results
			if(isset($result_array)) 
			{
				$sorted_result_array = $this->sort_alphabetical($result_array, $collation_array, $collation_value_array);
			
				foreach($sorted_result_array as $entry) 
				{
					echo("<p><a href=\"view.php?i=" . $lex_ID . "&e=" . $entry['Index_ID'] . "\" class=\"entrylink\">" . $entry['Word'] . "</p>\n");
				}
			}



		}
		elseif (isset($search_term) && $search_type == 'word')
		{

			// This is a word search, if a Search Query is provided retrieve the query term
			$query = clean_string($search_term);
			
			// Retrieve the list of fields that are searchable and split it into an array ($lexicon data array set above)
			$searchable_list = explode("\n", $lexicon_data->SearchableFields);
			
			// Query the database for words matching the search term, examining only the searchable fields
			$mysql_where_terms = "";
			foreach($searchable_list as $key => $field) 
			{
				$mysql_where_terms .= "`" . $field . "` LIKE '%" . $query . "%'";
				if(isset($searchable_list[$key + 1])) 
				{
					$mysql_where_terms .= " OR ";
				}
			}
			$query_reply = $this->db->query("SELECT `Index_ID`, `Word` FROM `" . $current_lexicon . "` WHERE " . $mysql_where_terms . ";");
			$total_entries = $query_reply->num_rows();
			
			// Iterate through the returned values and add valid values to an array
			$result_array;

			for ($i = 0; $i < $total_entries; $i++) 
			{
				$tmp = $query_reply->result_array();
				$result_array[$i] = $tmp[$i];// mysql_fetch_assoc($query_reply);
			}

			// Output the total number of valid returned entries
			echo("<p class=\"count\">" . $total_entries . " match" . (($total_entries == 1) ? "" : "es") . " returned.</p>\n");

			// If the results array is non-zero, sort it and output the results
			if(isset($result_array)) 
			{
				$sorted_result_array = $this->sort_alphabetical($result_array, $collation_array, $collation_value_array);

				foreach($sorted_result_array as $entry) 
				{
					echo("<p><a href=\"view.php?i=" . $lex_ID . "&e=" . $entry['Index_ID'] . "\" class=\"entrylink\">" . $entry['Word'] . "</p>\n");
				}
			}
		}
		else
		{
			// do nothing
		}

		// Call the wordLookup() JavaScript function (in admin.js) to bind new click events to the displayed list
		// (since this file will generally be called by AJAX, the function must be run every time a new page component is loaded)
		echo("<script type=\"text/javascript\">\nwordLookup();\n</script>");

	}



	// Sort an array of words against a collation using a Quicksort algorithm
	// Inputs:
	//     $array - an array of words and the index values
	//     $collation - an array containing all recognized characters used by the language
	//     $values - an array parallel to $collation that assigns a numerical value to each character
	// Outputs:
	//     A sorted array
	public function sort_alphabetical($array, $collation, $values) 
	{
		if(count($array) <= 1) 
		{
			return $array;
		}
		$left = $right = array();
		
		reset($array);
		$pivot_key = key($array);
		$pivot = array_shift($array);

		foreach($array as $key => $entry) 
		{
			if($this->compare($entry['Word'], $pivot['Word'], $collation, $values) == 1) 
			{
				$left[$key] = $entry;
			} 
			else 
			{
				$right[$key] = $entry;
			}
		}

		return array_merge($this->sort_alphabetical($left, $collation, $values), array($pivot_key => $pivot), $this->sort_alphabetical($right, $collation, $values));
	}




	// Compare two words against a collation
	// Inputs:
	//     $word1 - a word
	//     $word2 - another word
	//     $collation - an array containing all recognized characters used by the language
	//     $values - an array parallel to $collation that assigns a numerical value to each character
	// Outputs:
	//     1 if $word1 precedes $word2 alphabetically
	//     0 if $word1 follows $word2 alphabetically
	public function compare($word1, $word2, $collation, $values) 
	{
		$word1Array;
		$word2Array;
		
		// Convert the first word into an array of letters, disregarding characters with no collation value (such as punctuation)
		$counter = 0;
		for($i = 0; $i < mb_strlen($word1); $i++) {
			$location = array_search(mb_substr($word1, $i, 2), $collation, TRUE);
			if($location !== FALSE) {
				$word1Array[$counter] = $values[$location];
				$i++;
				$counter++;
			} else {
				$location = array_search(mb_substr($word1, $i, 1), $collation, TRUE);
				if($location !== FALSE) {
					$word1Array[$counter] = $values[$location];
					$counter++;
				} else {
				}
			}
		}
		
		// Convert the second word into an array of letters, disregarding characters with no collation values (such as punctuation)
		$counter = 0;
		for($i = 0; $i < mb_strlen($word2); $i++) {
			$location = array_search(mb_substr($word2, $i, 2), $collation, TRUE);
			if($location !== FALSE) {
				$i++;
				$word2Array[$counter] = $values[$location];
				$counter++;
			} else {
				$location = array_search(mb_substr($word2, $i, 1), $collation, TRUE);
				if($location !== FALSE) {
					$word2Array[$counter] = $values[$location];
					$counter++;
				} else {
				}
			}
		}
		
		// Find the shorter word
		$lengthOfShorterWord = (count($word1Array) < count($word2Array)) ? count($word1Array) : count($word2Array);
		
		// Go letter-by-letter through both words until one is found should precede the other
		for($i = 0; $i < $lengthOfShorterWord; $i++) {
			if($word1Array[$i] < $word2Array[$i]) {
				return 1;
			} elseif($word1Array[$i] > $word2Array[$i]) {
				return 0;
			} else {
				continue;
			}
		}
		return 0;
	}

}