-- Remove mistaken child rows in task_list (issues/fixes belong in task_issues only).
-- Review before running: only deletes rows with parent_id set (not main tasks).

USE new_emp_portal_cursor;

DELETE FROM task_list
WHERE parent_id IS NOT NULL
  AND parent_id > 0
  AND workflow_kind IN ('issue', 'fix');
