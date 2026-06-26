<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_management extends CI_Controller {
    public function __construct(){
		parent::__construct();
        $this->load->model("Common_model");
        $this->load->model("Team_model");

        date_default_timezone_set('Asia/Kolkata');
	}

    public function team_reports() {
        $data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
        $data['team_members_details'] = $this->Team_model->get_team_members_summary();
        $this->load->view('team_management/team_attendance_report', $data);
    }

    public function update_task_approval_status() {
        $postData = $this->input->post();
        $task_approval_status = $this->Team_model->approve_team_members_tasks($postData);
        echo $task_approval_status;
    }

    public function update_leave_status() {
        $postData = $this->input->post();
        $leave_approval_status = $this->Team_model->approve_team_members_leaves($postData);
        echo $leave_approval_status;
    }
}