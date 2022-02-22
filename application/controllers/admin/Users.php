<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller 
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
		$this->selected_tab = 'dashboard';
		$this->layout = 'template';
		$this->load->model('users_model', 'users');
        $this->load->model('connections_model', 'connections');
        $this->load->model('admin_model', 'admin');
		if(!$this->session->userdata('admin')) {
        $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
        redirect('admin/auth/');
        }
	}
	
	public function index()
	{
		$data = [];
		$this->load->view('admin/users/index', $data);
	}

    public function send_sms($user_id='4')
    {
        $conn = get_connection($user_id);
        debug($conn);
        if(!empty($conn)){
            $sms = 'Helo new World';
            $to = '+447418310635';
            $response = send_twilio_sms($conn['conn_sid'],$to,$conn['conn_address'], $sms);
            debug($response);
        }
        echo "done";
    }

    public function get_bu()
    {
        get_bundle();
    }

    public function update($user_id=0)
    {
        $this->layout = " ";
        $data = [];
        if(!$this->input->is_ajax_request()){
            exit;
        }
        $data['status'] = false;
        $where = "user_id='".$user_id."'";
        $user = $this->users->get_where('*', $where, true, '' , '', '');
        if(!empty($user)){
            $data['status'] = true;
            $data['user'] = $user[0];
            $data['numbers'] = '';
            $where = "user_id = '".$user_id."' AND is_removed = '0'";
            $connection = $this->connections->get_where('*', $where, true, '' , '', '');
            if(empty($connection)){
                $params = array("voiceEnabled" => "true", "mmsEnabled" => "true", "smsEnabled" => "true");
                $numbers = get_phone_numbers('GB',$params);
                //debug($numbers,true);
                $dropdown = '<div class="form-group"><label class="col-sm-3 control-label no-padding-right" for="form-field-1">Add Connection: </label><div class="col-sm-9"><select name="twilio_number" class="col-xs-10 col-sm-5 all_inputs"><option value="">Select Number</option>';
                if(!empty($numbers)){
                    foreach($numbers as $number){
                        $dropdown .= '<option value="'.$number->phoneNumber.'">'.$number->phoneNumber.'</option>';
                    }
                    $dropdown .= '</select></div></div>';
                    $data['numbers'] = $dropdown;
                }
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
        $user_id = $this->input->post('user_id');
        $this->form_validation->set_rules('first_name','first name','required|trim');
        $this->form_validation->set_rules('last_name','last name','required|trim');
        $this->form_validation->set_rules('email','Email','required|trim|valid_email|callback_is_email_exists['.$user_id.']');
        if($this->form_validation->run()===TRUE)
        {
            $user = $this->input->post();
            unset($user['user_id']);
            if(!empty($user['twilio_number'])){
                $where = "user_id = '".$user_id."'";
                $result = $this->users->get_where('*', $where, true, '' , '', '');
                $result = $result[0];
                $name = $result['first_name'].' '.$result['last_name'];
                $twn = connectNumberFromTwilio($name,$user['twilio_number'],"");
                debug($twn,true);
                if(!empty($twn)){
                    $save = [];
                    $save['user_id'] = $user_id;
                    $save['conn_address'] = $user['twilio_number'];
                    $save['conn_sid'] = $twn['s_sid'];
                    $this->connections->save($save);
                }
            }
            unset($user['twilio_number']);
            $this->users->update_by('user_id',$user_id,$user);
            $data['response'] = true;
        }
        else{
            $data['first_name_error'] = form_error('first_name');
            $data['last_name_error'] = form_error('last_name');
            $data['email_error'] = form_error('email');
        }
        echo json_encode($data);
    }

    public function is_email_exists($str, $id = 'ci_validation')
    {
        if(!empty($str)){
            $staff = $this->admin->uniqueLogin($str, $id);
            if (empty($staff)){
                return TRUE;
            }
            else{
                $this->form_validation->set_message('is_email_exists', 'This %s is already registered.');
                return FALSE;
            }
        }
    }

	public function get_users()
    {
        $this->layout = '';
        $like = array();
        $users_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $status_filter = $this->input->post('status_filter');
        $users_count = $this->users->count_rows();

        $where = "users.user_id > 0 AND user_role = '0'";

        if (isset($status_filter) && $status_filter != '') {
            $where .= " AND users.status ='" . $status_filter . "'";
        }

        if (isset($search) && $search != '') {
            $where .= " AND (users.first_name  LIKE CONCAT('%','" . $search . "' ,'%') OR users.last_name LIKE CONCAT('%','" . $search . "' ,'%') OR users.email LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'connections connections',
                'join_on' => 'users.user_id = connections.user_id AND connections.is_removed = 0 ',
                'join_type' => 'left'
            )
        );
        $from_table = "users users";
        $select_from_table = 'users.*,connections.conn_address,connections.conn_id,connections.is_removed as conn_removed';
        $users_data = $this->users->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($users_data,true);
        $users_count_rows = $this->users->get_by_join_total_rows('*', $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($users_count_rows) && isset($search) && !empty($search) || (isset($status_filter) && !empty($status_filter))) {
            $recordsFiltered = $users_count_rows;
        } else {
            $recordsFiltered = $users_count;
        }

        if (isset($users_data)) {
        	foreach ($users_data as $item) {
                // $users_field['admin_id'] = '<input type="checkbox" class="checkboxes sub_checks" name="users[]" id="sub_check_' . $item['admin_id'] . '" value="' . $item['admin_id'] . '"/>';
                $users_field['first_name'] = $item['first_name'];
                $users_field['last_name'] = $item['last_name'];
                $users_field['email'] = $item['email'];
                $users_field['status'] = ($item['status']==1)?'Active':(($item['status']==2)?'Blocked':'Pending');
                $users_field['days'] = $item['days'];
                $users_field['time'] = $item['time'];
                $users_field['orders'] = "<a href='".base_url('admin/orders/index/'.createBase64($item['user_id']))."'>Orders</a>";
                $users_field['is_removed'] = ($item['is_removed']==1)?'Yes':'No';
                $is_paused = '<a href='.base_url('admin/users/pause/'.createBase64($item['user_id'])).' onclick="delete_record_dt(this); return false;" class="btn btn-danger btn-xs">Pause</a>';
                if($item['is_paused']==1){
                    $is_paused = '<a href='.base_url('admin/users/unpause/'.createBase64($item['user_id'])).' onclick="delete_record_dt(this); return false;" class="btn btn-success btn-xs">Unpause</a>';
                }
                $users_field['is_paused'] = $is_paused;
                $users_field['connection'] = (!empty($item['conn_address'] && $item['conn_removed']==0))?$item['conn_address'].' <br/><a href="'.base_url('admin/users/remove_connection/'.createBase64($item['conn_id'])).'" onclick="delete_record_dt(this); return false;">Remove</a>':'---';
                $users_field['created_at'] = $item['created_at'];
                $users_field['action'] = "<a href='javascript::' rel='".$item['user_id']."' class='edit_user'><i class='ace-icon fa fa-pencil bigger-130'></i></a> &nbsp; <a href='".base_url('admin/users/delete/'.$item['user_id'])."' onclick='delete_record_dt(this); return false;'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
                $users_array[] = $users_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $users_count;
            $data['recordsFiltered'] = $recordsFiltered;
            $data['data'] = $users_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

    public function remove_connection($conn_id = 0)
    {
        $conn_id = decodeBase64($conn_id);
        $where = "conn_id = '".$conn_id."'";
        $connection = $this->connections->get_where('*', $where, true, '' , '', '');
        if(empty($connection)){
            $this->session->set_flashdata('error_message','Invalid request to delete connection.');
            redirect('admin/users');
        }
        
        $update = [];
        $update['is_removed'] = 1;
        $this->connections->update_by('conn_id',$conn_id,$update);

        $this->session->set_flashdata('success_message', 'Connection has been deleted successfully');

        redirect('admin/users/');
    }

    public function pause($user_id='')
    {
        $user_id = decodeBase64($user_id);
        if(!is_numeric($user_id)){
            $this->session->set_flashdata('error_message', 'Invalid request to pause user.');
            redirect('admin/users/');
        }
        $data = ['is_paused'=>1];
        if($this->users->update_by('user_id',$user_id,$data)){
            $this->session->set_flashdata('success_message', 'user has been paused successfully');
        }
        else{
            $this->session->set_flashdata('error_message', 'Invalid request to pause user.');
        }
        redirect('admin/users/');
    }

    public function unpause($user_id='')
    {
        $user_id = decodeBase64($user_id);
        if(!is_numeric($user_id)){
            $this->session->set_flashdata('error_message', 'Invalid request to unpause user.');
            redirect('admin/users/');
        }
        $data = ['is_paused'=>0];
        if($this->users->update_by('user_id',$user_id,$data)){
            $this->session->set_flashdata('success_message', 'User has been unpaused successfully.');
        }
        else{
            $this->session->set_flashdata('error_message', 'Invalid request to unpause user.');
        }
        redirect('admin/users/');
    }
}