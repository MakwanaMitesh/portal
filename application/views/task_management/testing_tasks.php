<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'views/common/header.php');
?>

<div class="mb-4">
    <br>
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom pb-6">
        <div class="row g-3 mb-3">
            <div class="col-auto">
                <h4 class="mb-0">Ready for Testing</h4>
                <p class="fs-9 text-body-secondary mb-0">Add issues, assign developers, log your time, and mark complete when no issues remain.</p>
            </div>
        </div>

        <div class="table-responsive ms-n1 ps-1 scrollbar">
            <table class="table table-sm fs-9 mb-0">
                <thead class="text-body">
                    <tr>
                        <th>PRIORITY</th>
                        <th>TASK</th>
                        <th>MODULE</th>
                        <th>SERVICE</th>
                        <th>PROJECT</th>
                        <th>DEVELOPER</th>
                        <th>TIME</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ready_tasks)) {
                        foreach ($ready_tasks as $task) {
                            if ($task->priority == '1') { $color = 'danger'; $text = 'Urgent'; }
                            elseif ($task->priority == '2') { $color = 'warning'; $text = 'High'; }
                            elseif ($task->priority == '3') { $color = 'primary'; $text = 'Normal'; }
                            else { $color = 'secondary'; $text = 'Low'; }
                            $dev_h = (int) ($task->actual_hrs ?? 0);
                            $dev_m = (int) ($task->actual_min ?? 0);
                            $test_h = (int) ($task->tester_hrs ?? 0);
                            $test_m = (int) ($task->tester_min ?? 0);
                    ?>
                    <tr>
                        <td><span class="badge badge-phoenix fs-10 badge-phoenix-<?php echo $color;?>"><?php echo $text;?></span></td>
                        <td>
                            <a class="fw-bold" href="<?php echo base_url();?>edit-task/<?php echo $task->task_id;?>"><?php echo htmlspecialchars($task->task_heading);?></a>
                            <?php if ($task->open_issues_count > 0) { ?>
                            <a href="<?php echo base_url('task-issues/' . (int) $task->task_id); ?>" class="badge badge-phoenix badge-phoenix-danger text-decoration-none">Open: <?php echo (int) $task->open_issues_count; ?></a>
                            <?php } ?>
                            <a href="javascript:void(0)" class="ms-2 view-activity-log" data-task-id="<?php echo $task->task_id;?>" title="Activity log"><span class="fas fa-history"></span></a>
                        </td>
                        <td><?php echo $task->module_name ? htmlspecialchars($task->module_name) : '—'; ?></td>
                        <td><span class="badge badge-phoenix badge-phoenix-secondary"><?php echo htmlspecialchars($task->service_name);?></span></td>
                        <td><span class="badge badge-phoenix badge-phoenix-secondary"><?php echo htmlspecialchars($task->project_name);?></span></td>
                        <td><?php echo htmlspecialchars($task->developer_name); ?></td>
                        <td class="fs-10">Dev <?php echo $dev_h; ?>h <?php echo $dev_m; ?>m<br>QA <?php echo $test_h; ?>h <?php echo $test_m; ?>m</td>
                        <td>
                            <button class="btn btn-phoenix-danger btn-xs raise-issue-btn" data-id="<?php echo $task->task_id;?>" data-dev="<?php echo $task->assignee;?>" data-title="<?php echo htmlspecialchars($task->task_heading);?>">Add issues</button>
                            <button class="btn btn-phoenix-primary btn-xs finalize-testing-btn" data-id="<?php echo $task->task_id;?>">Submit QA</button>
                            <button class="btn btn-phoenix-success btn-xs mark-completed" data-id="<?php echo $task->task_id;?>">Complete</button>
                        </td>
                    </tr>
                    <?php }
                    } else { ?>
                    <tr><td colspan="8" class="text-center py-4">No tasks ready for testing.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Raise Issue Modal -->
<div class="modal fade" id="raise_issue_modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issue_modal_title">Report issues</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal"><span class="fas fa-times fs-9"></span></button>
            </div>
            <div class="modal-body">
                <form id="raise_issue_form">
                    <input type="hidden" name="task_id" id="issue_task_id">
                    <input type="hidden" id="default_developer_no">
                    <div id="issue_container">
                        <div class="issue-row border-bottom pb-3 mb-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Issue title</label>
                                    <input type="text" name="issue_title[]" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Priority</label>
                                    <select name="priority[]" class="form-select">
                                        <option value="Low">Low</option>
                                        <option value="Normal" selected>Normal</option>
                                        <option value="High">High</option>
                                        <option value="Urgent">Urgent</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Assign developer</label>
                                    <select name="assigned_to[]" class="form-select issue-dev-select">
                                        <?php foreach ($developers as $dev) { ?>
                                        <option value="<?php echo $dev->employee_no; ?>"><?php echo $dev->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="issue_desc[]" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-link p-0" id="add_more_issues"><span class="fas fa-plus me-2"></span>Add another issue</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" id="submit_issues">Submit issues & return to developer</button>
            </div>
        </div>
    </div>
</div>

<!-- Finalize testing / log QA time -->
<div class="modal fade" id="finalize_testing_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log testing time</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal"><span class="fas fa-times fs-9"></span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="finalize_task_id">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Tester hours <span class="text-danger">*</span></label>
                        <input type="number" id="tester_hrs" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Tester minutes <span class="text-danger">*</span></label>
                        <input type="number" id="tester_min" class="form-control" value="0" min="0" max="59">
                    </div>
                    <div class="col-12">
                        <p class="fs-10 text-danger mb-0 d-none" id="finalize_time_error">Please enter testing time (at least 1 minute).</p>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="mark_complete_check">
                            <label class="form-check-label" for="mark_complete_check">Mark task completed (no open issues)</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="submit_finalize_testing">Save</button>
            </div>
        </div>
    </div>
</div>

<?php include(APPPATH.'views/common/footer.php');?>

<script>
$(document).ready(function() {
    // .mark-completed uses workflow time modal from common/footer.php

    $('.raise-issue-btn').click(function() {
        var task_id = $(this).data('id');
        var dev = $(this).data('dev');
        $('#issue_task_id').val(task_id);
        $('#default_developer_no').val(dev);
        $('#issue_modal_title').text('Issues: ' + $(this).data('title'));
        var row = $('.issue-row').first().clone();
        row.find('input, textarea').val('');
        row.find('.issue-dev-select').val(dev);
        $('#issue_container').html(row);
        $('#raise_issue_modal').modal('show');
    });

    $('#add_more_issues').click(function() {
        var row = $('.issue-row').first().clone();
        row.find('input, textarea').val('');
        row.find('.issue-dev-select').val($('#default_developer_no').val());
        $('#issue_container').append(row);
    });

    $('#submit_issues').click(function() {
        $.post("<?php echo base_url(); ?>save-issue", $('#raise_issue_form').serialize(), function(r) {
            if (r.status === 'success') location.reload();
            else alert(r.msg);
        }, 'json');
    });

    $('.finalize-testing-btn').click(function() {
        $('#finalize_task_id').val($(this).data('id'));
        $('#finalize_testing_modal').modal('show');
    });

    $('#submit_finalize_testing').click(function() {
        var hrs = parseInt($('#tester_hrs').val(), 10) || 0;
        var min = parseInt($('#tester_min').val(), 10) || 0;
        if ((hrs * 60) + min <= 0) {
            $('#finalize_time_error').removeClass('d-none');
            return;
        }
        $('#finalize_time_error').addClass('d-none');
        $.post("<?php echo base_url(); ?>finalize-testing", {
            task_id: $('#finalize_task_id').val(),
            tester_hrs: hrs,
            tester_min: min,
            mark_complete: $('#mark_complete_check').is(':checked') ? '1' : '0'
        }, function(r) {
            r = typeof r === 'string' ? JSON.parse(r) : r;
            if (r.status === 'success') location.reload();
            else alert(r.msg || 'Could not save');
        }, 'json');
    });
});
</script>
