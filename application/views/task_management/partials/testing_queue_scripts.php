<script>
$(document).ready(function() {
    function postTesterStatus(task_id, status, confirmMsg) {
        if (confirmMsg && !confirm(confirmMsg)) {
            return;
        }
        $.post("<?php echo base_url(); ?>update_task_status", {
            selectedValue: status,
            selectBoxId: task_id
        }, function(r) {
            r = typeof r === 'string' ? JSON.parse(r) : r;
            if (r.status === 'success') {
                location.reload();
            } else {
                alert(r.msg || 'Could not update status');
            }
        }, 'json');
    }

    // mark-completed, tester-change-status: handled in common/footer.php

    $('.reopen-for-qa').click(function() {
        var task_id = $(this).data('id');
        if (!confirm('Reopen this task for testing? Status will change to Ready for Testing.')) {
            return;
        }
        postTesterStatus(task_id, 'Ready for Testing');
    });

    $('.tester-set-status').click(function() {
        var task_id = $(this).data('id');
        var status = $(this).data('status');
        var msg = status === 'Need Discussion'
            ? 'Move task to Need Discussion?'
            : 'Change task status to ' + status + '?';
        postTesterStatus(task_id, status, msg);
    });

    $('.finalize-testing-btn').click(function() {
        $('#finalize_task_id').val($(this).data('id'));
        $('#tester_hrs').val(0);
        $('#tester_min').val(0);
        $('#finalize_time_error').addClass('d-none');
        $('#mark_complete_check').prop('checked', false);
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
            if (r.status === 'success') {
                location.reload();
            } else {
                alert(r.msg || 'Could not save');
            }
        }, 'json');
    });
});
</script>
