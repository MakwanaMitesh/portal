<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_management extends CI_Controller {
    public function __construct(){
		parent::__construct();
        $this->load->model("Common_model");
        $this->load->model("ClientProjects_model");
        $this->load->model("Task_model");
        date_default_timezone_set('Asia/Kolkata');
	}

    public function index()
	{   
	    $data['title'] = 'Client portal login';
		$this->load->view('client_get_access/login', $data);
	}

    public function dashboard($client_name) {
        $data['client_name'] = $client_name;
        
	    $data['get_client_project_list'] = $this->ClientProjects_model->get_client_project_list();
        $data['get_client_project_emp'] = $this->ClientProjects_model->get_client_project_employees();
        $this->load->view('client_management/client_dashboard', $data);
    }
}