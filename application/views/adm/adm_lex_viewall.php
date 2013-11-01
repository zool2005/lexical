<div id="leftbar">
    <?php $this->load->view('adm/adm_lex_list_partial'); ?>
</div>
	            <div id="entryview">
                	<?php
                        echo $this->session->flashdata('message');
                    	// Retrieve table structure and create two parallel arrays containing field labels and field types
                    	$field_label_array = explode("\n", $current_lexicon->FieldLabels);
                    	$field_type_array = explode("\n", $current_lexicon->FieldTypes);
                    ?>
                    <p><?php echo $this->lang->line('show'); ?> 
                    	<input type="text" size="5" id="maxEntriesDisplayed" style="display: inline;" value="<?php echo($max_entries_displayed); ?>"> 
                    	<?php echo $this->lang->line('entries_starting_from'); ?><input type="text" size="5" id="startFrom" style="display: inline;" value="<?php echo($start_from); ?>">. 
                    	<input type="button" id="showEntries" value="<?php echo $this->lang->line('show'); ?>">
                    	<input type="hidden" id="lexIndex" value="<?php echo($lang_ID); ?>"></p>
                    

                    <table class="lex_viewall">
                        <tr>
                            <?php

								$tmpl = array ( 'table_open'  => '<table class="lex_viewall">' );	// set class for table
								$this->table->set_template($tmpl);	// add new class to table template (this time only)

                            	array_push($field_label_array, '&nbsp;', '&nbsp;', '&nbsp;'); // add "View" and "Edit" header titles to $field_label_array

                            	$this->table->set_heading($field_label_array);	// set table header using the CI Table library

                            	$count = $word_array->num_rows();
								$word_array = $word_array->result_array();

                            	// add the associated view and edit links dynamically to the end of the array
                            	for ($i = 0; $i < $count; $i++)
                            	{
                            		$word_array[$i][] = anchor('lexmanager/view_word/'.$lang_ID.'/'.$word_array[$i]['Index_ID'].'/', $this->lang->line('view_link'), 'class="viewlink"');
                            		$word_array[$i][] = anchor('lex_admin/adm_lex_editentry/'.$lang_ID.'/'.$word_array[$i]['Index_ID'].'/', $this->lang->line('edit_link'), 'class="editlink"');
                                    $word_array[$i][] = anchor('lex_admin/adm_lex_deleteentry/'.$lang_ID.'/'.$word_array[$i]['Index_ID'].'/', $this->lang->line('delete_link'), 'class="deletelink"');
                            	}

                            	echo $this->table->generate($word_array);	// generate the table of results using the CI_Table library

                            ?>
                        </tr>

                    </table>

                    <noscript>
                    	<p class="statictext warning">This page requires that JavaScript be enabled.</p>
                    </noscript>
	            </div>