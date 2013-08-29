<?php

echo '<div id="leftbar">';

	$numTables = count($lexicons);

    $displayBuf = "";

	if (!$numTables) 
	{
		echo("<p>No lexicons found.</p>\n");
	}
	else
	{
		foreach ($lexicons as $lex)
		{
	        $displayBuf .= anchor('lexmanager/view_lexicon/'.$lex->Index_ID.'/', $lex->Name, 'class="lexlink"');
		}
		echo($displayBuf);
	}

echo '</div>';

echo '<div id="entryview">';

	// If no lexicons have yet been created, display a prompt guiding the administrator to the New Lexicon page
	if(!$numTables) 
	{
		$displayBuf = "<p class=\"warning\">It appears you have no lexicons set up. If you would like to set up a new lexicon, please contact your local administrator.</p>";
	}
	else
	{
		$displayBuf = 'Select a lexicon from the list to view';
	}
	echo($displayBuf);

echo '</div>';
