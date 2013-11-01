<div id="leftbar">
    <?php $this->load->view('adm/adm_lex_list_partial'); ?>
</div>
	            <div id="entryview">
                    <?php
						// Retrieve basic lexicon stats
						$displayBuf = "<h1>" . $current_lexicon->Name . " Lexicon Manager</h1>\n";
						echo($displayBuf);
					?>
                    <table>
                    	<tr>
                        	<th colspan="2"><?php echo $this->lang->line('lexicon_information'); ?></th>
                        </tr>
                        <tr>
                        	<th><?php echo $this->lang->line('lexicon_name'); ?></th>
                            <td>
								<?php
									// Show the name of the current lexicon
                            		echo($current_lexicon->Name);
								?>
							</td>
                        </tr>
                        <tr>
                        	<th><?php echo $this->lang->line('total_entries'); ?></th>
                            <td>
                            	<?php
									// Show the number of entries in the current lexicon
									echo($current_lexicon->Count);
								?>
                            </td>
                        </tr>
                        <tr>
                        	<th><?php echo $this->lang->line('date_created'); ?></th>
                            <td>
                            	<?php
									// Show the timestamp of the lexicon's creation
									echo date('d-m-Y H:i', strtotime($current_lexicon->DateCreated));
								?>
                            </td>
                        </tr>
                        <tr>
                        	<th><?php echo $this->lang->line('date_last_edited'); ?></th>
                            <td>
                            	<?php
									// Show the timestamp of the last time the lexicon was edited
									echo date('d-m-Y H:i', strtotime($current_lexicon->DateChanged));
								?>
                            </td>
                        </tr>
                    </table>
                    <p><?php echo anchor('lexmanager/view_lexicon/'.$current_lexicon->Index_ID.'/',$this->lang->line('view_lexicon'), 'class="buttonlink"'); ?></p>

                    <noscript>
                    	<p class="statictext warning">This page requires that JavaScript be enabled.</p>
                    </noscript>
                    <br/><br/>
	            </div>