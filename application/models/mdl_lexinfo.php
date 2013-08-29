<?php if(!defined('BASEPATH')) exit('No direct access is allowed');

class Mdl_lexinfo extends MY_Model {

    public $table               = 'lexinfo';
    public $primary_key         = 'index_ID';

	function __construct()
    {
        parent::__construct();
	}


}