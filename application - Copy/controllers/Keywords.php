<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//use Twilio\Rest\Client;

class Keywords extends CI_Controller 
{
	
	/**
	* @var stirng
	* @access Public
	*/
	public $selected_tab = '';
	public $user_id = 0;
	/** 
	* Controller constructor
	* 
	* @access public 
	*/

 	public function __construct()
	{
 		parent::__construct();
		$this->selected_tab = 'chat';
		$this->layout = 'chat';
 		$this->load->model('connections_model', 'connections');
 		$this->load->model('groups_model', 'groups');
		if(!$this->session->userdata('user')){
	        $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
		    redirect('sign-in');
	    }
	    $this->user_id = $this->session->userdata('user_id');
	}

	public function index()
	{
	    $data = [];
	    $this->load->view('keywords/index');
	}

	public function add()
	{
	    $data = [];
	    $this->load->view('keywords/add');
	}

	public function process_add()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$this->form_validation->set_rules('group_keyword','keyword','required|trim|alpha_numeric|max_length[10]|callback_is_keyword_exists');
		$this->form_validation->set_rules('group_reply_text','message','required|trim');
		if($this->form_validation->run()===TRUE){

			$save = $this->input->post();
			$save['user_id'] = $this->user_id;
			$this->groups->save($save);
			$data['response'] = true;
		}
		else{
			$data['group_keyword_error'] = form_error('group_keyword');
			$data['group_reply_text_error'] = form_error('group_reply_text');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function update($group_id='')
	{
		if(!$this->session->userdata('user')){
	        redirect('dashboard/');
	    }
	    $group_id = decodeBase64($group_id);
		$data = [];
		$where = "group_id = '".$group_id."'";
		$result = $this->groups->get_where('*', $where, true, '' , '', '');
		if(empty($result)){
			$this->session->set_flashdata('error_message','No record found');
			redirect('keywords/');
		}
		$data['group'] = $result[0];
		$this->load->view('keywords/update', $data);
	}

	public function process_update()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$group_id = $this->input->post('group_id');
		$this->form_validation->set_rules('group_keyword','keyword','required|trim|alpha_numeric|max_length[10]|callback_is_keyword_exists['.$group_id.']');
		$this->form_validation->set_rules('group_reply_text','message','required|trim');
		if($this->form_validation->run()===TRUE){
			$update = $this->input->post();
			unset($update['group_id']);
			$this->groups->update_by('group_id',$group_id,$update);
			$data['response'] = true;
		}
		else{
			$data['group_keyword_error'] = form_error('group_keyword');
			$data['group_reply_text_error'] = form_error('group_reply_text');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function is_keyword_exists($str,$group_id='')
	{
		if(!empty($str)){
			$where = "user_id = '".$this->user_id."' AND group_keyword = '".$str."'";
			if(!empty($group_id)){
				$where .= " AND group_id != '".$group_id."'";
			}
		    $result = $this->groups->get_where('*', $where, true, '' , '', '');
			if(empty($result)){
				return TRUE;
			}
			else{
				$this->form_validation->set_message('is_keyword_exists', 'This %s is already added.');
				return FALSE;
			}
	    }
	}

	public function get_keywords()
    {
        $this->layout = '';
        $like = array();
        $keywords_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        // $status_filter = $this->input->post('status_filter');
        // $from_date = $this->input->post('from_date');
        // $to_date = $this->input->post('to_date');
        
        $where = "groups.user_id = '".$this->user_id."'";
        $keywords_count = $this->groups->count_rows($where);

        // if( (isset($from_date) && $from_date != '') && (isset($to_date) && $to_date != '') ){
        //     $from_date = $from_date.' 00:00:00';
        //     $to_date = $to_date.' 23:00:00';
        //     $where = "orders.created_at BETWEEN '".$from_date."' AND '".$to_date."' AND orders.user_id = '".$this->user_id."'";
        // }

        // if( ($from_date != '' && $to_date == '') || ($to_date != '' && $from_date == '') ) {
        //    $date = ($from_date!='')?$from_date:$to_date;
        //    $where .= " AND (orders.created_at  LIKE CONCAT('%','".$date."' ,'%') )";
        // }

        // if(isset($status_filter) && $status_filter != ''){
        //     $where .= " AND orders.status ='".$status_filter."'";
        // }

        if (isset($search) && $search != '') {
            $where .= " AND (groups.group_keyword  LIKE CONCAT('%','" . $search . "' ,'%') OR groups.group_reply_text LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array();
        $from_table = "groups groups";
        $select_from_table = 'groups.*';
        $keywords_data = $this->groups->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($keywords_data,true);
        $keywords_count_rows = $this->groups->get_by_join_total_rows('*', $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($keywords_data)) {
            foreach ($keywords_data as $item) {
                $single_field['group_keyword'] = $item['group_keyword'];
                $single_field['group_reply_text'] = $item['group_reply_text'];
                $single_field['group_stamp'] = $item['group_stamp'];
                $single_field['actions'] = '<a href="'.base_url('keywords/update/'.createBase64($item['group_id'])).'"><i class="ace-icon fa fa-pencil bigger-130"></i></a> | <a href="'.base_url('keywords/delete/'.createBase64($item['group_id'])).'"><i class="ace-icon fa fa-trash bigger-130"></i></a>';
                $keywords_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $keywords_count;
            $data['recordsFiltered'] = $keywords_count_rows;
            $data['data'] = $keywords_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

    public function delete($group_id='')
    {
    	$group_id = decodeBase64($group_id);
    	$where = "group_id = '".$group_id."'";
    	$rows = $this->groups->count_rows($where);
    	if($rows==0){
    		$this->session->set_flashdata('error_message', 'No record found to delete.');
            redirect('keywords/');
    	}
    	$this->groups->delete_by('group_id', $group_id);
        $this->session->set_flashdata('success_message', 'Keyword has been deleted successfully.');
        redirect('keywords/');
    }
	
}