<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_management extends CI_Controller {
    public function __construct(){

		parent::__construct();
        $this->load->model("Common_model");
        $this->load->model("Task_model");
        $this->load->model("Project_model");
        $this->load->model("Testing_model");
        $this->load->library("Task_notification_lib");
	}

	public function task_view()
	{
		$data['project_name'] = $this->input->get('proj');
		$data['project_id'] = $proj_id = $this->input->get('proj_id');
		$data['service_id'] = $serv_id = $this->input->get('serv_id');
		$data['service_name'] = $this->input->get('serv');
		$data['get_login_user'] = $this->Common_model->get_login_user();

		$data['get_service_list'] = $this->Common_model->get_service_list($proj_id);
		$data['get_service_details'] = $this->Common_model->get_service_details($serv_id);
		$data['todays_task'] = $this->Task_model->get_service_wise_task($serv_id,'Today');
		$data['todo_task'] = $this->Task_model->get_service_wise_task($serv_id,'To Do');
		$data['complete_task'] = $this->Task_model->get_service_wise_task($serv_id,'Completed');

		$data['get_members_list'] = $this->Common_model->get_members_list();
		$data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
		$data['get_project_list'] = $this->Common_model->get_project_list();
		$data['get_proj_services'] = $this->Project_model->get_service_list($proj_id);
		$this->load->view('task_management/task_view', $data);
	}

	public function my_task()
	{
		$data['list'] = $list = urldecode($this->uri->segment(2));
		$user = $data['get_login_user'] = $this->Common_model->get_login_user();
		$data['is_tester_qa_panel'] = $this->Testing_model->is_tester($user) && $this->Testing_model->is_tester_qa_list($list);
		$data['is_testing_queue'] = ($list === 'Ready for Testing') || $data['is_tester_qa_panel'];
		$data['developers'] = [];
		$data['tester_qa_queues'] = $this->Testing_model->get_tester_qa_queues();

		if ($data['is_tester_qa_panel']) {
			$queues = $this->Testing_model->get_tester_qa_queues();
			$data['my_task_list'] = $this->Testing_model->get_tasks_for_testing(null, $queues[$list]);
			$data['developers'] = $this->Common_model->get_developers_list();
		} elseif ($list === 'Ready for Testing' && $this->Testing_model->is_tester($user)) {
			$data['my_task_list'] = $this->Testing_model->get_tasks_for_testing(null, 'Ready for Testing');
			$data['developers'] = $this->Common_model->get_developers_list();
			$data['is_tester_qa_panel'] = true;
		} elseif ($list === 'Ready for Testing') {
			$data['my_task_list'] = $this->Task_model->get_my_task('Ready for Testing');
		} else {
			$data['my_task_list'] = $this->Task_model->get_my_task($list);
		}

		// One list per screen — main tasks only (issues live in task_issues, not extra task_list rows)
		$data['doing_task_list'] = [];
		if ($list === 'In Progress') {
			$data['my_task_list'] = $this->Task_model->get_developer_fix_tasks();
		}

		$data['get_members_list'] = $this->Common_model->get_members_list();
		$data['get_project_list'] = $this->Common_model->get_project_list();
		$data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
		$data['flash_success'] = $this->session->flashdata('success');
		$data['flash_error'] = $this->session->flashdata('error');
		$this->load->view('task_management/my_task', $data);
	}

	public function task_overview()
	{
	    $data['get_login_user'] = $this->Common_model->get_login_user();

	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();

	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
		$this->load->view('task_management/task_overview',$data);
	}

	public function testing_tasks()
	{
		redirect('my-task/' . rawurlencode('Ready for Testing'));
	}

    public function testing_dashboard()
    {
        $user = $this->Common_model->get_login_user();
        if (strtolower($user->admin_section ?? '') !== 'yes') {
            redirect('dashboard');
        }

        $filters = [
            'project_id' => $this->input->get('project_id'),
            'module_id' => $this->input->get('module_id'),
            'employee' => $this->input->get('employee'),
            'from_date' => $this->input->get('from_date'),
            'to_date' => $this->input->get('to_date'),
        ];

        $data['get_login_user'] = $user;
        $data['filters'] = $filters;
        $data['stats'] = $this->Testing_model->get_dashboard_stats($filters);
        $data['module_breakdown'] = $this->Testing_model->get_module_issue_breakdown($filters['project_id']);
        $data['get_project_list'] = $this->Common_model->get_project_list();
        $data['get_testers'] = $this->Common_model->get_testers_list();
        $data['get_members_list'] = $this->Common_model->get_members_list();
        $data['get_module_list'] = $filters['project_id']
            ? $this->Common_model->get_module_list($filters['project_id'])
            : [];

        $this->load->view('task_management/testing_dashboard', $data);
    }

    public function check_module_required()
    {
        $project_id = $this->input->post('project_id');
        $service_id = $this->input->post('service_id');
        echo json_encode([
            'required' => $this->Testing_model->is_module_required($project_id, $service_id),
        ]);
    }

    public function get_testers_json()
    {
        echo json_encode($this->Common_model->get_testers_list());
    }

    /**
     * Module CRUD — allowed for department Teamleader or admin.
     */
    public function manage_modules()
    {
        $user = $this->Common_model->get_login_user();
        if (!$this->Common_model->can_manage_modules($user)) {
            redirect('dashboard');
        }

        $project_id = $this->input->get('project_id');

        $data['get_login_user'] = $user;
        $data['get_project_list'] = $this->Common_model->get_project_list();
        $data['selected_project_id'] = $project_id;
        $data['modules'] = $this->Common_model->get_modules_with_project($project_id);

        $this->load->view('task_management/manage_modules', $data);
    }

    public function save_module()
    {
        $user = $this->Common_model->get_login_user();
        if (!$this->Common_model->can_manage_modules($user)) {
            echo json_encode(['status' => 'error', 'msg' => 'Only Team Leaders or admins can manage modules']);
            return;
        }

        $module_id = $this->input->post('module_id');
        $project_id = $this->input->post('project_id');
        $module_name = trim($this->input->post('module_name'));
        $type = $this->input->post('type');

        if (empty($project_id) || $module_name === '') {
            echo json_encode(['status' => 'error', 'msg' => 'Project and module name are required']);
            return;
        }

        $this->db->where('project_id', $project_id);
        $this->db->where('module_name', $module_name);
        if ($module_id) {
            $this->db->where('module_id !=', $module_id);
        }
        if ($this->db->count_all_results('modules') > 0) {
            echo json_encode(['status' => 'error', 'msg' => 'A module with this name already exists for this project']);
            return;
        }

        $saved_id = $this->Common_model->save_module([
            'project_id' => $project_id,
            'module_name' => $module_name,
            'type' => $type,
        ], $module_id ?: null);

        echo json_encode([
            'status' => 'success',
            'msg' => $module_id ? 'Module updated' : 'Module created',
            'module_id' => $module_id ?: $saved_id,
        ]);
    }

    public function delete_module()
    {
        $user = $this->Common_model->get_login_user();
        if (!$this->Common_model->can_manage_modules($user)) {
            echo json_encode(['status' => 'error', 'msg' => 'Unauthorized']);
            return;
        }

        $module_id = $this->input->post('module_id');
        $in_use = $this->db->where('module_id', $module_id)->count_all_results('task_list');
        if ($in_use > 0) {
            echo json_encode(['status' => 'error', 'msg' => 'Cannot delete: ' . $in_use . ' task(s) use this module']);
            return;
        }

        if ($this->Common_model->delete_module($module_id)) {
            echo json_encode(['status' => 'success', 'msg' => 'Module deleted']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Could not delete module']);
        }
    }

	public function get_client_project_tasks_list() {
		$postData = $this->input->post();
		$data = $this->Task_model->get_client_project_tasklist($postData);
		echo json_encode($data);
	}

	public function task_overview_listing()
	{

	    $postData = $this->input->post();
            $data = $this->Task_model->project_overview_list($postData);
            echo json_encode($data);
	}

	public function service_list()
	{
	    $proj_id = $this->input->post('proj_id');
	    $get_service_list = $this->Common_model->get_service_list($proj_id);
	    if(!empty($get_service_list)){
        foreach ($get_service_list as $serv_list)
        {
            echo '<option value="'.$serv_list->service_id.'">'.$serv_list->service_name.'</option>';
        }}
	}

	public function module_list()
	{
	    $proj_id = $this->input->post('proj_id');
	    $get_module_list = $this->Common_model->get_module_list($proj_id);
	    if(!empty($get_module_list)){
            echo '<option value="">Select Module</option>';
            foreach ($get_module_list as $mod_list)
            {
                echo '<option value="'.$mod_list->module_id.'">'.$mod_list->module_name.'</option>';
            }
        } else {
            echo '<option value="">No Modules Found</option>';
        }
	}

	public function remove_task()
	{
	$id = $this->input->post("string");
    $delete_task = $this->Common_model->delete_data($id,'task_id','task_list');
    //echo ($delete_task) ? 1 : 2;
	}

	public function update_task_status()
	{
	    $selected_status = $this->input->post("selectedValue");
	    $rowid = $this->input->post("selectBoxId");
	    $user = $this->Common_model->get_login_user();

        $this->db->select('task_status, tester_id, assignee');
        $this->db->where('task_id', $rowid);
        $old_task = $this->db->get('task_list')->row();

        if (!$old_task) {
            echo json_encode(['status' => 'error', 'msg' => 'Task not found']);
            return;
        }

        $is_tester = $this->Testing_model->can_add_issues($user);
        $is_admin = strtolower($user->admin_section ?? '') === 'yes';

        if ($is_tester || $is_admin) {
            if (!in_array($selected_status, $this->Testing_model->get_tester_allowed_statuses(), true)) {
                echo json_encode(['status' => 'error', 'msg' => 'Status not allowed for QA workflow']);
                return;
            }
        }

        if ($selected_status == 'Completed') {
            if (!$is_tester && !$is_admin) {
                echo json_encode(['status' => 'error', 'msg' => 'Only testers can mark tasks as Completed']);
                return;
            }
            $open = $this->db->where('task_id', $rowid)->where('status', 'Open')->count_all_results('task_issues');
            if ($open > 0) {
                echo json_encode(['status' => 'error', 'msg' => 'Close all open issues before completing the task']);
                return;
            }
        }

        $is_correction = (int) $this->input->post('is_correction') === 1;

        $workflow_hrs = (int) $this->input->post('workflow_hrs');
        $workflow_min = (int) $this->input->post('workflow_min');
        $workflow_minutes = $this->Testing_model->parse_workflow_minutes($workflow_hrs, $workflow_min);

        $needs_dev_time = !$is_correction && $this->Testing_model->status_requires_developer_time(
            $selected_status,
            $old_task->task_status,
            (bool) $is_tester
        );
        $needs_tester_time = !$is_correction && $this->Testing_model->status_requires_tester_time($selected_status)
            && ($is_tester || $is_admin);

        if ($needs_dev_time || $needs_tester_time) {
            if ($workflow_minutes <= 0) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => $needs_dev_time
                        ? 'Enter how much time you spent on development before sending for testing.'
                        : 'Enter how much testing time you spent before changing this status.',
                    'requires_time' => true,
                ]);
                return;
            }
            if ($needs_dev_time) {
                $this->Testing_model->add_developer_time_to_parent($rowid, $workflow_hrs, $workflow_min);
                $this->Testing_model->log_activity(
                    $rowid,
                    'developer_time',
                    "Developer session +{$workflow_hrs}h {$workflow_min}m before Ready for Testing",
                    $user->employee_no
                );
            } else {
                $this->Testing_model->add_tester_time_to_parent($rowid, $workflow_hrs, $workflow_min);
                $this->Testing_model->log_activity(
                    $rowid,
                    'tester_time',
                    "Tester session +{$workflow_hrs}h {$workflow_min}m before {$selected_status}",
                    $user->employee_no
                );
            }
        }

	    $this->db->where('task_id', $rowid);
        $this->db->update('task_list', ['task_status' => $selected_status]);

        $status_remarks = $is_correction
            ? "Status corrected from {$old_task->task_status} to {$selected_status} (no time logged)"
            : "Status changed from {$old_task->task_status} to {$selected_status}";

        $this->Testing_model->log_activity(
            $rowid,
            'status_change',
            $status_remarks,
            $user->employee_no,
            $old_task->task_status,
            $selected_status
        );

        if ($selected_status === 'Ready for Testing') {
            $this->task_notification_lib->notify_tester_task_ready($rowid);
        } else {
            $notify_to = ($selected_status === 'In Progress') ? $old_task->assignee : $old_task->tester_id;
            if ($notify_to) {
                $this->task_notification_lib->notify_status_update($rowid, $old_task->task_status, $selected_status, $notify_to);
            }
        }

        $totals = $this->Testing_model->recalculate_task_total_time($rowid);
        echo json_encode([
            'status' => 'success',
            'time_summary' => $totals,
        ]);
	}

    public function report_issues($task_id = null)
    {
        $user = $this->Common_model->get_login_user();
        if (!$this->Testing_model->can_add_issues($user)) {
            redirect('dashboard');
        }

        $task_id = (int) ($task_id ?: $this->uri->segment(2));
        $task = $this->Testing_model->get_task_for_report($task_id);

        if (!$task) {
            $this->session->set_flashdata('error', 'Task not found.');
            redirect('my-task/' . rawurlencode('Ready for Testing'));
        }

        $data['get_login_user'] = $user;
        $data['task'] = $task;
        $data['developers'] = $this->Common_model->get_developers_list();
        $data['existing_issues'] = $this->_issues_with_images($this->Task_model->get_task_issues($task_id), $task_id);
        $data['time_summary'] = $this->Testing_model->recalculate_task_total_time($task_id);
        $data['flash_success'] = $this->session->flashdata('success');
        $data['flash_error'] = $this->session->flashdata('error');

        $this->load->view('task_management/report_issues', $data);
    }

	public function save_issue()
	{
	    $task_id = $this->input->post('task_id');
	    $submit_action = $this->input->post('submit_action') ?: 'save_and_return';
	    $issue_titles = $this->input->post('issue_title');
	    $issue_descs = $this->input->post('issue_desc');
	    $priorities = $this->input->post('priority');
	    $assigned_tos = $this->input->post('assigned_to');
	    $session_tester_hrs = max(0, (int) $this->input->post('session_tester_hrs'));
	    $session_tester_min = max(0, min(59, (int) $this->input->post('session_tester_min')));
	    $user = $this->Common_model->get_login_user();
        $is_ajax = $this->input->is_ajax_request();

        if (!$this->Testing_model->can_add_issues($user)) {
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'msg' => 'Only Software Testing team members can add issues']);
                return;
            }
            $this->session->set_flashdata('error', 'Only Software Testing team members can add issues.');
            redirect('dashboard');
        }

        $task = $this->db->get_where('task_list', ['task_id' => $task_id])->row();
        if (!$task) {
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'msg' => 'Task not found']);
                return;
            }
            $this->session->set_flashdata('error', 'Task not found.');
            redirect('my-task/' . rawurlencode('Ready for Testing'));
        }

        if (empty($issue_titles) || !is_array($issue_titles)) {
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'msg' => 'Please add at least one issue']);
                return;
            }
            $this->session->set_flashdata('error', 'Please add at least one issue with a title.');
            redirect('report-issues/' . $task_id);
        }

        $default_dev = $task->assignee;
        $issue_count = 0;
        $upload_warnings = [];
        foreach ($issue_titles as $key => $title) {
            if (!empty(trim($title))) {
                $dev = !empty($assigned_tos[$key]) ? $assigned_tos[$key] : $default_dev;
                $desc = isset($issue_descs[$key]) ? $issue_descs[$key] : '';

                $row = [
                    'task_id' => $task_id,
                    'issue_title' => trim($title),
                    'issue_desc' => $desc,
                    'priority' => $priorities[$key] ?? 'Normal',
                    'status' => 'Open',
                    'assigned_to' => $dev,
                    'created_by' => $user->employee_no,
                    'created_on' => date('Y-m-d H:i:s'),
                ];
                $this->db->insert('task_issues', $row);
                $issue_id = (int) $this->db->insert_id();

                $upload_result = $this->_upload_issue_images_for_row($key, $issue_id);
                if (!empty($upload_result['errors'])) {
                    foreach ($upload_result['errors'] as $err) {
                        $upload_warnings[] = 'Issue "' . trim($title) . '": ' . $err;
                    }
                }
                if (!empty($upload_result['first']) && $this->db->field_exists('issue_image', 'task_issues')) {
                    $this->db->where('issue_id', $issue_id)->update('task_issues', ['issue_image' => $upload_result['first']]);
                }

                $this->task_notification_lib->notify_developer_issue_assigned($task_id, trim($title), $dev);
                $issue_count++;
            }
        }

        if ($issue_count === 0) {
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'msg' => 'Please add at least one issue']);
                return;
            }
            $this->session->set_flashdata('error', 'Please add at least one issue with a title.');
            redirect('report-issues/' . $task_id);
        }

        if ($submit_action === 'save_and_return') {
            $session_tester_minutes = ($session_tester_hrs * 60) + $session_tester_min;
            if ($session_tester_minutes <= 0) {
                $this->session->set_flashdata('error', 'Please enter total testing time when assigning issues to the developer.');
                redirect('report-issues/' . $task_id);
            }
            $this->Testing_model->add_tester_time_to_parent($task_id, $session_tester_hrs, $session_tester_min);
        }

        $totals = $this->Testing_model->recalculate_task_total_time($task_id);

        if ($submit_action === 'save_and_return') {
            $this->db->where('task_id', $task_id);
            $this->db->update('task_list', ['task_status' => 'In Progress']);

            $this->Testing_model->log_activity(
                $task_id,
                'issues_raised',
                "{$issue_count} issue(s) reported. Task moved to In Progress",
                $user->employee_no,
                $task->task_status,
                'In Progress'
            );

            $msg = 'Issues submitted. Task returned to developer for fixing.';
            $redirect_url = 'my-task/' . rawurlencode('Ready for Testing');
        } else {
            $this->Testing_model->log_activity(
                $task_id,
                'issues_raised',
                "{$issue_count} issue(s) saved. Task remains {$task->task_status}",
                $user->employee_no,
                $task->task_status,
                $task->task_status
            );

            $msg = "{$issue_count} issue(s) saved. Task status unchanged.";
            $redirect_url = 'report-issues/' . $task_id;
        }

        if ($submit_action === 'save_and_return' && $totals) {
            $msg .= ' Total testing time added: ' . $session_tester_hrs . 'h ' . $session_tester_min . 'm.';
            $msg .= ' Task total: ' . $totals['total_hrs'] . 'h ' . $totals['total_min'] . 'm.';
        }

        if (!empty($upload_warnings)) {
            $msg .= ' ' . implode(' ', $upload_warnings);
        }

        if ($is_ajax) {
            echo json_encode(['status' => 'success', 'msg' => $msg]);
            return;
        }

        $this->session->set_flashdata('success', $msg);
        redirect($redirect_url);
	}

    /** Attach image rows (and legacy issue_image) to issue objects for views/API. */
    private function _issues_with_images($issues, $task_id)
    {
        $map = $this->Task_model->get_issue_images_map_for_task($task_id);
        foreach ($issues as $issue) {
            $issue->images = $map[$issue->issue_id] ?? [];
            if (empty($issue->images) && !empty($issue->issue_image)) {
                $issue->images = [(object) ['file_name' => $issue->issue_image]];
            }
        }
        return $issues;
    }

    /**
     * Upload all images for one form row (issue_images[row_index][]).
     * @return array{first: ?string, errors: string[]}
     */
    private function _upload_issue_images_for_row($row_index, $issue_id)
    {
        $out = ['first' => null, 'errors' => []];
        if (empty($_FILES['issue_images']['name'][$row_index])) {
            return $out;
        }

        $names = $_FILES['issue_images']['name'][$row_index];
        if (!is_array($names)) {
            $names = [$names];
            $types = [$_FILES['issue_images']['type'][$row_index]];
            $tmp_names = [$_FILES['issue_images']['tmp_name'][$row_index]];
            $errors = [$_FILES['issue_images']['error'][$row_index]];
            $sizes = [$_FILES['issue_images']['size'][$row_index]];
        } else {
            $types = $_FILES['issue_images']['type'][$row_index];
            $tmp_names = $_FILES['issue_images']['tmp_name'][$row_index];
            $errors = $_FILES['issue_images']['error'][$row_index];
            $sizes = $_FILES['issue_images']['size'][$row_index];
        }

        $upload_dir = FCPATH . 'uploads/issue_images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $config = [
            'upload_path'   => './uploads/issue_images/',
            'allowed_types' => 'jpg|jpeg|png|gif|webp',
            'max_size'      => 5120,
            'encrypt_name'  => true,
        ];
        $this->load->library('upload');

        $saved = 0;
        $max_per_issue = 10;

        foreach ($names as $i => $name) {
            if ($saved >= $max_per_issue) {
                $out['errors'][] = 'Maximum ' . $max_per_issue . ' images per issue.';
                break;
            }
            if (empty($name) || (int) ($errors[$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $_FILES['issue_upload'] = [
                'name'     => $name,
                'type'     => $types[$i],
                'tmp_name' => $tmp_names[$i],
                'error'    => $errors[$i],
                'size'     => $sizes[$i],
            ];

            $this->upload->initialize($config);
            if (!$this->upload->do_upload('issue_upload')) {
                $out['errors'][] = strip_tags($this->upload->display_errors('', ''));
                continue;
            }

            $file_name = $this->upload->data('file_name');
            $this->Task_model->save_issue_image_record($issue_id, $file_name);
            if ($out['first'] === null) {
                $out['first'] = $file_name;
            }
            $saved++;
        }

        return $out;
    }

    public function update_issue_status()
    {
        $issue_id = (int) $this->input->post('issue_id');
        $status = trim($this->input->post('status') ?? '');
        $user = $this->Common_model->get_login_user();
        $allowed = ['Open', 'Fixed', 'Closed', 'Reopened'];

        if (!in_array($status, $allowed, true)) {
            echo json_encode(['status' => 'error', 'msg' => 'Invalid issue status']);
            return;
        }

        $issue = $this->Task_model->get_issue_by_id($issue_id);
        if (!$issue) {
            echo json_encode(['status' => 'error', 'msg' => 'Issue not found']);
            return;
        }

        if (!$this->_can_view_issue($issue, $user)) {
            echo json_encode(['status' => 'error', 'msg' => 'Unauthorized']);
            return;
        }

        $old_status = $issue->status;
        $this->db->where('issue_id', $issue_id);
        $this->db->update('task_issues', [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->Testing_model->log_activity(
            $issue->task_id,
            'issue_status_changed',
            "Issue #{$issue_id} \"{$issue->issue_title}\": {$old_status} → {$status}",
            $user->employee_no
        );

        if ($this->input->is_ajax_request()) {
            echo json_encode(['status' => 'success', 'msg' => 'Issue status updated to ' . $status]);
            return;
        }

        $this->session->set_flashdata('success', 'Issue status updated to ' . $status . '.');
        redirect('issue-detail/' . $issue_id);
    }

    public function assign_issue()
    {
        $issue_id = $this->input->post('issue_id');
        $assigned_to = $this->input->post('assigned_to');
        $user = $this->Common_model->get_login_user();

        if (!$this->Testing_model->is_tester($user)) {
            echo json_encode(['status' => 'error', 'msg' => 'Unauthorized']);
            return;
        }

        $issue = $this->db->get_where('task_issues', ['issue_id' => $issue_id])->row();
        if (!$issue) {
            echo json_encode(['status' => 'error', 'msg' => 'Issue not found']);
            return;
        }

        $this->db->where('issue_id', $issue_id);
        $this->db->update('task_issues', [
            'assigned_to' => $assigned_to,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->Testing_model->log_activity(
            $issue->task_id,
            'issue_reassigned',
            "Issue \"{$issue->issue_title}\" reassigned",
            $user->employee_no
        );

        $this->task_notification_lib->notify_developer_issue_assigned($issue->task_id, $issue->issue_title, $assigned_to);

        echo json_encode(['status' => 'success', 'msg' => 'Issue reassigned']);
    }

    public function finalize_testing()
    {
        $task_id = $this->input->post('task_id');
        $tester_hrs = (int) $this->input->post('tester_hrs');
        $tester_min = (int) $this->input->post('tester_min');
        $mark_complete = $this->input->post('mark_complete') === '1';
        $user = $this->Common_model->get_login_user();

        if (!$this->Testing_model->can_add_issues($user)) {
            echo json_encode(['status' => 'error', 'msg' => 'Only testers can finalize testing']);
            return;
        }

        $task = $this->db->get_where('task_list', ['task_id' => $task_id])->row();
        if (!$task) {
            echo json_encode(['status' => 'error', 'msg' => 'Task not found']);
            return;
        }

        $open = $this->db->where('task_id', $task_id)->where('status', 'Open')->count_all_results('task_issues');

        if ($mark_complete && $open > 0) {
            echo json_encode(['status' => 'error', 'msg' => 'Resolve or close all issues before marking complete']);
            return;
        }

        $session_minutes = $this->Testing_model->parse_workflow_minutes($tester_hrs, $tester_min);
        if ($session_minutes <= 0) {
            echo json_encode(['status' => 'error', 'msg' => 'Please enter testing time (hours and/or minutes) before saving.']);
            return;
        }

        $this->Testing_model->add_tester_time_to_parent($task_id, $tester_hrs, $tester_min);

        $update_row = [];
        if ($mark_complete) {
            $update_row['task_status'] = 'Completed';
        }
        if (!empty($update_row)) {
            $this->db->where('task_id', $task_id);
            $this->db->update('task_list', $update_row);
        }

        $totals = $this->Testing_model->recalculate_task_total_time($task_id);

        $this->Testing_model->log_activity(
            $task_id,
            'testing_finalized',
            "Tester time logged: {$tester_hrs}h {$tester_min}m. Total task time: " . floor($totals['total_minutes'] / 60) . 'h ' . ($totals['total_minutes'] % 60) . 'm',
            $user->employee_no,
            $task->task_status,
            $mark_complete ? 'Completed' : $task->task_status
        );

        if ($mark_complete) {
            $this->task_notification_lib->notify_status_update($task_id, $task->task_status, 'Completed', $task->assignee);
        }

        echo json_encode([
            'status' => 'success',
            'msg' => $mark_complete ? 'Task marked complete' : 'Testing time saved',
            'time_summary' => $totals,
        ]);
    }

	public function update_task_time()
	{
	    $task_id = $this->input->post('task_id');
	    $issue_id = $this->input->post('issue_id');
	    $fix_hrs = (int) $this->input->post('fix_hrs');
	    $fix_min = (int) $this->input->post('fix_min');
	    $user = $this->Common_model->get_login_user();

        $this->db->select('actual_hrs, actual_min');
        $this->db->where('task_id', $task_id);
        $task = $this->db->get('task_list')->row();

        if (!$task) {
            echo json_encode(['status' => 'error', 'msg' => 'Task not found']);
            return;
        }

        $add_min = $this->Testing_model->parse_workflow_minutes($fix_hrs, $fix_min);
        if ($add_min <= 0) {
            echo json_encode(['status' => 'error', 'msg' => 'Please enter time spent (hours and/or minutes).']);
            return;
        }

        $issue = null;

        if ($issue_id) {
            $issue = $this->db->get_where('task_issues', ['issue_id' => $issue_id])->row();
            if ($issue) {
                $issue_total = ((int) $issue->time_spent_hrs * 60) + (int) $issue->time_spent_min + $add_min;
                $this->db->where('issue_id', $issue_id);
                $this->db->update('task_issues', [
                    'time_spent_hrs' => floor($issue_total / 60),
                    'time_spent_min' => $issue_total % 60,
                    'status' => 'Fixed',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        } else {
            $this->Testing_model->add_developer_time_to_parent($task_id, $fix_hrs, $fix_min);
        }

        $totals = $this->Testing_model->recalculate_task_total_time($task_id);

        $this->Testing_model->log_activity(
            $task_id,
            'developer_time',
            "Developer time +{$fix_hrs}h {$fix_min}m" . ($issue ? " on issue \"{$issue->issue_title}\"" : ' (task)'),
            $user->employee_no
        );

        echo json_encode([
            'status' => 'success',
            'msg' => 'Developer time logged',
            'time_summary' => $totals,
        ]);
	}

    public function get_task_activity_log()
    {
        $task_id = $this->input->post('task_id');
        $activities = $this->Task_model->get_task_activity($task_id);
        echo json_encode($activities);
    }

    public function get_task_issues_json()
    {
        $task_id = $this->input->post('task_id');
        $issues = $this->_issues_with_images($this->Task_model->get_task_issues($task_id), $task_id);
        foreach ($issues as $issue) {
            $issue->image_urls = [];
            foreach ($issue->images as $img) {
                $issue->image_urls[] = base_url('uploads/issue_images/' . $img->file_name);
            }
            if (!empty($issue->image_urls[0])) {
                $issue->image_url = $issue->image_urls[0];
            }
            $issue->detail_url = base_url('issue-detail/' . $issue->issue_id);
        }
        echo json_encode($issues);
    }

    public function task_issues_list($task_id = null)
    {
        $task_id = (int) ($task_id ?: $this->uri->segment(2));
        $user = $this->Common_model->get_login_user();
        $task = $this->Testing_model->get_task_for_report($task_id);

        if (!$task) {
            $this->session->set_flashdata('error', 'Task not found.');
            redirect('dashboard');
        }

        if (!$this->_can_view_task_issues($task, $user)) {
            $this->session->set_flashdata('error', 'You do not have access to this task\'s issues.');
            redirect('dashboard');
        }

        $issues = $this->_issues_with_images($this->Task_model->get_task_issues($task_id), $task_id);
        $open_count = 0;
        foreach ($issues as $issue) {
            if (($issue->status ?? '') === 'Open') {
                $open_count++;
            }
        }

        $data['get_login_user'] = $user;
        $data['task'] = $task;
        $data['issues'] = $issues;
        $data['open_issues_count'] = $open_count;
        $data['total_issues_count'] = count($issues);
        $data['time_summary'] = $this->Testing_model->recalculate_task_total_time($task_id);
        $data['can_add_issues'] = $this->Testing_model->can_add_issues($user);
        $data['is_developer'] = ($user->employee_no ?? '') === ($task->assignee ?? '');
        $data['back_url'] = $this->_task_issues_back_url($user, $task);
        $data['flash_success'] = $this->session->flashdata('success');
        $data['flash_error'] = $this->session->flashdata('error');

        $this->load->view('task_management/task_issues_list', $data);
    }

    public function issue_detail($issue_id = null)
    {
        $issue_id = (int) ($issue_id ?: $this->uri->segment(2));
        $user = $this->Common_model->get_login_user();
        $issue = $this->Task_model->get_issue_by_id($issue_id);

        if (!$issue) {
            $this->session->set_flashdata('error', 'Issue not found.');
            redirect('dashboard');
        }

        if (!$this->_can_view_issue($issue, $user)) {
            $this->session->set_flashdata('error', 'You do not have access to this issue.');
            redirect('dashboard');
        }

        $images = $this->Task_model->get_images_for_issue($issue_id);
        foreach ($images as $img) {
            $img->url = base_url('uploads/issue_images/' . $img->file_name);
        }
        $issue->images = $images;

        $data['get_login_user'] = $user;
        $data['issue'] = $issue;
        $data['time_summary'] = $this->Testing_model->recalculate_task_total_time($issue->task_id);
        $data['can_add_issues'] = $this->Testing_model->can_add_issues($user);
        $data['flash_error'] = $this->session->flashdata('error');
        $data['flash_success'] = $this->session->flashdata('success');

        $this->load->view('task_management/issue_detail', $data);
    }

    private function _can_view_issue($issue, $user)
    {
        if (!$user || !$issue) {
            return false;
        }
        if (strtolower($user->admin_section ?? '') === 'yes') {
            return true;
        }
        if ($this->Testing_model->is_tester($user)) {
            return true;
        }
        $emp = $user->employee_no;
        return $emp === ($issue->task_assignee ?? '')
            || $emp === ($issue->assigned_to ?? '')
            || $emp === ($issue->created_by ?? '')
            || $emp === ($issue->tester_id ?? '');
    }

    private function _can_view_task_issues($task, $user)
    {
        if (!$user || !$task) {
            return false;
        }
        if (strtolower($user->admin_section ?? '') === 'yes') {
            return true;
        }
        if ($this->Testing_model->is_tester($user)) {
            return true;
        }
        $emp = $user->employee_no;
        if ($emp === ($task->assignee ?? '')
            || $emp === ($task->task_assign_by ?? '')
            || $emp === ($task->tester_id ?? '')) {
            return true;
        }
        $this->db->where('task_id', (int) $task->task_id);
        $this->db->where('assigned_to', $emp);
        return $this->db->count_all_results('task_issues') > 0;
    }

    private function _task_issues_back_url($user, $task)
    {
        if ($this->Testing_model->is_tester($user)) {
            return base_url('my-task/' . rawurlencode('Ready for Testing'));
        }
        if (($user->employee_no ?? '') === ($task->assignee ?? '')) {
            return base_url('my-task/' . rawurlencode('In Progress'));
        }
        return base_url('dashboard');
    }

	public function edit_task()
	{
	 $data['edit_id'] = $edit_id = $this->uri->segment(2);
	 $data['edit_task_list'] = $edit_data = $this->Task_model->get_edit_task($edit_id);
	 $data['get_login_user'] = $this->Common_model->get_login_user();
	 $data['get_members_list'] = $this->Common_model->get_members_list();
	 $data['get_project_list'] = $this->Common_model->get_project_list();
	 $data['get_service_list'] = $this->Common_model->get_service_list($edit_data->project_id);
	 $data['get_module_list'] = $this->Common_model->get_module_list($edit_data->project_id);
	 $data['task_issues'] = $this->_issues_with_images($this->Task_model->get_task_issues($edit_id), $edit_id);
	 $data['get_testers'] = $this->Common_model->get_testers_list();
	 $data['time_summary'] = $this->Testing_model->recalculate_task_total_time($edit_id);
	 $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	  $this->load->view('task_management/edit_task',$data);
	}

	public function save_task()
	{
	     $current_datetime = new DateTime();
        $current_time = $current_datetime->format('H:i'); // Get the current time in 24-hour format

        // Get the current date
        $current_date = $current_datetime->format('Y-m-d');

	    $emp_num = $this->session->userdata('user_id');
	    $rowid = $this->input->post('task_id');
	    $service_id = $this->input->post('service_name');
	    $project_id = $this->input->post('proj_name');
	    $task_title = $this->input->post('task_title');
	    $task_type = $this->input->post('task_type');
	    $recurring_time = $this->input->post('recurring_task');
	    $module_id = $this->input->post('module_id');
	    $tester_id = $this->input->post('tester_id');
	    $task_desc = $this->input->post('task_desc');
	    if($this->input->post('assignees[]') != ''){
	         $assing = implode(",",$this->input->post('assignees[]'));
	    }else{
	         $assing = '';
	    }

	    $startdate = date("Y-m-d", strtotime($this->input->post('start_date')));
	    $enddate =   date("Y-m-d", strtotime($this->input->post('end_date')));

		$previous_day = date('Y-m-d', strtotime('-1 day'));
		$holidays = $this->Task_model->get_holiday_dates();

		if(in_array($previous_day, $holidays)==true) {
			while(in_array($previous_day, $holidays)==true) {
				$previous_day = date('Y-m-d', strtotime('-1 day', strtotime($previous_day)));
			}
		}

		$day = date('D', strtotime($previous_day));

		$sunday = date('Y-m-d', strtotime($previous_day));

		if(strtolower($day)=="sun") {
			$previous_day = date('Y-m-d', strtotime('-1 day', strtotime($sunday)));
		}

	    if($rowid == '') {
			// Check if the current time is after 2 pm (14:00)
			if ($current_time > '08:30') {

				if ($startdate < $current_date) {
					// If the submitted date is a past date, show an error message or redirect back to the form with an error message
					$data['code'] = 900;
					$data['msg'] = '<h4 class="alert alert-outline-danger">You cannot save tasks for past dates after 2 pm.</h4>';
					echo json_encode($data);
					die;
				} else {
					if($startdate < $previous_day) {
						$data['code'] = 901;
						$data['msg'] = '<h4 class="alert alert-outline-danger">You cannot save tasks for past dates before ' . date('d-m-Y', strtotime($previous_day)) . '.</h4>';
						echo json_encode($data);
						die;
					}
				}
			}
	    }

	    if ($this->Testing_model->is_module_required($project_id, $service_id) && empty($module_id)) {
			$data['code'] = 904;
			$data['msg'] = '<h4 class="alert alert-outline-danger">Module is required for this project/service.</h4>';
			echo json_encode($data);
			return;
	    }

	    if($project_id != '' && $service_id != '' && $task_title != '' && $assing != ''){

			$task_row = [
				'project_id' => $project_id,
				'service_id' => $service_id,
				'module_id' => ($module_id != '') ? $module_id : NULL,
				'task_heading' => $task_title,
				'task_desc' => $task_desc,
				'task_status' => $this->input->post('task_status'),
				'task_type' => $this->input->post('task_type'),
				'recurring_type' => $this->input->post('recurring_task'),
				'task_start_date' => $startdate,
				'task_end_date' => $enddate,
				'allotted_hrs' => $this->input->post('allotted_hrs'),
				'allotted_min' => $this->input->post('allotted_min'),
				'priority' => $this->input->post('task_priority'),
				'assignee' => $assing,
				'tester_id' => ($tester_id != '') ? $tester_id : NULL,
				'task_assign_by' => $emp_num,
				'created_on' => date('Y-m-d'),
			];
			if ($this->db->field_exists('actual_hrs', 'task_list')) {
				$task_row['actual_hrs'] = max(0, (int) $this->input->post('actual_hrs'));
				$task_row['actual_min'] = max(0, min(59, (int) $this->input->post('actual_min')));
			}

			if($rowid != '') {
				$this->db->where('task_id', $rowid);
				$this->db->update('task_list', $task_row);

                $this->Testing_model->log_activity($rowid, 'task_updated', 'Task details updated', $emp_num);

				if($this->db->affected_rows() > 0) {
					$data['code'] = 200;
					$data['msg'] = '<h4 class="alert alert-outline-success">Task updated successfully.<h4>';
					echo json_encode($data);
				}
				else {
					$data['code'] = 200;
					$data['msg'] = '<h4 class="alert alert-outline-info">Task already updated.<h4>';
					echo json_encode($data);
				}
			} else {
				$tags = explode(',',$assing);

				foreach($tags as $key) {
					$row = $task_row;
					$row['assignee'] = $key;
					if ($this->db->field_exists('workflow_kind', 'task_list')) {
						$row['workflow_kind'] = 'main';
						$row['parent_id'] = null;
					}
					$this->db->insert('task_list', $row);
                    $new_task_id = $this->db->insert_id();

                    $this->Testing_model->log_activity(
                        $new_task_id,
                        'task_created',
                        'Task created',
                        $emp_num,
                        null,
                        $this->input->post('task_status')
                    );

                    if ($this->input->post('task_status') === 'Ready for Testing') {
                        $this->task_notification_lib->notify_tester_task_ready($new_task_id);
                    }
                    $insert = TRUE;
				}
				if($insert)
				{
					$data['code'] = 200;
					$data['msg'] = '<h4 class="alert alert-outline-success">Great, Task added successfully<h4>';
					echo json_encode($data);
				} else {
					$data['code'] = 902;
					$data['msg'] = '<h4 class="alert alert-outline-danger">Something went wrong, try again later<h4>';
					echo json_encode($data);
				}
			}
		} else {
			$data['code'] = 903;
			$data['msg'] = '<h4 class="alert alert-outline-danger">Provide service name, project name and Assignees<h4>';
			echo json_encode($data);
		}
	}

	/*******************************************************/
// Direct Excel Download
    public function export_to_excel()
    {
        $postData = array(
            'search' => array('value' => $this->input->post('search') ?? ''),
            'employee' => $this->input->post('employee') ?? '',
            'projects' => $this->input->post('projects') ?? '',
            'from_date' => $this->input->post('from_date') ?? '',
            'to_date' => $this->input->post('to_date') ?? ''
        );

        // Get data
        $result = $this->Task_model->export_excel_data($postData);
        $records = $result['data'];
        $total_hrs = $result['total_hrs'];
        $total_min = $result['total_min'];

        // Set Excel Headers
        $filename = 'Task_Overview_' . date('d-m-Y_H-i-s') . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        // HTML Table for Excel
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Task Overview</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        echo '<body>';
        echo '<table border="1">';
        
        // Header Row
        echo '<thead>';
        echo '<tr style="background-color: #43A047; color: #FFFFFF; font-weight: bold; text-align: center;">';
        echo '<th style="width: 120px;">Start Date</th>';
        echo '<th style="width: 120px;">End Date</th>';
        echo '<th style="width: 300px;">Task Heading</th>';
        echo '<th style="width: 150px;">Employee</th>';
        echo '<th style="width: 150px;">Project</th>';
        echo '<th style="width: 120px;">Task Status</th>';
        echo '<th style="width: 80px;">Hrs</th>';
        echo '<th style="width: 80px;">Mins</th>';
        echo '<th style="width: 100px;">Total Days</th>';
        echo '</tr>';
        echo '</thead>';
        
        // Data Rows
        echo '<tbody>';
        foreach($records as $record) {
            echo '<tr>';
            echo '<td>' . $record['task_start_date'] . '</td>';
            echo '<td>' . $record['task_end_date'] . '</td>';
            echo '<td>' . $record['task_heading'] . '</td>';
            echo '<td>' . $record['employee'] . '</td>';
            echo '<td>' . $record['project'] . '</td>';
            echo '<td>' . $record['task_status'] . '</td>';
            echo '<td style="text-align: center;">' . $record['hrs'] . '</td>';
            echo '<td style="text-align: center;">' . $record['min'] . '</td>';
            echo '<td style="text-align: center;">' . $record['total_days'] . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        
        // Totals Row
        echo '<tfoot>';
        echo '<tr style="background-color: #43A047; color: #FFFFFF; font-weight: bold; text-align: center;">';
        echo '<td colspan="5"></td>';
        echo '<td>Totals</td>';
        echo '<td>' . $total_hrs . '</td>';
        echo '<td>' . $total_min . '</td>';
        echo '<td></td>';
        echo '</tr>';
        echo '</tfoot>';
        
        echo '</table>';
        echo '</body>';
        echo '</html>';
        exit;
    }

    // Generate and Save Excel to Server (Live Link)
    public function generate_live_excel()
    {
        $postData = array(
            'search' => array('value' => ''),
            'employee' => $this->input->post('employee') ?? '',
            'projects' => $this->input->post('projects') ?? '',
            'from_date' => $this->input->post('from_date') ?? '',
            'to_date' => $this->input->post('to_date') ?? ''
        );

        // Get data
        $result = $this->Task_model->export_excel_data($postData);
        $records = $result['data'];
        $total_hrs = $result['total_hrs'];
        $total_min = $result['total_min'];

        // Create uploads directory if not exists
        $upload_path = FCPATH . 'uploads/excel/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // File path
        $filename = 'task_overview.xls';
        $filepath = $upload_path . $filename;

        // Generate HTML content
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Task Overview</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body>';
        $html .= '<table border="1">';
        
        // Header
        $html .= '<thead>';
        $html .= '<tr style="background-color: #43A047; color: #FFFFFF; font-weight: bold; text-align: center;">';
        $html .= '<th style="width: 120px;">Start Date</th>';
        $html .= '<th style="width: 120px;">End Date</th>';
        $html .= '<th style="width: 300px;">Task Heading</th>';
        $html .= '<th style="width: 150px;">Employee</th>';
        $html .= '<th style="width: 150px;">Project</th>';
        $html .= '<th style="width: 120px;">Task Status</th>';
        $html .= '<th style="width: 80px;">Hrs</th>';
        $html .= '<th style="width: 80px;">Mins</th>';
        $html .= '<th style="width: 100px;">Total Days</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        
        // Data
        $html .= '<tbody>';
        foreach($records as $record) {
            $html .= '<tr>';
            $html .= '<td>' . $record['task_start_date'] . '</td>';
            $html .= '<td>' . $record['task_end_date'] . '</td>';
            $html .= '<td>' . $record['task_heading'] . '</td>';
            $html .= '<td>' . $record['employee'] . '</td>';
            $html .= '<td>' . $record['project'] . '</td>';
            $html .= '<td>' . $record['task_status'] . '</td>';
            $html .= '<td style="text-align: center;">' . $record['hrs'] . '</td>';
            $html .= '<td style="text-align: center;">' . $record['min'] . '</td>';
            $html .= '<td style="text-align: center;">' . $record['total_days'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        
        // Totals
        $html .= '<tfoot>';
        $html .= '<tr style="background-color: #43A047; color: #FFFFFF; font-weight: bold; text-align: center;">';
        $html .= '<td colspan="5"></td>';
        $html .= '<td>Totals</td>';
        $html .= '<td>' . $total_hrs . '</td>';
        $html .= '<td>' . $total_min . '</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        $html .= '</tfoot>';
        
        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';

        // Save to file
        file_put_contents($filepath, $html);

        // Return response
        echo json_encode([
            'status' => 'success',
            'message' => 'Excel file generated successfully',
            'file_url' => base_url('uploads/excel/' . $filename),
            'generated_at' => date('d-m-Y H:i:s')
        ]);
        exit;
    }
    
    // Controller me ye add karo
public function test_excel_generation()
{
    // Check if directory exists
    $upload_path = FCPATH . 'uploads/excel/';
    
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
        echo "Directory created: " . $upload_path . "<br>";
    } else {
        echo "Directory exists: " . $upload_path . "<br>";
    }
    
    // Check if writable
    if (is_writable($upload_path)) {
        echo "Directory is writable<br>";
    } else {
        echo "Directory is NOT writable - Fix permissions!<br>";
        chmod($upload_path, 0777);
    }
    
    // Test file creation
    $test_file = $upload_path . 'test.txt';
    $result = file_put_contents($test_file, 'Test content');
    
    if ($result !== false) {
        echo "✅ File created successfully: " . $test_file . "<br>";
        echo "File URL: " . base_url('uploads/excel/test.txt') . "<br>";
        unlink($test_file); // Delete test file
    } else {
        echo "❌ Failed to create file<br>";
    }
    
    // Try to generate actual Excel
    $this->generate_live_excel();
}

public function generate_live_excel_by_month()
{
    try {
        $postData = array(
            'search' => array('value' => ''),
            'employee' => $this->input->post('employee') ?? '',
            'projects' => $this->input->post('projects') ?? '',
            'from_date' => $this->input->post('from_date') ?? '',
            'to_date' => $this->input->post('to_date') ?? ''
        );

        $result = $this->Task_model->export_excel_data_by_month($postData);
        $grouped_data = $result['grouped_data'];
        $month_totals = $result['month_totals'];

        // Create directory
        $upload_path = FCPATH . 'uploads/excel/';
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // Start HTML
        $html = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $html .= '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' . "\n";
        $html .= '<head>' . "\n";
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
        $html .= '<!--[if gte mso 9]><xml>' . "\n";
        $html .= '<x:ExcelWorkbook>' . "\n";
        $html .= '<x:ExcelWorksheets>' . "\n";

        // Add worksheet definitions for each month
        foreach($grouped_data as $month => $records) {
            $sheet_name = $this->get_month_name($month);
            $html .= '<x:ExcelWorksheet>' . "\n";
            $html .= '<x:Name>' . htmlspecialchars($sheet_name) . '</x:Name>' . "\n";
            $html .= '<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>' . "\n";
            $html .= '</x:ExcelWorksheet>' . "\n";
        }

        $html .= '</x:ExcelWorksheets>' . "\n";
        $html .= '</x:ExcelWorkbook>' . "\n";
        $html .= '</xml><![endif]-->' . "\n";
        $html .= '</head>' . "\n";
        $html .= '<body>' . "\n";

        // Create tab for each month
        foreach($grouped_data as $month => $records) {
            $sheet_name = $this->get_month_name($month);
            $month_total_hrs = $month_totals[$month]['hrs'];
            $month_total_min = $month_totals[$month]['min'];

            $html .= '<!-- Sheet: ' . htmlspecialchars($sheet_name) . ' -->' . "\n";
            $html .= '<h2>' . htmlspecialchars($sheet_name) . '</h2>' . "\n";
            $html .= '<table border="1" cellpadding="5" cellspacing="0">' . "\n";
            
            // Header
            $html .= '<thead>' . "\n";
            $html .= '<tr style="background-color: #43A047; color: #FFFFFF; font-weight: bold; text-align: center;">' . "\n";
            $html .= '<th style="width: 120px;">Start Date</th>';
            $html .= '<th style="width: 120px;">End Date</th>';
            $html .= '<th style="width: 300px;">Task Heading</th>';
            $html .= '<th style="width: 150px;">Employee</th>';
            $html .= '<th style="width: 150px;">Project</th>';
            $html .= '<th style="width: 120px;">Task Status</th>';
            $html .= '<th style="width: 80px;">Hrs</th>';
            $html .= '<th style="width: 80px;">Mins</th>';
            $html .= '<th style="width: 100px;">Total Days</th>' . "\n";
            $html .= '</tr>' . "\n";
            $html .= '</thead>' . "\n";
            
            // Data rows
            $html .= '<tbody>' . "\n";
            foreach($records as $record) {
                $html .= '<tr>' . "\n";
                $html .= '<td>' . $record['task_start_date'] . '</td>';
                $html .= '<td>' . $record['task_end_date'] . '</td>';
                $html .= '<td>' . htmlspecialchars($record['task_heading']) . '</td>';
                $html .= '<td>' . htmlspecialchars($record['employee']) . '</td>';
                $html .= '<td>' . htmlspecialchars($record['project']) . '</td>';
                $html .= '<td>' . htmlspecialchars($record['task_status']) . '</td>';
                $html .= '<td align="center">' . $record['hrs'] . '</td>';
                $html .= '<td align="center">' . $record['min'] . '</td>';
                $html .= '<td align="center">' . $record['total_days'] . '</td>' . "\n";
                $html .= '</tr>' . "\n";
            }
            $html .= '</tbody>' . "\n";
            
            // Totals row
            $html .= '<tfoot>' . "\n";
            $html .= '<tr style="background-color: #43A047; color: #FFFFFF; font-weight: bold; text-align: center;">' . "\n";
            $html .= '<td colspan="5"></td>';
            $html .= '<td>Totals</td>';
            $html .= '<td>' . $month_total_hrs . '</td>';
            $html .= '<td>' . $month_total_min . '</td>';
            $html .= '<td></td>' . "\n";
            $html .= '</tr>' . "\n";
            $html .= '</tfoot>' . "\n";
            
            $html .= '</table>' . "\n";
            $html .= '<br><br>' . "\n";
        }

        // Overall Summary Sheet
        $html .= '<!-- Summary Sheet -->' . "\n";
        $html .= '<h2>Summary</h2>' . "\n";
        $html .= '<table border="1" cellpadding="5">' . "\n";
        $html .= '<tr style="background-color: #43A047; color: #FFFFFF; font-weight: bold;">' . "\n";
        $html .= '<th>Month</th><th>Total Hours</th><th>Total Minutes</th><th>Records</th>' . "\n";
        $html .= '</tr>' . "\n";

        $total_hrs_all = 0;
        $total_min_all = 0;
        $total_records = 0;

        foreach($grouped_data as $month => $records) {
            $sheet_name = $this->get_month_name($month);
            $hrs = $month_totals[$month]['hrs'];
            $mins = $month_totals[$month]['min'];
            $count = count($records);

            $total_hrs_all += $hrs;
            $total_min_all += $mins;
            $total_records += $count;

            $html .= '<tr>' . "\n";
            $html .= '<td>' . htmlspecialchars($sheet_name) . '</td>';
            $html .= '<td align="center">' . $hrs . '</td>';
            $html .= '<td align="center">' . $mins . '</td>';
            $html .= '<td align="center">' . $count . '</td>' . "\n";
            $html .= '</tr>' . "\n";
        }

        // Grand Total
        $html .= '<tr style="background-color: #2E7D32; color: #FFFFFF; font-weight: bold;">' . "\n";
        $html .= '<td>GRAND TOTAL</td>';
        $html .= '<td align="center">' . $total_hrs_all . '</td>';
        $html .= '<td align="center">' . $total_min_all . '</td>';
        $html .= '<td align="center">' . $total_records . '</td>' . "\n";
        $html .= '</tr>' . "\n";
        $html .= '</table>' . "\n";

        $html .= '</body>' . "\n";
        $html .= '</html>';

        // Save file
        $filepath = $upload_path . 'task_overview.xls';
        file_put_contents($filepath, $html);
        chmod($filepath, 0644);

        // Return response
        echo json_encode([
            'status' => 'success',
            'message' => 'Excel generated with month-wise sheets',
            'file_url' => base_url('uploads/excel/task_overview.xls'),
            'sheets_count' => count($grouped_data) + 1, // +1 for summary
            'total_records' => $total_records,
            'generated_at' => date('d-m-Y H:i:s')
        ]);
        exit;

    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

// Helper function to get month name
private function get_month_name($month_key) {
    // $month_key format: 2025-01, 2025-02, etc.
    $date = new DateTime($month_key . '-01');
    return $date->format('F Y'); // "January 2025"
}


public function get_task_data_json()
{
    $postData = array(
        'search' => array('value' => ''),
        'employee' => '',
        'projects' => '',
        'from_date' => '',
        'to_date' => ''
    );

    $result = $this->Task_model->export_excel_data($postData);
    $records = $result['data'];
    $total_hrs = $result['total_hrs'];
    $total_min = $result['total_min'];

    header('Content-Type: application/json; charset=UTF-8');
    
    echo json_encode([
        'status' => 'success',
        'records' => $records,
        'total_hrs' => $total_hrs,
        'total_min' => $total_min,
        'count' => count($records),
        'generated_at' => date('Y-m-d H:i:s')
    ]);
    exit;
}

	/*******************************************************/
}
