<?php 
/*
+-----------------------------------------------------------------------------------------------+
| Lexical, a web-based dictionary management ported to the CodeIgniter framework from 			|
| LexManager, created by Martin Posthumus														|
| Original Website : http://www.veche.net/programming/lexmanager.html 							|
| Original Source Code on GitHub : https://github.com/voikya/LexManager                         |
|                                                                                               |
| Lexical is free and open-source. You may redistribute and/or modify Lexical under the terms | 
| of the GNU General Public  License (GPL) as published by the Free Software Foundation, 		|
| either version 3 of the license or any later version. 										|
|                                                                                               |
| Lexical comes with no warranty for loss of data, as per the GPL3 license.    					|
+-----------------------------------------------------------------------------------------------+
*/

$this->load->view('headset');

/*
	if($this->session->flashdata('mtype') && $this->session->flashdata('message'))
	{
		echo = "<p class='{$this->session->flashdata('mtype')}'>{$this->session->flashdata('message')}</p>"; 
	}
*/
?>


<div id="content">

   	<div id="topbar">
   		<?php echo heading($headline, 1).' '.anchor('login/logout', 'Logout'); ?>
		<?php $this->load->view($topbar); ?>
    </div>


    <div id="main">
		<?php $this->load->view($content); ?>
	</div>


</div>
		<?php $this->load->view('footer'); ?>