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
                    <?php
						// Retrieve basic lexicon stats
						$displayBuf = "<h1>" . $current_lexicon->Name . " Lexicon Manager</h1>\n";
						echo($displayBuf);
					?>
                    <table>
                    	<tr>
                        	<th colspan="2">Lexicon Information</th>
                        </tr>
                        <tr>
                        	<th>Name</th>
                            <td>
								<?php
									// Show the name of the current lexicon
                            		echo($current_lexicon->Name);
								?>
							</td>
                        </tr>
                        <tr>
                        	<th>Total Entries</th>
                            <td>
                            	<?php
									// Show the number of entries in the current lexicon
									echo($current_lexicon->Count);
								?>
                            </td>
                        </tr>
                        <tr>
                        	<th>Date Created</th>
                            <td>
                            	<?php
									// Show the timestamp of the lexicon's creation
									echo date('d-m-Y H:i', strtotime($current_lexicon->DateCreated));
								?>
                            </td>
                        </tr>
                        <tr>
                        	<th>Date Last Edited</th>
                            <td>
                            	<?php
									// Show the timestamp of the last time the lexicon was edited
									echo date('d-m-Y H:i', strtotime($current_lexicon->DateChanged));
								?>
                            </td>
                        </tr>
                    </table>
                    <p><?php echo anchor('lexmanager/view_lexicon/'.$current_lexicon->Index_ID.'/','View Lexicon'); ?></p>

                    <noscript>
                    	<p class="statictext warning">This page requires that JavaScript be enabled.</p>
                    </noscript>
                    <br/><br/>
	            </div>