<?php defined('BASEPATH') OR exit('No direct script access allowed');
$tid = (int) ($task->task_id ?? 0);
$open = (int) ($task->open_issues_count ?? 0);
$total = (int) ($task->total_issues_count ?? $open);
$st = $task->task_status ?? '';
?>
<div class="d-flex flex-wrap gap-1 align-items-center">
    <a href="<?php echo base_url('task-issues/' . $tid); ?>" class="btn btn-phoenix-warning btn-xs" title="View all reported issues">
        <span class="fas fa-list me-1"></span>Show issues<?php if ($total > 0) { ?> (<?php echo $total; ?>)<?php } ?>
    </a>
    <?php if ($st === 'In Progress') { ?>
    <button type="button" class="btn btn-phoenix-primary btn-xs ready-for-testing" id="<?php echo $tid; ?>">Ready for Testing</button>
    <?php } ?>
    <button type="button" class="btn btn-phoenix-info btn-xs log-time-btn" data-task-id="<?php echo $tid; ?>">Log Fix Time</button>
    <a href="<?php echo base_url('edit-task/' . $tid); ?>" class="btn btn-phoenix-secondary btn-xs">Edit task</a>
</div>
