<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_leads extends CI_Controller 
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
		$this->selected_tab = 'api_leads';
		$this->layout = 'user_dashboard';
		$this->load->model('api_leads_model', 'api_leads');
		if(!$this->session->userdata('user')){
		    $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
		    redirect('sign-in');
	    }
	    $this->user_id = $this->session->userdata('user_id');
	}
	
	public function index()
	{
		$data = [];
        $this->load->view('api_leads/index', $data);
	}

	public function send_failed($api_lead_id=0)
	{
		$api_lead_id = decodeBase64($api_lead_id);
		(!is_numeric($api_lead_id))?redirect('api_leads/'):'';
		$where = "api_leads.api_lead_id = '".$api_lead_id."' AND api_leads.failed_attempts = 1";
        $joins = array(
            '0' => array('table_name' => 'user_api user_api',
                'join_on' => 'user_api.api_id = api_leads.api_id',
                'join_type' => 'left'
            ),
            '0' => array('table_name' => 'user_api user_api',
                'join_on' => 'user_api.user_id = api_leads.user_id',
                'join_type' => 'left'
            )
        );
        $from_table = "api_leads api_leads";
        $select_from_table = 'api_leads.lead_id,api_leads.api_id,api_leads.post_data,api_leads.failed_attempts,user_api.api_settings';
        $result = $this->api_leads->get_by_join($select_from_table, $from_table, $joins, $where, '', '', '', '', '', '', '', '');
        if(empty($result)){
        	$this->session->set_flashdata('error_message', 'Invalid request to resend lead.');
        	redirect('api_leads/');
        }
        $result = $result[0];
        //debug($result,true);
        $hupl = json_decode($result['api_settings']);
        $post_data = json_decode($result['post_data']);
        
        $post_hub = array('firstname'=>$post_data->first_name,'lastname'=>$post_data->last_name,'email'=>$post_data->email,'phone_mobile'=>$post_data->phone_mobile,'lead_generator'=>'debt advice service','tags'=>$post_data->tags);
        $post_hub['HUBSOLV-API-KEY']=$hupl->hubsolv_api_key;
        $post_hub['lead_source']=$hupl->lead_source;
        $username_pass = $hupl->username.':'.$hupl->password;
        $base64 =  base64_encode($username_pass);
        $curlPost = curl_init();
        curl_setopt_array($curlPost, array(
            CURLOPT_URL => "https://pfs.hubsolv.com/api/client/format/json/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:62.0) Gecko/20100101 Firefox/62.0",
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($post_hub),
            CURLOPT_HTTPHEADER => array(
                //"Content-Type: application/json",
                "Authorization: Basic $base64"
            )
        ));
        $responsePost = curl_exec($curlPost);
        $decode_result = json_decode($responsePost);
        $status = ($decode_result->status=='success_post')?1:0;
        $failed_attempts = ($status==1)?0:$result['failed_attempts']+1;
        $update = [];
        $update['lead_id'] = $result['lead_id'];
        $update['user_id'] = $this->user_id;
        $update['api_id'] = $result['api_id'];
        $update['post_data'] = json_encode($post_hub);
        $update['api_response'] = $responsePost;
        $update['status'] = $status;
        $update['failed_attempts'] = $failed_attempts;
        $this->api_leads->update_by('api_lead_id',$api_lead_id,$update);
        if($failed_attempts!=0){
        	$query = $this->db->last_query();
        	$message = 'A user resend the lead but failed again.<br/>Here is the query string of lead containing lead info.<br/>'.$query.'<br/>';
        	send_email_to("Dear Admin",ADMIN_EMAIL,$message,'Failed Lead');
        	$this->session->set_flashdata('success_message', 'A mail sent to admin for resending lead.');
        }
        else{
        	$this->session->set_flashdata('success_message', 'The lead is resent successfully.');
        }
        redirect('api_leads/');
	}

	public function get_api_leads()
    {
        $this->layout = '';
        $like = array();
        $api_leads_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $status_filter = $this->input->post('status_filter');
        
        $where = "api_leads.user_id = '".$this->user_id."'";
        $api_leads_count = $this->api_leads->count_rows($where);

        if(isset($status_filter) && $status_filter != ''){
            $where .= " AND api_leads.status ='".$status_filter."'";
        }

        if (isset($search) && $search != '') {
            $where .= " AND (leads.first_name  LIKE CONCAT('%','" . $search . "' ,'%') OR leads.last_name LIKE CONCAT('%','" . $search . "' ,'%') OR leads.contact_mobile LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'leads leads',
                'join_on' => 'leads.lead_id = api_leads.lead_id',
                'join_type' => 'left'
            )
        );
        $from_table = "api_leads api_leads";
        $select_from_table = 'api_leads.api_lead_id,api_leads.status,api_leads.created_at ,api_leads.failed_attempts,leads.first_name,leads.last_name,leads.contact_mobile';
        $api_leads_data = $this->api_leads->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($api_leads_data,true);
        $api_leads_count_rows = $this->api_leads->get_by_join_total_rows('*', $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($api_leads_data)) {
            foreach ($api_leads_data as $item) {
                $single_field['first_name'] = $item['first_name'];
                $single_field['last_name'] = $item['last_name'];
                $single_field['contact_mobile'] = $item['contact_mobile'];
                $single_field['status'] = ($item['status']==1)?'Success':'Failed';
                $single_field['failed_attempts'] = ($item['failed_attempts']==1)?"<a href='".base_url('api_leads/send_failed/'.createBase64($item['api_lead_id']))."' class='send_again'>Send Again</a>":'---';
                $single_field['created_at'] = $item['created_at'];
                $api_leads_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $api_leads_count;
            $data['recordsFiltered'] = $api_leads_count_rows;
            $data['data'] = $api_leads_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }
	
}