<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_portal extends CI_Controller {
    public function __construct(){
	
		parent::__construct();
        $this->load->model("Common_model");
        $this->load->model("jobportal_model");
        date_default_timezone_set('Asia/Kolkata');
	}

	public function create_candidate()
	{   
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    //$data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    
	    //$data['get_my_attendance'] =  $this->Employee_model->get_my_attendance($get_login_user->employee_no,date('Y-m-d'));
	    //$data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $this->load->view('job_portal/create_candidate',$data);
	}
	
	
	 
	
}
