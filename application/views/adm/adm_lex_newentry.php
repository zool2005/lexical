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
// Retrieve table structure and create two parallel arrays containing field labels and field types
	$field_label_array = explode("\n", $current_lexicon->FieldLabels);
	$field_type_array = explode("\n", $current_lexicon->FieldTypes);
					
    $attributes = array('id' => 'addentry'); echo form_open('lex_admin/adm_lex_newentry/'.$lang_ID, $attributes);

    if (isset($validation_error))
    {
    	echo '<p class="merror">'.$validation_error.'</p>';
    }

echo '<table class="lex_newentry">';

	// Iterate over the table structure and generate an empty form to input the new data
	foreach($field_label_array as $key => $field_label) 
    {
		$clean_field_label = str_replace(' ', '', $field_label);

		echo form_label($field_label, $clean_field_label);

		switch($field_type_array[$key]) 
		{
			case 'id':
			// If an ID field, show an uneditable field containing the presumed ID of the new entry
				$newIndex = $max_entry_ID + 1;
				$ni = array('name' => $clean_field_label, 'value' => $newIndex, 'disabled' => 'disabled');
				echo form_input($ni);
			break;
										
			case 'text':
				echo form_input($clean_field_label, $this->input->post($clean_field_label));
			break;
										
			case 'rich':
				echo form_textarea($clean_field_label, $this->input->post($clean_field_label));
			//	echo "<span class=\"howtoformat\">''bold'', //italic//, __underline__, [[URL|link text]], [[ID#|link text]]</span></td>\n";
			break;
										
			case 'list':
			// If a list field, show a list containing a single text input field corresponding to one list item and a button to add new fields
				echo '<div style="display block; float: left">';	// note : put list inside floated DIV to correct CSS display issue
					echo '<ol class="listinput" id="'.$clean_field_label.'"><li><input type="text" size="50"></li></ol>';
					echo "<input type=\"button\" class=\"addListInput\" value=\"+\">\n";
				//	echo '<br style="clear:both"/>';
				echo '</div>';
				echo '<br style="clear:both;"/>';
			break;

			case 'hidden':
			// If a hidden field, show an empty text input field
				echo form_input($clean_field_label, $this->input->post($clean_field_label)).' (Hidden)';
			break;

			default:
				// If none of the above, show an error message
				echo "No input means specified";
			break;
		}

	}

	echo form_submit('submit', 'Submit');

	echo form_close();

?>
</table>
<noscript>
<p class="statictext warning">This page requires that JavaScript be enabled.</p>
</noscript>
</div>