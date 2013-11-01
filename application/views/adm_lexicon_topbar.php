                <?php $this->load->view('adm_nav'); ?>

                <table>
                	<tr>
                    	<?php
							// Output navigation such that it is aware of the current lexicon
							$displayBuf = "<td>".anchor('lex_admin/adm_lex_viewall/'. $lang_ID . '/', 'View All Entries', 'class="lexlink"')."</td>\n";
                            $displayBuf .= "<td>".anchor('lex_admin/adm_lex_newentry/'. $lang_ID . '/', 'Add New Entry', 'class="lexlink"')."</td>\n";
                        //	$displayBuf .= "<td><a href=\"adm_lex_lexsettings.php?i=" . $lang_ID . "\" class=\"lexlink\">Display Settings</a></td>\n";
							echo($displayBuf);
						?>
                    </tr>
                </table>