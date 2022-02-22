<?php
/*
** Admin model
** Model to save/update/delete Admin Users
*/
include_once('Abstract_model.php');

class Admin_model extends Abstract_model {

    protected $table_name = "";	
    function __construct() {
        $this->table_name = "users";
		parent::__construct();
    }

    public function uniqueLogin($loginEmail='', $id, $userName='')
    {
		if(isset($loginEmail) && !empty($loginEmail) && empty($userName))
		{
	        $this->db->where('email', $loginEmail);
		}
		else if(isset($userName) && !empty($userName) && empty($loginEmail))
		{
	        $this->db->where('uname',$userName);
		}
		if($id != "ci_validation"){
			$this->db->where('user_id !=', $id);
        }
			
        if($result=$this->db->get($this->table_name, 1)->result('array'))
        {
           if(!empty($result))
                return true;
        }
        return false;
    }
    
}