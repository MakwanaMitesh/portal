-- Parent-child workflow on task_list (main task, issue, fix children)
USE new_emp_portal_cursor;

ALTER TABLE `task_list`
  ADD COLUMN `parent_id` int(11) DEFAULT NULL COMMENT 'main task_id when child' AFTER `task_id`,
  ADD COLUMN `workflow_kind` varchar(20) NOT NULL DEFAULT 'main' COMMENT 'main|issue|fix|test' AFTER `parent_id`,
  ADD KEY `idx_task_list_parent` (`parent_id`),
  ADD KEY `idx_task_list_workflow_kind` (`workflow_kind`);

-- Existing rows are top-level main tasks
UPDATE `task_list` SET `workflow_kind` = 'main' WHERE `parent_id` IS NULL OR `parent_id` = 0;
