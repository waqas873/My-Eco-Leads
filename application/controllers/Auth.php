<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller 
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
		$this->selected_tab = 'auth';
		$this->layout = 'publicsite';
		$this->load->model('admin_model', 'admin');
		$this->load->model('users_model', 'users');
		$this->load->model('interests_model', 'interests');
		$this->load->model('users_interests_model', 'users_interests');
		$this->load->model('leads_model', 'leads');
	}
	
	public function login()
	{
		if($this->session->userdata('user')){
	        redirect('dashboard/');
	    }
        $data = [];
		$this->load->view('auth/login', $data);
	}

	public function register()
	{
		$data = [];
		$where = "is_removed='0'";
		$data['interests'] = $this->interests->get_where('*', $where, true, '' , '', '');
		$this->load->view('auth/register', $data);
	}

	public function forgot_password()
	{
		if($this->session->userdata('user')){
	        redirect('dashboard/');
	    }
		$data = [];
		$this->load->view('auth/forgot_password', $data);
	}

	public function forgot_password_link()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
        $data['email_error'] = '';
		$this->form_validation->set_rules('email','Email','required|trim|valid_email');
		if($this->form_validation->run()===TRUE){
			$email = $this->input->post('email');
			$where = "email = '".$email."'";
		    $user = $this->users->get_where('*', $where, true, '' , '', '');
		    if(!empty($user)){
                $user = $user[0];
                $update = ['password_time'=>time()];
                if($this->users->update_by('email',$email,$update)){
                	$encoded_email = createBase64($email);
                	$reset_password_link = site_url('auth/reset_password/'.$encoded_email);
                	$data['link'] = $reset_password_link;
					
					$name = $user['first_name'].' '.$user['last_name'];
					$message = "To reset your account password please follow the below link <br /> $reset_password_link";
					$message .= "<br/>Regards,<br/>Administration of WLC";
					send_email_to($name,$email,$message,'Reset Password');

					$data['response'] = true;
                }	
		    }
			else{
				$data['email_error'] = '<p>This email does not exist.</p>';
			}			
		}
		else{
            $data['email_error'] = form_error('email');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function reset_password($encoded_email='')
	{
		if($this->session->userdata('user') || empty($encoded_email)){
	        redirect('dashboard/');
	    } 
		$data = [];
		$email = decodeBase64($encoded_email);
		$where = "email = '".$email."'";
	    $user = $this->users->get_where('*', $where, true, '' , '', '');
	    if(empty($user)){
	    	$this->session->set_flashdata('error_message', "Invalid request to reset password.");
		    redirect('auth/forgot_password');
	    }
	    $user = $user[0];
	    if(strtotime("-10 minutes") > $user['password_time']){
	    	$this->session->set_flashdata('error_message', "Reset password link is expired.Please try again.");
		    redirect('auth/forgot_password');
	    }
	    $data['user'] = $user;
		$this->load->view('auth/reset_password', $data);
	}

	public function process_reset_password()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$data['password_error'] = '';
		$data['cpassword_error'] = '';
		$this->form_validation->set_rules('user_id', '', 'required|numeric');
		$this->form_validation->set_rules('password', 'new password', 'required|trim|min_length[5]');
		$this->form_validation->set_rules('cpassword', 'confirm password', 'required|trim|matches[password]');
		if($this->form_validation->run()===TRUE){
			$user_id = $this->input->post('user_id');
			$password = $this->input->post('password');
			$cpassword = $this->input->post('cpassword');
			$password = md5($password);
        	$update = ['password'=>$password];
        	$this->users->update_by('user_id',$user_id,$update);
        	$data['response'] = true;
		}
		else{
			$data['password_error'] = form_error('password');
			$data['cpassword_error'] = form_error('cpassword');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}
	
	public function process_add()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$this->form_validation->set_rules('first_name','first name','required|trim');
		$this->form_validation->set_rules('last_name','first name','required|trim');
		$this->form_validation->set_rules('email','Email','required|trim|valid_email|callback_is_email_exists');
		if($this->form_validation->run()===TRUE){
			$user = $this->input->post();
			//unset($user['ineterest_id']);
            $password = alphanumeric_random_string(8);
            $data['password_check'] = $password;
			$user['password'] = md5($password);
			//$interest_ids = $this->input->post('ineterest_id');
			if($user_id = $this->users->save($user)) {
				// if(isset($interest_ids) && !empty($interest_ids)){
				// 	foreach($interest_ids as $interest_id){
				// 		$interest = ['user_id'=>$user_id,'interest_id'=>$interest_id];
				// 		$this->users_interests->save($interest);
				// 	}
			    //    }
				$data['response'] = true;

				$password_send = $password;
				$name = $user['first_name'].' '.$user['last_name'];
				$message = "Thank you for joining the Debt Monster lead panel. <br />
				Your new password is below.<br />
				<span style='font-size:17px;font-weight:600;'>PASS</span> : <span style='font-size:17px;font-weight:600;color:#5e3368;'>$password_send</span><br />
				Please Copy and paste this password and go to <br />http://www.debtmonster.co.uk/sign-in<br /> and sign into your new Debt Monster account now.<br />
				Here you will be able to place orders, view leads and much more.
				<br /><br />
				Debt Monster Team.
				";
				send_email_to($name,$user['email'],$message,'FIRST WELCOME PASSWORD EMAIL');
			}
		}
		else{
			$data['first_name_error'] = form_error('first_name');
			$data['last_name_error'] = form_error('last_name');
            $data['email_error'] = form_error('email');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
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

	public function update()
	{
		if(!$this->session->userdata('user')){
	        redirect('dashboard/');
	    }
	    $this->selected_tab = 'account';
	    $this->layout = 'user_dashboard';

		$data = [];
		$user_id = $this->session->userdata('user_id');
		$where = "user_id = '".$user_id."'";
		$user = $this->users->get_where('*', $where, true, '' , '', '');
		if(!empty($user)){
			$data['user'] = $user[0];
		}
		// $data['user_interests'] = $this->users_interests->get_where('*', $where, true, '' , '', '');

		// $where = "is_removed='0'";
		// $data['interests'] = $this->interests->get_where('*', $where, true, '' , '', '');
		$this->load->view('auth/update', $data);
	}

	public function process_update()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$user_id = $this->session->userdata('user_id');
		$data['response'] = false;
		$this->form_validation->set_rules('first_name','first name','required|trim');
		$this->form_validation->set_rules('last_name','first name','required|trim');
		$this->form_validation->set_rules('email','Email','required|trim|valid_email|callback_is_email_exists['.$user_id.']');
		$this->form_validation->set_rules('start_time','start time','required|trim');
		$this->form_validation->set_rules('end_time','end time','required|trim');
		if(!empty($this->input->post('secondary_email'))){
		$this->form_validation->set_rules('secondary_email','secondary email','required|trim|callback_secondary_email');
     	}
		if($this->form_validation->run()===TRUE){
			$user = $this->input->post();
			// unset($user['ineterest_id']);
			// $interest_ids = $this->input->post('ineterest_id');
			$user['days'] = implode(' to ', array($user['from_day'],$user['to_day']));
			$user['time'] = implode(' to ', array($user['start_time'],$user['end_time']));
			unset($user['from_day'],$user['to_day'],$user['start_time'],$user['end_time']);
			(!isset($user['is_email_notification']))?$user['is_email_notification']=0:'';
			$user['enable_auto_reply'] = (isset($user['enable_auto_reply']))?1:0;
			(empty($user['auto_reply_message']))?$user['auto_reply_message']=null:'';

			$user['is_paused'] = $this->input->post('is_paused');
			//debug($user,true);
			$this->users->update_by('user_id',$user_id,$user);
			//$this->users_interests->delete_by('user_id', $user_id);
			// if(isset($interest_ids) && !empty($interest_ids)){
			// 	foreach($interest_ids as $interest_id){
			// 		$interest = ['user_id'=>$user_id,'interest_id'=>$interest_id];
			// 		$this->users_interests->save($interest);
			// 	}
		 //    }
			$data['response'] = true;				
		}
		else{
			$data['first_name_error'] = form_error('first_name');
			$data['last_name_error'] = form_error('last_name');
            $data['email_error'] = form_error('email');
            $data['start_time_error'] = form_error('start_time');
			$data['end_time_error'] = form_error('end_time');
			$data['secondary_email_error'] = form_error('secondary_email');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function secondary_email()
	{
		$se = $this->input->post('secondary_email');
		$se = explode(',', $se);
		$email_validation = true;
		$count = 0;
		foreach($se as $email){
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			    $email_validation = false;
			}
			$count++;
		}
		if($email_validation === false){
			$this->form_validation->set_message('secondary_email', 'Please enter valid emails.');
			return false;
		}
	    $total_emails = count($se);
	    $unique_emails = count(array_unique($se));
	    if($total_emails!=$unique_emails){
	    	$this->form_validation->set_message('secondary_email', 'Please enter unique emails.');
	    	return false;
	    }
		if($count > 3){
			$this->form_validation->set_message('secondary_email', 'You can enter maximum 3  emails.');
			return false;
		}
		return true;
	}

	public function process_login()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$data['notifiy_error'] = false;
		$data['email_error'] = '';
		$data['password_error'] = '';
		$this->form_validation->set_rules('email','email','required|trim|valid_email');
		$this->form_validation->set_rules('password','Password','required|trim');
		if($this->form_validation->run()===TRUE){
			$data = $this->input->post();
			$password = md5($data['password']);
			$where = "email = '".$data['email']."' AND password = '".$password."'";
		    $user = $this->users->get_where('*', $where, true, '' , '', '');
		    if(empty($user)){
                $data['notifiy_error'] = true;
                $data['notifiy_msg'] = "Invalid email or password.";
		    }
		    else{
		    	$user = $user[0];
		    	if($user['status']==2){
		    		$data['notifiy_error'] = true;
                    $data['notifiy_msg'] = "your account is blocked.You cannot login.";
		    	}
		    	else{
		    		$data['response'] = true;
		    		$this->session->set_userdata(array('user'=>$user['user_id'],'user_id'=>$user['user_id'],'email'=>$user['email']));
		    	}
		    }	
		}
		else{
			$data['email_error'] = form_error('email');
			$data['password_error'] = form_error('password');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function change_password($cr='')
	{
		if(!$this->session->userdata('user')){
	        redirect('dashboard/');
	    }
	    $this->layout = 'user_dashboard';
		$data = [];
		$data['cr'] = $cr;
		$this->load->view('auth/change_password', $data);
	}

	public function process_change_password()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$data['old_password_error'] = '';
		$data['password_error'] = '';
		$data['cpassword_error'] = '';
		$this->form_validation->set_rules('old_password', 'old password', 'required|trim');
		$this->form_validation->set_rules('password', 'new password', 'required|trim|min_length[5]');
		$this->form_validation->set_rules('cpassword', 'confirm password', 'required|trim|matches[password]');
		if($this->form_validation->run()===TRUE){
			$old_password = md5($this->input->post('old_password'));
			$password = $this->input->post('password');
			$cpassword = $this->input->post('cpassword');

            $user_id = $this->session->userdata('user_id');
			$where = "user_id = '".$user_id."' AND password = '".$old_password."' ";	
			$result = $this->users->get_where('*', $where, true, '' , '', '');
            if(empty($result)){
                $data['old_password_error'] = "<p>Old password does not match.</p>";
            }
            else{
            	$password = md5($password);
            	$update = ['password'=>$password];
            	$this->users->update_by('user_id',$user_id,$update);
            	$data['response'] = true;
            }
		}
		else{
			$data['old_password_error'] = form_error('old_password');
			$data['password_error'] = form_error('password');
			$data['cpassword_error'] = form_error('cpassword');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}
		
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('sign-in'));
	}

	// public function csv()
	// {
 //        $where = "lead_id > 0";
 //        $date = date('Y-m-d', strtotime('-2 month')).' 00:00:00';
 //        $where .= " AND created_at >= '".$date."'";
	// 	$leads = $this->leads->get_where('first_name,last_name,email,contact_mobile', $where, true, '' , '', '');

	// 	//csv file name
	// 	$filename = 'leads_'.date('Ymd').'.csv';
	// 	header("Content-Description: File Transfer");
	// 	header("Content-Disposition: attachment; filename=$filename");
	// 	header("Content-Type: application/csv; "); 

	// 	// file creation
	// 	$file = fopen('php://output', 'w');

	// 	$header = array("First Name","Last Name","Email","Mobile");
	// 	fputcsv($file, $header);

	// 	foreach ($leads as $key=>$line){
	// 	 fputcsv($file,$line);
	// 	}

	// 	fclose($file);
	// 	exit;
	// }
	
}