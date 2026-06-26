<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'views/common/header.php');

$priority_class = [
    'Urgent' => 'danger',
    'High' => 'warning',
    'Normal' => 'primary',
    'Low' => 'secondary',
];
$status_class = [
    'Open' => 'info',
    'Fixed' => 'success',
    'Closed' => 'secondary',
    'Reopened' => 'warning',
];
?>

<style>
.task-issue-row {
    cursor: pointer;
}
.task-issue-row:hover {
    background: var(--phoenix-emphasis-bg, #f5f7fa);
}
.task-issue-row a, .task-issue-row select, .task-issue-row button {
    cursor: default;
}
.task-issue-row .issue-title-link {
    cursor: pointer;
}
</style>

<div class="mb-4">
    <br>
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis pt-6 border-top border-bottom pb-6">
        <?php if (!empty($flash_success)) { ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($flash_success); ?></div>
        <?php } ?>
        <?php if (!empty($flash_error)) { ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($flash_error); ?></div>
        <?php } ?>

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 fs-10">
                <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars($back_url); ?>"><?php echo !empty($can_add_issues) ? 'Ready for Testing' : 'Issues to fix'; ?></a></li>
                <li class="breadcrumb-item active">Task issues</li>
            </ol>
        </nav>

        <div class="row align-items-start mb-4 g-3">
            <div class="col-lg">
                <h4 class="mb-1">Reported issues</h4>
                <p class="text-body-secondary fs-9 mb-1">
                    <a href="<?php echo base_url('edit-task/' . (int) $task->task_id); ?>" class="fw-semibold"><?php echo htmlspecialchars($task->task_heading); ?></a>
                    — <?php echo htmlspecialchars($task->project_name ?? ''); ?>
                    <?php if (!empty($task->service_name)) { ?> / <?php echo htmlspecialchars($task->service_name); ?><?php } ?>
                    <?php if (!empty($task->module_name)) { ?> / <?php echo htmlspecialchars($task->module_name); ?><?php } ?>
                </p>
                <p class="fs-10 text-body-tertiary mb-0">
                    Status: <span class="badge badge-phoenix badge-phoenix-warning"><?php echo htmlspecialchars($task->task_status); ?></span>
                    &nbsp;|&nbsp;
                    Developer: <?php echo htmlspecialchars($task->developer_name ?? '—'); ?>
                    &nbsp;|&nbsp;
                    <span class="badge badge-phoenix badge-phoenix-danger"><?php echo (int) $open_issues_count; ?> open</span>
                    <span class="badge badge-phoenix badge-phoenix-secondary"><?php echo (int) $total_issues_count; ?> total</span>
                </p>
            </div>
            <div class="col-lg-auto d-flex flex-wrap gap-2">
                <a href="<?php echo htmlspecialchars($back_url); ?>" class="btn btn-phoenix-secondary btn-sm">
                    <span class="fas fa-arrow-left me-1"></span>Back
                </a>
                <!-- <a href="<?php echo base_url('edit-task/' . (int) $task->task_id); ?>" class="btn btn-phoenix-secondary btn-sm">Edit task</a> -->
                <?php if (!empty($can_add_issues)) { ?>
                <a href="<?php echo base_url('report-issues/' . (int) $task->task_id); ?>" class="btn btn-phoenix-primary btn-sm">
                    <span class="fas fa-plus me-1"></span>Report issues
                </a>
                <?php } elseif (!empty($is_developer)) { ?>
                <button type="button" class="btn btn-phoenix-info btn-sm log-time-btn" data-task-id="<?php echo (int) $task->task_id; ?>">Log fix time</button>
                <?php if (($task->task_status ?? '') === 'In Progress') { ?>
                <button type="button" class="btn btn-phoenix-primary btn-sm ready-for-testing" id="<?php echo (int) $task->task_id; ?>">Ready for Testing</button>
                <?php } ?>
                <?php } ?>
            </div>
        </div>

        <?php $this->load->view('task_management/partials/task_time_summary', ['time_summary' => $time_summary ?? null]); ?>

        <div class="card border border-translucent">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0">All issues for this task</h6>
                <span class="fs-10 text-body-tertiary">Click a row or title to open full issue details</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($issues)) { ?>
                <div class="text-center py-5 text-body-secondary">
                    <p class="mb-2">No issues reported yet.</p>
                    <?php if (!empty($can_add_issues)) { ?>
                    <a href="<?php echo base_url('report-issues/' . (int) $task->task_id); ?>" class="btn btn-phoenix-primary btn-sm">Report first issue</a>
                    <?php } ?>
                </div>
                <?php } else { ?>
                <div class="table-responsive">
                    <table class="table table-sm fs-9 mb-0 table-hover">
                        <thead class="text-body">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Assigned to</th>
                                <th>Reporter</th>
                                <!-- <th>Fix time</th> -->
                                <th>Images</th>
                                <th>Reported</th>
                                <th class="pe-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($issues as $issue) {
                                $detail_url = base_url('issue-detail/' . (int) $issue->issue_id);
                                $prio = $priority_class[$issue->priority] ?? 'secondary';
                                $st_cls = $status_class[$issue->status] ?? 'secondary';
                            ?>
                            <tr class="task-issue-row" data-href="<?php echo $detail_url; ?>">
                                <td class="ps-3 align-middle text-body-tertiary"><?php echo (int) $issue->issue_id; ?></td>
                                <td class="align-middle">
                                    <a href="<?php echo $detail_url; ?>" class="fw-semibold issue-title-link text-decoration-none">
                                        <?php echo htmlspecialchars($issue->issue_title); ?>
                                    </a>
                                    <?php if (!empty($issue->issue_desc)) { ?>
                                    <p class="mb-0 fs-10 text-body-tertiary text-truncate" style="max-width:320px;">
                                        <?php echo htmlspecialchars(strlen($issue->issue_desc) > 80 ? substr($issue->issue_desc, 0, 80) . '…' : $issue->issue_desc); ?>
                                    </p>
                                    <?php } ?>
                                </td>
                                <td class="align-middle" onclick="event.stopPropagation();">
                                    <?php $this->load->view('task_management/partials/issue_status_select', [
                                        'issue_id' => $issue->issue_id,
                                        'current_status' => $issue->status,
                                    ]); ?>
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-phoenix badge-phoenix-<?php echo $prio; ?>"><?php echo htmlspecialchars($issue->priority); ?></span>
                                </td>
                                <td class="align-middle"><?php echo htmlspecialchars($issue->assigned_name ?? '—'); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($issue->reporter_name ?? '—'); ?></td>
                                <!-- <td class="align-middle"><?php echo (int) ($issue->time_spent_hrs ?? 0); ?>h <?php echo (int) ($issue->time_spent_min ?? 0); ?>m</td> -->
                                <td class="align-middle" onclick="event.stopPropagation();">
                                    <?php $this->load->view('task_management/partials/issue_images_thumbs', [
                                        'images' => $issue->images ?? [],
                                        'issue_id' => $issue->issue_id,
                                    ]); ?>
                                </td>
                                <td class="align-middle"><?php echo date('d M Y', strtotime($issue->created_on)); ?></td>
                                <td class="pe-3 align-middle text-end">
                                    <a href="<?php echo $detail_url; ?>" class="btn btn-phoenix-primary btn-xs" onclick="event.stopPropagation();">
                                        View<span class="fas fa-chevron-right ms-1"></span>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.task-issue-row[data-href]').forEach(function(row) {
    row.addEventListener('click', function(e) {
        if (e.target.closest('select, a, button, input')) {
            return;
        }
        window.location = row.getAttribute('data-href');
    });
});
</script>

<?php include(APPPATH.'views/common/footer.php'); ?>
