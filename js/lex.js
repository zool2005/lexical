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
			$('#leftbar').load(base_url+ 'index.php/ajax/query/'+lexindex+'/'+query+'/word');
//			$('#leftbar').load('query.php?i=' + lexindex + '&q=' + query);
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


function addComment() {

	uc = $('#user_feedback_div');
	var lex_ID = uc.data('lex_id');
	var entry_index = uc.data('entry_index');	

// Perform action on submit button click
	$('#user_feedback_submit').on('click', function() {


		feedback_content = $('#user_feedback_textarea').val();

		// If the lexicon_ID and entry_index_ID values have been supplied, add them to db_array for insert
		if (lex_ID && entry_index) {

			data_array = {
				'lex_ID': lex_ID,
				'entry_index': entry_index,
				'feedback_content': feedback_content 
			};
			// Run insert command via AJAX
			$.ajax({
				data: data_array,
				type: "POST",
				url: base_url + 'index.php/ajax/add_lexicon_comment',
				async: false,
				// If insert successful, update the lexicon comments on-the-fly without page refresh
				success: function() {
					$('#user_feedback_textarea').val('');
					var datasource = base_url + 'index.php/ajax/retrieve_lexicon_comments/'+lex_ID+'/'+entry_index;
					$('#user_feedback_div').load(datasource);
				}

			}); // end ajax
		}

	});


	$('.user_feedback_delete').on('click', function() {
		comment_id = $(this).data('comment_id');
		$.ajax({
			url: base_url + 'index.php/ajax/delete_lexicon_comment/'+comment_id,
			success: function() {
					var datasource = base_url + 'index.php/ajax/retrieve_lexicon_comments/'+lex_ID+'/'+entry_index;
					data = $('#comment_'+comment_id).fadeOut();
				}
		});
	});

} // end addComment function
