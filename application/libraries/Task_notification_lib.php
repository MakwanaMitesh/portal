<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Email notifications for task testing workflow.
 */
class Task_notification_lib {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->library('email');
    }

    /**
     * @param string $to_email
     * @param string $subject
     * @param string $body Plain-text body
     */
    public function send($to_email, $subject, $body)
    {
        if (empty($to_email) || !filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $from = $this->CI->config->item('task_notify_from');
        if (empty($from)) {
            $from = 'noreply@sanpurple.com';
        }

        $this->CI->email->clear();
        $this->CI->email->from($from, 'Employee Portal');
        $this->CI->email->to($to_email);
        $this->CI->email->subject($subject);
        $this->CI->email->message($body);

        return @$this->CI->email->send();
    }

    public function notify_tester_task_ready($task_id)
    {
        $task = $this->get_task_context($task_id);
        if (!$task || empty($task->tester_email)) {
            return false;
        }

        $subject = 'Task ready for testing: ' . $task->task_heading;
        $body = "Hello {$task->tester_name},\n\n"
            . "A task has been marked Ready for Testing.\n\n"
            . "Project: {$task->project_name}\n"
            . "Service: {$task->service_name}\n"
            . "Task: {$task->task_heading}\n"
            . "Developer: {$task->developer_name}\n\n"
            . "Open Testing Tasks: " . base_url('testing-tasks') . "\n";

        return $this->send($task->tester_email, $subject, $body);
    }

    public function notify_developer_issue_assigned($task_id, $issue_title, $developer_no)
    {
        $task = $this->get_task_context($task_id);
        $dev = $this->get_employee($developer_no);
        if (!$task || !$dev || empty($dev->email)) {
            return false;
        }

        $subject = 'Issue assigned for fix: ' . $issue_title;
        $body = "Hello {$dev->name},\n\n"
            . "A tester reported an issue on your task.\n\n"
            . "Task: {$task->task_heading}\n"
            . "Issue: {$issue_title}\n"
            . "Project: {$task->project_name}\n\n"
            . "View task: " . base_url('edit-task/' . $task_id) . "\n";

        return $this->send($dev->email, $subject, $body);
    }

    public function notify_status_update($task_id, $old_status, $new_status, $recipient_employee_no = null)
    {
        $task = $this->get_task_context($task_id);
        if (!$task) {
            return false;
        }

        $emp = $recipient_employee_no
            ? $this->get_employee($recipient_employee_no)
            : null;

        if (!$emp) {
            if ($new_status === 'Ready for Testing' && !empty($task->tester_email)) {
                return $this->notify_tester_task_ready($task_id);
            }
            return false;
        }

        if (empty($emp->email)) {
            return false;
        }

        $subject = "Task status: {$old_status} → {$new_status}";
        $body = "Hello {$emp->name},\n\n"
            . "Task \"{$task->task_heading}\" status changed from {$old_status} to {$new_status}.\n\n"
            . "Project: {$task->project_name}\n";

        return $this->send($emp->email, $subject, $body);
    }

    protected function get_task_context($task_id)
    {
        $this->CI->db->select('tl.*, pl.project_name, ps.service_name,
            dev.name as developer_name, dev.email as developer_email,
            tester.name as tester_name, tester.email as tester_email');
        $this->CI->db->from('task_list tl');
        $this->CI->db->join('project_list pl', 'tl.project_id = pl.project_id', 'left');
        $this->CI->db->join('project_services ps', 'tl.service_id = ps.service_id', 'left');
        $this->CI->db->join('employees dev', 'tl.assignee = dev.employee_no', 'left');
        $this->CI->db->join('employees tester', 'tl.tester_id = tester.employee_no', 'left');
        $this->CI->db->where('tl.task_id', $task_id);
        return $this->CI->db->get()->row();
    }

    protected function get_employee($employee_no)
    {
        return $this->CI->db->get_where('employees', ['employee_no' => $employee_no])->row();
    }
}
