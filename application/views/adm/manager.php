<div id="leftbar">
	<?php $this->load->view('adm/adm_lex_list_partial'); ?>
</div>
<div id="entryview">
	<?php echo $this->session->flashdata('message'); ?>
	<p class="statictext"><?php echo $this->lang->line('welcome_message'); ?></p>
    <?php
		// If no lexicons have yet been created, display a prompt guiding the administrator to the New Lexicon page
		if (!$lexicons)
		{
			$displayBuf = '<p class="mwarning">'.$this->lang->line('no_lexicons_error').'</p>'; echo($displayBuf);
		}
	?>

    <noscript>
    	<p class="statictext warning">This page requires that JavaScript be enabled.</p>
    </noscript>
    <br/><br/>
</div>