<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model{
    function __construct() {
        parent::__construct();
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
    }

/****************************************************************************/

function insert($table_name="",$args=array()){
      $result = $this->db->insert($table_name,$args);
      return ($result) ? $result : false;
}

/****************************************************************************/

function formatDateWithSuffix($dateStr) {
    $timestamp = strtotime($dateStr);
    $day = date('j', $timestamp); // Day without leading zeros
    $suffix = $this->getOrdinalSuffix($day);
    return $day . $suffix . date(' F Y', $timestamp);
}

function getOrdinalSuffix($day) {
    $days = array();
    for($i=1; $i<=31; $i++) {
        $days[] = $i;
    }

    if (!in_array(($day % 100), $days)) {
        switch ($day % 10) {
            case 1: return 'st';
            case 2: return 'nd';
            case 3: return 'rd';
        }
    }
    return 'th';
}

function get_project_list($get_project_list='')
{
   $this->db->select('*');
   $this->db->from('project_list');

    if($get_project_list != '') {
      $this->db->where('project_id', $get_project_list);
    }

   $this->db->where('project_phase !=', 'Complete');

   $this->db->where('project_phase !=', 'Stuck');

   $this->db->order_by('project_name', 'asc');

   return  $this->db->get()->result();

}

/****************************************************************************/

function get_service_list($project_id)

{

   $this->db->select('*');

   $this->db->from('project_services');

   $this->db->where('project_id', $project_id);

   $this->db->order_by('service_name', 'asc');

   return  $this->db->get()->result();

}

/****************************************************************************/

function get_categorywise_service_list()
{
   $this->db->select('category_name');
   $this->db->from('project_service_list');
   $this->db->group_by('category_name');
   $this->db->order_by('category_name', 'asc');
   return  $this->db->get()->result();
}



/****************************************************************************/

function get_members_list($val='')
{
    $this->db->select('*');
    $this->db->from('employees');

    if($val != ''){
        $this->db->where('employee_no', $val);
    }

    $this->db->where('status !=', '0');
    return  $this->db->get()->result();
}

/****************************************************************************/

function get_login_user()
{
    $emp_num = $this->session->userdata('user_id');
    $this->db->select('*');
    $this->db->from('employees')->where('employee_no',  $emp_num);
    $query =  $this->db->get();
    return $query->row();
}

/****************************************************************************/

function get_service_details($serv_id)

{

   $this->db->select('*');

   $this->db->from('project_services');

   //$this->db->join('employees', 'task_list.assignee = employees.employee_no', 'left');

   $this->db->where('service_id ',  $serv_id);

   //->where('service_id ',  $serv_id);

   $query =  $this->db->get();

   return $query->row();

}

/*************************************************************************/

function getRows($params = array()){

$this->db->select('*');

$this->db->from($params['table_name']);

 if(array_key_exists("conditions",$params)){

        foreach($params['conditions'] as $key => $value){

            $this->db->where($key,$value);

        }

    }

    if(array_key_exists("id",$params)){

        $this->db->where('id',$params['id']);

        $query = $this->db->get();

        $result = $query->row_array();

    }else{

        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){

            $this->db->limit($params['limit'],$params['start']);

        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){

            $this->db->limit($params['limit']);

        }

        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){

            $result = $this->db->count_all_results();

        }elseif(array_key_exists("returnType",$params) && $params['returnType'] == 'single'){

            $query = $this->db->get();

            $result = ($query->num_rows() > 0)?$query->row_array():false;

        }else{

            $query = $this->db->get();



            $result = ($query->num_rows() > 0)?$query->result_array():false;

        }

    }

    return $result;

}

/************************************************************************/

// function getTotalHolidays() {
//     $this->db->select('*');
//     $query = $this->db->get('holiday_list');
    
//     if($query->num_rows() > 0) {
            
//     }
// }

/************************************************************************/

function getEmployeeLeavesStats() {

    $empLeavesStats = [];
    
    $this->db->select("*");
    $this->db->where("year", date('Y'));
    $this->db->where("emp_id", $this->session->userdata('user_id'));
    
    $query = $this->db->get("employee_leaves_statistics");
    
    if($query->num_rows() > 0) {
        $empLeavesStats = $query->result();
    }
    
    return $empLeavesStats;
}

/************************************************************************/

function delete_data($rowid,$coumn_name,$table_name)

{

   $this->db->where($coumn_name, $rowid);

   $result = $this->db->delete($table_name);

   return ($result) ? $result : false;

}



/************************************************************************/

function get_module_list($project_id)
{
   $this->db->select('*');
   $this->db->from('modules');
   $this->db->where('project_id', $project_id);
   $this->db->order_by('module_name', 'asc');
   return  $this->db->get()->result();
}

/****************************************************************************/

function can_manage_modules($user = null)
{
   if (!$user) {
      $user = $this->get_login_user();
   }
   if (!$user) {
      return false;
   }
   if (strtolower(trim($user->admin_section ?? '')) === 'yes') {
      return true;
   }
   return strtolower(trim($user->department ?? '')) === 'teamleader';
}

/****************************************************************************/

function get_modules_with_project($project_id = null)
{
   $this->db->select('modules.*, project_list.project_name');
   $this->db->from('modules');
   $this->db->join('project_list', 'modules.project_id = project_list.project_id', 'left');
   if ($project_id) {
      $this->db->where('modules.project_id', $project_id);
   }
   $this->db->order_by('project_list.project_name', 'asc');
   $this->db->order_by('modules.module_name', 'asc');
   return $this->db->get()->result();
}

/****************************************************************************/

function save_module($data, $module_id = null)
{
   $row = [
      'project_id' => $data['project_id'],
      'module_name' => trim($data['module_name']),
      'type' => in_array($data['type'] ?? '', ['development', 'digital']) ? $data['type'] : 'development',
      'updated_at' => date('Y-m-d H:i:s'),
   ];

   if ($module_id) {
      $this->db->where('module_id', $module_id);
      return $this->db->update('modules', $row);
   }

   $row['created_at'] = date('Y-m-d H:i:s');
   $this->db->insert('modules', $row);
   return $this->db->insert_id();
}

/****************************************************************************/

function delete_module($module_id)
{
   $this->db->where('module_id', $module_id);
   return $this->db->delete('modules');
}

/****************************************************************************/

function get_module_by_id($module_id)
{
   return $this->db->get_where('modules', ['module_id' => $module_id])->row();
}

/****************************************************************************/

function get_testers_list()
{
   $this->db->select('employee_no, name, email');
   $this->db->from('employees');
   $this->db->where('department', 'Software Testing');
   $this->db->where('status !=', '0');
   $this->db->order_by('name', 'asc');
   return $this->db->get()->result();
}

/****************************************************************************/

function get_developers_list()
{
   $this->db->select('employee_no, name, email');
   $this->db->from('employees');
   $this->db->where('department !=', 'Software Testing');
   $this->db->where('status !=', '0');
   $this->db->order_by('name', 'asc');
   return $this->db->get()->result();
}

}