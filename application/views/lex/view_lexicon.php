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
});

</script>
