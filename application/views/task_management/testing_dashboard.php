<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'views/common/header.php');
$stats = $stats ?? [];
?>

<div class="mb-4">
    <br>
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis pt-6 border-top border-bottom pb-6">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h4 class="mb-1">Testing & QA Dashboard</h4>
                <p class="text-body-secondary mb-0 fs-9">Project → module → task overview with issue counts</p>
            </div>
        </div>

        <form method="get" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Project</label>
                <select name="project_id" class="form-select">
                    <option value="">All projects</option>
                    <?php foreach ($get_project_list as $p) { ?>
                    <option value="<?php echo $p->project_id; ?>" <?php echo ($filters['project_id'] ?? '') == $p->project_id ? 'selected' : ''; ?>><?php echo $p->project_name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Module</label>
                <select name="module_id" class="form-select">
                    <option value="">All modules</option>
                    <?php foreach ($get_module_list as $m) { ?>
                    <option value="<?php echo $m->module_id; ?>" <?php echo ($filters['module_id'] ?? '') == $m->module_id ? 'selected' : ''; ?>><?php echo $m->module_name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Employee / Tester</label>
                <select name="employee" class="form-select">
                    <option value="">All</option>
                    <?php foreach ($get_members_list as $e) { ?>
                    <option value="<?php echo $e->employee_no; ?>" <?php echo ($filters['employee'] ?? '') == $e->employee_no ? 'selected' : ''; ?>><?php echo $e->name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From</label>
                <input type="text" name="from_date" class="form-control datetimepicker" value="<?php echo htmlspecialchars($filters['from_date'] ?? ''); ?>" data-options='{"dateFormat":"d-m-Y"}'>
            </div>
            <div class="col-md-2">
                <label class="form-label">To</label>
                <input type="text" name="to_date" class="form-control datetimepicker" value="<?php echo htmlspecialchars($filters['to_date'] ?? ''); ?>" data-options='{"dateFormat":"d-m-Y"}'>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Apply filters</button>
                <a href="<?php echo base_url('testing-dashboard'); ?>" class="btn btn-phoenix-secondary">Reset</a>
            </div>
        </form>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100 border border-translucent">
                    <div class="card-body">
                        <h6 class="text-body-secondary">Completed tasks</h6>
                        <h3 class="text-success mb-0"><?php echo (int) $stats['completed_tasks']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border border-translucent">
                    <div class="card-body">
                        <h6 class="text-body-secondary">Pending tasks</h6>
                        <h3 class="text-warning mb-0"><?php echo (int) $stats['pending_tasks']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border border-translucent">
                    <div class="card-body">
                        <h6 class="text-body-secondary">Ready for testing</h6>
                        <h3 class="text-primary mb-0"><?php echo (int) $stats['ready_for_testing']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border border-translucent">
                    <div class="card-body">
                        <h6 class="text-body-secondary">Total issues</h6>
                        <h3 class="text-danger mb-0"><?php echo (int) $stats['total_issues']; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mb-3">Module-wise breakdown</h5>
        <div class="table-responsive">
            <table class="table table-sm fs-9">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Module</th>
                        <th>Tasks</th>
                        <th>Completed</th>
                        <th>Pending</th>
                        <th>Issues</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($module_breakdown)) {
                        foreach ($module_breakdown as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row->project_name); ?></td>
                        <td><?php echo htmlspecialchars($row->module_name); ?></td>
                        <td><?php echo (int) $row->task_count; ?></td>
                        <td><?php echo (int) $row->completed; ?></td>
                        <td><?php echo (int) $row->pending; ?></td>
                        <td><?php echo (int) $row->issue_count; ?></td>
                    </tr>
                    <?php }
                    } else { ?>
                    <tr><td colspan="6" class="text-center text-body-secondary">No module data for selected filters.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include(APPPATH.'views/common/footer.php'); ?>
