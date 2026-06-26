<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'views/common/header.php');
?>

<div class="mb-4">
    <br>
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis pt-6 border-top border-bottom pb-6">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h4 class="mb-1">Manage Modules</h4>
                <p class="text-body-secondary fs-9 mb-0">
                    Create modules per project (e.g. Login, Dashboard). Developers pick a module when creating tasks.
                    <br>Access: <strong>Team Leader</strong> department or <strong>Admin</strong>.
                </p>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-primary" id="btn_add_module">
                    <span class="fas fa-plus me-2"></span>Add module
                </button>
            </div>
        </div>

        <form method="get" class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Filter by project</label>
                <select name="project_id" class="form-select" id="filter_project" onchange="this.form.submit()">
                    <option value="">All projects</option>
                    <?php foreach ($get_project_list as $p) { ?>
                    <option value="<?php echo $p->project_id; ?>" <?php echo ($selected_project_id ?? '') == $p->project_id ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($p->project_name); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm fs-9">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Module name</th>
                        <th>Type</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($modules)) {
                        foreach ($modules as $m) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($m->project_name); ?></td>
                        <td><?php echo htmlspecialchars($m->module_name); ?></td>
                        <td>
                            <span class="badge badge-phoenix badge-phoenix-<?php echo $m->type === 'digital' ? 'info' : 'primary'; ?>">
                                <?php echo ucfirst($m->type ?? 'development'); ?>
                            </span>
                        </td>
                        <td><?php echo !empty($m->created_at) ? date('d M Y', strtotime($m->created_at)) : '—'; ?></td>
                        <td class="text-end">
                            <button type="button" class="btn btn-phoenix-primary btn-xs btn-edit-module"
                                data-id="<?php echo $m->module_id; ?>"
                                data-project="<?php echo $m->project_id; ?>"
                                data-name="<?php echo htmlspecialchars($m->module_name, ENT_QUOTES); ?>"
                                data-type="<?php echo $m->type ?? 'development'; ?>">Edit</button>
                            <button type="button" class="btn btn-phoenix-danger btn-xs btn-delete-module"
                                data-id="<?php echo $m->module_id; ?>"
                                data-name="<?php echo htmlspecialchars($m->module_name, ENT_QUOTES); ?>">Delete</button>
                        </td>
                    </tr>
                    <?php }
                    } else { ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-body-secondary">
                            No modules yet. Click <strong>Add module</strong> to create one.
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add / Edit module modal -->
<div class="modal fade" id="module_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="module_modal_title">Add module</h5>
                <button type="button" class="btn p-1" data-bs-dismiss="modal"><span class="fas fa-times fs-9"></span></button>
            </div>
            <div class="modal-body">
                <form id="module_form">
                    <input type="hidden" name="module_id" id="module_id">
                    <div class="mb-3">
                        <label class="form-label">Project <span class="text-danger">*</span></label>
                        <select name="project_id" id="module_project_id" class="form-select" required>
                            <option value="">Select project</option>
                            <?php foreach ($get_project_list as $p) { ?>
                            <option value="<?php echo $p->project_id; ?>" <?php echo ($selected_project_id ?? '') == $p->project_id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p->project_name); ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Module name <span class="text-danger">*</span></label>
                        <input type="text" name="module_name" id="module_name" class="form-control" placeholder="e.g. User Authentication" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" id="module_type" class="form-select">
                            <option value="development">Development</option>
                            <option value="digital">Digital marketing</option>
                        </select>
                        <small class="text-body-secondary">Development modules are required when creating dev tasks.</small>
                    </div>
                </form>
                <div id="module_form_msg" class="mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-subtle-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_save_module">Save module</button>
            </div>
        </div>
    </div>
</div>

<?php include(APPPATH.'views/common/footer.php'); ?>

<script>
$(function() {
    function openModal(edit) {
        $('#module_form')[0].reset();
        $('#module_id').val('');
        $('#module_modal_title').text(edit ? 'Edit module' : 'Add module');
        if (!edit && $('#filter_project').val()) {
            $('#module_project_id').val($('#filter_project').val());
        }
        $('#module_form_msg').html('');
        $('#module_modal').modal('show');
    }

    $('#btn_add_module').click(function() { openModal(false); });

    $('.btn-edit-module').click(function() {
        $('#module_id').val($(this).data('id'));
        $('#module_project_id').val($(this).data('project'));
        $('#module_name').val($(this).data('name'));
        $('#module_type').val($(this).data('type'));
        openModal(true);
    });

    $('#btn_save_module').click(function() {
        $.post('<?php echo base_url(); ?>save-module', $('#module_form').serialize(), function(r) {
            if (r.status === 'success') {
                var pid = $('#module_project_id').val();
                window.location = '<?php echo base_url(); ?>manage-modules' + (pid ? '?project_id=' + pid : '');
            } else {
                $('#module_form_msg').html('<div class="alert alert-danger py-2">' + r.msg + '</div>');
            }
        }, 'json');
    });

    $('.btn-delete-module').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        if (!confirm('Delete module "' + name + '"?')) return;
        $.post('<?php echo base_url(); ?>delete-module', { module_id: id }, function(r) {
            alert(r.msg);
            if (r.status === 'success') location.reload();
        }, 'json');
    });
});
</script>
