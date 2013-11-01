<script type="text/javascript">
$(document).ready(function() 
{
	$("tr:odd").css("background-color", "#e6e6e6");
});
</script>

<div id="leftbar">
	&nbsp;
</div>

<div id="entryview">
	<?php echo $this->session->flashdata('message'); ?>
	<div id="userlist">
		<?php echo $t_body; ?>
		<?php echo anchor('users/adm_register_user', $this->lang->line('register_user'), 'class="buttonlink"'); ?>

	</div>
</div>