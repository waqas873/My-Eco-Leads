<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_apis extends CI_Controller 
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
		$this->selected_tab = 'user_apis';
		$this->layout = 'template';
        $this->load->model('user_api_model', 'user_api');
		if(!$this->session->userdata('admin')) {
        $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
        redirect('admin/auth/');
        }
	}
	
	public function index()
	{
		$data = [];
		$this->load->view('admin/user_apis/index', $data);
	}

    public function user_api_detail()
    {
        $this->layout = " ";
        $data = [];
        if(!$this->input->is_ajax_request()){
            exit;
        }
        $data['response'] = false;
        $user_api_id = $this->input->post('user_api_id');
        $where = "user_api.user_api_id = '".$user_api_id."'";
        $joins = array(
            '0' => array('table_name' => 'api api',
                'join_on' => 'api.api_id = user_api.api_id',
                'join_type' => 'left'
            )
        );
        $from_table = "user_api user_api";
        $select_from_table = 'user_api.*,api.api_name';
        $result = $this->user_api->get_by_join($select_from_table, $from_table, $joins, $where, '', '', '', '', '', '', '', '');

        $hubsolve = ['Hubsolv API Key','Username','Password','Lead Source','API Url'];
        $zeavo = ['API Url','API Key','Lead Group Id'];
        if(!empty($result)){
            $api_detail = $result[0]['api_settings'];
            $api_detail = json_decode($api_detail, true);
            $api_data = '';
            $ii = 0;
            foreach($api_detail as $info){
                $label = ($result[0]['api_name']=="zeavo")?$zeavo[$ii]:$hubsolve[$ii];
                $api_data .= '<span class="api_label">'.$label.'</span><br/>';
                $api_data .= '<span class="label_detail">'.$info.'</span><br/>';
                $ii++;
            }
            $data['api_detail'] = $api_data;
            $data['response'] = true;
        }
        echo json_encode($data);
    }

    public function update($user_api_id=0)
    {
        $this->layout = " ";
        $data = [];
        if(!$this->input->is_ajax_request()){
            exit;
        }
        $data['response'] = false;
        $where = "user_api.user_api_id = '".$user_api_id."'";
        $joins = array(
            '0' => array('table_name' => 'api api',
                'join_on' => 'api.api_id = user_api.api_id',
                'join_type' => 'left'
            )
        );
        $from_table = "user_api user_api";
        $select_from_table = 'user_api.*,api.api_name';
        $result = $this->user_api->get_by_join($select_from_table, $from_table, $joins, $where, '', '', '', '', '', '', '', '');
        if(!empty($result)){
            $data['response'] = true;
            $result = $result[0];
            $api_detail = $result['api_settings'];
            $api_detail = json_decode($api_detail, true);
            $data['api_name'] = $result['api_name'];
            $data['user_api_id'] = $result['user_api_id'];
            if($data['api_name']=="hubsolv"){
                $data['hubsolv_api_key'] = $api_detail['hubsolv_api_key'];
                $data['username'] = $api_detail['username'];
                $data['password'] = $api_detail['password'];
                $data['api_url'] = $api_detail['api_url'];
            }
            if($data['api_name']=="zeavo"){
                $data['zeavo_api_url'] = $api_detail['api_url'];
                $data['api_key'] = $api_detail['api_key'];
                $data['lead_group_id'] = $api_detail['lead_group_id'];
            }
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
        $user_api_id = $this->input->post('user_api_id');
        $api_name = $this->input->post('api_name');
        if($api_name=="hubsolv"){
            $this->form_validation->set_rules('hubsolv_api_key','hubsolv api key','required|trim');
            $this->form_validation->set_rules('username','username','required|trim');
            $this->form_validation->set_rules('password','password','required|trim');
            $this->form_validation->set_rules('api_url','api url','required|trim');
        }
        else{
            $this->form_validation->set_rules('api_key','api key','required|trim');
            $this->form_validation->set_rules('lead_group_id','lead group id','required|trim');
            $this->form_validation->set_rules('zeavo_api_url','api url','required|trim');
        }
        if($this->form_validation->run()===TRUE){
            $api_detail = $this->input->post();
            unset($api_detail['user_api_id']);
            unset($api_detail['api_name']);
            if($api_name=="zeavo"){
                $api_detail['api_url'] = $this->input->post('zeavo_api_url');
                unset($api_detail['zeavo_api_url']);
            }
            $api_detail = json_encode($api_detail);
            $update = ['api_settings'=>$api_detail];
            $this->user_api->update_by('user_api_id',$user_api_id,$update);
            $data['response'] = true;
        }
        else{
            if($api_name=="hubsolv"){
                $data['hubsolv_api_key_error'] = form_error('hubsolv_api_key');
                $data['username_error'] = form_error('username');
                $data['password_error'] = form_error('password');
                $data['api_url_error'] = form_error('api_url');
            }
            else{
                $data['api_key_error'] = form_error('api_key');
                $data['lead_group_id_error'] = form_error('lead_group_id');
                $data['zeavo_api_url_error'] = form_error('zeavo_api_url');
            }
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

	public function get_user_apis()
    {
        $this->layout = '';
        $like = array();
        $user_apis_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        if($orderByColumn=="user_full_name" || $orderByColumn=="email" ||$orderByColumn=="api_name"){
            $orderByColumn = "user_api.created_at";
        }
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $user_apis_count = $this->user_api->count_rows();

        $where = "user_api.user_api_id > 0";
        if(isset($search) && $search != ''){
            $where .= " AND (api.api_name  LIKE CONCAT('%','" . $search . "' ,'%') OR users.first_name LIKE CONCAT('%','" . $search . "' ,'%') OR users.last_name LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'users users',
                'join_on' => 'users.user_id = user_api.user_id',
                'join_type' => 'left'
            ),
            '1' => array('table_name' => 'api api',
                'join_on' => 'api.api_id = user_api.api_id',
                'join_type' => 'left'
            )
        );
        $from_table = "user_api user_api";
        $select_from_table = 'user_api.*,api.api_name,users.first_name,users.last_name,users.email';
        $user_apis_data = $this->user_api->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($user_apis_data,true);
        $user_apis_count_rows = $this->user_api->get_by_join_total_rows('*', $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', '', '');

        if(isset($user_apis_data)){
        	foreach($user_apis_data as $item){
                $single_field['user_full_name'] = $item['first_name'].' '.$item['last_name'];
                $single_field['email'] = $item['email'];
                $single_field['api_name'] = $item['api_name'];
                $single_field['created_at'] = $item['created_at'];
                $single_field['action'] = "<a href='javascript::' rel='".$item['user_api_id']."' class='edit_user_api'><i class='ace-icon fa fa-pencil bigger-130'></i></a> &nbsp;| <a href='javascript::' rel='".$item['user_api_id']."' class='user_api_detail'>Detail</a>";
                $user_apis_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $user_apis_count;
            $data['recordsFiltered'] = $user_apis_count_rows;
            $data['data'] = $user_apis_array;
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