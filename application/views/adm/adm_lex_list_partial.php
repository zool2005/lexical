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
                $displayBuf .= "<p>".anchor('lex_admin/adm_view_lexicon/'.$lang_ID.'/',$lang_name, 'class="lexlink"')."</p>\n";
			}

			echo($displayBuf);
		}
    ?>