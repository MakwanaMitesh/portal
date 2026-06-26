-- Add testing workflow columns to task_list (run once)
USE new_emp_portal_cursor;

ALTER TABLE `task_list`
  ADD COLUMN `module_id` int(11) DEFAULT NULL AFTER `service_id`,
  ADD COLUMN `tester_id` varchar(50) DEFAULT NULL COMMENT 'employees.employee_no' AFTER `assignee`,
  ADD COLUMN `tester_hrs` int(11) NOT NULL DEFAULT 0 AFTER `allotted_min`,
  ADD COLUMN `tester_min` int(11) NOT NULL DEFAULT 0 AFTER `tester_hrs`,
  ADD COLUMN `actual_hrs` int(11) NOT NULL DEFAULT 0 COMMENT 'developer time hours' AFTER `tester_min`,
  ADD COLUMN `actual_min` int(11) NOT NULL DEFAULT 0 COMMENT 'developer time minutes' AFTER `actual_hrs`;
