-- Optional screenshot/attachment per issue (tester upload on report-issues page)
ALTER TABLE `task_issues`
  ADD COLUMN `issue_image` varchar(255) DEFAULT NULL COMMENT 'filename under uploads/issue_images/' AFTER `issue_desc`;
