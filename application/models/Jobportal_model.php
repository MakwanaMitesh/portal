<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Jobportal_model extends CI_Model{
 function __construct() {
       
      //$this->userTbl = 'project_list';
    }
 /****************************************************************************/  
function get_lead_details($lead_id)
{
   $this->db->select('*');
   $this->db->from('leads')->where('lead_id',  $lead_id);
   $query =  $this->db->get();
   return $query->row(); 
}
/****************************************************************************/  
function get_lead_status()
{
   $this->db->select('*');
   $this->db->from('lead_status');
   //$this->db->where('project_id', $project_id);
   $this->db->order_by('lead_status_id ', 'asc');
   return  $this->db->get()->result();
}
/****************************************************************************/


/****************************************************************************/

function get_lead_list($postData=null){

   $response = array();
   
   ## Read value
   $draw = $postData['draw'];
   $start = $postData['start'];
   $rowperpage = $postData['length']; // Rows display per page
   $columnIndex = $postData['order'][0]['column']; // Column index
   $columnName = $postData['columns'][$columnIndex]['data']; // Column name
   //$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
   $searchValue = $postData['search']['value']; // Search value
   
   $searchlead_status = $postData['lead_status'];
   $searchlead_source = $postData['lead_source'];
   $searchlead_type = $postData['lead_type'];
    
   ## Search 
   $search_arr = array();
   $searchQuery = "";
   if($searchValue != ''){
      $search_arr[] = " (person_name like '%".$searchValue."%' or 
      mobile like '%".$searchValue."%' or email like '%".$searchValue."%'  ) ";
       
   }
   if($searchlead_status != ''){
       $search_arr[] = "lead_status='".$searchlead_status."' ";
   }
   if($searchlead_source != ''){
       $search_arr[] = "lead_source like '%".$searchlead_source."%' ";
   }
   if($searchlead_type != ''){
       $search_arr[] = "lead_type like '%".$searchlead_type."%' ";
   }
    
   //var_dump($search_arr);
   if(!empty($search_arr)){
      $searchQuery = implode(" and ",$search_arr);
   }
   
   ## Total number of records without filtering
   
   $this->db->select('count(*) as allcount');
   if($searchQuery != '')
   $this->db->where($searchQuery);
   $records = $this->db->get('leads')->result();
   $totalRecords = $records[0]->allcount;
   
   ## Total number of record with filtering
   $this->db->select('count(*) as allcount');
   if($searchQuery != '')
   $this->db->where($searchQuery);
   $records = $this->db->get('leads')->result();
   $totalRecordwithFilter = $records[0]->allcount;
   
   ## Fetch records
   $this->db->select('*');
   if($searchQuery != '')
   $this->db->where($searchQuery);
   $this->db->order_by('created_at', 'desc');
   $this->db->limit($rowperpage, $start);
   $records = $this->db->get('leads')->result();
  $this->db->last_query();
   $data = array();
 
        foreach($records as $record ){
       
      $data[] = array( 
         "person_name"=>$record->person_name,
         "contact"=>'<a class="text-primary" href="tel:+91'.$record->mobile.'">'.$record->mobile.'</a>',
         "lead_status"=>$record->lead_status,
         "lead_source"=>$record->lead_source,
         "lead_type"=>$record->lead_type,
         "remark"=>$record->remark,
         "created_at"=>$record->created_at,
         "action"=>'<a href="'.base_url().'create-lead?lead_id='.$record->lead_id.'" class="badge badge-phoenix badge-phoenix-info"><i class="fa fa-pencil-square-o"></i> Edit</a> &nbsp;&nbsp;<a href="'.base_url().'lead-detail?lead_id='.$record->lead_id.'" class="badge badge-phoenix badge-phoenix-warning"><i class="fa fa-eye"></i> View</a>',

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


}