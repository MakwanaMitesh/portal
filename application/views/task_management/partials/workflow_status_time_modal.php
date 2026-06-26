<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal fade" id="workflow_status_time_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workflow_time_modal_title">Log time before status change</h5>
                <button type="button" class="btn p-1" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs-9"></span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="workflow_task_id" value="">
                <input type="hidden" id="workflow_new_status" value="">
                <input type="hidden" id="workflow_time_role" value="developer">
                <p class="fs-9 text-body-secondary mb-3" id="workflow_time_modal_hint">
                    Time is required before this task can move to the next step. Totals are tracked per task for reporting.
                </p>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Hours <span class="text-danger">*</span></label>
                        <input type="number" id="workflow_time_hrs" class="form-control" value="0" min="0" max="99">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Minutes <span class="text-danger">*</span></label>
                        <input type="number" id="workflow_time_min" class="form-control" value="0" min="0" max="59">
                    </div>
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="workflow_time_correction">
                    <label class="form-check-label fs-9" for="workflow_time_correction">
                        This is a status correction only (no time logged)
                    </label>
                </div>
                <p class="fs-10 text-danger mb-0 mt-2 d-none" id="workflow_time_error">Please enter time spent (at least 1 minute).</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="submit_workflow_status_time">Confirm &amp; update status</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var workflowCancelCallback = null;

    function workflowMinutes() {
        var hrs = parseInt($('#workflow_time_hrs').val(), 10) || 0;
        var min = parseInt($('#workflow_time_min').val(), 10) || 0;
        return (hrs * 60) + min;
    }

    window.openWorkflowStatusTimeModal = function(opts) {
        opts = opts || {};
        workflowCancelCallback = opts.onCancel || null;
        $('#workflow_task_id').val(opts.taskId || '');
        $('#workflow_new_status').val(opts.newStatus || '');
        $('#workflow_time_role').val(opts.role || 'developer');
        $('#workflow_time_hrs').val(0);
        $('#workflow_time_min').val(0);
        $('#workflow_time_error').addClass('d-none');
        $('#workflow_time_correction').prop('checked', false).trigger('change');

        var status = opts.newStatus || '';
        var isDev = (opts.role === 'developer');
        var titles = {
            'Ready for Testing': 'Development time before QA',
            'Completed': 'Testing time before completing task',
            'In Progress': 'Testing time before returning to developer'
        };
        var hints = {
            'Ready for Testing': 'How much time did you spend on this task in this development session? This is added to developer time on the task.',
            'Completed': 'How much testing time did you spend on this task in this session? This is added to tester time on the task.',
            'In Progress': 'How much testing time did you spend before sending issues back to the developer? This is added to tester time on the task.'
        };

        $('#workflow_time_modal_title').text(titles[status] || (isDev ? 'Developer time' : 'Testing time'));
        $('#workflow_time_modal_hint').text(hints[status] || 'Enter session time for this status change.');
        $('#workflow_status_time_modal').modal('show');
    };

    $('#workflow_status_time_modal').on('hidden.bs.modal', function() {
        if ($(this).data('cancelled') && typeof workflowCancelCallback === 'function') {
            workflowCancelCallback();
        }
        $(this).data('cancelled', false);
        workflowCancelCallback = null;
    });

    $('#workflow_status_time_modal').on('click', '[data-bs-dismiss="modal"]', function() {
        $('#workflow_status_time_modal').data('cancelled', true);
    });

    window.submitWorkflowStatusWithTime = function(extraData, done) {
        var isCorrection = $('#workflow_time_correction').is(':checked') ? 1 : 0;
        if (!isCorrection && workflowMinutes() <= 0) {
            $('#workflow_time_error').removeClass('d-none');
            return;
        }
        $('#workflow_time_error').addClass('d-none');
        var payload = $.extend({
            selectedValue: $('#workflow_new_status').val(),
            selectBoxId: $('#workflow_task_id').val(),
            workflow_hrs: $('#workflow_time_hrs').val(),
            workflow_min: $('#workflow_time_min').val(),
            is_correction: isCorrection
        }, extraData || {});

        $.post("<?php echo base_url(); ?>update_task_status", payload, function(r) {
            r = typeof r === 'string' ? JSON.parse(r) : r;
            if (typeof done === 'function') {
                done(r);
            } else if (r.status === 'success') {
                $('#workflow_status_time_modal').modal('hide');
                location.reload();
            } else {
                alert(r.msg || 'Could not update status');
            }
        }, 'json').fail(function() {
            alert('Could not update status. Please try again.');
        });
    };

    $('#workflow_time_correction').on('change', function() {
        var checked = $(this).is(':checked');
        $('#workflow_time_hrs').prop('disabled', checked);
        $('#workflow_time_min').prop('disabled', checked);
        if (checked) {
            $('#workflow_time_hrs').val(0);
            $('#workflow_time_min').val(0);
            $('#workflow_time_error').addClass('d-none');
        }
    });

    $('#submit_workflow_status_time').on('click', function() {
        submitWorkflowStatusWithTime();
    });
})();
</script>
