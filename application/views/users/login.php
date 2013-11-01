<div id="leftbar">
</div>

<div id="entryview">


<?php 
	echo form_fieldset($this->lang->line('login'));

		echo form_open('login/login_user');

			echo isset($error_message) ? $error_message : NULL; // show error message if set

			$eml = array('name' => 'email_address', 'id' => 'email_address', 'autocomplete' => 'off'); ?>
			<p><?php echo form_label($this->lang->line('email_address'), 'email_address').' '.form_input($eml); ?></p>
			<p><?php echo form_error('email_address'); ?></p>

			<?php $pw = array('name' => 'password', 'id' => 'password', 'autocomplete' => 'off'); ?>
			<p><?php echo form_label($this->lang->line('password'), 'password').' '.form_password($pw); ?></p>
			<p><?php echo form_error('password'); ?></p>
					
			<p><?php echo form_submit('submit', $this->lang->line('login')); ?></p>
					
			<p><?php echo '<p style="background-color: yellow;" id="noscript">Please enable Javascript in your browser settings</p>'; ?></p>
		
			<?php echo form_close(); ?>
	
	<?php echo form_fieldset_close(); ?>

	<p style="text-align: center";><?php echo anchor_popup('login/about', 'About Lexical', array('height' => 300, 'width' => 600, 'class' => 'aboutlink')); ?></p>

</div>

	<script type="text/javascript">
		$('#noscript').remove();
	</script>

