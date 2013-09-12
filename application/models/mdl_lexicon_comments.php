<?php if(!defined('BASEPATH')) exit('No direct access is allowed');
/*
+-----------------------------------------------------------------------------------------------+
| Lexical, a web-based dictionary management ported to the CodeIgniter framework from           |
| LexManager, created by Martin Posthumus                                                       |
| Original Website : http://www.veche.net/programming/lexmanager.html                           |
| Original Source Code on GitHub : https://github.com/voikya/LexManager                         |
|                                                                                               |
| Lexical is free and open-source. You may redistribute and/or modify Lexical under the terms | 
| of the GNU General Public  License (GPL) as published by the Free Software Foundation,        |
| either version 3 of the license or any later version.                                         |
|                                                                                               |
| Lexical comes with no warranty for loss of data, as per the GPL3 license.                     |
+-----------------------------------------------------------------------------------------------+
*/
class Mdl_lexicon_comments extends MY_Model {

    public $table               = 'lexicon_comments';
    public $primary_key         = 'Index_ID';

	function __construct()
    {
        parent::__construct();
	}


    
    public function retrieve_lexicon_comments($lex_ID, $entry_index)
    {
        $query = $this->db
                ->join('lex_userinfo', 'lex_userinfo.uid = lexicon_comments.user_id', 'left')
                ->where('lexicon_ID', $lex_ID)
                ->where('entry_index_ID', $entry_index)
                ->order_by('DateCreated', 'asc')
                ->get($this->table);

        if ($query)
        {
            $display_buf = '';
            foreach ($query->result_array() as $row)
            {
                $display_buf .= '<div class="lexicon_comments" id="comment_'.$row['Index_ID'].'">';
                $image_properties = array('src' => 'images/trash.png', 'class' => 'user_feedback_delete', 'title' => 'Delete Comment', 'data-comment_id' => $row['Index_ID']);
                $display_buf .= '<p style="font-style: italic; padding: 2px;">Comment left by  <b>'.$row['first_name'].'</b> on '.date('D jS M y \a\t H:i', strtotime($row['DateCreated'])).'&nbsp;&nbsp;&nbsp;'.img($image_properties).'</p>';
                $display_buf .= '<p style="margin-left: 20px; padding: 2px">'.$row['comment'].'</p>';
                $display_buf .= '</div>';
            }
            return $display_buf;
        }
    }



}

/*
    public function validation_rules()
    {
        return array(
            'status'          => array(
                'field' => 'status',
                'label' => $this->lang->line('val_status'),
                'rules' => 'trim|required|numeric'
            ),
            'first_name'      => array(
                'field' => 'first_name',
                'label' => $this->lang->line('val_first_name'),
                'rules' => 'trim|required|strip_tags|min_length[3]|max_length[15]'
            ),
            'last_name'      => array(
                'field' => 'last_name',
                'label' => $this->lang->line('val_last_name'),
                'rules' => 'trim|required|strip_tags|strtoupper|min_length[3]|max_length[20]'
            ),
            'email_address'      => array(
                'field' => 'email_address',
                'label' => $this->lang->line('val_email'),
                'rules' => 'trim|required|valid_email'
            ),
            'password'      => array(
                'field' => 'password',
                'label' => $this->lang->line('val_password'),
                'rules' => 'trim|required|strip_tags|min_length[7]|matches[passconf]|sha1'
            )

		);

	}
*/

