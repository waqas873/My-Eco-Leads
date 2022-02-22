<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phone_numbers extends CI_Controller 
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
		$this->selected_tab = 'phone_numbers';
		$this->layout = 'template';
        $this->load->model('phone_numbers_model', 'phone_numbers');
		if(!$this->session->userdata('admin')) {
        $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
        redirect('admin/auth/');
        }
	}
	
	public function index()
	{
		$data = [];
		$this->load->view('admin/phone_numbers/index', $data);
	}

    public function view_response()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $data['view_response'] = '';
        $phone_number_id = $this->input->post('phone_number_id');
        $where = "phone_number_id = '".$phone_number_id."'";
        $result = $this->phone_numbers->get_where('*', $where, true, '' , '', '');
        if(!empty($result)){
            $result = $result[0]['api_response'];
            $view_response = 'Data is not available';
            if(!empty($result)){
                $view_response = '';
                $result = json_decode($result, true);
                if(isset($result['result'])){
                    $detail = $result['result'];
                    if(isset($detail['number'])){
                        $view_response .= '<span class="question">Number</span><br/>';
                        $view_response .= '<span class="answer">'.$detail['number'].'</span><br/>';
                    }
                    if(isset($detail['validated_phone_number'])){
                        $view_response .= '<span class="question">Validated Phone Number</span><br/>';
                        $view_response .= '<span class="answer">'.$detail['validated_phone_number'].'</span><br/>';
                    }
                    if(isset($detail['formatted_phone_number'])){
                        $view_response .= '<span class="question">Formatted Phone Number</span><br/>';
                        $view_response .= '<span class="answer">'.$detail['formatted_phone_number'].'</span><br/>';
                    }
                    if(isset($detail['phone_type'])){
                        $view_response .= '<span class="question">Phone Type</span><br/>';
                        $view_response .= '<span class="answer">'.$detail['phone_type'].'</span><br/>';
                    }
                    if(isset($detail['confidence'])){
                        $view_response .= '<span class="question">Cofidence</span><br/>';
                        $view_response .= '<span class="answer">'.$detail['confidence'].'</span><br/>';
                    }
                }
            }
            $data['view_response'] = $view_response;
            $data['response'] = true;
        }   
        echo json_encode($data);
    }

	public function get_phone_numbers()
    {
        $this->layout = '';
        $like = array();
        $phone_numbers_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $phone_numbers_count = $this->phone_numbers->count_rows();

        if( (isset($from_date) && $from_date != '') && (isset($to_date) && $to_date != '') ){
            $from_date = $from_date.' 00:00:00';
            $to_date = $to_date.' 23:00:00';
            $where = "phone_numbers.created_at BETWEEN '".$from_date."' AND '".$to_date."'";
        }
        else{
            $where = "phone_numbers.phone_number_id > 0";
        }

        if( ($from_date != '' && $to_date == '') || ($to_date != '' && $from_date == '') ) {
           $date = ($from_date!='')?$from_date:$to_date;
           $where .= " AND (phone_numbers.created_at  LIKE CONCAT('%','".$date."' ,'%') )";
        }

        if (isset($search) && $search != '') {
            $where .= " AND (phone_numbers.phone_number  LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array();
        $from_table = "phone_numbers phone_numbers";
        $select_from_table = 'phone_numbers.*';
        $phone_numbers_data = $this->phone_numbers->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($phone_numbers_data,true);
        $phone_numbers_count_rows = $this->phone_numbers->get_by_join_total_rows('*', $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($phone_numbers_data)) {
        	foreach ($phone_numbers_data as $item) {
                $single_field['phone_number'] = $item['phone_number'];
                $single_field['valid'] = ($item['is_valid']==1)?'Yes':'No';
                $single_field['view_response'] = "<a href='javascript::' rel='".$item['phone_number_id']."' class='view_response'>View Response</a>";
                $phone_numbers_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $phone_numbers_count;
            $data['recordsFiltered'] = $phone_numbers_count_rows;
            $data['data'] = $phone_numbers_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }
}