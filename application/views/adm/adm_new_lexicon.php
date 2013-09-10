<div id="leftbar">
	<?php $this->load->view('adm/adm_lex_list_partial'); ?>
</div>


<div id="entryview">

<!-- The form consists of three virtual pages (actually a single page) managed by JavaScript in admin.js -->

	<?php $attributes = array('id' => 'addlex'); echo form_open('lex_admin/adm_new_lexicon', $attributes); ?>

<!-- VIRTUAL PAGE ONE -->
	<fieldset id="language_name"><legend><?php echo $this->lang->line('glossary_name'); ?></legend>
		<p><?php echo $this->lang->line('instruction_new_glossary_name'); ?></p>
			<input type="text" name="lang" size="50">
		<p></p>
			<input type="button" class="next" id="toFields" value="<?php echo $this->lang->line('next'); ?> &gt;">
	</fieldset>

<!-- VIRTUAL PAGE TWO -->
	<fieldset id="fields"><legend><?php echo $this->lang->line('add_fields'); ?></legend>
		<p><?php echo $this->lang->line('instruction_select_lexicon_fields'); ?></p>
		<ul>
			<li><?php echo $this->lang->line('instruction_basic_text'); ?></li>
			<li><?php echo $this->lang->line('instruction_rich_text'); ?></li>
			<li><?php echo $this->lang->line('instruction_list'); ?></li>
			<li><?php echo $this->lang->line('instruction_hidden'); ?></li>
		</ul>

		<div id="fieldlist">
			<div class="fieldcontainer idfield">
				<div class="onefield">
					<table>
						<tr>
							<td><select disabled="disabled"><option value="id" selected="yes">ID</option><option value="text">Basic Text</option><option value="rich">Rich Text</option><option value="list">List</option><option value="hidden">Hidden</option></select></td>
							<td><input type="text" size="50" value="Index_ID" disabled="disabled"></td>
						</tr>
					</table>
				</div>
				<div class="onefield_break"></div>
			</div>

			<div class="fieldcontainer idfield">
				<div class="onefield">
					<table>
						<tr>
							<td><select disabled="disabled"><option value="text" selected="yes">Basic Text</option></select></td>
							<td><input type="text" size="50" value="<?php echo $this->lang->line('word'); ?>" disabled="disabled"></td>
						</tr>
					</table>
				</div>
				<div class="onefield_break"></div>
			</div>

			<div class="fieldcontainer">
				<div class="onefield">
					<table>
						<tr>
							<td><select><option value="text" selected="yes">Basic Text</option><option value="rich">Rich Text</option><option value="list">List</option><option value="hidden">Hidden</option></select></td>
							<td><input type="text" size="50" value="<?php echo $this->lang->line('pronunciation'); ?>"></td>
							<td><a href="#" class="remove_link">X</a></td>
						</tr>
					</table>
				</div>
				<div class="onefield_break"></div>
			</div>
			
			<div class="fieldcontainer">
				<div class="onefield">
					<table>
						<tr>
							<td><select><option value="text" selected="yes">Basic Text</option><option value="rich">Rich Text</option><option value="list">List</option><option value="hidden">Hidden</option></select></td>
							<td><input type="text" size="50" value="<?php echo $this->lang->line('part_of_speech'); ?>"></td>
							<td><a href="#" class="remove_link">X</a></td>
						</tr>
					</table>
				</div>
				<div class="onefield_break"></div>
			</div>
			
			<div class="fieldcontainer">
				<div class="onefield">
					<table>
						<tr>
							<td><select><option value="text">Basic Text</option><option value="rich">Rich Text</option><option value="list" selected="yes">List</option><option value="hidden">Hidden</option></select></td>
							<td><input type="text" size="50" value="<?php echo $this->lang->line('definition'); ?>"></td>
							<td><a href="#" class="remove_link">X</a></td>
						</tr>
					</table>
				</div>
				<div class="onefield_break"></div>
			</div>

			<div class="fieldcontainer">
				<div class="onefield">
					<table>
						<tr>
							<td><select><option value="text">Basic Text</option><option value="rich" selected="yes">Rich Text</option><option value="list">List</option><option value="hidden">Hidden</option></select></td>
							<td><input type="text" size="50" value="<?php echo $this->lang->line('examples'); ?>"></td>
							<td><a href="#" class="remove_link">X</a></td>
						</tr>
					</table>
				</div>
				<div class="onefield_break"></div>
			</div>

			<div class="fieldcontainer">
				<div class="onefield">
					<table>
						<tr>
							<td><select><option value="text">Basic Text</option><option value="rich" selected="yes">Rich Text</option><option value="list">List</option><option value="hidden">Hidden</option></select></td>
							<td><input type="text" size="50" value="<?php echo $this->lang->line('etymology'); ?>"></td>
							<td><a href="#" class="remove_link">X</a></td>
						</tr>
					</table>
				</div>
				<div class="onefield_break"></div>
			</div>

		</div> <!-- END OF FIELDLIST DIV -->
				
		<input type="button" id="addfield" value="<?php echo $this->lang->line('add_field'); ?>">
		<input type="button" class="next" id="toCollation" value="<?php echo $this->lang->line('next'); ?> &gt;">
	</fieldset> <!-- END OF FIELDS FIELDSET -->



<!-- VIRTUAL PAGE THREE -->
	<fieldset id="collation"><legend><?php echo $this->lang->line('alphabet_and_collation'); ?></legend>

		<p><?php echo $this->lang->line('instruction_lexicon_alphabet'); ?></p>

		<textarea rows="5" cols="50" name="alphabet">A B C D E F G H I J K L M N O P Q R S T U V W X Y Z</textarea>

		<p><?php echo $this->lang->line('instruction_lexicon_collation'); ?></p>

		<p>English: Aa Bb Cc Dd Ee Ff Gg Hh Ii Jj Kk Ll Mm Nn Oo Pp Qq Rr Ss Tt Uu Vv Ww Xx Yy Zz</p>

		<p>Spanish: AaÁá Bb Cc [CH][Ch][ch] Dd EeÉé Ff Gg Hh Ii Jj Kk Ll [LL][Ll][ll] Mm Nn Ññ OoÓó Pp Qq Rr [RR][Rr][rr] Ss Tt UuÚúÜü Vv Ww Xx Yy Zz</p>

		<textarea rows="5" cols="50" name="collation">Aa Bb Cc Dd Ee Ff Gg Hh Ii Jj Kk Ll Mm Nn Oo Pp Qq Rr Ss Tt Uu Vv Ww Xx Yy Zz</textarea>

		<p></p>

		<input type="submit" class="next" name="submit" value="<?php echo $this->lang->line('create_lexicon'); ?>">

	</fieldset>

<?php echo form_close(); ?>

        <noscript>
        	<p class="statictext warning">This page requires that JavaScript be enabled.</p>
        </noscript>
        <br/><br/>
</div>