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

// PAGE TITLES & HEADLINES
$lang['title_login'] = 'Lexical Login';
$lang['headline_login'] = 'Lexical Login';
$lang['title_view_all'] = 'View All Lexicons';
$lang['headline_view_all'] = 'View All Lexicons';
$lang['title_view_all_entries'] = 'View All Lexicon Entries';
$lang['headline_view_all_entries'] = 'View All Lexicon Entries';
$lang['title_lex_admin'] = 'Lexical Administration';
$lang['headline_lex_admin'] = 'Lexical Administration';
$lang['title_lex_newentry'] = 'New Lexicon Entry';
$lang['headline_lex_newentry'] = 'New Lexicon Entry';
$lang['title_lex_editentry'] = 'Edit Lexicon Entry';
$lang['headline_lex_editentry'] = 'Edit Lexicon Entry';
$lang['title_new_lexicon'] = 'Create New Lexicon';
$lang['headline_new_lexicon'] = 'Create New Lexicon';
$lang['title_manage_users'] = 'Manage Users';
$lang['headline_manage_users'] = 'Manage Users';
$lang['title_register_user'] = 'Register User';
$lang['headline_register_user'] = 'Register User';
// FORM & FORM VALIDATION
$lang['val_id'] = 'ID';
$lang['val_status'] = 'Status';
$lang['val_first_name'] = 'First Name';
$lang['val_last_name'] = 'Last Name';
$lang['val_email'] = 'Email Address';
$lang['val_password'] = 'Password';
$lang['val_pass_conf'] = 'Password Conf.';
// USER DETAILS
$lang['first_name'] = 'First Name';
$lang['last_name'] = 'Last Name';
$lang['email_address'] = 'Email Address';
$lang['password'] = 'Password';
$lang['username'] = 'Username';
$lang['password'] = 'Password';
// USER TYPES
$lang['administrator'] = 'Administrator';
$lang['user'] = 'User';
// LINKS & ACTIONS
$lang['administrator_access'] = 'Administrator Access';
$lang['new_lexicon'] = 'New Lexicon';
$lang['list_of_lexicons'] = 'List of Lexicons';
$lang['logout'] = 'Logout';
$lang['login'] = 'Login';
$lang['register_user'] = 'Register User';
$lang['select_one'] = 'Select One : ';
$lang['btn_submit'] = 'Submit';
$lang['delete'] = 'Delete';
$lang['view_link'] = 'View';
$lang['edit_link'] = 'Edit';
$lang['action'] = 'Action';
$lang['delete_link'] = 'Delete';
$lang['next'] = 'Next';
$lang['add_field'] = 'Add Field';
$lang['create_lexicon'] = 'Create Lexicon';
$lang['glossary_name'] = 'Glossary Name';
$lang['add_fields'] = 'Add Fields';
// LEXICON DISPLAY
$lang['alphabet_and_collation'] = 'Alphabet and Collation';
$lang['show'] = 'Show';
$lang['entries_starting_from'] = 'entries starting from #';
$lang['lexicon_information'] = 'Lexicon Information';
$lang['lexicon_name'] = 'Lexicon Name';
$lang['total_entries'] = 'Total Entries';
$lang['date_created'] = 'Date Created';
$lang['date_last_edited'] = 'Date Last Edited';
$lang['view_lexicon'] = 'View Lexicon';
$lang['select_lexicon_from_list'] = 'Select a lexicon from the list to view';
$lang['lexicon_select_or_search_message'] = 'Enter a search term in the box above, or select a letter to browse.';
// SYSTEM MESSAGES
$lang['authentication_error'] = 'Authentication Error';
$lang['record_create_success'] = 'New Record Created';
$lang['record_create_failure'] = 'Record Creation Failed';
$lang['record_delete_success'] = 'Record Deleted';
$lang['record_update_success'] = 'Record Updated Successfully';
$lang['cannot_delete_primary_account'] = 'Cannot delete primary administrator account, please create another administrator account and try again';
$lang['sign_in_to_continue'] = 'Please sign in to continue';
$lang['no_lexicons_error'] = 'It appears you have no lexicons set up. If you would like to set up a new lexicon, please select "New Lexicon" above or contact your local administrator.';
$lang['no_dictionary_word_error'] = 'Error, the Word field must not be left blank. Please enter a new dictionary word with optional definition(s) and example(s).';
$lang['no_lexicons_found'] = 'No Lexicons Found';

// INFORMATION MESSAGES
$lang['welcome_message'] = 'Welcome to the <b>Lexical</b> Administration page.<br /> From here you can control all of the lexicons within <b>Lexical</b>. Select an option from the top right corner to create, import, and export lexicons, or select a specific lexicon in the list to the left to see the options available for that particular language.<br />
                    From a particular lexicon\'s page you can add, edit, and remove entries or modify the structure and appearance of the lexicon as a whole.';

// CREATE NEW LEXICON INSTRUCTIONS
$lang['instruction_new_glossary_name'] = 'Please enter the name for the new glossary (e.g. tourism, maritime, commerce etc) :';
$lang['instruction_select_lexicon_fields'] = 'Select the fields that will be available for every lexicon entry. Click and drag to reorder. A basic outline has already been provided :';
$lang['instruction_basic_text'] = 'Choose "Basic Text" for simple, short text fields, such as the word itself, pronunciation, translation, etc';
$lang['instruction_rich_text'] = 'Choose "Rich Text" for longer fields that may contain paragraphs, formatting, links, etc.';
$lang['instruction_list'] = 'Choose "List" for fields such as definition lists.';
$lang['instruction_hidden'] = 'Choose "Hidden" for fields that will not be visible on the public lexicon (for personal notes, plugins, etc';
$lang['word'] = 'Word';
$lang['pronunciation'] = 'Pronunciation';
$lang['part_of_speech'] = 'Part of Speech';
$lang['definition'] = 'Definition';
$lang['examples'] = 'Examples';
$lang['etymology'] = 'Etymology';
$lang['instruction_lexicon_alphabet'] = 'In the following field, list the language\'s alphabet (or, if not applicable, the alphabet used in romanization). Use capital letters only, separated by a space. This is what will appear in the top navigation bar of the lexicon. The standard Roman alphabet has been inserted below.';
$lang['instruction_lexicon_collation'] = "In the next field, describe the collation (alphabetical ordering) to be used. Group together all letters that are treated identically for collation. For instance, in English, upper and lowercase letters ('A' and 'a', 'B' and 'b', etc.) are considered to be variants of the same letter, and so when ordered alphabetically, words beginning with 'A' may be interspersed with words beginning with 'a'. In Spanish, the four glyphs 'A', 'a', 'Á', and 'á' are considered to be variants of the same letter, while 'Ñ' and 'ñ' are considered distinct from 'N' and 'n'. Use brackets to group together digraphs that should be treated as a single letter. Thus, the collations for English and Spanish would look as follows :";
