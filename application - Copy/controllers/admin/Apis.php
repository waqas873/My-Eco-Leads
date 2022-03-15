<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apis extends CI_Controller 
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
		$this->selected_tab = 'api';
		$this->layout = 'template';
        $this->load->model('apis_model', 'api');
		if(!$this->session->userdata('admin')) {
        $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
        redirect('admin/auth/');
        }
	}
	
	public function index()
	{
		$data = [];
		$this->load->view('admin/apis/index', $data);
	}

    public function process_add()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $data['api_name_error'] = '';
        $this->form_validation->set_rules('api_name','api name','required|trim');
        if($this->form_validation->run()===TRUE){
            $api = $this->input->post();
            if($api_id = $this->api->save($api)) {
                $data['response'] = true;
            }
        }
        else{
            $data['api_name_error'] = form_error('api_name');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

    public function update($api_id=0)
    {
        $this->layout = " ";
        $data = [];
        if(!$this->input->is_ajax_request()){
            exit;
        }
        $data['response'] = false;
        $where = "api_id='".$api_id."'";
        $api = $this->api->get_where('*', $where, true, '' , '', '');
        if(!empty($api)){
            $data['response'] = true;
            $data['api'] = $api[0];
            $arr = [0,1];
            $status = '';
            foreach($arr as $ar){
                $display = ($ar==0)?'Inactive':'Active';
                $selected = ($ar==$api[0]['status'])?'selected':'';
                $status .= '<option value="'.$ar.'" '.$selected.'>'.$display.'</option>';
            }
            $data['status'] = $status;
        }
        echo json_encode($data);
    }

    public function process_update()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $api_id = $this->input->post('api_id');
        $this->form_validation->set_rules('api_name','api name','required|trim');
        $this->form_validation->set_rules('status','','required');
        if($this->form_validation->run()===TRUE){
            $api = $this->input->post();
            unset($api['api_id']);
            $this->api->update_by('api_id',$api_id,$api);
            $data['response'] = true;
        }
        else{
            $data['api_name_error'] = form_error('api_name');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

	public function get_apis()
    {
        $this->layout = '';
        $like = array();
        $apis_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $apis_count = $this->api->count_rows();

        $where = "api.api_id > 0";
        if (isset($search) && $search != '') {
            $where .= " AND (api.api_name  LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array();
        $from_table = "api api";
        $select_from_table = 'api.*';
        $apis_data = $this->api->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($apis_data,true);
        $apis_count_rows = $this->api->get_by_join_total_rows('*', $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($apis_data)) {
        	foreach ($apis_data as $item) {
                $single_field['api_name'] = $item['api_name'];
                $single_field['status'] = ($item['status']==1)?'Active':'Inactive';
                $single_field['created_at'] = $item['created_at'];
                $single_field['action'] = "<a href='javascript::' rel='".$item['api_id']."' class='edit_api'><i class='ace-icon fa fa-pencil bigger-130'></i></a> &nbsp; <a href='".base_url('admin/apis/delete/'.$item['api_id'])."' onclick='delete_record_dt(this); return false;'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
                $apis_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $apis_count;
            $data['recordsFiltered'] = $apis_count_rows;
            $data['data'] = $apis_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

    public function delete($api_id)
    {
        if (isset($api_id) && !empty($api_id)) {

            $this->api->delete_by('api_id', $api_id);
            $this->session->set_flashdata('success_message', 'API has been deleted successfully');
        } else {
            $this->session->set_flashdata('error_message', 'Invalid request to delete API.');
        }
        redirect('admin/apis/');
    }
}