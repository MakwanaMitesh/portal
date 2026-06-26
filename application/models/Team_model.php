<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Team_model extends CI_Model{
    public $empnum;
    public $team_members_details = [];
    public $team_members_leaves;

    function __construct() {
        parent::__construct();
        $this->empnum = $this->session->userdata('user_id');
    }

    function get_team_members_summary() {
        $ystrdy = date('Y-m-d', strtotime('-1 day'));

        $day = date('D', strtotime($ystrdy));

        $sunday = date('Y-m-d', strtotime($ystrdy));
        if(strtolower($day)=="sun") {
            $ystrdy = date('Y-m-d', strtotime('-1 day', strtotime($sunday)));
        }

        $this->db->select('name, employee_no, profile_photo, attendence_date, login_time, logout_time, Teamleader, attendance_type');
        $this->db->join("attendence","attendence.emp_id = employees.employee_no");
        $this->db->where('Teamleader', $this->empnum);
        $this->db->where('attendence_date', $ystrdy);

        $query = $this->db->get('employees');

        // var_dump($_SERVER['REMOTE_ADDR']);

        // if($_SERVER['REMOTE_ADDR']=="103.167.184.90") {
        //     echo "<pre>"; echo $this->db->last_query(); echo "</pre>"; die();
        // }


        if($query->num_rows() > 0) {
            $this->team_members_details = $query->result();
            foreach($this->team_members_details as $i => $team_member_detail) {
                if($team_member_detail->attendance_type=='Leave') {
                    $this->team_members_details[$i]->wasOnLeave = 1;
                } else {
                    $this->team_members_details[$i]->wasOnLeave = 0;
                }

                $this->db->select('t.task_id, t.project_id, t.assignee, t.task_heading, t.task_desc, t.task_status, t.allotted_hrs, t.allotted_min, p.project_name');
                $this->db->join('project_list as p', 'p.project_id=t.project_id', 'INNER');
                $this->db->where('assignee', $team_member_detail->employee_no);
                $this->db->where('task_start_date', $ystrdy);
                $this->db->where('task_approval_status', '0');
                $task_query = $this->db->get('task_list as t');
                // echo $this->db->last_query(); die();
                if($task_query->num_rows() > 0) {
                    $emp_tasks = $task_query->result_array();
                    $this->team_members_details[$i]->emp_tasks = $emp_tasks;
                } else {
                    $this->team_members_details[$i]->emp_tasks = '';
                }
            }

            // echo "<pre> In here :";
            // print_r($this->team_members_details);
            // echo "</pre>";
            // die();

            $this->get_team_members_leaves();
        }
        return $this->team_members_details;
    }

    function get_team_members_leaves() {
        $today = date('Y-m-d');

        $this->db->select('attendence.id as attendence_id, name, employee_no, attendence_date, leave_details, reason_for_leave, leave_approved_by_HR, leave_approved_by_TL');
        $this->db->join("attendence","attendence.emp_id = employees.employee_no","right");
        $this->db->where('Teamleader', $this->empnum);
        $this->db->where('attendence_date >=', $today);
        $this->db->where('attendance_type', 'Leave');
        $this->db->where('leave_approved_by_HR', 0);
        $this->db->where('leave_approved_by_TL', 0);
        $query = $this->db->get('employees');

        if($query->num_rows() > 0) {
            $team_members_leaves = $query->result_array();

            foreach($team_members_leaves as $j => $team_members_leave) {
                $this->team_members_leaves[$team_members_leave['employee_no']][$j] = $team_members_leave;
            }

            foreach($this->team_members_details as $i => $team_member_detail) {
                if(isset($this->team_members_leaves[$team_member_detail->employee_no])) {
                    $this->team_members_details[$i]->leaves = $this->team_members_leaves[$team_member_detail->employee_no];
                }
            }
        }
    }

    function approve_team_members_tasks($postData) {
        if($postData['comment_for_approval']=="") {
            $data = array(
                'task_approval_status' => '1'
            );
        } else {
            $data = array(
                'task_approval_status' => '2'
            );
        }
        if($postData['project_id']!="" && $postData['emp_id']!="" && $postData['tl_id']!="" && $postData['task_id']!="") {
            $this->db->where('task_id', $postData['task_id']);
            $this->db->update('task_list', $data);

            if($this->db->affected_rows() > 0) {
                if($postData['comment_for_approval']=="") {
                    $data1 = array(
                        'task_id' => $postData['task_id'],
                        'emp_id' => $postData['emp_id'],
                        'is_approved' => '1',
                        'TL_id' => $postData['tl_id'],
                        'comments' => $postData['comment_for_approval']
                    );
                } else {
                    $data1 = array(
                        'task_id' => $postData['task_id'],
                        'emp_id' => $postData['emp_id'],
                        'is_approved' => '2',
                        'TL_id' => $postData['tl_id'],
                        'comments' => $postData['comment_for_approval']
                    );
                }

                $this->db->insert('team_member_reports', $data1);

                if($this->db->affected_rows() > 0) {
                    return 1;
                } else
                    return -1;
            } else {
                return -1;
            }
        } else {
            return -1;
        }
    }

    function approve_team_members_leaves($postData) {
        // echo "<pre>"; print_r($postData); echo "</pre>"; die();
        if(isset($postData['approved_by']) && $postData['approved_by']=='tl') {
            $data = array (
                'leave_approved_by_TL' => 1,
            );
        } else if(isset($postData['approved_by']) && $postData['approved_by']=='hr') {
            $data = array(
                'leave_approved_by_HR' => 1,
            );
        } else if(isset($postData['rejected_by']) && $postData['rejected_by']=='tl') {
            $data = array (
                'leave_approved_by_TL' => 2,
            );
        } else if(isset($postData['rejected_by']) && $postData['rejected_by']=='hr') {
            $data = array(
                'leave_approved_by_HR' => 2,
            );
        }

        $this->db->where('attendence.id', $postData['leave_id']);
        $this->db->where('emp_id', $postData['emp_id']);
        $this->db->update('attendence', $data);

        if($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return -1;
        }
    }
}