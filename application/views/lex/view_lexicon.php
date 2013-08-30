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
?>
<div id="leftbar">
</div>

<div id="entryview">
	<p class="statictext">Enter a search term in the box above, or select a letter to browse.</p>
</div>


<script type="text/javascript">

	var base_url = '<?php echo base_url(); ?>';

$(document).ready(function(){

	$('.alpha')
		.on('click', function() {
			var letter = $(this).text();
			var lex = $(this).attr('href');
			var datasource = base_url+ 'index.php/ajax/query/'+lex+'/'+letter+'/alphabet';
			$('#leftbar')
				.load(datasource)
				.scrollTop(0);
			return false;
		});


	$('a.entrylink')
		.not('.external')
		.unbind()
		.bind('click', function() {
			var datasource = $(this).attr('href');
			$('#entryview').load(datasource);
			return false;
		});
		
	$('a.searchedentrylink')
		.unbind()
		.bind('click', function() {
			var index = $(this).attr('id');
			var query = $('#query').text();
			var datasource = base_url+ 'index.php/lexmanager/view_word/'+index+'/'+query+'/';
//			var datasource = 'view.php?i=' + index + '&s=' + query;
			$('#entryview').load(datasource);
			return false;
		});

});




</script>
