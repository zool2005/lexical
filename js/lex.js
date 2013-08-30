$(function() {
	adjustSearch();
	alphaQueries();
	wordLookup();
});



function adjustSearch() {
	var defaultText='Search...';
	$('#searchbox')
		.attr('value', defaultText)
		.bind('focus', function() {
			if ($(this).attr('value') == defaultText) {
				$(this)
					.attr('value', '')
					.css({'font-style': 'normal', 'color': 'black'});
			}
				
		})
		.bind('blur', function() {
			if ($(this).attr('value') != defaultText) {
				$(this)
					.attr('value', defaultText)
					.css({'font-style': 'italic', 'color': '#999'});
			}
		});
	$('#searchform')
		.submit(function() {
			var query = $('#searchbox').attr('value');
			var lexindex = $('#lexindex').html();
			$('#leftbar').load('query.php?i=' + lexindex + '&q=' + query);
			return false;
		});
}



function alphaQueries() {	
	// modified to use URI strings instead, updated for CI
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

}




function wordLookup() {
	// updated for CI
	$('a.entrylink')
		.not('.external')
		.unbind()
		.bind('click', function(event) {
			event.preventDefault();
			var datasource = $(this).attr('href');
			console.log(datasource);
			$('#entryview').load(datasource);
			return false;
		});
	$('a.searchedentrylink')
		.unbind()
		.bind('click', function(event) {
			event.preventDefault();
			var index = $(this).attr('id');
			var query = $('#query').text();
			var datasource = base_url+ 'index.php/lexmanager/view_word/'+index+'/'+query;
			$('#entryview').load(datasource);
			return false;
		});
}