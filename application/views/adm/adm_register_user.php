<div id="leftbar">
	&nbsp;
</div>

<div id="entryview">
<?php

	echo form_fieldset($this->lang->line('register_user'));

		echo validation_errors();

		echo form_open('users/adm_register_user');

			echo form_label($this->lang->line('val_status'), 'status');
			$status = array('' => $this->lang->line('select_one'), '1' => $this->lang->line('administrator'), '2' => $this->lang->line('user'));
			echo form_dropdown('status', $status, $this->mdl_lex_userinfo->form_value('status'));
			
			echo form_label($this->lang->line('val_first_name'), 'first_name');
			$fn_atts = array('name' => 'first_name', 'id' => 'first_name', 'value' => $this->mdl_lex_userinfo->form_value('first_name'));
			echo form_input($fn_atts);
		
			echo form_label($this->lang->line('val_last_name'), 'last_name');
			$ln_atts = array('name' => 'last_name', 'id' => 'last_name', 'value' => $this->mdl_lex_userinfo->form_value('last_name'));
			echo form_input($ln_atts);

			echo form_label($this->lang->line('val_email'), 'email_address');
			$ea_atts = array('name' => 'email_address', 'id' => 'email_address', 'value' => $this->mdl_lex_userinfo->form_value('email_address'));
			echo form_input($ea_atts);
			
			echo form_label($this->lang->line('val_password'), 'password');
			$pw_atts = array('name' => 'password', 'id' => 'password');
			echo form_password($pw_atts);

			echo form_label($this->lang->line('val_pass_conf'), 'passconf');
			$pc_atts = array('name' => 'passconf', 'id' => 'passconf');
			echo form_password($pc_atts);

			echo form_label('&nbsp;');
			echo form_submit('submit', $this->lang->line('btn_submit'));
			
		
		echo form_close(); 

	echo form_fieldset_close();

?>

</div>