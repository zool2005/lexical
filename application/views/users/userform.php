<script type="text/javascript">
	$(document).ready(function() {
		$('#entry_date').datepicker({
			changeMonth: true,
			yearRange: "1900:2020",
			changeYear: true,
			dateFormat : "yy-mm-dd"
		});
	});
</script>

<div id="standardform">

<?php

	echo form_fieldset('Details');

		echo validation_errors();

		echo form_open($form_open);		 

			echo form_label($this->lang->line('val_status'), 'status');
			echo form_dropdown('status', $roles_dropdown, $this->mdl_users->form_value('status'));
			
			echo form_label($this->lang->line('val_first_name'), 'first_name');
			$fn_atts = array('name' => 'first_name', 'id' => 'first_name', 'value' => $this->mdl_users->form_value('first_name'));
			echo form_input($fn_atts);
		
			echo form_label($this->lang->line('val_last_name'), 'last_name');
			$ln_atts = array('name' => 'last_name', 'id' => 'last_name', 'value' => $this->mdl_users->form_value('last_name'));
			echo form_input($ln_atts);

		if ($btn_type == 'btn_submit')
		{	
			echo form_label($this->lang->line('val_initials'), 'initials');
			$initials = array('name' => 'initials', 'id' => 'initials', 'value' => $this->mdl_users->form_value('initials'));
			echo form_input($initials);

			echo form_label($this->lang->line('val_site_id'), 'site_id');
			echo form_dropdown('site_id', $sites_dropdown, $this->mdl_users->form_value('site_id'));
			
			echo form_label($this->lang->line('val_username'), 'username');
			$un_atts = array('name' => 'username', 'id' => 'username', 'value' => $this->mdl_users->form_value('username'));
			echo form_input($un_atts);

			echo form_label($this->lang->line('val_password'), 'password');
			$pw_atts = array('name' => 'password', 'id' => 'password');
			echo form_password($pw_atts);

			echo form_label($this->lang->line('val_passconf'), 'passconf');
			$pc_atts = array('name' => 'passconf', 'id' => 'passconf');
			echo form_password($pc_atts);
		}
			echo form_label($this->lang->line('val_telephone'), 'telephone');
			$tn_atts = array('name' => 'telephone', 'id' => 'telephone', 'value' => $this->mdl_users->form_value('telephone'));
			echo form_input($tn_atts);
		
			echo form_label($this->lang->line('val_email'), 'email');
			$ea_atts = array('name' => 'email', 'id' => 'email', 'value' => $this->mdl_users->form_value('email'));
			echo form_input($ea_atts);

			echo form_label($this->lang->line('val_entry_date'), 'entry_date');
			$ed_atts = array('name' => 'entry_date', 'id' => 'entry_date', 'value' => $this->mdl_users->form_value('entry_date'));
			echo form_input($ed_atts);
		
			echo form_label('&nbsp;');
			echo form_submit($btn_type, $this->lang->line('btn_submit')); echo form_submit('btn_cancel', $this->lang->line('btn_cancel'));
			
		
		echo form_close(); 

	echo form_fieldset_close();

?>

</div>