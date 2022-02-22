<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companies extends CI_Controller 
{
	
	/**
	* @var stirng
	* @access Public
	*/
	public $selected_tab = '';

	public $user_id = '';
	
	/** 
	* Controller constructor
	* 
	* @access public 
	*/
	public function __construct()
	{
		parent::__construct();
		$this->selected_tab = 'companies';
		$this->layout = 'user_dashboard';
		$this->load->model('users_model', 'users');
		$this->load->model('companies_model', 'companies');
		if(!$this->session->userdata('user')){
		    $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
		    redirect('sign-in');
	    }
	    $this->user_id = $this->session->userdata('user_id');
	}
	
	public function index()
	{
		$data = [];
		$company = company($this->user_id);
		if(!empty($company)){
           $data['data'] = $company;
		}
		$this->load->view('companies/index', $data);
	}

	public function process_company()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$this->form_validation->set_rules('user_name','name','required|trim');
		$this->form_validation->set_rules('name','company name','required|trim');
		$this->form_validation->set_rules('registration_no','registration no','required|trim');
		$this->form_validation->set_rules('fca_license_no','fca/license no','required|trim');
		$this->form_validation->set_rules('address','address','required|trim');
		$this->form_validation->set_rules('contact_no','contact no','required|min_length[11]|max_length[11]|numeric');
		if($this->form_validation->run()===TRUE){
			$update = false;
			$company = company($this->user_id);
			if(!empty($company)){
                $company_id = $company['company_id'];
                $update = true;
			}
			$company = $this->input->post();
			$message = '';
			if($update){
				$company['status'] = 0;
                $this->companies->update_by('company_id',$company_id,$company);
                $data['msg']="your company has been updated successfully.Wait for admin approvel"; 
                $message = "A client has updated his company registration and waiting for your approvel.";
			}
			else{
				$company['user_id'] = $this->user_id;
				$this->companies->save($company);
				$data['msg']="your company has been added successfully.";
				$message = "A client has registered his company and waiting for your approvel.";
			}
			send_email_to("Dear Admin",ADMIN_EMAIL,$message,'Company Registration');
			$data['response'] = true;
		}
		else{
			$data['user_name_error'] = form_error('user_name');
			$data['name_error'] = form_error('name');
            $data['registration_no_error'] = form_error('registration_no');
            $data['fca_license_no_error'] = form_error('fca_license_no');
			$data['address_error'] = form_error('address');
            $data['contact_no_error'] = form_error('contact_no');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}
	
}