<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_management extends CI_Controller {

	public function __construct(){
	
		parent::__construct();
        $this->load->model("Common_model");
        $this->load->model("Project_model");
		
	}
	public function create_project()
	{
	    $project_id = $data['prorow_id'] = $this->input->get('project_id');
	    $data['project_info'] = $this->Project_model->get_project_details($project_id);
    	$data['get_login_user'] = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_category'] = $this->Project_model->get_project_category();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    
		$this->load->view('project_management/create_new',$data);
	}
	
	
	public function projects_list()
	{

	    $data['get_login_user'] = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    $data['get_project_category'] = $this->Project_model->get_project_category();
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
		$this->load->view('project_management/projects_list',$data);
	}
	
    public function additional_hrsrequest()
	{
	    $data['get_login_user'] = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    $data['get_project_category'] = $this->Project_model->get_project_category();
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $data['get_extra_hrs_request'] = $this->Project_model->get_extra_hrs_request();
		$this->load->view('project_management/additional_hrsrequest',$data);
	}
	
	public function project_detail()
	
	{
	    $data['emp_num_ses'] = $emp_num = $this->session->userdata('user_id');
	    $project_id = $this->uri->segment(2);
	    $data['get_login_user'] = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    $data['project_info'] = $this->Project_model->get_project_details($project_id);
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $data['get_emp_hrs'] = $this->Project_model->get_proj_emp_hrs($project_id);
	    $data['get_proj_services'] = $this->Project_model->get_service_list($project_id);
	    
	    
		$this->load->view('project_management/project_detail',$data);
	}
	
	public function edit_service()
	{
	     $data['service_id'] = $service_id = $this->input->get('serviceid');
	     $data['projectid'] = $projectid = $this->input->get('projectid');
	    $data['get_login_user'] = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    
	    $data['get_serv_details'] = $this->Project_model->get_serv_details($service_id);
	    $data['get_project_details'] = $this->Project_model->get_project_details($projectid);
	    $data['get_emp_hrs'] = $this->Project_model->get_emp_hrs($projectid,$service_id);
		$this->load->view('project_management/service_edit',$data);
	}
	
	public function get_project_list()
	{
            $postData = $this->input->post();
            $data = $this->Project_model->project_list($postData);
            echo json_encode($data);
	}
	
	public function get_project_name()
	{
	    $project_id = $this->uri->segment(2);
	    
	    $get_project_name = $this->Common_model->get_project_list($project_id);
		foreach ($get_project_name as $name)
        {
           echo  $name->project_name; 
        }
	}
	
	public function save_project()
	{
	    $rowid = $this->input->post('project_id');
	    $project_title = $this->input->post('project_title');
	    //$project_category = $this->input->post('project_category');
	    if($this->input->post('project_category[]') != ''){
	         $project_category = implode(",",$this->input->post('project_category[]'));
	    }else{
	         $project_category = '';
	    }
	    if($this->input->post('services[]') != ''){
	         $services = implode(",",$this->input->post('services[]'));
	    }else{
	         $services = '';
	    }
	    
	    if($project_title != '' && $project_category != ''){
	    $data = array(
	     'project_category' => $project_category,
         'project_name' => $project_title,
         'project_phase' => $this->input->post('project_phase'),
         'client_name' => $this->input->post('client_name'),
         'contact_number' => $this->input->post('client_number'),
         'project_overview' => $this->input->post('project_overview'),
         'created_date' => date('Y-m-d'),
        ); 
	    if($rowid != ''){
	    $this->db->where('project_id', $rowid);
        $this->db->update('project_list', $data);
	    }else{
	        $insert = $this->db->insert('project_list', $data);
	        $project_id = $this->db->insert_id();
	     if($services != ''){   
	        $tags = explode(',',$services);
        foreach($tags as $key) { 
        $data = array(
         'project_id' => $project_id,
         'service_name' => $key,
         'service_start_date' =>date('Y-m-d'),
         'service_due_date' => date('Y-m-d'),
         'total_hrs_alloted' => '0',
         'service_status' => 'Ongoing',
         'assignees' => '',
         'created_on' => date('Y-m-d'),
         
        );
	   $insert_serv = $this->db->insert('project_services', $data);
        } 
        }

	      if($insert){echo '<h4 class="alert alert-outline-success ">Great, Project added successfully<h4>';}
	      else{echo '<h4 class="alert alert-outline-danger ">Something went wrong, try again later<h4>';}  
	    }
	    }else{
	        echo '<h4 class="alert alert-outline-danger ">Provide project title<h4>';
	    }
		
	}
	
	public function save_project_service()
	{
	    $rowid = $this->input->post('service_id');
	    $project_id = $this->input->post('project_id');
	    $service_name = $this->input->post('service_name');
	    if($this->input->post('assignees[]') != ''){
	         $assing = implode(",",$this->input->post('assignees[]'));
	    }else{
	         $assing = '';
	    }
	    $startdate = $this->input->post('start_date');
	    
	    $enddate = $this->input->post('end_date');
	    
	    if($project_id != '' && $service_name != ''){
	    $data = array(
         'project_id' => $project_id,
         'service_name' => $service_name,
         'service_start_date' => $startdate,
         'service_due_date' => $enddate,
         'total_hrs_alloted' => $this->input->post('allotted_hrs'),
         'service_status' => $this->input->post('service_status'),
         'assignees' => $assing,
         'created_on' => date('Y-m-d'),
        ); 
        
	    if($rowid != ''){
	    $this->db->where('service_id', $rowid);
        $this->db->update('project_services', $data);
	    }else{
	        $insert = $this->db->insert('project_services', $data);
	      if($insert){echo '<h4 class="alert alert-outline-success ">Great, Service added successfully<h4>';}
	      else{echo '<h4 class="alert alert-outline-danger ">Something went wrong, try again later<h4>';}  
	    }
	    }else{
	        echo '<h4 class="alert alert-outline-danger ">Provide service name or project id<h4>';
	    }
		
	}
	
	
	public function save_emp_hrs()
	{
	     $sumhrs = 0;
	    $serviceid = $this->input->post('serviceid');
	    $project_id = $this->input->post('projectid');
	    $emp_assign = $this->input->post('emp_assign');
	    $assigned_total_hrs = $this->input->post('assigned_total_hrs');
	    $emp_allotted_hrs = $this->input->post('emp_allotted_hrs');
	     $total_hrs = $this->input->post('total_hrs');
	    $sumhrs = $total_hrs + $emp_allotted_hrs;
	   
	    if($sumhrs <= $assigned_total_hrs){ 
	   // die;
	    if($serviceid != '' && $project_id != '' && $emp_assign != '' && $emp_allotted_hrs != ''){
	    $data = array(
         'service_id' => $serviceid,
         'project_id' => $project_id,
         'emp_num' => $emp_assign,
         'hrs_allotted' => $emp_allotted_hrs,
         'created_on' => date('Y-m-d H:i:s'),
        ); 
        
        $insert = $this->db->insert('project_assign_emp_hrs', $data);
	    if($insert){
	        echo 'Hours have been successfully allocated to the employee.';
	    }else{
	        echo 'An error occurred. Please try again later.';
	    }
	    }else{
	        echo 'Required Project id, Service id, Employee and Allotted_hrs';
	    }
	    }else{echo 'The allotted hours for an employee should not exceed the total project allotted hours.';}
		
	}
	
	public function delete_emp_hrs(){
	    
	    $id = $this->input->post("row_id");
       $delete_task = $this->Common_model->delete_data($id,'emp_hrs_id','project_assign_emp_hrs');
	}
	
		public function save_extra_hrs_request(){
	
	    $emp_num = $this->session->userdata('user_id');
	    $service_id = $this->input->post("service_id");
	    $reason = $this->input->post("resaon_box");
	    $extra_hrs = $this->input->post("additional_hrs");
	    
	     $get_extra_hrs_request_emp = $this->Project_model->get_extra_hrs_request_emp($service_id,$emp_num);
	     if($get_extra_hrs_request_emp){
	     
	
	    $data = array(
         'emp_num' => $emp_num,
         'service_id' => $service_id,
         'reason' => $reason,
         'extra_hrs' => $extra_hrs,
         'created_on' => date('Y-m-d H:i:s'),
        ); 
        $insert = $this->db->insert('extra_hrs_request', $data);
	    if($insert){
	        echo 'Your request for additional hours has been successfully submitted.';
	    }else{
	        echo 'An error occurred. Please try again later.';
	    }
	     }else{
	        echo 'You have not been allotted any specific time for the project. You cannot send a request for additional time.';
	    }
	     
		}
		
		
		
	public function update_extra_hrs_request(){
	    $emp_num = $this->input->post("emp_num");
	    $service_id = $this->input->post("service_id");
	    $projectid = $this->input->post("project_id");
	    
	    $row_id = $this->input->post("row_id");
	    $allowed_hrs = $this->input->post("allowed_hrs");
	    
	    $status = $this->input->post("status");
	    if($row_id != '' && $allowed_hrs != '' && $status != ''){
	   
	    $data = array(
         'allowed_hrs' => $allowed_hrs,
         'status' => $status,
         'updated_on' => date('Y-m-d H:i:s'),
        ); 
        $this->db->where('id', $row_id);
        $update = $this->db->update('extra_hrs_request', $data);
       /*****************************************************************/ 
        $get_project_assign_emp_hrs = $this->Project_model->project_assign_emp_hrs_details($projectid,$service_id,$emp_num);
       
        $emp_hrs_id = $get_project_assign_emp_hrs->emp_hrs_id ;
        $hrs_allotted = $get_project_assign_emp_hrs->hrs_allotted ;
        $new_hrs_allotted = $hrs_allotted + $allowed_hrs;
        
        $data2 = array(
         'hrs_allotted' => $new_hrs_allotted,
        ); 
        $this->db->where('emp_hrs_id', $emp_hrs_id);
        $update2 = $this->db->update('project_assign_emp_hrs', $data2);
       /*****************************************************************/ 
        $get_service_hrs = $this->Project_model->get_service_hrs($service_id);
        $total_hrs = $get_service_hrs->total_hrs_alloted;
        $add_total_hrs =$total_hrs + $allowed_hrs;
        
        $data3 = array(
         'total_hrs_alloted' => $add_total_hrs,
        ); 
        $this->db->where('service_id', $service_id);
        $update3 = $this->db->update('project_services', $data3);
        /*****************************************************************/ 
        
	    if($update3){
	        echo 'Additional hours has been successfully submitted.';
	    }else{
	        echo 'An error occurred. Please try again later.';
	    }
	     
	    
	    }else{
	        echo 'All fields are required';
	    }
	
		}
}
