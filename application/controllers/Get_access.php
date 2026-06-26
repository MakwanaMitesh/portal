<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get_access extends CI_Controller {
    public function __construct(){
		parent::__construct();
        $this->load->model("Common_model");
	}

	public function login()
	{   
		$username = $this->input->post('emp_num');
		$password = $this->input->post('user_code');
			if(!empty($username) && !empty($password)){
			    $con['table_name'] = 'employees';
				$con['returnType'] = 'single';
				$con['conditions'] = array(
					'employee_no' => $username,
					'password' => sha1($password),
				);
				$user = $this->Common_model->getRows($con);
				if($user) {
					$user_id = $user['employee_no'];
					$data = array(
						//'mobile'=> $username,
						'user_id'=> $user_id,
						'logged_in'=>TRUE
					);
					$this->session->set_userdata($data);
					echo '1';
				}else{   
					 echo '0';
				}
			}else{
				echo '2';
			}
	}
	
	public function logout()
	{
		session_destroy();
		redirect(base_url(), 'refresh');	
	}
}
