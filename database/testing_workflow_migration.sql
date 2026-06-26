-- Testing workflow schema (CodeIgniter employee portal)
-- Run once against database: new_emp_portal_cursor
-- Existing tables task_issues and task_activity_log are extended (not renamed).

-- ---------------------------------------------------------------------------
-- Modules (project → module → task)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `modules` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `type` enum('development','digital') NOT NULL DEFAULT 'development',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`module_id`),
  KEY `idx_modules_project` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- If modules already exists without type/timestamps, run these manually if needed:
-- ALTER TABLE `modules` ADD COLUMN `type` enum('development','digital') NOT NULL DEFAULT 'development' AFTER `module_name`;
-- ALTER TABLE `modules` ADD COLUMN `created_at` datetime DEFAULT CURRENT_TIMESTAMP;
-- ALTER TABLE `modules` ADD COLUMN `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

-- ---------------------------------------------------------------------------
-- task_list extensions (required for task create with module/tester)
-- Run: database/alter_task_list_testing_columns.sql
-- ---------------------------------------------------------------------------
-- ALTER TABLE `task_list` ADD COLUMN `module_id` int(11) DEFAULT NULL AFTER `service_id`;
-- ALTER TABLE `task_list` ADD COLUMN `tester_id` varchar(50) DEFAULT NULL AFTER `assignee`;
-- ALTER TABLE `task_list` ADD COLUMN `tester_hrs` int(11) NOT NULL DEFAULT 0 AFTER `allotted_min`;
-- ALTER TABLE `task_list` ADD COLUMN `tester_min` int(11) NOT NULL DEFAULT 0 AFTER `tester_hrs`;
-- ALTER TABLE `task_list` ADD COLUMN `actual_hrs` int(11) NOT NULL DEFAULT 0 AFTER `tester_min`;
-- ALTER TABLE `task_list` ADD COLUMN `actual_min` int(11) NOT NULL DEFAULT 0 AFTER `actual_hrs`;

-- ---------------------------------------------------------------------------
-- Issues (task_issues = Issues in spec; parent_task_id = task_id)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_issues` (
  `issue_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL COMMENT 'parent_task_id',
  `issue_title` varchar(255) NOT NULL,
  `issue_desc` text,
  `issue_image` varchar(255) DEFAULT NULL COMMENT 'filename under uploads/issue_images/',
  `description` text COMMENT 'alias for issue_desc',
  `status` varchar(50) NOT NULL DEFAULT 'Open',
  `priority` varchar(20) DEFAULT 'Normal',
  `assigned_to` varchar(50) DEFAULT NULL COMMENT 'developer employee_no',
  `time_spent_hrs` int(11) NOT NULL DEFAULT 0,
  `time_spent_min` int(11) NOT NULL DEFAULT 0,
  `created_by` varchar(50) DEFAULT NULL,
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`issue_id`),
  KEY `idx_task_issues_task` (`task_id`),
  KEY `idx_task_issues_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `task_issue_images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `issue_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`),
  KEY `idx_task_issue_images_issue` (`issue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ALTER TABLE `task_issues` ADD COLUMN `assigned_to` varchar(50) DEFAULT NULL AFTER `status`;
-- ALTER TABLE `task_issues` ADD COLUMN `time_spent_hrs` int(11) NOT NULL DEFAULT 0;
-- ALTER TABLE `task_issues` ADD COLUMN `time_spent_min` int(11) NOT NULL DEFAULT 0;
-- ALTER TABLE `task_issues` ADD COLUMN `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

-- ---------------------------------------------------------------------------
-- Activity log (task_activity_log = ActivityLog in spec)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_activity_log` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL COMMENT 'parent_task_id',
  `activity` varchar(500) NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  `remarks` text,
  `status_from` varchar(50) DEFAULT NULL,
  `status_to` varchar(50) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL COMMENT 'performed_by',
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`activity_id`),
  KEY `idx_activity_task` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ALTER TABLE `task_activity_log` ADD COLUMN `action` varchar(100) DEFAULT NULL AFTER `activity`;
-- ALTER TABLE `task_activity_log` ADD COLUMN `remarks` text AFTER `action`;
