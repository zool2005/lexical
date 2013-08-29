<div id="search">
	<form id="searchform" action="">
		<input type="text" size="50" id="searchbox" name="searchbox" />
		<input type="image" src="<?php echo base_url(); ?>images/search.png" value="Submit" id="submit" /><br/>
	</form>
</div>

<table class="alphabet">
   	<tr>
       	<?php
		// Split the alphabet into an array of individual letters, then output the alphabetical navigation
		$displayBuf = "";
		$alphabet_array = explode(" ", $alphabet);
		foreach($alphabet_array as $letter) 
		{
			$displayBuf .= "<td><a href=\"" . $lex_ID . "\" class=\"alpha\">" . $letter . "</a></td>";
		}
		echo($displayBuf . "\n");
		?>
	</tr>
</table>
