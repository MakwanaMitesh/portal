<?php defined('BASEPATH') OR exit('No direct script access allowed');
$st = $serv_task->task_status ?? '';
$tid = (int) $serv_task->task_id;
?>
<div class="d-flex flex-wrap gap-1">
    <?php if ($st === 'Ready for Testing') { ?>
    <a href="<?php echo base_url('report-issues/' . $tid); ?>" class="btn btn-phoenix-danger btn-xs">Add issues</a>
    <button type="button" class="btn btn-phoenix-primary btn-xs finalize-testing-btn" data-id="<?php echo $tid; ?>">Log QA time</button>
    <button type="button" class="btn btn-phoenix-success btn-xs mark-completed" data-id="<?php echo $tid; ?>">Mark complete</button>
    <button type="button" class="btn btn-phoenix-warning btn-xs tester-set-status" data-id="<?php echo $tid; ?>" data-status="Need Discussion">Need discussion</button>
    <?php } elseif (in_array($st, ['Pending', 'In Progress'], true)) { ?>
    <span class="badge badge-phoenix badge-phoenix-secondary me-1">Awaiting developer</span>
    <?php if ($serv_task->open_issues_count > 0) { ?>
    <a href="<?php echo base_url('report-issues/' . $tid); ?>" class="btn btn-phoenix-danger btn-xs">Add issues</a>
    <?php } ?>
    <button type="button" class="btn btn-phoenix-warning btn-xs tester-set-status" data-id="<?php echo $tid; ?>" data-status="Need Discussion">Need discussion</button>
    <button type="button" class="btn btn-phoenix-primary btn-xs tester-set-status" data-id="<?php echo $tid; ?>" data-status="Ready for Testing">Back to QA queue</button>
    <?php } elseif ($st === 'Need Discussion') { ?>
    <button type="button" class="btn btn-phoenix-primary btn-xs reopen-for-qa" data-id="<?php echo $tid; ?>">Reopen for testing</button>
    <a href="<?php echo base_url('edit-task/' . $tid); ?>" class="btn btn-phoenix-secondary btn-xs">View task</a>
    <?php } elseif ($st === 'Completed') { ?>
    <button type="button" class="btn btn-phoenix-primary btn-xs reopen-for-qa" data-id="<?php echo $tid; ?>">Reopen task</button>
    <a href="<?php echo base_url('edit-task/' . $tid); ?>" class="btn btn-phoenix-secondary btn-xs">View task</a>
    <?php } else { ?>
    <button type="button" class="btn btn-phoenix-success btn-xs mark-completed" data-id="<?php echo $tid; ?>">Mark complete</button>
    <button type="button" class="btn btn-phoenix-primary btn-xs tester-set-status" data-id="<?php echo $tid; ?>" data-status="Ready for Testing">Ready for testing</button>
    <?php } ?>
</div>
