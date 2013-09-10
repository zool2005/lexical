<?php
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
?>

<div id="leftbar">
<?php
	// Retrieve list of available lexicons
    $numTables = count($lexicons);
    $displayBuf = "";

	// Display list of lexicons with links to their individual administration pages
	if(!$numTables) 
	{
		echo("<p>".$this->lang->line('no_lexicons_found')."</p>\n");
	} 
	else 
	{

		foreach ($lexicons as $lex)
		{
			$lang_ID = $lex->Index_ID;
			$lang_name = $lex->Name;
	        $displayBuf .= "<p>".anchor('lexmanager/view_lexicon/'.$lex->Index_ID.'/',$lex->Name, 'class="lexlink"')."</p>\n";
		}
		echo($displayBuf);
						}
?>
</div>

<?php 
echo '<div id="entryview">';

	// If no lexicons have yet been created, display a prompt guiding the administrator to the New Lexicon page
	if(!$numTables) 
	{
		$displayBuf = '<p class="mwarning">'.$this->lang->line('no_lexicons_error').'</p>';
	}
	else
	{
		$displayBuf = $this->lang->line('select_lexicon_from_list');
	}
	echo($displayBuf);

echo '</div>';
