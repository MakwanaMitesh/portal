<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_management extends CI_Controller {
    public function __construct(){
		parent::__construct();
        $this->load->model("Common_model");
    $this->load->model("Lead_model");
	date_default_timezone_set('Asia/Kolkata');
	}

	public function create_lead()
	{   
		$data['lead_row_id'] = $lead_id =$this->input->get('lead_id');
	    $data['get_login_user'] = $this->Common_model->get_login_user();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    
	    $data['get_lead_status'] = $this->Lead_model->get_lead_status();
	    $data['get_lead_type'] = $this->Lead_model->get_lead_type();
	    $data['get_lead_source'] = $this->Lead_model->get_lead_source();
		$data['get_lead_row'] = $this->Lead_model->get_lead_details($lead_id);
	    
		$this->load->view('lead_management/create_lead',$data);
	}

	public function lead_detail()
	{   
		$data['lead_row_id'] = $lead_id =$this->input->get('lead_id');
	    $data['get_login_user'] = $this->Common_model->get_login_user();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    
	    $data['get_lead_status'] = $this->Lead_model->get_lead_status();
	    $data['get_lead_type'] = $this->Lead_model->get_lead_type();
	    $data['get_lead_source'] = $this->Lead_model->get_lead_source();
		$data['get_lead_row'] = $this->Lead_model->get_lead_details($lead_id);
	    
		$this->load->view('lead_management/lead_detail',$data);
	}

	public function get_lead_list()
	{
            $postData = $this->input->post();
            $data = $this->Lead_model->get_lead_list($postData);
            echo json_encode($data);
	}
	
	public function save_lead()
	{
	    $rowid = $this->input->post('lead_id');
	    $person_name = $this->input->post('person_name');
	    $lead_type = $this->input->post('lead_type');
	    
	    if($person_name != '' && $lead_type != ''){
	    $data = array(
	     'mobile' => $this->input->post('mobile_num'),
         'person_name' => $person_name,
         'lead_status' => $this->input->post('lead_status'),
         'lead_source' => $this->input->post('lead_source'),
         'lead_type' => $this->input->post('lead_type'),
		 'remark' => $this->input->post('remark'),
         'created_at' => date('Y-m-d H:i:s'),
        ); 
	    if($rowid != ''){
	    $this->db->where('lead_id', $rowid);
        $this->db->update('leads', $data);

		$data2 = array(
			'lead_id' => $rowid,
			'lead_hist_status' => $this->input->post('lead_status'),
			'remark' => $this->input->post('remark'),
			'created_at' => date('Y-m-d H:i:s'),
		   ); 
		   $insert = $this->db->insert('lead_history', $data2);
	    }else{
	        $insert = $this->db->insert('leads', $data);
	      if($insert){echo '<h4 class="alert alert-outline-success ">Great, lead added successfully<h4>';}
	      else{echo '<h4 class="alert alert-outline-danger ">Something went wrong, try again later<h4>';}  
	    }
	    }else{
	        echo '<h4 class="alert alert-outline-danger ">Provide lead title<h4>';
	    }
		
	}

	public function lead_list()
	{
	    
	    $data['get_login_user'] = $this->Common_model->get_login_user();
		$data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
		$data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();

		$data['get_lead_status'] = $this->Lead_model->get_lead_status();
	    $data['get_lead_type'] = $this->Lead_model->get_lead_type();
	    $data['get_lead_source'] = $this->Lead_model->get_lead_source();
		$this->load->view('lead_management/lead_list',$data);
	}
	
	
}
