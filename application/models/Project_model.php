<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Project_model extends CI_Model{
    function __construct() {
       
      $this->userTbl = 'project_list';
    }
    
function get_service_list($project_id)
{
    $emp_num = $this->session->userdata('user_id');
    $this->db->select('*');
   $this->db->from('employees');
   $this->db->where('employee_no', $emp_num);
   //$this->db->order_by('service_name', 'asc');
   $query=  $this->db->get();
    $row =  $query->row();
   //$column_value = $row->admin_section;   
      
   $this->db->select('service_id,service_name,project_id,service_status,service_start_date,assignees,total_hrs_alloted');
   $this->db->from('project_services');
   $this->db->where('project_id', $project_id);
    if($row->admin_section != 'yes'){
    $this->db->where("FIND_IN_SET('$emp_num', assignees) > 0");
  }
   $this->db->order_by('service_name', 'asc');
   return  $this->db->get()->result();
}
function get_serv_details($service_id)
{
   $this->db->select('*');
   $this->db->from('project_services');
   $this->db->where('service_id', $service_id);
   //$this->db->order_by('service_name', 'asc');
   $query=  $this->db->get();
      return $query->row();
}
/****************************************************************************/  
function get_project_phases()
{
   $this->db->select('*');
   $this->db->from('project_phases');
   //$this->db->order_by('phase_name', 'asc');
   return  $this->db->get()->result();
}
/****************************************************************************/  
function get_project_category()
{
   $this->db->select('*');
   $this->db->from('project_category');
   //$this->db->order_by('phase_name', 'asc');
   return  $this->db->get()->result();
}
/****************************************************************************/  
function get_extra_hrs_request()
{
   $this->db->select('extra_hrs_request.*, employees.employee_no , employees.name,project_services.service_name,project_services.project_id');
   $this->db->from('extra_hrs_request');
   $this->db->join('employees', 'extra_hrs_request.emp_num = employees.employee_no', 'left');
    $this->db->join('project_services', 'extra_hrs_request.service_id = project_services.service_id', 'left');
   $this->db->where('extra_hrs_request.status', '0');
   //$this->db->order_by('phase_name', 'asc');
   return  $this->db->get()->result();
}
/****************************************************************************/  
function get_emp_hrs($projectid,$service_id)
{
   $this->db->select('project_assign_emp_hrs.*, employees.employee_no , employees.name');
   $this->db->from('project_assign_emp_hrs');
   $this->db->join('employees', 'project_assign_emp_hrs.emp_num = employees.employee_no', 'left');
  
   $this->db->where('service_id', $service_id);
   $this->db->where('project_id', $projectid);
   //$this->db->order_by('phase_name', 'asc');
   return  $this->db->get()->result();
}
/****************************************************************************/  
function get_proj_emp_hrs($projectid)
{
   $this->db->select('project_assign_emp_hrs.*, employees.employee_no , employees.name');
   $this->db->from('project_assign_emp_hrs');
   $this->db->join('employees', 'project_assign_emp_hrs.emp_num = employees.employee_no', 'left');
   //$this->db->where('service_id', $service_id);
   $this->db->where('project_id', $projectid);
   //$this->db->order_by('phase_name', 'asc');
   return  $this->db->get()->result();
}
/****************************************************************************/  
function get_project_details($project_id)
{
   $this->db->select('*');
   $this->db->from('project_list')->where('project_id',  $project_id);
   $query =  $this->db->get();
   return $query->row(); 
}
/****************************************************************************/  
function project_assign_emp_hrs_details($projectid,$service_id,$emp_num)
{
   $this->db->select('*');
   $this->db->from('project_assign_emp_hrs')->where('project_id',  $projectid)->where('service_id',  $service_id)->where('emp_num',  $emp_num);
   $query =  $this->db->get();
   return $query->row(); 
}
/****************************************************************************/  
function get_service_hrs($service_id)
{
   $this->db->select('*');
   $this->db->from('project_services')->where('service_id',  $service_id);
   $query =  $this->db->get();
   return $query->row(); 
}
/****************************************************************************/  
function get_extra_hrs_request_emp($service_id,$emp_num)
{
   $this->db->select('*');
   $this->db->from('project_assign_emp_hrs')->where('service_id',  $service_id)->where('emp_num',  $emp_num);
   $query =  $this->db->get();
   return $query->row(); 
}
/****************************************************************************/
function getServiceNames($array) {
    $serviceNames = array();

    // Loop through each array
    foreach ($array as $subArray) {
        // Check if the array is not empty
        if (!empty($subArray)) {
            // Loop through each object in the array
            foreach ($subArray as $object) {
                // Check if the object has the 'service_name' property
                if (isset($object->service_name)) {
                    // Add the service_name to the result array
                    $serviceNames[] = $object->service_name;
                }
            }
        }
    }

    return $serviceNames;
}

function project_list($postData = null) {
    $response = array();

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value
    
    $searchstatus = $postData['project_phase'];
    $searchcategory = $postData['category'];
    $searchproject_category = $postData['project_category'];
    $search_assignee = $postData['assignee'];
    
    ## Search 
    $search_arr = array();
    $searchQuery = "";
    if ($searchValue != '') {
        $search_arr[] = " (project_name like '%" . $searchValue . "%' or 
        client_name like '%" . $searchValue . "%' or category_name like '%" . $searchValue . "%'  ) ";
    }
    if ($searchstatus != '') {
        $search_arr[] = "project_services.service_status='" . $searchstatus . "' ";
    }
    if ($searchproject_category != '') {
        $search_arr[] = "project_services.service_name like '%" . $searchproject_category . "%' ";
    }
    if ($searchcategory != '') {
        $search_arr[] = "project_service_list.category_name like '%" . $searchcategory . "%' ";
    }
    
    ## Check for specific assignee
    if ($search_assignee != '') {
        $search_arr[] = "project_services.assignees like '%" . $search_assignee . "%'";
    }
    
    if (!empty($search_arr)) {
        $searchQuery = implode(" and ", $search_arr);
    }
    
    ## Total number of records without filtering
    $this->db->select('count(*) as allcount');
    if ($searchQuery != '') {
        $this->db->where($searchQuery);
    }
    $this->db->join('project_list', 'project_services.project_id  = project_list.project_id');
    $this->db->join('project_service_list', 'project_services.service_name = project_service_list.service_name');
    $records = $this->db->get('project_services')->result();
    $totalRecords = $records[0]->allcount;
    
    ## Total number of record with filtering
    $this->db->select('count(*) as allcount');
    if ($searchQuery != '') {
        $this->db->where($searchQuery);
    }
    $this->db->join('project_list', 'project_services.project_id  = project_list.project_id');
    $this->db->join('project_service_list', 'project_services.service_name = project_service_list.service_name');
    $records = $this->db->get('project_services')->result();
    $totalRecordwithFilter = $records[0]->allcount;
    
    ## Fetch records
    $this->db->select('*');
    if ($searchQuery != '') {
        $this->db->where($searchQuery);
    }
    $this->db->join('project_list', 'project_services.project_id  = project_list.project_id');
    $this->db->join('project_service_list', 'project_services.service_name = project_service_list.service_name');
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get('project_services')->result();
    //echo $this->db->last_query();die;
    $data = array();
    foreach ($records as $record) {
        if ($record->service_status == 'Completed') {
            $color = 'primary';
        } else if ($record->service_status == 'Stuck') {
            $color = 'danger';
        } else {
            $color = 'success';
        }
        
        $data[] = array(
            "category" => $record->category_name,
            "project" => '<a class="text-primary" href="' . base_url() . 'create-project?project_id=' . $record->project_id . '">' . $record->project_name . '</a>',
            "services" => $record->service_name,
            "project_phase" => '<a class="badge badge-phoenix badge-phoenix-' . $color . '">' . $record->service_status . '</a>',
            "client_name" => $record->client_name,
            "created_date" => $record->created_date,
            "action" => '<a href="' . base_url() . 'edit-service?serviceid=' . $record->service_id . '&projectid=' . $record->project_id . '" class="badge badge-phoenix badge-phoenix-info"><i class="fa fa-pencil-square-o"></i> Edit</a> &nbsp;&nbsp;<a href="' . base_url() . 'project-detail/' . $record->project_id . '" class="badge badge-phoenix badge-phoenix-warning"><i class="fa fa-eye"></i> View</a>',
        ); 
    }
    
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );
    
    return $response; 
}

    
    
   /**********************************************************/ 
    function project_list_bk($postData=null){

    $response = array();
    
    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    //$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value
    
    $searchstatus = $postData['project_phase'];
    $searchproject_category = $postData['project_category'];
     
    ## Search 
    $search_arr = array();
    $searchQuery = "";
    if($searchValue != ''){
       $search_arr[] = " (project_name like '%".$searchValue."%' or 
       client_name like '%".$searchValue."%' or project_phase like '%".$searchValue."%'  ) ";
        
    }
    if($searchstatus != ''){
        $search_arr[] = " project_phase='".$searchstatus."' ";
    }
    if($searchproject_category != ''){
        $search_arr[] = " project_category like '%".$searchproject_category."%' ";
    }
     
    //var_dump($search_arr);
    if(!empty($search_arr)){
       $searchQuery = implode(" and ",$search_arr);
    }
    
    ## Total number of records without filtering
    
    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->join('project_services', 'project_list.project_id = project_services.project_id');
   // $this->db->join('project_service_list', 'project_services.service_name = project_service_list.category_name');
    $records = $this->db->get('project_list')->result();
    $totalRecords = $records[0]->allcount;
    
    ## Total number of record with filtering
    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->join('project_services', 'project_list.project_id = project_services.project_id');
   // $this->db->join('project_service_list', 'project_services.service_name = project_service_list.category_name');
    $records = $this->db->get('project_list')->result();
    $totalRecordwithFilter = $records[0]->allcount;
    
    ## Fetch records
    $this->db->select('*');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->join('project_services', 'project_list.project_id = project_services.project_id');
   // $this->db->join('project_service_list', 'project_services.service_name = project_service_list.category_name');
    $this->db->order_by('project_list.project_id', 'desc');
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get('project_list')->result();
   $this->db->last_query();
    $data = array();
    //  $counter = '1';
         foreach($records as $record ){
             
    if($record->project_phase == 'Complete'){$color = 'primary';}else if($record->project_phase == 'Stuck'){$color = 'danger';}else{$color = 'success';}
              
              
       $data[] = array( 
          "category"=>$record->project_category,
          "project"=>'<a class="text-primary" href="'.base_url().'project-detail/'.$record->project_id.'">'.$record->project_name.'</a>',
          "services"=>$record->service_name,
          "project_phase"=>'<a class="badge badge-phoenix badge-phoenix-'.$color.'">'.$record->project_phase.'</a>',
          "client_name"=>$record->client_name,
          "created_date"=>$record->created_date,
          "action"=>'<a href="'.base_url().'create-project?project_id='.$record->project_id.'" class="badge badge-phoenix badge-phoenix-info"><i class="fa fa-pencil-square-o"></i> Edit</a> &nbsp;&nbsp;<a href="'.base_url().'project-detail/'.$record->project_id.'" class="badge badge-phoenix badge-phoenix-warning"><i class="fa fa-eye"></i> View</a>',
       
       ); 
      // $counter++;
    }
    
    $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
    );
    
    return $response; 
    }
    /****************************************************************************/ 
}