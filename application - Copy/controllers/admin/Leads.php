<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leads extends CI_Controller 
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
		$this->selected_tab = 'leads';
		$this->layout = 'template';
		$this->load->model('users_model', 'users');
        $this->load->model('lists_model', 'lists');
        $this->load->model('leads_model', 'leads');
        $this->load->model('lead_order_model', 'lead_order');
        $this->load->model('orders_model', 'orders');
        $this->load->model('notes_model', 'notes');
		if(!$this->session->userdata('admin')) {
        $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
        redirect('admin/auth/');
        }
	}
	
	public function index($list_id=0)
	{
        $data = [];
        //$data['list_id'] = decodeBase64($list_id);
        $users = $this->leads->get_where('user_id','', true, '','', 'user_id');
        $all_users = [];
        foreach ($users as $user) {
            $where = "users.user_id = '".$user['user_id']."'";
            $su = $this->users->get_where('user_id,first_name,last_name',$where, true, '','', '');
            if(!empty($su)){
                $all_users[] = $su[0];
            }
        }
        $data['users'] = $all_users;
		$this->load->view('admin/leads/index', $data);
	}

    public function invalid_leads()
    {
        $this->selected_tab = 'invalid_leads';
        $data = [];
        //$data['list_id'] = decodeBase64($list_id);
        $where = "orders.status = 0";
        $users = $this->orders->get_where('user_id',$where, true, '','', 'user_id');
        $all_users = [];
        foreach ($users as $user) {
            $where = "users.user_id = '".$user['user_id']."'";
            $su = $this->users->get_where('user_id,first_name,last_name',$where, true, '','', '');
            if(!empty($su)){
                $all_users[] = $su[0];
            }
        }
        $data['users'] = $all_users;
        $this->load->view('admin/leads/invalid_leads', $data);
    }

    public function process_invalid_leads()
    {
        $data = [];
        $user_id = $this->input->post('form_user_id');
        if(empty($user_id)){
            $this->session->set_flashdata('error_message', 'Please select a user.');
            redirect('admin/leads/invalid_leads/');
        }
        $api = $this->input->post('simple_check');
        $force = $this->input->post('force_check');
        if(empty($api) && empty($force)){
            $this->session->set_flashdata('error_message', 'Please select leads.');
            redirect('admin/leads/invalid_leads/');
        }
        
        if(!empty($force)){
            foreach($force as $lead_id){
                $update_lead = update_leads($user_id,$lead_id);
            }
        }

        if(!empty($api)){
            foreach($api as $lead_id){
                $where = "leads.lead_id = '".$lead_id."'";
                $result = $this->leads->get_where('*',$where, true, '','', '');
                $result = $result[0];
                $num = $result['contact_mobile'];
                $phone_no = lead_phone_number($num);
                if($phone_no['is_valid']===true){
                    $post_hub = array('firstname'=>$result['first_name'],'lastname'=>$result['last_name'],'email'=>$result['email'],'phone_mobile'=>$result['contact_mobile'],'lead_generator'=>'debt advice service','tags'=>$result['tags']);
                    hub($user_id,$lead_id,$post_hub);
                    $update_lead = update_leads($user_id,$lead_id);
                }
            }
        }
        $this->session->set_flashdata('success_message', 'Leads delivered successfully.');
        redirect('admin/leads/invalid_leads/');
    }

	public function get_leads()
    {
        $this->layout = '';
        $like = array();
        $leads_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $status_filter = $this->input->post('status_filter');
        //$list_id = $this->input->post('list_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $user_id = $this->input->post('user_id');
        $action_filter = $this->input->post('action_filter');
        $validation_filter = $this->input->post('validation_filter');
        
        if( (isset($from_date) && $from_date != '') && (isset($to_date) && $to_date != '') ){
            $from_date = $from_date.' 00:00:00';
            $to_date = $to_date.' 23:00:00';
            $where = "leads.created_at BETWEEN '".$from_date."' AND '".$to_date."'";
        }
        else{
            $where = "leads.lead_id > 0";
        }

        if(isset($validation_filter) && $validation_filter != ''){
            $where .= ($validation_filter=='0')?" AND leads.user_id = 0":" AND leads.user_id > 0";
        }

        if( ($from_date != '' && $to_date == '') || ($to_date != '' && $from_date == '') ) {
           $date = ($from_date!='')?$from_date:$to_date;
           $where .= " AND (leads.created_at  LIKE CONCAT('%','".$date."' ,'%') )";
        }

        $leads_count = $this->leads->count_rows($where);

        if(isset($status_filter) && $status_filter != ''){
            $where .= " AND leads.status ='" . $status_filter . "'";
        }

        if(isset($user_id) && $user_id != ''){
            $where .= " AND leads.user_id = '".$user_id."'";
        }

        if(isset($action_filter) && $action_filter != ''){
            $where .= " AND leads.lead_action ='".$action_filter."'";
        }

        if (isset($search) && $search != ''){
            $where .= " AND (leads.first_name  LIKE CONCAT('%','" . $search . "' ,'%') OR leads.last_name LIKE CONCAT('%','" . $search . "' ,'%') OR leads.email LIKE CONCAT('%','" . $search . "' ,'%') OR leads.contact_mobile LIKE CONCAT('%','" . $search . "' ,'%') OR leads.lead_info LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'users users',
                'join_on' => 'users.user_id = leads.user_id',
                'join_type' => 'left'
            )
        );
        $from_table = "leads leads";
        $select_from_table = 'leads.*,users.first_name as user_first_name,users.last_name as user_last_name';
        $leads_data = $this->leads->get_by_join($select_from_table, $from_table, $joins, $where, "leads.".$orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($leads_data,true);
        $leads_count_rows = $this->leads->get_by_join_total_rows('*', $from_table, $joins, $where, "leads.".$orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($leads_data)) {
        	foreach ($leads_data as $item) {
                $single_field['first_name'] = $item['first_name'];
                $single_field['last_name'] = $item['last_name'];
                $single_field['email'] = $item['email'];
                $single_field['contact_mobile'] = $item['contact_mobile'];
                $single_field['confirm_mobile_number'] = (empty($item['confirm_mobile_number']))?"---":$item['confirm_mobile_number'];
                $single_field['best_time_to_call'] = (empty($item['best_time_to_call']))?"---":$item['best_time_to_call'];
                $single_field['status'] = ($item['user_id']==0)?'Invalid':'Valid';

                $single_field['user_fullname'] = $item['user_first_name'].' '.$item['user_last_name'];
                $id = $item['lead_id'];
                $action = $item['lead_action'];
                if(!empty($action)){
                    $explode = explode('_', $action);
                    $action = ucwords($explode[0]);
                    if(isset($explode[1])){
                        $action .= ' '.ucwords($explode[1]);
                    }
                }
                $single_field['action'] = $action;
                $single_field['notes'] = '<a href="javascript::" class="view_notes btn btn-info btn-sm" rel="'.$id.'">View Notes</a>';
                $single_field['lead_info'] = (!empty($item['lead_info']))?'<a href="javascript::" class="btn btn-info btn-sm lead_info" rel="'.$id.'">Info</a>':'---';
                $appeal = '<a href="'.base_url('admin/leads/appeal_approve/'.createBase64($id)).'" class="btn btn-info btn-sm" onclick="delete_record_dt(this); return false;">Approve</a> <a href="'.base_url('admin/leads/appeal_reject/'.createBase64($id)).'" class="btn btn-danger btn-sm" onclick="delete_record_dt(this); return false;">Reject</a>';
                ($item['lead_appeal']==0)?$appeal="Not Appealed":'';
                ($item['lead_appeal']==2)?$appeal="Rejected":'';
                ($item['lead_appeal']==1)?$appeal="Approved":'';
                $single_field['lead_appeal'] = $appeal;
                $single_field['created_at'] = $item['created_at'];
                $leads_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $leads_count;
            $data['recordsFiltered'] = $leads_count_rows;
            $data['data'] = $leads_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

    public function view_notes()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $data['view_notes'] = '';
        $lead_id = $this->input->post('lead_id');
        $where = "lead_id = '".$lead_id."'";
        $result = $this->notes->get_where('*', $where, true, '' , '', '');
        if(!empty($result)){
            $note_data = '';
            foreach($result as $value){
                $note_data .= '<div id="note'.$value['note_id'].'"><span class="answer note_des">'.$value['description'].'.</span> <br/><span class="ndate">'.$value['created_at'].'.</span><hr></div>';
            }
            //debug($lead_info,true);
            $data['view_notes'] = $note_data;
            $data['response'] = true;
        }   
        echo json_encode($data);
    }

    public function get_invalid_leads()
    {
        $this->layout = '';
        $like = array();
        $leads_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        if($orderByColumn=="simple_check" || $orderByColumn=="force_check"){
            $orderByColumn = 'first_name';
        }
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        
        if( (isset($from_date) && $from_date != '') && (isset($to_date) && $to_date != '') ){
            $from_date = $from_date.' 00:00:00';
            $to_date = $to_date.' 23:00:00';
            $where = "leads.created_at BETWEEN '".$from_date."' AND '".$to_date."' AND leads.user_id = 0";
        }
        else{
            $where = "leads.user_id = 0";
        }

        if( ($from_date != '' && $to_date == '') || ($to_date != '' && $from_date == '') ) {
           $date = ($from_date!='')?$from_date:$to_date;
           $where .= " AND (leads.created_at  LIKE CONCAT('%','".$date."' ,'%') )";
        }

        $leads_count = $this->leads->count_rows($where);

        if(isset($search) && $search != ''){
            $where .= " AND (leads.first_name  LIKE CONCAT('%','" . $search . "' ,'%') OR leads.last_name LIKE CONCAT('%','" . $search . "' ,'%') OR leads.email LIKE CONCAT('%','" . $search . "' ,'%') OR leads.contact_mobile LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array();
        $from_table = "leads leads";
        $select_from_table = 'leads.*';
        $leads_data = $this->leads->get_by_join($select_from_table, $from_table, $joins, $where, "leads.".$orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($leads_data,true);
        $leads_count_rows = $this->leads->get_by_join_total_rows('*', $from_table, $joins, $where, "leads.".$orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($leads_data)) {
            foreach ($leads_data as $item) {
                $single_field['simple_check'] = '<input type="checkbox" name="simple_check[]" class="simple_check" id="simple_check_'.$item['lead_id'].'" value="'.$item['lead_id'].'">';
                $single_field['force_check'] = '<input type="checkbox" class="force_check" id="force_check_'.$item['lead_id'].'" name="force_check[]"  value="'.$item['lead_id'].'">';
                $single_field['first_name'] = $item['first_name'];
                $single_field['first_name'] = $item['first_name'];
                $single_field['last_name'] = $item['last_name'];
                $single_field['email'] = $item['email'];
                $single_field['contact_mobile'] = $item['contact_mobile'];
                $id = $item['lead_id'];
                $single_field['lead_info'] = (!empty($item['lead_info']))?'<a href="javascript::" class="btn btn-info btn-sm lead_info" rel="'.$id.'">Info</a>':'---';
                $single_field['created_at'] = $item['created_at'];
                $leads_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $leads_count;
            $data['recordsFiltered'] = $leads_count_rows;
            $data['data'] = $leads_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

    public function lead_info()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $data['lead_info'] = '';
        $lead_id = $this->input->post('lead_id');
        $where = "lead_id = '".$lead_id."'";
        $result = $this->leads->get_where('*', $where, true, '' , '', '');
        if(!empty($result)){
            $lead_info = $result[0]['lead_info'];
            $lead_info = json_decode($lead_info, true);
            $lead_data = '';
            foreach($lead_info as $key => $info){
                $info = str_replace("u00a3","<span>&#163;</span>",$info);
                $lead_data .= '<span class="question">'.$key.'</span><br/>';
                $lead_data .= '<span class="answer">'.$info.'</span><br/>';
            }
            //debug($lead_info,true);
            $data['lead_info'] = $lead_data;
            $data['response'] = true;
        }   
        echo json_encode($data);
    }

    public function appeal_approve($lead_id='',$list_id='')
    {
        $lead_id = decodeBase64($lead_id);
        if(!is_numeric($lead_id)){
            $this->session->set_flashdata('error_message', 'Invalid request to approve appeal.');
            redirect('admin/');
        }
        $where = "lead_id = '".$lead_id."' AND lead_appeal = 3";
        $result = $this->leads->get_where('*', $where, true, '' , '', '');
        if(empty($result)){
            $this->session->set_flashdata('error_message', 'Invalid request for appeal.');
            redirect('admin/leads/index/'.$list_id);
        }
        $update = ['lead_appeal'=>1];
        if($this->leads->update_by('lead_id',$lead_id,$update)){
            $where = "lead_id = '".$lead_id."'";
            $result = $this->lead_order->get_where('order_id', $where, true, '' , '', '');
            $order_id = $result[0]['order_id'];
            $where = "order_id = '".$order_id."'";
            $result = $this->orders->get_where('remaining_leads', $where, true, '' , '', '');
            $rem_leads = $result[0]['remaining_leads']+1;
            $update = ['remaining_leads'=>$rem_leads,'status'=>0];
            $this->orders->update_by('order_id',$order_id,$update);
            //$this->email_to_client($lead_id,true);
            $this->session->set_flashdata('success_message', 'Appeal has been approved successfully.1 lead has added to client order.');
        }
        else{
            $this->session->set_flashdata('error_message', 'Invalid request to approve appeal.');
        }
        redirect('admin/leads/index/');
    }

    public function appeal_reject($lead_id='',$list_id='')
    {
        $lead_id = decodeBase64($lead_id);
        if(!is_numeric($lead_id)){
            $this->session->set_flashdata('error_message', 'Invalid request to reject appeal.');
            redirect('admin/');
        }
        $update = ['lead_appeal'=>2];
        if($this->leads->update_by('lead_id',$lead_id,$update)){
            //$this->email_to_client($lead_id,true);
            $this->session->set_flashdata('success_message', 'Appeal has been rejected successfully');
        }
        else{
            $this->session->set_flashdata('error_message', 'Invalid request to reject appeal.');
        }
        redirect('admin/leads/index/');
    }

    public function delete($list_id)
    {
        if (isset($list_id) && !empty($list_id)) {

            $this->lists->delete_by('list_id', $list_id);
            $this->session->set_flashdata('success_message', 'List has been deleted successfully');
        } else {
            $this->session->set_flashdata('error_message', 'Invalid request to delete list.');
        }
        redirect('admin/lists/');
    }
}