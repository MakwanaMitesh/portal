<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'views/common/header.php');
$default_dev = $task->assignee;
?>

<style>
.issue-dropzone {
    border: 2px dashed var(--phoenix-border-color, #cbd0dd);
    border-radius: 0.5rem;
    min-height: 132px;
    padding: 0.75rem;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
    background: var(--phoenix-emphasis-bg, #f5f7fa);
}
.issue-dropzone.is-dragover {
    border-color: var(--phoenix-primary, #3874ff);
    background: rgba(56, 116, 255, 0.06);
}
.issue-dropzone .dropzone-hint { pointer-events: none; }
.issue-images-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}
.issue-preview-item {
    position: relative;
    width: 64px;
    height: 64px;
}
.issue-preview-item img {
    width: 64px;
    height: 64px;
    object-fit: cover;
    border-radius: 0.25rem;
    border: 1px solid var(--phoenix-border-color, #dee2e6);
}
.issue-preview-item .remove-preview {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 20px;
    height: 20px;
    padding: 0;
    line-height: 1;
    border-radius: 50%;
    font-size: 10px;
}
</style>

<div class="mb-4">
    <br>
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis pt-6 border-top border-bottom pb-6">
        <div class="row align-items-center mb-4">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2 fs-10">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('my-task/' . rawurlencode('Ready for Testing')); ?>">Ready for Testing</a></li>
                        <li class="breadcrumb-item active">Report issues</li>
                    </ol>
                </nav>
                <h4 class="mb-1">Report issues</h4>
                <p class="text-body-secondary fs-9 mb-0">
                    <strong><?php echo htmlspecialchars($task->task_heading); ?></strong>
                    — <?php echo htmlspecialchars($task->project_name); ?>
                    <?php if (!empty($task->service_name)) { ?> / <?php echo htmlspecialchars($task->service_name); ?><?php } ?>
                    <?php if (!empty($task->module_name)) { ?> / <?php echo htmlspecialchars($task->module_name); ?><?php } ?>
                </p>
                <p class="fs-10 text-body-tertiary mb-0">
                    Developer: <?php echo htmlspecialchars($task->developer_name ?? '—'); ?>
                    &nbsp;|&nbsp; Status: <span class="badge badge-phoenix badge-phoenix-warning"><?php echo htmlspecialchars($task->task_status); ?></span>
                </p>
            </div>
        </div>

        <?php if (!empty($flash_success)) { ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($flash_success); ?></div>
        <?php } ?>
        <?php if (!empty($flash_error)) { ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($flash_error); ?></div>
        <?php } ?>

        <?php $this->load->view('task_management/partials/task_time_summary', ['time_summary' => $time_summary ?? null]); ?>

        <?php if (!empty($existing_issues)) { ?>
        <div class="card mb-4 border border-translucent">
            <div class="card-header py-2">
                <h6 class="mb-0">Issues already reported (<?php echo count($existing_issues); ?>)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm fs-9 mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Assigned to</th>
                                <th>Images</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($existing_issues as $issue) { ?>
                            <tr>
                                <td>
                                    <a href="<?php echo base_url('issue-detail/' . (int) $issue->issue_id); ?>" class="fw-semibold">
                                        <?php echo htmlspecialchars($issue->issue_title); ?>
                                    </a>
                                </td>
                                <td><?php $this->load->view('task_management/partials/issue_status_select', [
                                    'issue_id' => $issue->issue_id,
                                    'current_status' => $issue->status,
                                ]); ?></td>
                                <td><?php echo htmlspecialchars($issue->priority); ?></td>
                                <td><?php echo htmlspecialchars($issue->assigned_name ?? '—'); ?></td>
                                <td><?php $this->load->view('task_management/partials/issue_images_thumbs', ['images' => $issue->images ?? [], 'issue_id' => $issue->issue_id]); ?></td>
                                <td><?php echo date('d M Y', strtotime($issue->created_on)); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>

        <form id="raise_issue_form" method="post" action="<?php echo base_url('save-issue'); ?>" enctype="multipart/form-data">
            <input type="hidden" name="task_id" value="<?php echo (int) $task->task_id; ?>">
            <input type="hidden" name="submit_action" id="submit_action_field" value="save_only">
            <input type="hidden" name="session_tester_hrs" id="session_tester_hrs" value="">
            <input type="hidden" name="session_tester_min" id="session_tester_min" value="">
            <input type="hidden" id="default_developer_no" value="<?php echo htmlspecialchars($default_dev); ?>">

            <div class="card border border-translucent">
                <div class="card-header py-3">
                    <h6 class="mb-0">New issues</h6>
                </div>
                <div class="card-body pb-2" id="issue_container">
                    <div class="issue-row border-bottom pb-4 mb-4" data-issue-index="0">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Issue title <span class="text-danger">*</span></label>
                                <input type="text" name="issue_title[]" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Priority</label>
                                <select name="priority[]" class="form-select">
                                    <option value="Low">Low</option>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="High">High</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-3">
                                <label class="form-label">Assign developer <span class="text-danger">*</span></label>
                                <select name="assigned_to[]" class="form-select issue-dev-select" required>
                                    <?php foreach ($developers as $dev) { ?>
                                    <option value="<?php echo $dev->employee_no; ?>" <?php echo ($default_dev == $dev->employee_no) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($dev->name); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div> -->
                            <div class="col-md-7">
                                <label class="form-label">Description</label>
                                <textarea name="issue_desc[]" class="form-control issue-desc-input" rows="5" placeholder="Steps to reproduce, expected vs actual behaviour…"></textarea>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Screenshots <span class="text-body-tertiary fw-normal">(optional, drag &amp; drop)</span></label>
                                <div class="issue-dropzone" role="button" tabindex="0" aria-label="Drop images or click to browse">
                                    <div class="dropzone-hint py-3">
                                        <span class="fas fa-cloud-upload-alt fs-3 text-primary d-block mb-2"></span>
                                        <span class="fs-9 d-block">Drag images here or <span class="text-primary">click to browse</span></span>
                                        <span class="fs-10 text-body-tertiary d-block mt-1">JPG, PNG, GIF, WebP — max 5 MB each, up to 10 per issue</span>
                                    </div>
                                </div>
                                <input type="file" name="issue_images[0][]" class="d-none issue-files-input" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
                                <div class="issue-images-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0 border-top border-translucent pt-4">
                    <button type="button" class="btn  btn-outline-success btn-sm" id="add_more_issues">
                        <span class="fas fa-plus me-1"></span>Add another issue
                    </button>
                </div>
                <div class="card-footer bg-body-emphasis">
                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                        <a href="<?php echo base_url('my-task/' . rawurlencode('Ready for Testing')); ?>" class="btn btn-phoenix-secondary">Cancel</a>
                        <button type="button" id="btn_save_issues_only" class="btn btn-primary">Submit</button>
                        <button type="button" id="btn_assign_to_developer" class="btn btn-danger">Submit issues &amp; return to developer</button>
                    </div>
                    <p class="fs-10 text-body-secondary mb-0 mt-3">
                        <strong>Submit</strong> — saves issues only; task stays <em>Ready for Testing</em> (no testing time asked).&nbsp;
                        <strong>Submit issues &amp; return to developer</strong> — asks for <em>total testing time</em> for this session (all issues together), then assigns to developer.
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="assign_testing_time_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Testing time before assign to developer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fs-9 text-body-secondary">Enter <strong>total time</strong> you spent testing this task in this session (for all issues you are submitting together).</p>
                <p class="fs-10 mb-3" id="assign_issue_count_hint"></p>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Hours <span class="text-danger">*</span></label>
                        <input type="number" id="modal_tester_hrs" class="form-control" value="0" min="0" max="99">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Minutes <span class="text-danger">*</span></label>
                        <input type="number" id="modal_tester_min" class="form-control" value="0" min="0" max="59">
                    </div>
                </div>
                <p class="fs-10 text-danger mb-0 mt-2 d-none" id="assign_time_error">Please enter testing time (hours and/or minutes).</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-phoenix-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm_assign_to_developer">Assign to developer</button>
            </div>
        </div>
    </div>
</div>

<?php include(APPPATH.'views/common/footer.php'); ?>

<script>
(function() {
    var MAX_FILES = 10;
    var MAX_BYTES = 5 * 1024 * 1024;
    var ALLOWED = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    function reindexIssueRows() {
        $('#issue_container .issue-row').each(function(i) {
            $(this).attr('data-issue-index', i);
            $(this).find('.issue-files-input').attr('name', 'issue_images[' + i + '][]');
        });
    }

    function isValidImage(file) {
        if (ALLOWED.indexOf(file.type) === -1) return false;
        if (file.size > MAX_BYTES) return false;
        return true;
    }

    function getStoredFiles(input) {
        return $(input).data('storedFiles') || [];
    }

    function setStoredFiles(input, files) {
        $(input).data('storedFiles', files);
        var dt = new DataTransfer();
        files.forEach(function(f) { dt.items.add(f); });
        input.files = dt.files;
    }

    function appendFiles(input, fileList) {
        var files = getStoredFiles(input).slice();
        for (var j = 0; j < fileList.length; j++) {
            if (files.length >= MAX_FILES) break;
            if (isValidImage(fileList[j])) {
                files.push(fileList[j]);
            }
        }
        setStoredFiles(input, files);
    }

    function renderPreviews($row) {
        var input = $row.find('.issue-files-input')[0];
        var $box = $row.find('.issue-images-preview');
        $box.empty();
        if (!input || !input.files) return;
        for (var i = 0; i < input.files.length; i++) {
            (function(idx, file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var $item = $('<div class="issue-preview-item"></div>');
                    $item.append('<img src="' + e.target.result + '" alt="">');
                    $item.append('<button type="button" class="btn btn-danger btn-sm remove-preview" data-idx="' + idx + '" aria-label="Remove">&times;</button>');
                    $box.append($item);
                };
                reader.readAsDataURL(file);
            })(i, input.files[i]);
        }
    }

    function removeFileAtIndex(input, index) {
        var files = getStoredFiles(input);
        files.splice(index, 1);
        setStoredFiles(input, files);
    }

    function initIssueRow($row) {
        var $zone = $row.find('.issue-dropzone');
        var $input = $row.find('.issue-files-input');

        $zone.off('click.issue').on('click.issue', function(e) {
            if ($(e.target).closest('.remove-preview').length) return;
            $input.trigger('click');
        });

        $zone.off('keydown.issue').on('keydown.issue', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $input.trigger('click');
            }
        });

        $input.off('change.issue').on('change.issue', function() {
            if (this.files && this.files.length) {
                appendFiles(this, this.files);
            }
            renderPreviews($row);
        });

        $zone.off('dragenter dragover dragleave drop.issue');
        $zone.on('dragenter dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('is-dragover');
        });
        $zone.on('dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('is-dragover');
        });
        $zone.on('drop', function(e) {
            var files = e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files;
            if (!files || !files.length) return;
            var input = $row.find('.issue-files-input')[0];
            appendFiles(input, files);
            renderPreviews($row);
        });

        $row.off('click.removepreview').on('click.removepreview', '.remove-preview', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var idx = parseInt($(this).data('idx'), 10);
            var input = $row.find('.issue-files-input')[0];
            removeFileAtIndex(input, idx);
            renderPreviews($row);
        });
    }

    $('#add_more_issues').on('click', function() {
        var $first = $('#issue_container .issue-row').first();
        if (!$first.length) return;
        var $row = $first.clone();
        $row.find('input[type="text"], textarea').val('');
        $row.find('.issue-dev-select').val($('#default_developer_no').val());
        $row.find('.issue-images-preview').empty();
        $row.find('.issue-dropzone').removeClass('is-dragover');
        var $newInput = $('<input type="file" class="d-none issue-files-input" accept="image/jpeg,image/png,image/gif,image/webp" multiple>');
        $row.find('.issue-files-input').replaceWith($newInput);
        $newInput.data('storedFiles', []);
        $('#issue_container').append($row);
        reindexIssueRows();
        initIssueRow($row);
    });

    function countFilledIssues() {
        var n = 0;
        $('#issue_container input[name="issue_title[]"]').each(function() {
            if ($.trim($(this).val()) !== '') n++;
        });
        return n;
    }

    function validateIssueForm() {
        var form = document.getElementById('raise_issue_form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }
        if (countFilledIssues() === 0) {
            alert('Please add at least one issue with a title.');
            return false;
        }
        return true;
    }

    $('#btn_save_issues_only').on('click', function() {
        if (!validateIssueForm()) return;
        reindexIssueRows();
        $('#submit_action_field').val('save_only');
        $('#session_tester_hrs').val('');
        $('#session_tester_min').val('');
        $('#raise_issue_form').submit();
    });

    $('#btn_assign_to_developer').on('click', function() {
        if (!validateIssueForm()) return;
        var n = countFilledIssues();
        $('#assign_issue_count_hint').text('You are assigning ' + n + ' issue(s) to the developer.');
        $('#modal_tester_hrs').val(0);
        $('#modal_tester_min').val(0);
        $('#assign_time_error').addClass('d-none');
        var modal = new bootstrap.Modal(document.getElementById('assign_testing_time_modal'));
        modal.show();
    });

    $('#confirm_assign_to_developer').on('click', function() {
        var hrs = parseInt($('#modal_tester_hrs').val(), 10) || 0;
        var min = parseInt($('#modal_tester_min').val(), 10) || 0;
        if (hrs === 0 && min === 0) {
            $('#assign_time_error').removeClass('d-none');
            return;
        }
        reindexIssueRows();
        $('#submit_action_field').val('save_and_return');
        $('#session_tester_hrs').val(hrs);
        $('#session_tester_min').val(min);
        bootstrap.Modal.getInstance(document.getElementById('assign_testing_time_modal')).hide();
        $('#raise_issue_form').submit();
    });

    $('#raise_issue_form').on('submit', function() {
        reindexIssueRows();
    });

    $('#issue_container .issue-row').each(function() {
        initIssueRow($(this));
    });
    reindexIssueRows();
})();
</script>
