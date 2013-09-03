	        	<div id="leftbar">
					<?php
						// Retrieve list of available lexicons
                        $numTables = count($lexicons);
                        $displayBuf = "";

						// Display list of lexicons with links to their individual administration pages
						if(!$numTables) 
						{
							echo("<p>No lexicons found.</p>\n");
						}
						else
						{
							foreach ($lexicons as $lex)
							{
	                            $displayBuf .= "<p>".anchor('lex_admin/adm_view_lexicon/'.$lex->Index_ID.'/',$lex->Name, 'class="lexlink"')."</p>\n";
							}

							echo($displayBuf);
						}

                    ?>

	            </div>
	            <div id="entryview">
                	<?php
                        echo $this->session->flashdata('message');
                    	// Retrieve table structure and create two parallel arrays containing field labels and field types
                    	$field_label_array = explode("\n", $current_lexicon->FieldLabels);
                    	$field_type_array = explode("\n", $current_lexicon->FieldTypes);
                    ?>
                    <p>Show 
                    	<input type="text" size="5" id="maxEntriesDisplayed" style="display: inline;" value="<?php echo($max_entries_displayed); ?>"> 
                    	entries starting from #<input type="text" size="5" id="startFrom" style="display: inline;" value="<?php echo($start_from); ?>">. 
                    	<input type="button" id="showEntries" value="Go">
                    	<input type="hidden" id="lexIndex" value="<?php echo($lang_ID); ?>"></p>
                    

                    <table class="lex_viewall">
                        <tr>
                            <?php

								$tmpl = array ( 'table_open'  => '<table class="lex_viewall">' );	// set class for table
								$this->table->set_template($tmpl);	// add new class to table template (this time only)

                            	array_push($field_label_array, 'View', 'Edit', 'Delete'); // add "View" and "Edit" header titles to $field_label_array

                            	$this->table->set_heading($field_label_array);	// set table header using the CI Table library

                            	$count = $word_array->num_rows();
								$word_array = $word_array->result_array();

                            	// add the associated view and edit links dynamically to the end of the array
                            	for ($i = 0; $i < $count; $i++)
                            	{
                            		$word_array[$i][] = anchor('lexmanager/view_word/'.$lang_ID.'/'.$word_array[$i]['Index_ID'].'/', 'View', 'class="viewlink"');
                            		$word_array[$i][] = anchor('lexmanager/adm_lex_editentry/'.$lang_ID.'/'.$word_array[$i]['Index_ID'].'/', 'Edit', 'class="editlink"');
                                    $word_array[$i][] = anchor('lex_admin/adm_lex_deleteentry/'.$lang_ID.'/'.$word_array[$i]['Index_ID'].'/', 'Delete', 'class="deletelink"');
                            	}

                            	echo $this->table->generate($word_array);	// generate the table of results using the CI_Table library

                            ?>
                        </tr>

                    </table>

                    <noscript>
                    	<p class="statictext warning">This page requires that JavaScript be enabled.</p>
                    </noscript>
	            </div>