<?php

include_once('Abstract_model.php');

class Leads_new_model extends Abstract_model
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
        $this->table_name = "leads_new";
		parent::__construct();
    }

}