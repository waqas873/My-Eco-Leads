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

	public function saveLead()
	{
		$data = [];
		$data['response'] = false;
		$data['msg'] = '';
		if(empty($this->input->post())){
			$data['response'] = "error";
		    $data['msg'] = 'Invalid request';
		    echo json_encode($data); exit;
		}
		$formData = $this->input->post();
		//$formData = ['property_type'=>'hello','floor_type'=>'test'];
		$save = [];
		$save['postal_code'] = (!empty($formData['postal_code']))?$formData['postal_code']:'N/A';
		$save['house_no'] = (!empty($formData['house_no']))?$formData['house_no']:'N/A';
		$save['name'] = (!empty($formData['name']))?$formData['name']:'N/A';
		$save['email'] = (!empty($formData['email']))?$formData['email']:'N/A';
		$save['property_type'] = (!empty($formData['property_type']))?$formData['property_type']:'N/A';
		$save['household_benefits'] = (!empty($formData['household_benefits']))?$formData['household_benefits']:'N/A';
		$save['residential_status'] = (!empty($formData['residential_status']))?$formData['residential_status']:'N/A';
		$save['floor_type'] = (!empty($formData['floor_type']))?$formData['floor_type']:'N/A';
		$save['fuel_type'] = (!empty($formData['fuel_type']))?$formData['fuel_type']:'N/A';
		$save['heating_system'] = (!empty($formData['heating_system']))?$formData['heating_system']:'N/A';
		$save['phone'] = (!empty($formData['phone']))?$formData['phone']:'N/A';
		if($id = $this->leads_new->save($save)){
            $data['response'] = "SUCCESS";
		    $data['msg'] = 'Lead saved successfully';
		    echo json_encode($data); exit;
		}
		$this->createLog($this->db->last_query());
		$data['response'] = "error";
	    $data['msg'] = 'Error occured while saving lead';
	    echo json_encode($data); exit;
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