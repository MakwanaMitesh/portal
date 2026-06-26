<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct(){
		parent::__construct();
        $this->load->model("Common_model");
        $this->load->model("Team_model");
        $this->load->model("Task_model");
	}

	public function index()
	{
	    $data['title'] = 'Sanpurple employee portal login';
		$this->load->view('get_access/login', $data);
	}

	public function dashboard()
	{
    	$data['get_project_list'] = $this->Common_model->get_project_list();
    	$data['get_login_user'] = $this->Common_model->get_login_user();
    	$data['get_members_list'] = $this->Common_model->get_members_list();

    	// $data['projects_list'] = $this->Project_model->get_projects_list();
    	$data['get_task_statues'] = $this->Task_model->get_task_statuses();
    	$data['today_task_list'] = $this->Task_model->get_my_task('Today');
    	$data['todo_task_list'] = $this->Task_model->get_my_task('To Do');
    	$data['doing_task_list'] = $this->Task_model->get_developer_fix_tasks();
    	$data['Completed_task_list'] = $this->Task_model->get_my_task('Completed');
    	$data['my_recurring_task'] = $this->Task_model->my_recurring_task();

    	$data['today_task_count'] = $this->Task_model->task_count('Today');
    	$data['todo_task_count'] = $this->Task_model->task_count('To Do');
    	$data['urgent_task_count'] = $this->Task_model->urgent_task_count();
    	$data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();

    	$this->load->view('dashboard', $data);
	}

	public function common_fetch_emp()
	{
    	$emp_num =  $this->session->userdata('user_id');
    	$con['table_name'] = 'employees';
    	$con['returnType'] = 'single';
    	$con['conditions'] = array(
    	'employee_no' => $emp_num,
    	);
    	$user = $this->Common_model->getRows($con);
    	return $user;
	}

	public function testing_file()
	{
    	$data['get_project_list'] = $this->Common_model->get_project_list();
    	$data['get_login_user'] = $this->Common_model->get_login_user();
    	$data['get_members_list'] = $this->Common_model->get_members_list();

    	$this->load->view('testing_file',$data);
	}


}
