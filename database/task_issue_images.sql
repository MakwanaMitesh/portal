-- Multiple screenshots per issue (tester drag-and-drop on report-issues)
CREATE TABLE IF NOT EXISTS `task_issue_images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `issue_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL COMMENT 'under uploads/issue_images/',
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`),
  KEY `idx_task_issue_images_issue` (`issue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copy legacy single image into new table (safe to re-run: skips if row exists)
INSERT INTO `task_issue_images` (`issue_id`, `file_name`, `created_on`)
SELECT ti.`issue_id`, ti.`issue_image`, COALESCE(ti.`created_on`, NOW())
FROM `task_issues` ti
WHERE ti.`issue_image` IS NOT NULL AND ti.`issue_image` != ''
  AND NOT EXISTS (
    SELECT 1 FROM `task_issue_images` tii
    WHERE tii.`issue_id` = ti.`issue_id` AND tii.`file_name` = ti.`issue_image`
  );
