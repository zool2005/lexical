	        	<div id="leftbar">
					<?php
						// Retrieve list of available lexicons
                        $numTables = count($lexicons);
                        $displayBuf = "";

						// Display list of lexicons with links to their individual administration pages
						if(!$numTables) {
							echo("<p>No lexicons found.</p>\n");
						} else {

							foreach ($lexicons as $lex)
							{
								$lang_ID = $lex->Index_ID;
								$lang_name = $lex->Name;
	                         //   $displayBuf .= "<p><a href=\"adm_viewlex.php?i=" . $langID . "\" class=\"lexlink\">" . $langName . "</a></p>\n";
	                            $displayBuf .= "<p>".anchor('lex_admin/adm_view_lexicon/'.$lang_ID.'/',$lang_name, 'class="lexlink"')."</p>\n";
							}

							echo($displayBuf);
						}
                    ?>
	            </div>
	            <div id="entryview">
	            	<p class="statictext">Welcome to the <b>Lexical</b> Administration page.</p>
                    <p class="statictext">From here you can control all of the lexicons within <b>Lexical</b>. Select an option from the top right corner to create, import, and export lexicons, or select a specific lexicon in the list to the left to see the options available for that particular language.</p>
                    <p class="statictext">From a particular lexicon's page you can add, edit, and remove entries or modify the structure and appearance of the lexicon as a whole.</p>
                    <?php
						// If no lexicons have yet been created, display a prompt guiding the administrator to the New Lexicon page
						if(!$numTables) {
							$displayBuf = "<p class=\"warning\">It appears you have no lexicons set up. If you would like to set up a new lexicon, please select \"New Lexicon\" above. If you believe this message is in error, check your MySQL and Lexical configurations.</p>";
							
							echo($displayBuf);
						}
					?>

                    <noscript>
                    	<p class="statictext warning">This page requires that JavaScript be enabled.</p>
                    </noscript>
                    <br/><br/>
	            </div>