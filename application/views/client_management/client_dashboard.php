<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include('header.php');
?>
    <div class="mb-4">
    <h4>Dashboard</h4>
    <br>
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="form-floating">
                    <select class="form-select" id="client_projects" required>
                        <option value="">--Select--</option>
                        <?php
                            if(!empty($get_client_project_list)){
                            foreach ($get_client_project_list as $project)
                            { 
                            ?>
                        <option value="<?php echo $project->project_id; ?>"><?php echo $project->project_name; ?></option>
                        <?php }}?>
                    </select><label for="client_projects">Projects</label>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="form-floating">
                    <select class="form-select" id="client_employees" required>
                        <option value="">--Select--</option>
                        <?php
                            if(!empty($get_client_project_emp)){
                            foreach ($get_client_project_emp as $emp)
                            { 
                            ?>
                        <option value="<?php echo $emp->employee_no;?>"><?php echo $emp->name;?></option>
                    <?php }}?>
                    </select><label for="client_employees">Employees</label>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="flatpickr-input-container">
                    <div class="form-floating">
                        <input readonly class="form-control datepicker project_from_date" name="project_from_date" id="project_from_date" type="text" placeholder="From" data-options='{"disableMobile":true,"dateFormat":"Y-m-d"}' value="<?php echo date('Y-m-d'); ?>" />
                        <label class="ps-6" for="project_from_date">Start From</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="flatpickr-input-container">
                    <div class="form-floating">
                        <input readonly class="form-control datepicker project_from_date" name="project_to_date" id="project_to_date" type="text" placeholder="From" data-options='{"disableMobile":true,"dateFormat":"Y-m-d"}' value="<?php echo date('Y-m-d'); ?>" />
                        <label class="ps-6" for="project_to_date">Start To</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="table-responsive ms-n1 ps-1 scrollbar">
                <table class="table table-sm fs-9 mb-0" id="clientProjectsTable">
                    <thead class="text-body">
                        <tr>
                            <!-- <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="priority">PRIORITY</th>  -->
                            <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="start_date">TASK DATE</th>
                            <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="task">TASK</th>
                            <!-- <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="service">SERVICE</th> -->
                            <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="project">PROJECT</th>
                            <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="assignee">DEVELOPER NAME</th>
                            <th class="sort white-space-nowrap ps-3 align-middle ps-0" scope="col" data-sort="status">STATUS</th>
                            <!-- <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="end_date">END DATE</th> -->
                            <!-- <th class="sort align-middle ps-3 white-space-nowrap" scope="col"></th> -->
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br><br>
    </div>
</div>  

<?php include('footer.php'); ?>