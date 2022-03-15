<?php

include_once('Abstract_model.php');

class Sms_invoices_model extends Abstract_model
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
        $this->table_name = "sms_invoices";
		parent::__construct();
    }

}