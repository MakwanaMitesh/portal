-- Per-issue tester time + link to child task_list row
USE new_emp_portal_cursor;

ALTER TABLE `task_issues`
  ADD COLUMN `tester_time_hrs` int(11) NOT NULL DEFAULT 0 AFTER `assigned_to`,
  ADD COLUMN `tester_time_min` int(11) NOT NULL DEFAULT 0 AFTER `tester_time_hrs`,
  ADD COLUMN `child_task_id` int(11) DEFAULT NULL COMMENT 'task_list child workflow_kind=issue' AFTER `tester_time_min`;
