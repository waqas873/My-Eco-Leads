<?php

include_once('Abstract_model.php');

class Messages_model extends Abstract_model
{
	/**
	* @var stirng
	* @access protected
	*/
    protected $table_name = "";
	
	/** 
	*  Model constructor
	* 
	* @access public 
	*/
    public function __construct() 
	{
        $this->table_name = "messages";
		parent::__construct();
    }

}