<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Typeform_leads extends CI_Controller 
{
	
	/**
	* @var stirng
	* @access Public
	*/
	public $selected_tab = '';
	
	/** 
	* Controller constructor
	* 
	* @access public 
	*/
	public function __construct()
	{
		parent::__construct();
		$this->selected_tab = 'home';
		$this->layout = 'publicsite';
		$this->load->model('leads_new_model', 'leads_new');
	}
	
	public function storeLead()
	{
		$this->createLog("<====================================>");
		$input = file_get_contents('php://input');
		$input = str_replace("'", '', $input);
		$input = json_decode($input , true);
		if(!empty($input['form_response']['answers'])){
			$answers = $input['form_response']['answers'];
			$save = [];
			$save['postal_code'] = $answers[0]['text'];
			$save['house_no'] = $answers[1]['text'];
			$save['name'] = $answers[2]['text'];
			$save['email'] = $answers[3]['email'];
			$save['property_type'] = $answers[4]['choice']['label'];
			$save['household_benefits'] = $answers[5]['choice']['label'];
			$save['residential_status'] = $answers[6]['choice']['label'];
			$save['floor_type'] = $answers[7]['choice']['label'];
			$save['fuel_type'] = $answers[8]['choice']['label'];
			$save['heating_system'] = $answers[9]['choice']['label'];
			$save['phone'] = $answers[10]['phone_number'];
			if($id = $this->leads_new->save($save)){

			}
			else{
				$this->createLog($this->db->last_query());
			}
		}
	}

	public function createLog($data = '')
	{
	    if(empty($data)){
	        return false;
	    }
	    $log_filename = "logsss";
	    if (!file_exists($log_filename)){
	        mkdir($log_filename, 0777, true);
	    }
	    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
	    file_put_contents($log_file_data, $data."\n\n", FILE_APPEND);
	    return false;
	}
	
}