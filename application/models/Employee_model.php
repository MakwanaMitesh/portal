<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Employee_model extends CI_Model{
 function __construct() {

      //$this->userTbl = 'project_list';
    }

function get_my_attendance($emp,$date)
{
   $this->db->select('*');
   $this->db->from('attendence')->where('emp_id', $emp)->where('date(attendence_date)', date($date));
  $query =  $this->db->get();
   // echo $this->db->last_query();
   return $query->row();

}
/***************************************************************************/
function get_working_hours() {
    $today = date('Y-m-d');
    $attendance_day = date('D', strtotime($today));

    $emp_num = $this->session->userdata('user_id');
    $this->db->select('login_time, attendance_type');
    $this->db->from('attendence');
    $this->db->where('emp_id', $emp_num);
    $this->db->where('attendence_date', $today);
    $whq = $this->db->get();

    $login_details = $whq->result();

    if(!empty($login_details) || !empty($login_details)) {
        $login_time = $login_details[0]->login_time;
        date_default_timezone_set("Asia/Kolkata");

        $log_time = date('H:i', strtotime($login_time));
        $current_time = new DateTime();
        $logout_time = $current_time->format('H:i');

        $log_time = new DateTime($login_time);
        $out_time = new DateTime($logout_time);

        // Calculate the difference
        $interval = $log_time->diff($out_time);
        $working_hours = (float) $interval->h.".".$interval->i;

        $work_hrs = 0;
        if(strtolower($attendance_day)=='sat') {
            $this->db->select('employee_no, sat_off');
            $this->db->from('employees');
            $this->db->where('employee_no', $emp_num);
            $this->db->limit(1);
            $query = $this->db->get();

            if($query->num_rows() > 0) {
                $sat_work_type = (int) $query->result()[0]->sat_off;

                if($sat_work_type==3) {
                    $work_hrs = 9.30;
                } else if($sat_work_type==2) {
                    $work_hrs = 4.30;
                } else if($sat_work_type==1 || $sat_work_type=='') {
                    $work_hrs = 0;
                }
            }

            if($working_hours < $work_hrs) {
                // echo "<h4 class='alert alert-outline-danger'>Your minimum working hours for saturday are ".$work_hrs." hrs.<br/>Your ". $today." working hours are less : ".floor($total_working_hrs). " Hrs " . $total_working_mins." Mins.</h4>";
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            if(strtolower($login_details[0]->attendance_type)=="full" && $working_hours >= 10 && $working_hours <= 15) {
                return TRUE;
            } elseif(strtolower($login_details[0]->attendance_type)=="half" && $working_hours >= 4 && $working_hours < 8) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    } else {
        return FALSE;
    }
}

/****************************************************************************/
public function get_departments_list() {
        $this->db->select('*');
        $this->db->from('department');
        $query = $this->db->get();
        return $query->result();
    }
/****************************************************************************/
function get_last_emp_num()
{
   $this->db->select('*');
   $this->db->from('employees');
   $this->db->limit(1);
   $this->db->order_by('id', 'desc');
   $query =  $this->db->get();
   return $query->row();
}
/****************************************************************************/
function get_emp($val)
{
   $this->db->select('*');
   $this->db->from('employees');
      $this->db->where('employee_no', $val);
  $query =  $this->db->get();
   return $query->row();
}
/****************************************************************************/
function get_emp_list($postData=null){

    $response = array();

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    //$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value

    $search_gender = $postData['search_gender'];
    $search_department = $postData['search_department'];
    $search_status = $postData['search_status'];
    $search_all = $postData['search_all'];

    ## Search
    $search_arr = array();
    $searchQuery = "";
    if($search_all != ''){
       $search_arr[] = " (name like '%".$search_all."%' or
       designation like '%".$search_all."%' or employee_no like '%".$search_all."%' or mobile_no like '%".$search_all."%'  ) ";
    }
    if($search_gender != ''){
        $search_arr[] = "gender='".$search_gender."' ";
    }
    if($search_department != ''){
       $search_arr[] = "department='".$search_department."' ";
    }
    if($search_status != ''){
       $search_arr[] = "status='".$search_status."' ";
    }

    //var_dump($search_arr);
    if(!empty($search_arr)){
       $searchQuery = implode(" and ",$search_arr);
    }

    ## Total number of records without filtering

    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $records = $this->db->get('employees')->result();
    $totalRecords = $records[0]->allcount;

    ## Total number of record with filtering
    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
     $records = $this->db->get('employees')->result();
    $totalRecordwithFilter = $records[0]->allcount;

    ## Fetch records
    $this->db->select('*');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->order_by('employee_no', 'desc');
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get('employees')->result();
   $this->db->last_query();
    $data = array();
    //  $counter = '1';
         foreach($records as $record ){

    if($record->status == '1'){
        $status = '<a onClick="emp_status_change(this.id,this.getAttribute(\'data-id\'))" data-id="0" id="'.$record->id.'"><span class="badge badge-phoenix fs-10 badge-phoenix-success">Active</span></a>';
    }else{
        $status = '<a onClick="emp_status_change(this.id,this.getAttribute(\'data-id\'))" data-id="1" id="'.$record->id.'"><span class="badge badge-phoenix fs-10 badge-phoenix-danger">InActive</span></a>';
    }

    $filePath = './uploads/' . $record->profile_photo;
    if (file_exists($filePath) && $record->profile_photo != '') {
        $img = '<img class="imgw" src="./uploads/'.$record->profile_photo.'">';
    }else{
        $img = '<img class="imgw" src="assets/img/user_image.jpg">';
    }

       $data[] = array(
          "empno"=>$record->employee_no,
          "name"=>$img.' '.$record->name,
          "mobile"=>$record->mobile_no,
          "department"=>$record->department,
          "designation"=>$record->designation,
          "status"=>$status,
          "action"=>$record->status,
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
function get_attendance_my_list($postData=null){
$emp_num = $this->session->userdata('user_id');
    $response = array();

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    //$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value

    $search_year = $postData['search_year'];
    $search_months = $postData['search_months'];

    ## Search
    $search_arr = array();
    $searchQuery = "";
    if($searchValue != ''){
       $search_arr[] = " (attendence_date like '%".$searchValue."%' or
       login_time like '%".$searchValue."%' or logout_time like '%".$searchValue."%'  ) ";
    }
    if($search_year != ''){
        $search_arr[] = "year(attendence_date)='".$search_year."' ";
    }
    if($search_months != ''){
        $search_arr[] = " month(attendence_date) like '%".$search_months."%' ";
    }

    //var_dump($search_arr);
    if(!empty($search_arr)){
       $searchQuery = implode(" and ",$search_arr);
    }

    ## Total number of records without filtering

    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->where('emp_id', $emp_num);
    //$this->db->join('project_category', 'project_list.project_category = project_category.id');
    $records = $this->db->get('attendence')->result();
    $totalRecords = $records[0]->allcount;

    ## Total number of record with filtering
    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->where('emp_id', $emp_num);
    //$this->db->join('project_category', 'project_list.project_category = project_category.id');
    $records = $this->db->get('attendence')->result();
    $totalRecordwithFilter = $records[0]->allcount;

    ## Fetch records
    $this->db->select('*');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->where('emp_id', $emp_num);
   //$this->db->join('project_category', 'project_list.project_category = project_category.id');
    $this->db->order_by('attendence.attendence_date', 'desc');
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get('attendence')->result();
   $this->db->last_query();
    $data = array();
    //  $counter = '1';
         foreach($records as $record ){


       $data[] = array(
          "date"=>$record->attendence_date,
          "login_time"=>$record->login_time,
          //"services"=>'',
          "logout_time"=>$record->logout_time,
          "attendance_type"=>$record->attendance_type,

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

/**************************************************************/
function get_leave_history($postData=null){
$emp_num = $this->session->userdata('user_id');
    $response = array();

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value

    $search_year = $postData['search_year'];
    $search_months = $postData['search_months'];
    $search_leave_status = $postData['search_leave_status'];

    ## Search
    $search_arr = array();
    $searchQuery = "";
    if($searchValue != ''){
       $search_arr[] = " (attendence_date like '%".$searchValue."%' or
       login_time like '%".$searchValue."%' or logout_time like '%".$searchValue."%'  ) ";
    }
    if($search_year != ''){
        $search_arr[] = "year(attendence_date)='".$search_year."' ";
    }
    if($search_months != ''){
        $search_arr[] = " month(attendence_date) like '%".$search_months."%' ";
    }
    if($search_leave_status != ''){
        $search_arr[] = "leave_approved_by_HR = '".$search_leave_status."' ";
    }

    //var_dump($search_arr);
    if(!empty($search_arr)){
       $searchQuery = implode(" and ",$search_arr);
    }

    ## Total number of records without filtering

    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->where('emp_id', $emp_num);
    $this->db->where('attendance_type', 'Leave');
    //$this->db->join('project_category', 'project_list.project_category = project_category.id');
    $records = $this->db->get('attendence')->result();
    $totalRecords = $records[0]->allcount;

    ## Total number of record with filtering
    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->where('emp_id', $emp_num);
    $this->db->where('attendance_type', 'Leave');
    //$this->db->join('project_category', 'project_list.project_category = project_category.id');
    $records = $this->db->get('attendence')->result();
    $totalRecordwithFilter = $records[0]->allcount;

    ## Fetch records
    $this->db->select('*');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->where('emp_id', $emp_num);
    $this->db->where('attendance_type', 'Leave');
   //$this->db->join('project_category', 'project_list.project_category = project_category.id');
    $this->db->order_by('attendence.attendence_date', 'desc');
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get('attendence')->result();
   $this->db->last_query();
    $data = array();
    //  $counter = '1';
         foreach($records as $record ){
       if($record->leave_approved_by_HR == '0'){$hr_status='<span class="badge badge-phoenix fs-10 badge-phoenix-warning">Pending</span>';}else{$hr_status='<span class="badge badge-phoenix fs-10 badge-phoenix-success">Approved</span>';}
       if($record->leave_approved_by_TL == '0'){$tl_status='<span class="badge badge-phoenix fs-10 badge-phoenix-warning">Pending</span>';}else{$tl_status='<span class="badge badge-phoenix fs-10 badge-phoenix-success">Approved</span>';}

       $data[] = array(
          "date"=>$record->attendence_date,
          "reason"=>$record->reason_for_leave,
          "comment"=>$record->leave_details,
          "hr_status"=>$hr_status,
          "tl_status"=>$tl_status,
          "action"=>'<a class="remove_leave_request" id="'.$record->id.'" ><span class="badge badge-phoenix fs-10 badge-phoenix-danger">Remove</span></a>',

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
  /**************************************************************/

function getDatesInMonth($year, $month) {
    $dates = [];
    $date = new DateTime("$year-$month-01");
    $totalDays = $date->format('t');

    for ($day = 1; $day <= $totalDays; $day++) {
        $dates[] = $date->format('Y-m-d');
        $date->modify('+1 day');
    }

    return $dates;
}

function get_employees_attendance_history($postData=null){
    $response = array();

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value

    $search_emp_num = $postData['search_emp_num'];
    $search_year = $postData['search_year'];
    $search_months = $postData['search_months'];

    ## Search
    $search_arr = array();
    $searchQuery = "";
    if($searchValue != ''){
       $search_arr[] = " (attendence_date like '%".$searchValue."%' or
       login_time like '%".$searchValue."%' or logout_time like '%".$searchValue."%'  ) ";
    }

    if($search_emp_num != ''){
        $search_arr[] = "emp_id ='".$search_emp_num."' ";
    }
    if($search_year != ''){
        $search_arr[] = "year(attendence_date)='".$search_year."' ";
    }
    if($search_months != ''){
        $search_arr[] = " month(attendence_date) like '%".$search_months."%' ";
    }

    if(!empty($search_arr)){
       $searchQuery = implode(" and ",$search_arr);
    }

    ## Total number of records without filtering
    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
        $this->db->where($searchQuery);
    $this->db->join('employees', 'attendence.emp_id = employees.employee_no');
    $records = $this->db->get('attendence')->result();
    $totalRecords = $records[0]->allcount;

    ## Total number of records with filtering
    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
        $this->db->where($searchQuery);
    $this->db->join('employees', 'attendence.emp_id = employees.employee_no');
    $records = $this->db->get('attendence')->result();
    $totalRecordwithFilter = $records[0]->allcount;

    ## Fetch records
    $this->db->select('*, attendence.id as attend_id');
    if($searchQuery != '')
        $this->db->where($searchQuery);
    $this->db->join('employees', 'attendence.emp_id = employees.employee_no');
    $this->db->order_by('attendence.attendence_date', 'desc');
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get('attendence')->result();

    ## Generate all dates for the given month and year
    $year = $search_year;
    $month = $search_months; // June
    $dates = $this->getDatesInMonth($year, $month);
    $attendanceData = [];

    foreach ($records as $record) {
        $attendanceData[$record->attendence_date] = $record;
    }

    $data = [];
    foreach ($dates as $date) {
        $dateTime = new DateTime($date);
        $isSunday = $dateTime->format('l') === 'Sunday';
        $sundayLabel = $isSunday ? ' <span class="badge badge-phoenix fs-10 badge-phoenix-info">Sunday</span>' : '';


         if (isset($attendanceData[$date])) {
            $record = $attendanceData[$date];
            $attendance_type = ($record->attendance_type == 'Leave') ? '<span class="badge badge-phoenix fs-10 badge-phoenix-danger">Leave</span>' : '<span class="badge badge-phoenix fs-10 badge-phoenix-success">'.$record->attendance_type.'</span>';

            $data[] = array(
                "date" => $record->attendence_date . $sundayLabel,
                "attendance" => $attendance_type,
                "login" => $record->login_time,
                "logout" => $record->logout_time,
            );
        } else {
            $data[] = array(
                "date" => $date ,
                "attendance" => '<span class="badge badge-phoenix fs-10 badge-phoenix-warning">Absent '.$sundayLabel.'</span>',
                "login" => '',
                "logout" => '',
            );
        }
    }

    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );

    return $response;
}

   /**************************************************************/
function get_employees_leave_history($postData=null){

    $response = array();

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value

    $search_year = $postData['search_year'];
    $search_months = $postData['search_months'];
    $search_leave_status = $postData['search_leave_status'];

    ## Search
    $search_arr = array();
    $searchQuery = "";
    if($searchValue != ''){
       $search_arr[] = " (attendence_date like '%".$searchValue."%' or
       login_time like '%".$searchValue."%' or logout_time like '%".$searchValue."%'  ) ";
    }
    if($search_year != ''){
        $search_arr[] = "year(attendence_date)='".$search_year."' ";
    }
    if($search_months != ''){
        $search_arr[] = " month(attendence_date) like '%".$search_months."%' ";
    }
    if($search_leave_status != ''){
        $search_arr[] = "leave_approved_by_HR = '".$search_leave_status."' ";
    }
    //var_dump($search_arr);
    if(!empty($search_arr)){
       $searchQuery = implode(" and ",$search_arr);
    }

    ## Total number of records without filtering

    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    //$this->db->where('emp_id', $emp_num);
    $this->db->where('attendance_type', 'Leave');
    $this->db->join('employees', 'attendence.emp_id = employees.employee_no');
    $records = $this->db->get('attendence')->result();
    $totalRecords = $records[0]->allcount;

    ## Total number of record with filtering
    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    //$this->db->where('emp_id', $emp_num);
    $this->db->where('attendance_type', 'Leave');
    $this->db->join('employees', 'attendence.emp_id = employees.employee_no');
    $records = $this->db->get('attendence')->result();
    $totalRecordwithFilter = $records[0]->allcount;

    ## Fetch records
    $this->db->select('*,attendence.id as attend_id');
    if($searchQuery != '')
    $this->db->where($searchQuery);
   // $this->db->where('emp_id', $emp_num);
    $this->db->where('attendance_type', 'Leave');
   $this->db->join('employees', 'attendence.emp_id = employees.employee_no');
    $this->db->order_by('attendence.attendence_date', 'desc');
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get('attendence')->result();
   $this->db->last_query();
    $data = array();
    //  $counter = '1';
         foreach($records as $record ){

       if($record->leave_approved_by_HR == '0'){$hr_status='<a class="hr_update_leave_status" atend-id="'.$record->attend_id.'" status-value="1" ><span class="badge badge-phoenix fs-10 badge-phoenix-warning">Pending</span></a>';}else{$hr_status='<span class="badge badge-phoenix fs-10 badge-phoenix-success">Approved</span>';}
    //    if($record->leave_approved_by_TL == '0'){$tl_status='<span class="badge badge-phoenix fs-10 badge-phoenix-warning">Pending</span>';}else{$tl_status='<span class="badge badge-phoenix fs-10 badge-phoenix-success">Approved</span>';}

    //if($record->leave_approved_by_HR == '0'){$hr_status='<a href="'.base_url().'hr_update_leave_status?atend_id='.$record->attend_id.'&value=1"><span class="badge badge-phoenix fs-10 badge-phoenix-warning">Pending</span></a>';}else{$hr_status='<span class="badge badge-phoenix fs-10 badge-phoenix-success">Approved</span>';}
       $data[] = array(
          "emp_name"=>$record->name,
          "date"=>$record->attendence_date,
          "reason"=>$record->reason_for_leave,
          "comment"=>$record->leave_details,
          "hr_status"=>$hr_status,
          //"tl_status"=>$tl_status,
         // "action"=>'',

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
     /**************************************************************/
function get_tl_employees_leave_history($postData=null){
$emp_num = $this->session->userdata('user_id');
    $response = array();

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value

    $search_year = $postData['search_year'];
    $search_months = $postData['search_months'];
    $search_leave_status = $postData['search_leave_status'];

    ## Search
    $search_arr = array();
    $searchQuery = "";
    if($searchValue != ''){
       $search_arr[] = " (attendence_date like '%".$searchValue."%' or
       login_time like '%".$searchValue."%' or logout_time like '%".$searchValue."%'  ) ";
    }
    if($search_year != ''){
        $search_arr[] = "year(attendence_date)='".$search_year."' ";
    }
    if($search_months != ''){
        $search_arr[] = " month(attendence_date) like '%".$search_months."%' ";
    }
    if($search_leave_status != ''){
        $search_arr[] = "leave_approved_by_HR = '".$search_leave_status."' ";
    }
    //var_dump($search_arr);
    if(!empty($search_arr)){
       $searchQuery = implode(" and ",$search_arr);
    }

    ## Total number of records without filtering

    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    //$this->db->where('emp_id', $emp_num);
    $this->db->where('attendance_type', 'Leave');
    $this->db->join('employees', 'attendence.emp_id = employees.employee_no');
    $records = $this->db->get('attendence')->result();
    $totalRecords = $records[0]->allcount;

    ## Total number of record with filtering
    $this->db->select('count(*) as allcount');
    if($searchQuery != '')
    $this->db->where($searchQuery);
    //$this->db->where('emp_id', $emp_num);
    $this->db->where('attendance_type', 'Leave');
    $this->db->join('employees', 'attendence.emp_id = employees.employee_no');
    $records = $this->db->get('attendence')->result();
    $totalRecordwithFilter = $records[0]->allcount;

    ## Fetch records
    $this->db->select('*,attendence.id as attend_id');
    if($searchQuery != '')
    $this->db->where($searchQuery);
   // $this->db->where('emp_id', $emp_num);
    $this->db->where('attendance_type', 'Leave');
    $this->db->where('employees.Teamleader', $emp_num);
   $this->db->join('employees', 'attendence.emp_id = employees.employee_no');
    $this->db->order_by('attendence.attendence_date', 'desc');
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get('attendence')->result();
   $this->db->last_query();
    $data = array();
    //  $counter = '1';
         foreach($records as $record ){

      // if($record->leave_approved_by_HR == '0'){$hr_status='<a class="hr_update_leave_status" atend-id="'.$record->attend_id.'" status-value="1" ><span class="badge badge-phoenix fs-10 badge-phoenix-warning">Pending</span></a>';}else{$hr_status='<span class="badge badge-phoenix fs-10 badge-phoenix-success">Approved</span>';}
       if($record->leave_approved_by_TL == '0'){$tl_status='<span class="badge badge-phoenix fs-10 badge-phoenix-warning">Pending</span>';}else{$tl_status='<span class="badge badge-phoenix fs-10 badge-phoenix-success">Approved</span>';}

    //if($record->leave_approved_by_HR == '0'){$hr_status='<a href="'.base_url().'hr_update_leave_status?atend_id='.$record->attend_id.'&value=1"><span class="badge badge-phoenix fs-10 badge-phoenix-warning">Pending</span></a>';}else{$hr_status='<span class="badge badge-phoenix fs-10 badge-phoenix-success">Approved</span>';}
       $data[] = array(
          "emp_name"=>$record->name,
          "date"=>$record->attendence_date,
          "reason"=>$record->reason_for_leave,
          "comment"=>$record->leave_details,
          "hr_status"=>$tl_status,
          //"tl_status"=>$tl_status,
         // "action"=>'',

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
function getemploye_atendence_admin($postData = null)
{
    $response = array();

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $searchValue = $postData['search']['value']; // Search value

    ## Search
    $search_arr = array();
    $searchQuery = "";
    if ($searchValue != '') {
        $search_arr[] = " (employee_no like '%" . $searchValue . "%' or
           email like '%" . $searchValue . "%' or mobile_no like '%" . $searchValue . "%' or name like '%" . $searchValue . "%' or department like '%" . $searchValue . "%'  ) ";
    }

    if (!empty($search_arr)) {
        $searchQuery = implode(" and ", $search_arr);
    }

    ## Total number of records without filtering

    ## Fetch records
    $this->db->select('employees.employee_no, employees.name, COUNT(attendence.emp_id) as attendance_count');
    $this->db->join('attendence', 'attendence.emp_id = employees.employee_no', 'left');
    $this->db->where('employees.department !=', 'Admin');
    $this->db->where('employees.status !=', '0');
    if ($searchQuery != '') {
        $this->db->where($searchQuery);
    }
    $this->db->group_by('employees.employee_no, employees.name');
    $this->db->order_by('employees.employee_no');
    $records = $this->db->get('employees')->result();

    $data = array();

    foreach ($records as $record) {
        $data[] = array(
            "name" => '<a href="emp_wise_attendance?emp_id=' . $record->employee_no . '">' . ucfirst($record->name) . '</a>',
            "jan" => $this->db->where('month(attendence_date)', 1)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "feb" => $this->db->where('month(attendence_date)', 2)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "mar" => $this->db->where('month(attendence_date)', 3)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "apr" => $this->db->where('month(attendence_date)', 4)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "may" => $this->db->where('month(attendence_date)', 5)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "jun" => $this->db->where('month(attendence_date)', 6)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "jul" => $this->db->where('month(attendence_date)', 7)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "aug" => $this->db->where('month(attendence_date)', 8)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "sep" => $this->db->where('month(attendence_date)', 9)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "oct" => $this->db->where('month(attendence_date)', 10)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "nov" => $this->db->where('month(attendence_date)', 11)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "dec" => $this->db->where('month(attendence_date)', 12)->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
            "leave" => $this->db->where('attendance_type', 'Leave')->where('emp_id', $record->employee_no)->where('year(attendence_date)', date('Y'))->from("attendence")->count_all_results(),
        );
    }

    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => count($records),
        "iTotalDisplayRecords" => count($records),
        "aaData" => $data
    );

    return $response;
}

/****************************************************************************/
}