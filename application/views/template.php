<?php $this->load->view('headset');

/*
	if($this->session->flashdata('mtype') && $this->session->flashdata('message'))
	{
		echo = "<p class='{$this->session->flashdata('mtype')}'>{$this->session->flashdata('message')}</p>"; 
	}
*/
?>


<div id="content">

   	<div id="topbar">
   		<?php echo heading($headline, 1); ?>
		<?php $this->load->view($topbar); ?>
    </div>


    <div id="main">
		<?php $this->load->view($content); ?>
	</div>


</div>
		<?php $this->load->view('footer'); ?>