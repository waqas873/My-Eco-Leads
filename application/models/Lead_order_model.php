<?php

include_once('Abstract_model.php');

class Lead_order_model extends Abstract_model
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
        $this->table_name = "lead_order";
		parent::__construct();
    }

}