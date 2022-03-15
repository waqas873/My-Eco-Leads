<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller 
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
		$this->selected_tab = 'settings';
		$this->layout = 'template';
        $this->load->model('Settings_model', 'settings');
		if(!$this->session->userdata('admin')){
            $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
            redirect('admin/auth/');
        }
	}
	
	public function index()
	{
        $data = [];
        $where = "name = 'lead_price'";
        $result = $this->settings->get_where('*', $where, true, '' , '', '');
        if(!empty($result)){
            $data['result'] = $result[0];
        }  
        $this->load->view('admin/settings/index', $data);
	}

    public function process_add()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $this->form_validation->set_rules('value','price','required|trim|numeric');
        $this->form_validation->set_rules('name','','required');
        if($this->form_validation->run()===TRUE)
        {
            $formData = $this->input->post();
            //debug($formData,true);
            $where = "name = '".$formData['name']."'";
            $data = $this->settings->get_where('*', $where, true, '' , '', '');
            if(!empty($data)){
                $update = [];
                $update['value'] = $formData['value'];
                $this->settings->update_by('name',$formData['name'],$update);
            }
            else{
                $id = $this->settings->save($formData);
            }
            $data['response'] = true;
            $this->session->set_flashdata('success_message',"Lead price has been updated successfully.");
        }
        else{
            $data['price_error'] = form_error('value');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

}