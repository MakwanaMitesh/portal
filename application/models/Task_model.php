<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model{
  public function __construct() {
    parent::__construct();
    // $this->userTbl = 'tblusers';
    $this->load->model('ClientProjects_model');
  }

  /****************************************************************************/

  /****************************************************************************/

  public function get_service_wise_task($val, $task_status) {

    $this->db->select('task_list.*, ee.employee_no, ee.name as name, bb.employee_no, bb.name as assign_by_name');
    //$this->db->select('*');
    $this->db->from('task_list');
    $this->db->join('employees as ee', 'task_list.assignee = ee.employee_no', 'left');
    $this->db->join('employees as bb', 'task_list.task_assign_by = bb.employee_no', 'left');

        if($val != ''){
          $this->db->where('service_id', $val);
        }

        $this->db->where('task_status', $task_status);

        $this->db->order_by('priority', 'asc');

        $query = $this->db->get();

        return $query->result();

    }
/****************************************************************************/
public function get_tasks_by_project_id($project_id) {
  $this->db->select("*");
  $this->db->from('task_list');
  $this->db->where('project_id', $project_id);
  return $this->db->get()->result();
}
/****************************************************************************/
public function get_client_project_tasklist($postData = NULL) {
    $response = array();

    ## Read value
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value

    $searchclientprojects = $postData['client_projects'];
    $searchclientemps = $postData['client_employees'];
    $searchprojectfromdate = date('Y-m-d', strtotime($postData['project_from_date']));
    $searchprojecttodate = date('Y-m-d', strtotime($postData['project_to_date']));

    ## Search
    $search_arr = array();
    $searchQuery = "";
    if ($searchValue != '') {
        $search_arr[] = " (project_list.project_name like '%" . $searchValue . "%' or
        employees.name like '%" . $searchValue . "%' or task_list.task_start_date like '%" . $searchValue . "%' or task_list.task_end_date like '%".$searchValue."%' ) ";
    }
    if ($searchclientprojects != '') {
        $search_arr[] = "task_list.project_id='" . $searchclientprojects . "' ";
    } else {
        $project_ids = $this->ClientProjects_model->get_client_project_ids();
        $searchclientprojects = implode(",", $project_ids);
        $search_arr[] = "task_list.project_id IN (" . $searchclientprojects . ")";
    }
    if ($searchclientemps != '') {
        $search_arr[] = "task_list.assignee='" . $searchclientemps . "' ";
    }
    if ($searchprojectfromdate != '' && $searchprojecttodate != '') {
        $search_arr[] = "task_list.task_start_date BETWEEN '" . $searchprojectfromdate . "' AND  '" . $searchprojecttodate . "'";
    }
    // if () {
    //   $search_arr[] = "task_list.task_end_date <='" . $searchprojecttodate . "' ";
    // }

    if (!empty($search_arr)) {
        $searchQuery = implode(" and ", $search_arr);
    }

    ## Total number of records without filtering
    $this->db->select('count(*) as allcount');
    if ($searchQuery != '') {
        $this->db->where($searchQuery);
    }
    $this->db->join("employees","employees.employee_no = task_list.assignee","left");
    $this->db->join("project_service_list","project_service_list.id=task_list.service_id","left");
    $this->db->join("project_list", "project_list.project_id=task_list.project_id","left");
    $this->db->order_by('task_start_date', 'DESC');
    $records = $this->db->get('task_list')->result();
    //echo $this->db->last_query();
    //echo "<br/>";
    $totalRecords = $records[0]->allcount;
    // die();
    ## Total number of record with filtering
    $this->db->select('count(*) as allcount');
    if ($searchQuery != '') {
        $this->db->where($searchQuery);
    }
    $this->db->join("employees","employees.employee_no = task_list.assignee","left");
    $this->db->join("project_service_list","project_service_list.id=task_list.service_id","left");
    $this->db->join("project_list", "project_list.project_id=task_list.project_id","left");
    $this->db->order_by('task_start_date', 'DESC');
    $records = $this->db->get('task_list')->result();
    $totalRecordwithFilter = $records[0]->allcount;

    $this->db->select('*');
    if ($searchQuery != '') {
        $this->db->where($searchQuery);
    }
    $this->db->join("employees","employees.employee_no = task_list.assignee","left");
    $this->db->join("project_service_list","project_service_list.id=task_list.service_id","left");
    $this->db->join("project_list", "project_list.project_id=task_list.project_id","left");
    $this->db->order_by('task_start_date', 'DESC');
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get('task_list')->result();
    // echo $this->db->last_query();
    // die();
    $data = [];
    if(!empty($records)) {
      foreach ($records as $record) {
        // echo "<pre>";
        // print_r($record);
        // echo "</pre>";
        // die();

        if($record->priority == '1')
        { $color1 = 'danger';
          $text1 = 'Urgent';
        } else if($record->priority == '2') {
          $color1 = 'warning';
          $text1 = 'High';
        } else if($record->priority == '3') {
          $color1 = 'primary';
          $text1 = 'Normal';
        } else {
          $color1 = 'secondary';
          $text1 = 'Low';
        }
        $data[] = array(
          "PRIORITY" => '<span class="badge badge-phoenix fs-10 badge-phoenix-'. $color1. '"><b>'. $text1.'<span class="ms-1" data-feather="flag" style="height:12.8px;width:12.8px;"></b></span>',
          "TASK" => $record->task_heading,
          "STATUS" => $record->task_status,
          "SERVICE" => $record->service_name,
          "PROJECT" => $record->project_name,
          "ASSIGNED TO" => $record->name,
          "START DATE" => $record->task_start_date,
          "END DATE" => $record->task_end_date,
        );
      }
    }

    if(!empty($data)) {
      $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
      );
    } else {
      $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => []
      );
    }

  return $response;

}
/****************************************************************************/
    public function get_tasks_by_status($status) {
        $this->db->select('task_list.*, project_list.project_name, project_service_list.service_name, employees.name as emp_name, (SELECT COUNT(*) FROM task_issues WHERE task_issues.task_id = task_list.task_id AND status = "Open") as open_issues_count');
        $this->db->from('task_list');
        $this->db->join('project_list', 'task_list.project_id = project_list.project_id', 'left');
        $this->db->join('project_service_list', 'task_list.service_id = project_service_list.id', 'left');
        $this->db->join('employees', 'task_list.assignee = employees.employee_no', 'left');
        $this->db->where('task_status', $status);
        $this->db->order_by('priority', 'asc');
        return $this->db->get()->result();
    }

public function get_task_statuses() {
    $this->db->select('*');
    $this->db->from('task_statuses');
    $status_query = $this->db->get();

    return $status_query->result();
}
/****************************************************************************/

public function get_task_issues($task_id) {
    $this->db->select('task_issues.*, employees.name as reporter_name, assignee.name as assigned_name');
    $this->db->from('task_issues');
    $this->db->join('employees', 'task_issues.created_by = employees.employee_no', 'left');
    $this->db->join('employees assignee', 'task_issues.assigned_to = assignee.employee_no', 'left');
    $this->db->where('task_id', $task_id);
    $this->db->order_by('created_on', 'desc');
    return $this->db->get()->result();
}

public function get_issue_by_id($issue_id)
{
    $this->db->select('task_issues.*,
        reporter.name as reporter_name,
        assignee.name as assigned_name,
        task_list.task_id,
        task_list.task_heading,
        task_list.task_status,
        task_list.task_desc as parent_task_desc,
        task_list.assignee as task_assignee,
        task_list.tester_id,
        project_list.project_name,
        project_services.service_name,
        modules.module_name');
    $this->db->from('task_issues');
    $this->db->join('task_list', 'task_issues.task_id = task_list.task_id', 'inner');
    $this->db->join('project_list', 'task_list.project_id = project_list.project_id', 'left');
    $this->db->join('project_services', 'task_list.service_id = project_services.service_id', 'left');
    $this->db->join('modules', 'task_list.module_id = modules.module_id', 'left');
    $this->db->join('employees reporter', 'task_issues.created_by = reporter.employee_no', 'left');
    $this->db->join('employees assignee', 'task_issues.assigned_to = assignee.employee_no', 'left');
    $this->db->where('task_issues.issue_id', (int) $issue_id);
    return $this->db->get()->row();
}

/** @return object[] image rows with file_name */
public function get_images_for_issue($issue_id)
{
    $images = [];
    if ($this->db->table_exists('task_issue_images')) {
        $this->db->where('issue_id', (int) $issue_id);
        $this->db->order_by('image_id', 'asc');
        $images = $this->db->get('task_issue_images')->result();
    }
    if (empty($images) && $this->db->field_exists('issue_image', 'task_issues')) {
        $row = $this->db->select('issue_image')->get_where('task_issues', ['issue_id' => (int) $issue_id])->row();
        if ($row && !empty($row->issue_image)) {
            $images = [(object) ['file_name' => $row->issue_image]];
        }
    }
    return $images;
}

/** @return array issue_id => list of image rows */
public function get_issue_images_map_for_task($task_id)
{
    $map = [];
    if ($this->db->table_exists('task_issue_images')) {
        $this->db->select('tii.*');
        $this->db->from('task_issue_images tii');
        $this->db->join('task_issues ti', 'ti.issue_id = tii.issue_id');
        $this->db->where('ti.task_id', (int) $task_id);
        $this->db->order_by('tii.image_id', 'asc');
        foreach ($this->db->get()->result() as $row) {
            $map[$row->issue_id][] = $row;
        }
    }
    if ($this->db->field_exists('issue_image', 'task_issues')) {
        $this->db->select('issue_id, issue_image as file_name');
        $this->db->from('task_issues');
        $this->db->where('task_id', (int) $task_id);
        $this->db->where('issue_image IS NOT NULL', null, false);
        $this->db->where('issue_image !=', '');
        foreach ($this->db->get()->result() as $row) {
            if (empty($map[$row->issue_id])) {
                $map[$row->issue_id][] = $row;
            }
        }
    }
    return $map;
}

public function save_issue_image_record($issue_id, $file_name)
{
    if ($this->db->table_exists('task_issue_images')) {
        return $this->db->insert('task_issue_images', [
            'issue_id' => (int) $issue_id,
            'file_name' => $file_name,
            'created_on' => date('Y-m-d H:i:s'),
        ]);
    }
    if ($this->db->field_exists('issue_image', 'task_issues')) {
        $existing = $this->db->select('issue_image')->get_where('task_issues', ['issue_id' => (int) $issue_id])->row();
        if (empty($existing->issue_image)) {
            $this->db->where('issue_id', (int) $issue_id);
            return $this->db->update('task_issues', ['issue_image' => $file_name]);
        }
        return true;
    }
    return false;
}

public function get_task_activity($task_id) {
    $this->db->select('task_activity_log.*, employees.name as user_name');
    $this->db->from('task_activity_log');
    $this->db->join('employees', 'task_activity_log.created_by = employees.employee_no', 'left');
    $this->db->where('task_id', $task_id);
    $this->db->order_by('created_on', 'desc');
    return $this->db->get()->result();
}

public function get_yesterday_task_hours() {
  $emp_num = $this->session->userdata('user_id');
  $today = date('Y-m-d');
  $previous_date = date('Y-m-d', strtotime('-1 day', strtotime($today)));
  $total_working_hrs = 0;
  $total_working_mins = 0;
  $holidays = $this->get_holiday_dates();

  $this->db->select('*');
  $this->db->from('attendence');
  $this->db->where('emp_id', $emp_num);
  $this->db->where('attendance_type !=', 'Leave');
  $this->db->where('attendence_date <', $today);
  $this->db->order_by('attendence_date', 'DESC');
  $this->db->limit(1);
  $query = $this->db->get();

  if($query->num_rows() > 0) {
    $last_attendance_dt = $query->row();
    $previous_date = date('Y-m-d', strtotime($last_attendance_dt->attendence_date));
  }

  if(in_array($previous_date, $holidays)==true) {
    while(in_array($previous_date, $holidays)==true) {
      $previous_date = date('Y-m-d', strtotime('-1 day', strtotime($previous_date)));
    }
  }

  $day = date('D', strtotime($previous_date));

  $sunday = date('Y-m-d', strtotime($previous_date));
  if(strtolower($day)=="sun") {
    $previous_date = date('Y-m-d', strtotime('-1 day', strtotime($sunday)));
  }

  $attendance_day = date('D', strtotime($previous_date));

  $this->db->select('task_id, allotted_hrs, allotted_min, task_status');
  $this->db->where('assignee', $emp_num);
  $this->db->where('task_start_date', $previous_date);

  $work_hours_query = $this->db->get('task_list');
  $working_hours = $work_hours_query->result();

  if(!empty($working_hours)) {
    foreach($working_hours as $working_hour) {
      $total_working_hrs = $total_working_hrs + $working_hour->allotted_hrs;
      $total_working_mins = $total_working_mins + $working_hour->allotted_min;
    }

    if($total_working_mins>=60) {
      $hours = $total_working_mins / 60;
      $minutes = $total_working_mins % 60;
      $total_working_hrs = $total_working_hrs + $hours;
      $total_working_mins = $minutes;
    }
    $total_working_hrs = (float) floor($total_working_hrs). "." . $total_working_mins;
  }

  $working_hrs = 0;
  if(strtolower($attendance_day)=='sat') {
    $this->db->select('employee_no, sat_off');
    $this->db->from('employees');
    $this->db->where('employee_no', $emp_num);
    $this->db->limit(1);
    $query = $this->db->get();

    if($query->num_rows() > 0) {
      $sat_work_type = (int) $query->result()[0]->sat_off;

      if($sat_work_type==3) {
        $working_hrs = 8.30;
      } else if($sat_work_type==2) {
        $working_hrs = 4.15;
      } else if($sat_work_type==1 || $sat_work_type=='') {
        $working_hrs = 0;
      }
    }

    if($total_working_hrs < $working_hrs) {
      echo "<h4 class='alert alert-outline-danger'>Your minimum working hours for saturday were ".$working_hrs." hrs.<br/>Your ". $previous_date." working hours are less : ".floor($total_working_hrs). " Hrs " . $total_working_mins." Mins.</h4>";
      return FALSE;
    } else {
      return TRUE;
    }
  } else {
    if($total_working_hrs <= 8.29) {
      echo "Your previous day working hours are less : ".floor($total_working_hrs). " Hrs " . $total_working_mins." Mins.<br/>";
      return FALSE;
    } else {
      return TRUE;
    }
  }
}
/****************************************************************************/
public function get_todays_task_hours() {
  $today = date('Y-m-d');
  $total_working_hrs = 0;
  $total_working_mins = 0;

  $attendance_day = date('D', strtotime($today));

  $emp_num = $this->session->userdata('user_id');
  $this->db->select('task_id, allotted_hrs, allotted_min, task_status');
  $this->db->from('task_list');
  $this->db->where('assignee', $emp_num);
  $this->db->where('created_on', $today);
  $work_hours_query = $this->db->get();

  $working_hours = $work_hours_query->result();
  if(!empty($working_hours)) {
    foreach($working_hours as $working_hour) {
      $total_working_hrs = $total_working_hrs + $working_hour->allotted_hrs;
      $total_working_mins = $total_working_mins + $working_hour->allotted_min;
    }

    if($total_working_mins>=60) {
      $hours = $total_working_mins / 60;
      $minutes = $total_working_mins % 60;
      $total_working_hrs = $total_working_hrs + $hours;
      $total_working_mins = $minutes;
    }
    $total_working_hrs = (float) floor($total_working_hrs). "." . $total_working_mins;
  }

  $working_hrs = 0;
  if(strtolower($attendance_day)=='sat') {
    $this->db->select('employee_no, sat_off');
    $this->db->from('employees');
    $this->db->where('employee_no', $emp_num);
    $this->db->limit(1);
    $query = $this->db->get();

    if($query->num_rows() > 0) {
      $sat_work_type = (int) $query->result()[0]->sat_off;

      if($sat_work_type==3) {
        $working_hrs = 8.30;
      } else if($sat_work_type==2) {
        $working_hrs = 4.15;
      } else if($sat_work_type==1 || $sat_work_type=='') {
        $working_hrs = 0;
      }
    }

    if($total_working_hrs < $working_hrs) {
        echo "<h4 class='alert alert-outline-danger'>Your minimum working hours for saturday are ".$working_hrs." hrs.<br/>Your ". $today." working hours are less : ".floor($total_working_hrs). " Hrs " . $total_working_mins." Mins.</h4>";
        return FALSE;
      } else {
        return TRUE;
      }
    } else {
      if($total_working_hrs <= 8.29) {
        echo "Your Today's tasks hours are less : ".floor($total_working_hrs). " Hrs " . $total_working_mins." Mins.<br/>";
        return FALSE;
      } else {
        return TRUE;
      }
    }
}
/****************************************************************************/
public function get_holiday_dates() {
  $holiday_dates = [];
  $this->db->select('*');
  $this->db->from('holiday_list');
  $holidays_list = $this->db->get()->result();
  $i = 0;
  if(!empty($holidays_list)) {
    foreach($holidays_list as $holidays) {
      $holiday_dates[$i] = $holidays->holiday_date;
      $i++;
    }
  }
  return $holiday_dates;
}
/****************************************************************************/
/** Tasks returned from QA or actively in progress (developer fix queue). */
public function get_developer_fix_tasks()
{
    $emp_num = $this->session->userdata('user_id');
    $this->db->select('task_list.*, project_list.project_id, project_list.project_name, employees.name as emp_name,
        project_services.service_name,
        (SELECT COUNT(*) FROM task_issues ti WHERE ti.task_id = task_list.task_id AND ti.status = "Open") as open_issues_count,
        (SELECT COUNT(*) FROM task_issues ti2 WHERE ti2.task_id = task_list.task_id) as total_issues_count');
    $this->db->from('task_list');
    $this->db->join('project_list', 'task_list.project_id = project_list.project_id', 'left');
    $this->db->join('employees', 'task_list.task_assign_by = employees.employee_no', 'left');
    $this->db->join('project_services', 'task_list.service_id = project_services.service_id', 'left');
    $this->db->where('task_list.task_type', 'Regular');
    $this->_scope_main_tasks_only('task_list');
    $this->db->group_start();
    $this->db->where('task_list.assignee', $emp_num);
    $this->db->where_in('task_list.task_status', ['Doing', 'In Progress']);
    $this->db->or_where(
        'task_list.task_id IN (SELECT DISTINCT task_id FROM task_issues WHERE assigned_to = ' . $this->db->escape($emp_num) . ')',
        null,
        false
    );
    $this->db->group_end();
    $this->db->group_by('task_list.task_id');
    $this->db->order_by('task_list.task_id', 'DESC');
    return $this->db->get()->result();
}

private function _scope_main_tasks_only($alias = 'task_list')
{
    if ($this->db->field_exists('parent_id', 'task_list')) {
        $this->db->group_start();
        $this->db->where($alias . '.parent_id IS NULL', null, false);
        $this->db->or_where($alias . '.parent_id', 0);
        $this->db->group_end();
    }
    if ($this->db->field_exists('workflow_kind', 'task_list')) {
        $this->db->group_start();
        $this->db->where($alias . '.workflow_kind', 'main');
        $this->db->or_where($alias . '.workflow_kind IS NULL', null, false);
        $this->db->or_where($alias . '.workflow_kind', '');
        $this->db->group_end();
        $this->db->where_not_in($alias . '.workflow_kind', ['issue', 'fix', 'test']);
    }
}

public function get_my_task($val) {
  $emp_num = $this->session->userdata('user_id');

  if($val != 'Recurring') {
    $this->db->select('task_list.*, project_list.project_id , project_list.project_name, employees.name as emp_name, employees.task_sort_by_date, project_services.service_name,
        (SELECT COUNT(*) FROM task_issues WHERE task_issues.task_id = task_list.task_id AND status = "Open") as open_issues_count,
        (SELECT COUNT(*) FROM task_issues ti3 WHERE ti3.task_id = task_list.task_id) as total_issues_count');
    //$this->db->select('*');
    $this->db->from('task_list');
    $this->db->join('project_list', 'task_list.project_id = project_list.project_id', 'left');
    $this->db->join('employees', 'task_list.task_assign_by = employees.employee_no', 'left');
    $this->db->join('project_services', 'task_list.service_id = project_services.service_id', 'left');
    $this->db->where('task_list.assignee', $emp_num);
    $this->db->where('task_list.task_status', $val);
    $this->db->where('task_list.task_type', 'Regular');
    $this->_scope_main_tasks_only('task_list');

    if($emp_num == '100041') {
    $this->db->where('employees.task_sort_by_date', 'yes');
    $this->db->where('task_list.task_start_date', date('Y-m-d'));
    }

    $this->db->group_by('task_list.task_id');
    $this->db->order_by('task_list.task_id', 'DESC');

  }else{

    $this->db->select('recurring_task.*, project_list.project_id , project_list.project_name, employees.name as emp_name, project_services.service_name');
    //$this->db->select('*');
    $this->db->from('recurring_task');
    $this->db->join('project_list', 'recurring_task.project_id = project_list.project_id', 'left');
    $this->db->join('employees', 'recurring_task.task_assign_by = employees.employee_no', 'left');
    $this->db->join('project_services', 'recurring_task.service_id = project_services.service_id', 'left');
    $this->db->where('assignee', $emp_num);
   // $this->db->where('recurring_task.task_status', $val);
    $this->db->where('task_type', 'Recurring');
   // $this->db->order_by('recurring_task.priority', 'asc');
    $this->db->order_by('recurring_task.task_id', 'DESC');
  }
    $query = $this->db->get();
    return $query->result();
}


    /****************************************************************************/

public function my_recurring_task() {

      $emp_num = $this->session->userdata('user_id');

     $today = date('Y-m-d');

        $this->db->select('task_list.*, project_list.project_id , project_list.project_name, project_services.service_name');

        //$this->db->select('*');

        $this->db->from('task_list');

        $this->db->join('project_list', 'task_list.project_id = project_list.project_id', 'left');

        $this->db->join('project_services', 'task_list.service_id = project_services.service_id', 'left');

        $this->db->where('assignee', $emp_num);

        $this->db->where('task_type', 'Recurring');

        $this->db->where('task_status !=', 'Completed');

        $this->db->where('task_start_date', $today);

       $this->db->order_by('task_start_date', 'asc');

        $this->db->order_by('priority', 'asc');

      // $this->db->limit('1');

        $query = $this->db->get();

        return $query->result();

    }

/****************************************************************************/

public function get_edit_task($val) {



        $this->db->select('task_list.*, project_list.project_id , project_list.project_name, employees.employee_no, employees.name');

        //$this->db->select('*');

        $this->db->from('task_list');

        $this->db->join('project_list', 'task_list.project_id = project_list.project_id', 'left');

        $this->db->join('employees', 'task_list.assignee = employees.employee_no', 'left');

        $this->db->where('task_id', $val);

        $query = $this->db->get();

//echo $this->db->last_query();

        return $query->row();

    }

/****************************************************************************/

public function task_count($val) {

    $emp_num = $this->session->userdata('user_id');

        $this->db->from('task_list');

        $this->db->where('task_list.task_start_date', date('Y-m-d'));

        $this->db->or_where('task_list.task_end_date', date('Y-m-d'));

        $this->db->where('task_list.task_status <>', 'Completed');

        $this->db->where('assignee', $emp_num);

        return $this->db->count_all_results();

    }

/****************************************************************************/

public function urgent_task_count() {

    $emp_num = $this->session->userdata('user_id');

        $this->db->from('task_list');

        $this->db->where('task_status !=', 'Completed');

        $this->db->where('priority', '1');

        $this->db->where('assignee', $emp_num);

        return $this->db->count_all_results();

    }

/****************************************************************************/

function project_overview_list($postData=null){

    $response = array();

    ## Read value

    $draw = $postData['draw'];

    $start = $postData['start'];

    $rowperpage = $postData['length']; // Rows display per page

    $columnIndex = $postData['order'][0]['column']; // Column index

    $columnName = $postData['columns'][$columnIndex]['data']; // Column name

    //$columnSortOrder = $postData['order'][0]['dir']; // asc or desc

    $searchValue = $postData['search']['value']; // Search value

    $searchemployee = $postData['employee'];

    $searchprojects = $postData['projects'];

    $searchfrom_date = $postData['from_date'];

    $searchto_date = $postData['to_date'];

    ## Search

    $search_arr = array();

    $searchQuery = "";

    if($searchValue != '') {
      $search_arr[] = "(tl.task_heading like '%".$searchValue."%' or
      tl.task_status like '%".$searchValue."%' ) ";
    }

    if($searchfrom_date != '') {
      $search_arr[] = " tl.task_start_date  >='".date('Y-m-d', strtotime($searchfrom_date))."' ";
    }

    if($searchto_date != '') {
      $search_arr[] = " tl.task_start_date <='".date('Y-m-d', strtotime($searchto_date))."' ";
    }

    if($searchemployee != '') {
      $search_arr[] = " tl.assignee ='".$searchemployee."' ";
    }

    if($searchprojects != '') {
      $search_arr[] = " tl.project_id = '".$searchprojects."' ";
    }

    if(!empty($search_arr)) {
      $searchQuery = implode(" and ",$search_arr);
    }

    ## Total number of records without filtering

    $this->db->select_sum('tl.allotted_hrs');

    if($searchQuery != '') {
      $this->db->where($searchQuery);
    }

    // $this->db->where('tl.task_status', 'Completed');
    $this->db->join('project_list AS pl', 'tl.project_id = pl.project_id');
    $this->db->join('employees AS e', 'tl.assignee = e.employee_no');

    $totalhrs = $this->db->get('task_list AS tl')->result();

    $this->db->select_sum('allotted_min');

    if($searchQuery != '') {
      $this->db->where($searchQuery);
    }

    // $this->db->where('task_status', 'Completed');
    $this->db->join('project_list', 'tl.project_id = project_list.project_id');
    $this->db->join('employees', 'tl.assignee = employees.employee_no');

    $totalmin = $this->db->get('task_list AS tl')->result();

    $this->db->select('GROUP_CONCAT(DISTINCT(tl.task_id)) AS task_id,
                      GROUP_CONCAT(DISTINCT(tl.project_id)) AS project_id,
                      GROUP_CONCAT(DISTINCT(tl.service_id)) AS service_id,
                      GROUP_CONCAT(DISTINCT(tl.task_heading)) AS task_heading,
                      GROUP_CONCAT(DISTINCT(tl.task_status)) AS task_status,
                      GROUP_CONCAT(DISTINCT(tl.task_type)) AS task_type,
                      GROUP_CONCAT(DISTINCT(tl.task_start_date)) AS task_start_date,
                      GROUP_CONCAT(DISTINCT(tl.task_end_date)) AS task_end_date,
                      GROUP_CONCAT(DISTINCT(tl.task_approval_status)) AS task_approval_status,
                      GROUP_CONCAT(DISTINCT(tl.allotted_hrs)) AS allotted_hrs,
                      GROUP_CONCAT(DISTINCT(tl.allotted_min)) AS allotted_min,
                      GROUP_CONCAT(DISTINCT(tl.priority)) AS priority,
                      GROUP_CONCAT(DISTINCT(tl.assignee)) AS assignee,
                      GROUP_CONCAT(DISTINCT(tl.task_assign_by)) AS task_assign_by,
                      GROUP_CONCAT(DISTINCT(tl.created_on)) AS created_on,
                      GROUP_CONCAT(DISTINCT(e.name)) AS full_name,
                      GROUP_CONCAT(DISTINCT(pl.project_name)) AS project_name');

    if($searchQuery != '') {
      $this->db->where($searchQuery);
    }

    $this->db->join('project_list AS pl', 'tl.project_id = pl.project_id');
    $this->db->join('employees AS e', 'tl.assignee = e.employee_no');
    // $this->db->where('tl.task_status', 'Completed');
    $this->db->group_by('tl.task_id');

    $records = $this->db->get('task_list AS tl')->num_rows();

    $totalRecords = $records;

    ## Total number of record with filtering
    $this->db->select('GROUP_CONCAT(DISTINCT(tl.task_id)) AS task_id,
                      GROUP_CONCAT(DISTINCT(tl.project_id)) AS project_id,
                      GROUP_CONCAT(DISTINCT(tl.service_id)) AS service_id,
                      GROUP_CONCAT(DISTINCT(tl.task_heading)) AS task_heading,
                      GROUP_CONCAT(DISTINCT(tl.task_status)) AS task_status,
                      GROUP_CONCAT(DISTINCT(tl.task_type)) AS task_type,
                      GROUP_CONCAT(DISTINCT(tl.task_start_date)) AS task_start_date,
                      GROUP_CONCAT(DISTINCT(tl.task_end_date)) AS task_end_date,
                      GROUP_CONCAT(DISTINCT(tl.task_approval_status)) AS task_approval_status,
                      GROUP_CONCAT(DISTINCT(tl.allotted_hrs)) AS allotted_hrs,
                      GROUP_CONCAT(DISTINCT(tl.allotted_min)) AS allotted_min,
                      GROUP_CONCAT(DISTINCT(tl.priority)) AS priority,
                      GROUP_CONCAT(DISTINCT(tl.assignee)) AS assignee,
                      GROUP_CONCAT(DISTINCT(tl.task_assign_by)) AS task_assign_by,
                      GROUP_CONCAT(DISTINCT(tl.created_on)) AS created_on,
                      GROUP_CONCAT(DISTINCT(e.name)) AS full_name,
                      GROUP_CONCAT(DISTINCT(pl.project_name)) AS project_name');

    if($searchQuery != '') {
      $this->db->where($searchQuery);
    }

    $this->db->join('project_list AS pl', 'tl.project_id = pl.project_id');
    $this->db->join('employees AS e', 'tl.assignee = e.employee_no');
    // $this->db->where('tl.task_status', 'Completed');
    $this->db->group_by('tl.task_id');

    $records = $this->db->get('task_list AS tl')->num_rows();
    $totalRecordwithFilter = $records;

    ## Fetch records
    $this->db->select('GROUP_CONCAT(DISTINCT(tl.task_id)) AS task_id,
                      GROUP_CONCAT(DISTINCT(tl.project_id)) AS project_id,
                      GROUP_CONCAT(DISTINCT(tl.service_id)) AS service_id,
                      GROUP_CONCAT(DISTINCT(tl.task_heading)) AS task_heading,
                      GROUP_CONCAT(DISTINCT(tl.task_status)) AS task_status,
                      GROUP_CONCAT(DISTINCT(tl.task_type)) AS task_type,
                      GROUP_CONCAT(DISTINCT(tl.task_start_date)) AS task_start_date,
                      GROUP_CONCAT(DISTINCT(tl.task_end_date)) AS task_end_date,
                      GROUP_CONCAT(DISTINCT(tl.task_approval_status)) AS task_approval_status,
                      GROUP_CONCAT(DISTINCT(tl.allotted_hrs)) AS allotted_hrs,
                      GROUP_CONCAT(DISTINCT(tl.allotted_min)) AS allotted_min,
                      GROUP_CONCAT(DISTINCT(tl.priority)) AS priority,
                      GROUP_CONCAT(DISTINCT(tl.assignee)) AS assignee,
                      GROUP_CONCAT(DISTINCT(tl.task_assign_by)) AS task_assign_by,
                      GROUP_CONCAT(DISTINCT(tl.created_on)) AS created_on,
                      GROUP_CONCAT(DISTINCT(e.name)) AS full_name,
                      GROUP_CONCAT(DISTINCT(pl.project_name)) AS project_name');

    if($searchQuery != '') {
      $this->db->where($searchQuery);
    }

    $this->db->join('project_list as pl', 'tl.project_id = pl.project_id');
    $this->db->join('employees AS e', 'tl.assignee = e.employee_no');

    // $this->db->where('tl.task_status', 'Completed');
    // $this->db->where('e.status', 1);
    $this->db->group_by('tl.task_id');
    $this->db->order_by('tl.task_start_date', 'desc');
    $this->db->limit($rowperpage, $start);

    $records = $this->db->get('task_list AS tl')->result();

    $data = array();

    // $counter = '1';
    foreach($records as $record) {
      $start_date = new DateTime($record->task_start_date);
      $end_date = new DateTime($record->task_end_date);
      $interval = $start_date->diff($end_date);
      $work_days = $interval->days + 1;
      $data[] = array(
        "task_start_date" => date('d-m-Y', strtotime($record->task_start_date)),
        "task_end_date" => date('d-m-Y', strtotime($record->task_end_date)),
        "task_heading" => $record->task_heading,
        "employee" => $record->full_name,
        "project" => $record->project_name,
        "task_status" => $record->task_status,
        "hrs" => $record->allotted_hrs,
        "min" => $record->allotted_min,
        "total_days" => $work_days,
      );

      // $counter++;

    }



    $response = array(

      "draw" => intval($draw),

      "iTotalRecords" => $totalRecords,

      "iTotalDisplayRecords" => $totalRecordwithFilter,

      "aaData" => $data,

      "total_hrs" => $totalhrs,

      "total_min" => $totalmin,

    );



    return $response;

    }

public function export_excel_data($postData=null){
    
    ## Read value
    $searchValue = $postData['search']['value'] ?? '';
    $searchemployee = $postData['employee'] ?? '';
    $searchprojects = $postData['projects'] ?? '';
    $searchfrom_date = $postData['from_date'] ?? '';
    $searchto_date = $postData['to_date'] ?? '';

    ## Search
    $search_arr = array();
    $searchQuery = "";

    if($searchValue != '') {
      $search_arr[] = "(tl.task_heading like '%".$searchValue."%' or
      tl.task_status like '%".$searchValue."%' ) ";
    }

    if($searchfrom_date != '') {
      $search_arr[] = " tl.task_start_date  >='".date('Y-m-d', strtotime($searchfrom_date))."' ";
    }

    if($searchto_date != '') {
      $search_arr[] = " tl.task_start_date <='".date('Y-m-d', strtotime($searchto_date))."' ";
    }

    if($searchemployee != '') {
      $search_arr[] = " tl.assignee ='".$searchemployee."' ";
    }

    if($searchprojects != '') {
      $search_arr[] = " tl.project_id = '".$searchprojects."' ";
    }

    if(!empty($search_arr)) {
      $searchQuery = implode(" and ",$search_arr);
    }

    ## Get Total Hours
    $this->db->select_sum('tl.allotted_hrs');
    if($searchQuery != '') {
      $this->db->where($searchQuery);
    }
    $this->db->join('project_list AS pl', 'tl.project_id = pl.project_id');
    $this->db->join('employees AS e', 'tl.assignee = e.employee_no');
    $this->db->where('pl.client_name', 'Digigram'); // ✅ Added here
    $totalhrs = $this->db->get('task_list AS tl')->result();
    $total_hrs = $totalhrs[0]->allotted_hrs ?? 0;

    ## Get Total Minutes
    $this->db->select_sum('tl.allotted_min');
    if($searchQuery != '') {
      $this->db->where($searchQuery);
    }
    $this->db->join('project_list AS pl', 'tl.project_id = pl.project_id'); // ✅ Changed alias to pl
    $this->db->join('employees AS e', 'tl.assignee = e.employee_no'); // ✅ Changed alias to e
    $this->db->where('pl.client_name', 'Digigram'); // ✅ Already added
    $totalmin = $this->db->get('task_list AS tl')->result();
    $total_min = $totalmin[0]->allotted_min ?? 0;

    ## Fetch all records
    $this->db->select('tl.task_id,
                      tl.project_id,
                      tl.service_id,
                      tl.task_heading,
                      tl.task_status,
                      tl.task_type,
                      tl.task_start_date,
                      tl.task_end_date,
                      tl.task_approval_status,
                      tl.allotted_hrs,
                      tl.allotted_min,
                      tl.priority,
                      tl.assignee,
                      tl.task_assign_by,
                      tl.created_on,
                      e.name as full_name,
                      pl.project_name,
                      pl.client_name'); // ✅ Added client_name to select

    if($searchQuery != '') {
      $this->db->where($searchQuery);
    }

    $this->db->join('project_list AS pl', 'tl.project_id = pl.project_id');
    $this->db->join('employees AS e', 'tl.assignee = e.employee_no');
    $this->db->where('pl.client_name', 'Digigram'); // ✅ Already added
    $this->db->order_by('tl.task_start_date', 'desc');

    $records = $this->db->get('task_list AS tl')->result();

    $data = array();

    foreach($records as $record) {
      $start_date = new DateTime($record->task_start_date);
      $end_date = new DateTime($record->task_end_date);
      $interval = $start_date->diff($end_date);
      $work_days = $interval->days + 1;
      
      $data[] = array(
        "task_start_date" => date('d-m-Y', strtotime($record->task_start_date)),
        "task_end_date" => date('d-m-Y', strtotime($record->task_end_date)),
        "task_heading" => $record->task_heading,
        "employee" => $record->full_name,
        "project" => $record->project_name,
        "task_status" => $record->task_status,
        "hrs" => $record->allotted_hrs,
        "min" => $record->allotted_min,
        "total_days" => $work_days,
      );
    }

    return array(
      'data' => $data,
      'total_hrs' => $total_hrs,
      'total_min' => $total_min
    );
}


public function export_excel_data_by_month($postData=null){
    
    $searchValue = $postData['search']['value'] ?? '';
    $searchemployee = $postData['employee'] ?? '';
    $searchprojects = $postData['projects'] ?? '';
    $searchfrom_date = $postData['from_date'] ?? '';
    $searchto_date = $postData['to_date'] ?? '';

    ## Search
    $search_arr = array();
    $searchQuery = "";

    if($searchValue != '') {
      $search_arr[] = "(tl.task_heading like '%".$searchValue."%' or
      tl.task_status like '%".$searchValue."%' ) ";
    }

    if($searchfrom_date != '') {
      $search_arr[] = " tl.task_start_date  >='".date('Y-m-d', strtotime($searchfrom_date))."' ";
    }

    if($searchto_date != '') {
      $search_arr[] = " tl.task_start_date <='".date('Y-m-d', strtotime($searchto_date))."' ";
    }

    if($searchemployee != '') {
      $search_arr[] = " tl.assignee ='".$searchemployee."' ";
    }

    if($searchprojects != '') {
      $search_arr[] = " tl.project_id = '".$searchprojects."' ";
    }

    if(!empty($search_arr)) {
      $searchQuery = implode(" and ",$search_arr);
    }

    ## Fetch all records
    $this->db->select('tl.task_id,
                      tl.project_id,
                      tl.service_id,
                      tl.task_heading,
                      tl.task_status,
                      tl.task_type,
                      tl.task_start_date,
                      tl.task_end_date,
                      tl.task_approval_status,
                      tl.allotted_hrs,
                      tl.allotted_min,
                      tl.priority,
                      tl.assignee,
                      tl.task_assign_by,
                      tl.created_on,
                      e.name as full_name,
                      pl.project_name');

    if($searchQuery != '') {
      $this->db->where($searchQuery);
    }

    $this->db->join('project_list as pl', 'tl.project_id = pl.project_id');
    $this->db->join('employees AS e', 'tl.assignee = e.employee_no');
    $this->db->order_by('tl.task_start_date', 'asc');

    $records = $this->db->get('task_list AS tl')->result();

    $data = array();

    foreach($records as $record) {
      $start_date = new DateTime($record->task_start_date);
      $end_date = new DateTime($record->task_end_date);
      $interval = $start_date->diff($end_date);
      $work_days = $interval->days + 1;
      
      $data[] = array(
        "task_start_date" => date('d-m-Y', strtotime($record->task_start_date)),
        "task_end_date" => date('d-m-Y', strtotime($record->task_end_date)),
        "task_heading" => $record->task_heading,
        "employee" => $record->full_name,
        "project" => $record->project_name,
        "task_status" => $record->task_status,
        "hrs" => $record->allotted_hrs,
        "min" => $record->allotted_min,
        "total_days" => $work_days,
        "month" => date('Y-m', strtotime($record->task_start_date)) // Month key
      );
    }

    // Group by month
    $grouped_data = array();
    $month_totals = array();

    foreach($data as $record) {
      $month = $record['month'];
      
      if (!isset($grouped_data[$month])) {
        $grouped_data[$month] = array();
        $month_totals[$month] = array('hrs' => 0, 'min' => 0);
      }
      
      $grouped_data[$month][] = $record;
      $month_totals[$month]['hrs'] += $record['hrs'];
      $month_totals[$month]['min'] += $record['min'];
    }

    return array(
      'grouped_data' => $grouped_data,
      'month_totals' => $month_totals
    );
}

    /****************************************************************************/

}