<div id="leftbar">
	<?php $this->load->view('adm/adm_lex_list_partial'); ?>
</div>

<div id="entryview">

<?php
// Retrieve table structure and create two parallel arrays containing field labels and field types
					
    $attributes = array('id' => 'editentry'); echo form_open($form_open, $attributes);

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

		// Show field options based on the field type
		switch($field_type_array[$key]) 
		{
			case 'id':
			// If an ID field, show the ID, but do not allow it to be edited
				$index = array('name' => $clean_field_label, 'value' => $led->$field_label, 'disabled' => 'disabled');
				echo form_input($index);
			break;

			case 'text':
			// If a text field, show a text input field with the current value
				echo form_input($clean_field_label, $led->$field_label);
			break;

			case 'rich':
			// If a rich text field, show a textarea input with the current raw value
				echo form_textarea($clean_field_label, $led->$field_label);
			break;

			case 'list':
			// If a list, show a series of text input fields, each containing one list item, plus a button to add new list items
				$def_list = explode("\n", $led->$field_label); // get 
				echo '<div style="display block; float: left">';	// note : put list inside floated DIV to correct CSS display issue
					echo '<ol class="listinput" id="'.$clean_field_label.'">';
						foreach ($def_list as $def)
						{
							echo '<li><input type="text" size="50" value="'.$def.'"></li>';							
						}
					echo '</ol>';

					echo "<input type=\"button\" class=\"addListInput\" value=\"+\">\n";
				//	echo '<br style="clear:both"/>';
				echo '</div>';
				echo '<br style="clear:both;"/>';
			break;

			case 'hidden':
			// If a hidden field, show a text input field with the current value
				echo form_input($clean_field_label, $led->$field_label).' (Hidden)';
			break;

			default:
			// If none of the above, show an error message
				echo 'No input means specified';
			break;
		}	// END OF SWITCH
	}	// END OF FOREACH LOOP
	
	echo form_label('&nbsp;'); echo form_submit('submit', $this->lang->line('btn_submit'));
    
    echo form_close(); ?>

	</table>

	<noscript>
    	<p class="statictext warning">This page requires that JavaScript be enabled.</p>
    </noscript>
</div>
