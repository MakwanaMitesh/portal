<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /** Employees with department = Software Testing */
    public function get_testers()
    {
        $this->db->select('employee_no, name, email, department');
        $this->db->from('employees');
        $this->db->where('department', 'Software Testing');
        $this->db->where('status !=', '0');
        $this->db->order_by('name', 'asc');
        return $this->db->get()->result();
    }

    public function is_tester($user)
    {
        return $user && (
            strtolower(trim($user->department)) === 'software testing'
            || strtolower($user->admin_section ?? '') === 'yes'
        );
    }

    public function can_add_issues($user)
    {
        return $user && strtolower(trim($user->department)) === 'software testing';
    }

    /** Sidebar QA queues: list label => status(es) in task_list */
    public function get_tester_qa_queues()
    {
        return [
            'Ready for Testing' => ['Ready for Testing'],
            'Pending' => ['Pending', 'In Progress'],
            'Need Discussion' => ['Need Discussion'],
            'Completed' => ['Completed'],
        ];
    }

    public function is_tester_qa_list($list)
    {
        return array_key_exists($list, $this->get_tester_qa_queues());
    }

    public function get_tester_allowed_statuses()
    {
        return ['Ready for Testing', 'Pending', 'In Progress', 'Need Discussion', 'Completed'];
    }

    /**
     * Module required: Development projects/services; optional for Digital-only.
     */
    public function is_module_required($project_id, $service_id = null)
    {
        $project = $this->db->get_where('project_list', ['project_id' => $project_id])->row();
        if (!$project) {
            return true;
        }

        $categories = array_map('trim', explode(',', strtolower($project->project_category ?? '')));

        if ($service_id) {
            $cat = $this->get_service_category_name($service_id);
            if ($cat) {
                $cat_lower = strtolower($cat);
                if (strpos($cat_lower, 'digital') !== false && strpos($cat_lower, 'develop') === false) {
                    return false;
                }
                if (strpos($cat_lower, 'develop') !== false) {
                    return true;
                }
            }
        }

        $has_dev = false;
        $has_digital_only = true;
        foreach ($categories as $c) {
            if ($c === '') {
                continue;
            }
            if (strpos($c, 'develop') !== false) {
                $has_dev = true;
                $has_digital_only = false;
            }
            if (strpos($c, 'digital') === false) {
                $has_digital_only = false;
            }
        }

        if ($has_dev) {
            return true;
        }
        if ($has_digital_only && count(array_filter($categories)) > 0) {
            return false;
        }

        return true;
    }

    public function get_service_category_name($service_id)
    {
        $this->db->select('psl.category_name');
        $this->db->from('project_services ps');
        $this->db->join('project_service_list psl', 'ps.service_name = psl.service_name', 'left');
        $this->db->where('ps.service_id', $service_id);
        $row = $this->db->get()->row();
        return $row ? $row->category_name : null;
    }

    public function log_activity($task_id, $action, $remarks, $performed_by, $status_from = null, $status_to = null)
    {
        $activity = $action;
        if ($remarks) {
            $activity .= ': ' . $remarks;
        }

        $data = [
            'task_id' => $task_id,
            'activity' => $activity,
            'action' => $action,
            'remarks' => $remarks,
            'status_from' => $status_from,
            'status_to' => $status_to,
            'created_by' => $performed_by,
            'created_on' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('task_activity_log', $data);
        return $this->db->insert_id();
    }

    /** Exclude child rows (issues/fixes) from main task lists */
    public function scope_main_tasks_only($alias = 'task_list')
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

    public function recalculate_task_total_time($task_id)
    {
        $task = $this->db->get_where('task_list', ['task_id' => $task_id])->row();
        if (!$task) {
            return null;
        }

        $dev_min = ((int) $task->actual_hrs * 60) + (int) $task->actual_min;
        if ($dev_min === 0) {
            $dev_min = ((int) ($task->allotted_hrs ?? 0) * 60) + (int) ($task->allotted_min ?? 0);
        }
        $tester_min = ((int) ($task->tester_hrs ?? 0) * 60) + (int) ($task->tester_min ?? 0);

        $this->db->select('COALESCE(SUM(time_spent_hrs * 60 + time_spent_min), 0) as fix_min', false);
        $this->db->where('task_id', $task_id);
        $issue_row = $this->db->get('task_issues')->row();
        $fix_min = $issue_row ? (int) $issue_row->fix_min : 0;

        return [
            'developer_minutes' => $dev_min,
            'developer_hrs' => floor($dev_min / 60),
            'developer_min' => $dev_min % 60,
            'tester_minutes' => $tester_min,
            'tester_hrs' => floor($tester_min / 60),
            'tester_min' => $tester_min % 60,
            'issue_fix_minutes' => $fix_min,
            'issue_fix_hrs' => floor($fix_min / 60),
            'issue_fix_min' => $fix_min % 60,
            'total_minutes' => $dev_min + $tester_min + $fix_min,
            'total_hrs' => floor(($dev_min + $tester_min + $fix_min) / 60),
            'total_min' => ($dev_min + $tester_min + $fix_min) % 60,
        ];
    }

    public function add_tester_time_to_parent($task_id, $add_hrs, $add_min)
    {
        $task = $this->db->get_where('task_list', ['task_id' => $task_id])->row();
        if (!$task) {
            return false;
        }
        $total = ((int) $task->tester_hrs * 60) + (int) $task->tester_min + ((int) $add_hrs * 60) + (int) $add_min;
        $this->db->where('task_id', $task_id);
        $this->db->update('task_list', [
            'tester_hrs' => floor($total / 60),
            'tester_min' => $total % 60,
        ]);
        return true;
    }

    public function add_developer_time_to_parent($task_id, $add_hrs, $add_min)
    {
        $task = $this->db->get_where('task_list', ['task_id' => $task_id])->row();
        if (!$task) {
            return false;
        }
        $total = ((int) ($task->actual_hrs ?? 0) * 60) + (int) ($task->actual_min ?? 0) + ((int) $add_hrs * 60) + (int) $add_min;
        $this->db->where('task_id', $task_id);
        $this->db->update('task_list', [
            'actual_hrs' => floor($total / 60),
            'actual_min' => $total % 60,
        ]);
        return true;
    }

    /** Status changes that require developer session time before the transition. */
    public function status_requires_developer_time($new_status, $old_status = null, $is_tester = false)
    {
        if ($new_status !== 'Ready for Testing') {
            return false;
        }
        if ($is_tester && in_array($old_status, ['Completed', 'Need Discussion'], true)) {
            return false;
        }
        return true;
    }

    /** Status changes that require tester session time before the transition. */
    public function status_requires_tester_time($new_status)
    {
        return in_array($new_status, ['Completed', 'In Progress'], true);
    }

    public function parse_workflow_minutes($hrs, $min)
    {
        $hrs = max(0, (int) $hrs);
        $min = max(0, min(59, (int) $min));
        return ($hrs * 60) + $min;
    }

    public function get_task_for_report($task_id)
    {
        $this->db->select('task_list.*, project_list.project_name, project_services.service_name,
            modules.module_name, dev.name as developer_name, dev.employee_no as developer_no');
        $this->db->from('task_list');
        $this->db->join('project_list', 'task_list.project_id = project_list.project_id', 'left');
        $this->db->join('project_services', 'task_list.service_id = project_services.service_id', 'left');
        $this->db->join('modules', 'task_list.module_id = modules.module_id', 'left');
        $this->db->join('employees dev', 'task_list.assignee = dev.employee_no', 'left');
        $this->db->where('task_list.task_id', $task_id);
        return $this->db->get()->row();
    }

    public function get_tasks_for_testing($tester_id = null, $status = 'Ready for Testing')
    {
        $this->db->select('task_list.*, project_list.project_name, project_services.service_name,
            dev.name as developer_name, tester.name as tester_name,
            modules.module_name,
            (SELECT COUNT(*) FROM task_issues ti WHERE ti.task_id = task_list.task_id AND ti.status = "Open") as open_issues_count');
        $this->db->from('task_list');
        $this->db->join('project_list', 'task_list.project_id = project_list.project_id', 'left');
        $this->db->join('project_services', 'task_list.service_id = project_services.service_id', 'left');
        $this->db->join('employees dev', 'task_list.assignee = dev.employee_no', 'left');
        $this->db->join('employees tester', 'task_list.tester_id = tester.employee_no', 'left');
        $this->db->join('modules', 'task_list.module_id = modules.module_id', 'left');
        if (is_array($status)) {
            $this->db->where_in('task_list.task_status', $status);
        } else {
            $this->db->where('task_list.task_status', $status);
        }
        $this->scope_main_tasks_only('task_list');

        if ($tester_id) {
            $this->db->where('task_list.tester_id', $tester_id);
        }

        $this->db->group_by('task_list.task_id');
        $this->db->order_by('task_list.priority', 'asc');
        return $this->db->get()->result();
    }

    public function get_dashboard_stats($filters = [])
    {
        $where = [];
        if (!empty($filters['project_id'])) {
            $this->db->where('tl.project_id', $filters['project_id']);
        }
        if (!empty($filters['module_id'])) {
            $this->db->where('tl.module_id', $filters['module_id']);
        }
        if (!empty($filters['employee'])) {
            $this->db->group_start();
            $this->db->where('tl.assignee', $filters['employee']);
            $this->db->or_where('tl.tester_id', $filters['employee']);
            $this->db->group_end();
        }
        if (!empty($filters['from_date'])) {
            $from = date('Y-m-d', strtotime($filters['from_date']));
            $this->db->where('tl.task_start_date >=', $from);
        }
        if (!empty($filters['to_date'])) {
            $to = date('Y-m-d', strtotime($filters['to_date']));
            $this->db->where('tl.task_start_date <=', $to);
        }

        $this->db->select('
            COUNT(*) as total_tasks,
            SUM(CASE WHEN tl.task_status = "Completed" THEN 1 ELSE 0 END) as completed_tasks,
            SUM(CASE WHEN tl.task_status NOT IN ("Completed") THEN 1 ELSE 0 END) as pending_tasks,
            SUM(CASE WHEN tl.task_status = "Ready for Testing" THEN 1 ELSE 0 END) as ready_for_testing
        ', false);
        $this->db->from('task_list tl');
        $stats = $this->db->get()->row();

        $this->db->select('COUNT(DISTINCT ti.issue_id) as total_issues');
        $this->db->from('task_issues ti');
        $this->db->join('task_list tl', 'ti.task_id = tl.task_id');
        if (!empty($filters['project_id'])) {
            $this->db->where('tl.project_id', $filters['project_id']);
        }
        if (!empty($filters['module_id'])) {
            $this->db->where('tl.module_id', $filters['module_id']);
        }
        $issue_stats = $this->db->get()->row();

        return [
            'total_tasks' => (int) ($stats->total_tasks ?? 0),
            'completed_tasks' => (int) ($stats->completed_tasks ?? 0),
            'pending_tasks' => (int) ($stats->pending_tasks ?? 0),
            'ready_for_testing' => (int) ($stats->ready_for_testing ?? 0),
            'total_issues' => (int) ($issue_stats->total_issues ?? 0),
        ];
    }

    public function get_module_issue_breakdown($project_id = null)
    {
        $this->db->select('m.module_id, m.module_name, pl.project_name,
            COUNT(DISTINCT tl.task_id) as task_count,
            SUM(CASE WHEN tl.task_status = "Completed" THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN tl.task_status != "Completed" THEN 1 ELSE 0 END) as pending,
            COUNT(ti.issue_id) as issue_count');
        $this->db->from('modules m');
        $this->db->join('task_list tl', 'tl.module_id = m.module_id', 'left');
        $this->db->join('project_list pl', 'm.project_id = pl.project_id', 'left');
        $this->db->join('task_issues ti', 'ti.task_id = tl.task_id', 'left');
        if ($project_id) {
            $this->db->where('m.project_id', $project_id);
        }
        $this->db->group_by('m.module_id, m.module_name, pl.project_name');
        $this->db->order_by('pl.project_name, m.module_name');
        return $this->db->get()->result();
    }
}
