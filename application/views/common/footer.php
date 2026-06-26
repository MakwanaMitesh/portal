                <footer class="footer position-absolute">
                    <div class="row g-0 justify-content-between align-items-center h-100">
                        <div class="col-12 col-sm-auto text-center">
                            <p class="mb-0 mt-2 mt-sm-0 text-body">Copyright © 2024 Sanpurple Inc All Rights Reserved</p>
                        </div>
                        <div class="col-12 col-sm-auto text-center">
                            <!--<p class="mb-0 text-body-tertiary text-opacity-85">v1.14.0</p>-->
                        </div>
                    </div>
                </footer>
            </div>
            
            <!--------------------------Create Service modal box----------------------->
            <div class="modal fade" id="add_service_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="project_name"></h5><button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs-9"></span></button>
                        </div>
                        
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <form class="row g-3 mb-6" id="project_service_form" method="post" >
                                        <input hidden id="project_id" name="project_id">
                                        
                                        <div class="col-sm-6 col-md-8">
                                            <div class="form-floating">
                                                <select class="form-select" name="service_name" id="service_name" >
                                                    <option value="">--Select--</option>
                        
                                                    <?php
                                                        if(!empty($get_proservice_list)) {
                                                            foreach ($get_proservice_list as $proservice_list) {
                                                                $this->db->select("*");
                                    		                    $this->db->from('project_service_list');
                                    		                    $this->db->where('category_name', $proservice_list->category_name);
                                    		                    $this->db->order_by('service_name', 'asc');
                                    		                    $queryp = $this->db->get();
                                    		                    $totalre = $queryp->num_rows();
                                                    ?>
                                                        <optgroup label="<?php echo $proservice_list->category_name; ?>">
                                                        <?php foreach($queryp->result() as $row) { ?>
                                                            <option value="<?php echo $row->service_name ?>"><?php echo $row->service_name ?></option>
                                                        <?php } ?>
                                                        </optgroup>
                                                    <?php }
                                                    } 
                                                    ?>
                                                </select>
                                                
                                                <label for="service_name">Services</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-floating">
                                                <select class="form-select" name="service_status" id="service_status">
                                                    <option value="Ongoing">Ongoing</option>
                                                    <option value="Completed">Completed </option>
                                                    <option value="Stuck">Stuck </option>
                                                </select>
                                                <label for="service_status">Default Status</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-4">
                                            <div class="flatpickr-input-container">
                                                <div class="form-floating">
                                                    <input class="form-control datetimepicker" name="start_date"  id="start_date" type="text" placeholder="Start date" data-options='{"disableMobile":true,"dateFormat":"Y-m-d"}' />
                                                    <label class="ps-6" for="start_date">Start date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span>
                                                </div>
                                            </div>
                                        </div>
                                                
                                        <div class="col-sm-6 col-md-4">
                                            <div class="flatpickr-input-container">
                                                <div class="form-floating">
                                                    <input class="form-control datetimepicker" name="end_date"  id="end_date" type="text" placeholder="deadline" data-options='{"disableMobile":true,"dateFormat":"Y-m-d"}' />
                                                    <label class="ps-6" for="end_date">Due Date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-floating">
                                                <input class="form-control" name="allotted_hrs" id="allotted_hrs" type="text" placeholder="Allotted Hrs" />
                                                <label for="allotted_hrs">Allotted Hrs</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-8">
                                            <select class="form-select" id="assignees" name="assignees[]" data-choices="data-choices" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}'>
                                                <option value="">Assign Members...</option>
                                                <?php
                                                    if(!empty($get_members_list)) {
                                                        foreach ($get_members_list as $emp_list2) {
                                                ?>
                                                    <option value="<?php echo $emp_list2->employee_no; ?>" ><?php echo $emp_list2->name; ?></option>
                                                <?php 
                                                    }
                                                } ?>
                                            </select>
                                        </div>
            
                                        <div class="col-12 gy-6">
                                            <div class="row g-3 justify-content-end">
                                                <div class="col-auto"><a type="button" data-bs-dismiss="modal" class="btn btn-phoenix-primary px-5">Cancel</a></div>
                                                <div class="col-auto"><button type="submit" class="btn btn-primary px-5 px-sm-15">Create Service</button></div>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="form_msg_service"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------------End Create Service modal box------------------->
            <!--------------------End add_task modal box------------------->
            
            <div class="modal fade" id="add_task_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Task</h5><button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs-9"></span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <form class="row g-3 mb-6" id="task_save_form" method="post">
                                        <div class="col-sm-12 col-md-6">
                                            <label class="mylabel" for="proj_list">Project</label>
                                            <select name="proj_name" class="form-select" id="proj_list" data-choices="data-choices" data-options='{"removeItemButton":true,"placeholder":true}'>
                                                <option value="">Select Project</option>
                                                
                                                <?php if(!empty($get_project_list)) {
                                                    foreach ($get_project_list as $proj_list) {
                                                ?>
                                                <option value="<?php echo $proj_list->project_id; ?>" <?php if($project_id == $proj_list->project_id) { echo 'selected'; } ?>><?php echo $proj_list->project_name;?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                    
                                        <div class="col-sm-12 col-md-6">
                                            <label class="mylabel" for="service_list">Services</label>
                                            <select name="service_name" class="form-select" id="service_list" style="height:48px;">
                                                <option value="">Select Service</option>
                                                <?php
                                                    if(!empty($get_service_list)) {
                                                        foreach ($get_service_list as $serv_list) {
                                                ?>
                                                            <option value="<?php echo $serv_list->service_id;?>" <?php if($service_id == $serv_list->service_id){echo 'selected';}?>><?php echo $serv_list->service_name;?></option>
                                                <?php }
                                                } 
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-12 col-md-12">
                                            <label class="mylabel" for="module_list" id="module_label">Module (Optional)</label>
                                            <select name="module_id" class="form-select" id="module_list" style="height:48px;">
                                                <option value="">Select Module</option>
                                            </select>
                                            <small class="text-body-secondary d-block mt-1" id="module_hint"></small>
                                        </div>
                    
                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-floating">
                                                <textarea class="form-control" name=task_title id="task_title" placeholder="" style="height:100px;"></textarea>
                                                <label for="task_title">Task Title</label>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-floating">
                                                <textarea class="form-control" name="task_desc" id="task_desc" placeholder="" style="height:80px;"></textarea>
                                                <label for="task_desc">Description (optional)</label>
                                            </div>
                                        </div>
                                    
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-floating">
                                                <select class="form-select" name="task_status" id="task_status">
                                                <?php // if(!empty($get_task_statues)) ?>
                                                    <option value="Pending">Pending</option>
                                                    <option value="In Progress">In Progress </option>
                                                    <option value="Ready for Testing">Ready for Testing </option>
                                                    <option value="Need Discussion">Need Discussion</option>
                                                    <option value="Completed">Completed </option>
                                                </select>
                                                <label for="task_status">Default Status</label></div>
                                            </div>
                                        
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-floating">
                                                <select class="form-select show_recurring_div" name="task_type" id="task_type">
                                                    <option value="Regular">Regular</option>
                                                    <option value="Recurring">Recurring</option>
                                                </select>
                                                <label for="task_type">Task Type</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-3" id="recurring_div" style="display: none;">
                                            <div class="form-floating">
                                                <select class="form-select" name="recurring_task" id="recurring_task">
                                                    <option value="P1D">Daily</option>
                                                    <option value="P1W">Weekly</option>
                                                    <option value="P1M">Monthly</option>
                                                    <option value="P1Y">Yearly</option>
                                                </select>
                                                <label for="recurring_task">Recurring Type</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-3">
                                            <div class="flatpickr-input-container">
                                                <div class="form-floating">
                                                    <input class="form-control datetimepicker" name="start_date" value="<?php echo date('d-m-Y');?>" id="start_date_task" type="text" placeholder="Start date" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />
                                                    <label class="ps-6" for="start_date_task">Start date</label>
                                                    <span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-3">
                                            <div class="flatpickr-input-container">
                                                <div class="form-floating">
                                                    <input class="form-control datetimepicker" name="end_date" id="end_date_task" value="<?php echo date('d-m-Y'); ?>" type="text" placeholder="deadline" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />
                                                    <label class="ps-6" for="end_date_task">Deadline</label>
                                                    <span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span>
                                                </div>
                                            </div>
                                        </div>
                    
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-floating">
                                                <input class="form-control" name="allotted_hrs" id="allotted_hrs" type="text" value="0" placeholder="Allotted Hrs" />
                                                <label for="allotted_hrs" >Allotted Hrs</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-floating">
                                                <input class="form-control" name="allotted_min" id="allotted_min" type="text" value="0" placeholder="Allotted Minute" />
                                                <label for="allotted_min">Allotted Minute</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-floating">
                                                <select class="form-select" name="task_priority" id="task_priority">
                                                    <option value="1">Urgent</option>
                                                    <option value="2">High</option>
                                                    <option value="3">Normal</option>
                                                    <option value="4">Low</option>
                                                </select>
                                                <label for="task_priority">Priority</label>
                                            </div>
                                        </div>
                    
                                        <?php
                                        if (!isset($get_testers_list)) {
                                            $ci =& get_instance();
                                            $ci->load->model('Common_model');
                                            $get_testers_list = $ci->Common_model->get_testers_list();
                                        }
                                        ?>
                                        <div class="col-sm-12 col-md-6">
                                            <label class="mylabel" for="tester_id">Assign Tester</label>
                                            <select name="tester_id" class="form-select" id="tester_id" style="height:48px;">
                                                <option value="">Select tester (Software Testing)</option>
                                                <?php if (!empty($get_testers_list)) {
                                                    foreach ($get_testers_list as $tester) { ?>
                                                <option value="<?php echo $tester->employee_no; ?>"><?php echo $tester->name; ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-12 col-md-12">
                                            <label class="mylabel">Assign Developer(s)</label>
                                            <select class="form-select" id="assignees" name="assignees[]" data-choices="data-choices" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}'>
                                                <option value="">Select developer...</option>
                                                <?php
                                                    if(!empty($get_members_list)) {
                                                        foreach ($get_members_list as $emp_list) {
                                                ?>
                                                    <option value="<?php echo $emp_list->employee_no; ?>" <?php if($empnumber == $emp_list->employee_no) { echo 'selected'; } ?>><?php echo $emp_list->name; ?></option>
                                                <?php 
                                                    }
                                                } 
                                                ?>
                                            </select>
                                        </div>
                    
                                        <div class="col-12 gy-6">
                                            <div class="row g-3 justify-content-end">
                                                <div class="col-auto">
                                                    <a type="button" data-bs-dismiss="modal" class="btn btn-subtle-danger px-5">Cancel</a>
                                                </div>
                                                
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-primary px-5 px-sm-15">Save Task</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <div id="form_msg_task"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------------End add_task modal box------------------->
        </main>
    
        <script src="<?php echo base_url(); ?>vendors/popper/popper.min.js"></script>
        <script src="<?php echo base_url(); ?>vendors/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>vendors/anchorjs/anchor.min.js"></script>
        <script src="<?php echo base_url(); ?>vendors/is/is.min.js"></script>
        <script src="<?php echo base_url(); ?>vendors/fontawesome/all.min.js"></script>
        <script src="<?php echo base_url(); ?>vendors/lodash/lodash.min.js"></script>
        <script src="<?php echo base_url(); ?>vendors/list.js/list.min.js"></script>
        <script src="<?php echo base_url(); ?>vendors/feather-icons/feather.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/phoenix.js"></script>
        <script src="<?php echo base_url(); ?>vendors/choices/choices.min.js"></script>
        <script src="<?php echo base_url(); ?>vendors/dayjs/dayjs.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/flatpickr.js"></script>
    
        <script src="<?php echo base_url(); ?>assets/js/main.js?v=<?php echo uniqid(); ?>"></script>
    
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        
        <script>
            $(document).ready(function() {
                var userDataTable = $('#projectTable').DataTable({
                    "responsive": true,
                    "orderSequence": ["desc", "asc"],
                    "orderable": true,
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'pageLength': '25',
                    'ajax': {
                    'url':base_url+'get_project_list',
                    'data': function(data) {
                                data.project_phase = $('#project_phase').val();
                                data.project_category = $('#project_category').val();
                                data.category = $('#category').val();
                                data.assignee = $('#search_employee').val();
                            }
                    },
                    'columns': [
                        { data: 'category' },
                        { data: 'project' },
                        { data: 'services' },
                        { data: 'project_phase' },
                        { data: 'client_name' },
                        { data: 'created_date' },
                        { data: 'action' }
                    ],
                });
    
                $('#project_phase,#project_category,#category,#search_employee').change(function() {
                    userDataTable.draw();
                });
            });
        </script>
    
        <script>
            $(document).ready(function() {
                var userDataTable = $('#leadTable').DataTable({
                    "responsive": true,
                    "orderSequence": ["desc", "asc"],
                    "orderable": true,
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'pageLength': '25',
                    'ajax': {
                        'url':base_url+'get_lead_list',
                        'data': function(data){
                                    data.lead_status = $('#lead_status').val();
                                    data.lead_source = $('#lead_source').val();
                                    data.lead_type = $('#lead_type').val();
                                }
                    },
                    'columns': [
                        { data: 'person_name' },
                        { data: 'contact' },
                        { data: 'lead_status' },
                        { data: 'lead_source' },
                        { data: 'lead_type' },
                        { data: 'remark' },
                        { data: 'created_at' },
                        { data: 'action' }
                    ],
                });
    
                $('#lead_status,#lead_source,#lead_type').change(function() {
                    userDataTable.draw();
                });
            });
        </script>
    
        <script>
            $(document).ready(function() {
                var userDataTable = $('#task_overviewtable').DataTable({
                    "responsive": true,
                    "orderSequence": ["desc", "asc"],
                    "orderable": true,
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'pageLength': '40',
                    'ajax': {
                        'url':base_url+'task_overview_listing',
                        'data': function(data) {
                                    data.employee = $('#employee').val();
                                    data.projects = $('#projects').val();
                                    data.from_date = $('#from_date').val();
                                    data.to_date = $('#to_date').val();
                                }
                        },
                    'columns': [
                        { data: 'task_start_date' },
                        { data: 'task_end_date' },
                        { data: 'task_heading', className: 'task-heading' },
                        { data: 'employee' },
                        //{ data: 'services' },
                        { data: 'project' },
                        { data: 'task_status' },
                        { data: 'hrs' },
                        { data: 'min' },
                        { data: 'total_days' }
                    ],
                    drawCallback:function(data) {
                        // console.log(data.json.total_time);
                        // console.log(data.json.total_min[0]['min']);
            
                        // var total_hrs = data.json.total_hrs[0]['allotted_hrs'];
                        // var total_min =  data.json.total_min[0]['allotted_min'];
                        // const workingHoursPerDay = 8;
            
                        // $('#total_hrs').html(total_hrs);
                        // $('#total_min').html(total_min);
            
            
                        // const totalWorkingDays = Math.floor(total_hrs / workingHoursPerDay);
                        // const remainingHours = total_hrs % workingHoursPerDay;
            
                        // const totalWorkingMinutes = total_min + remainingHours * 60;
            
                        // const daysInMonth = 30; // Assuming 30 days in a month
                        // const totalWorkingMonths = Math.floor(totalWorkingDays / daysInMonth);
                        // const remainingWorkingDays = totalWorkingDays % daysInMonth;
                        // console.log(`Total Working Months: ${totalWorkingMonths}`);
                        // console.log(`Remaining Working Days: ${remainingWorkingDays}`);
                        // $('#total_days').html(totalWorkingMonths+' Months '+remainingWorkingDays+' Days');
            
                        var total_hrs = data.json.total_hrs[0]['allotted_hrs'];
                        var total_min = data.json.total_min[0]['allotted_min'];
                        var converted_hours = parseInt(parseInt(total_min) / 60);
            
                        total_min = total_min % 60;
                        total_hrs = parseInt(total_hrs) + converted_hours;
            
                        const workingHoursPerDay = 9;
            
                        $('#total_hrs').html(total_hrs + " Hours");
                        $('#total_min').html(total_min + " Minutes");
            
                        var totalWorkingDays = Math.floor(total_hrs / workingHoursPerDay);
                        const remainingHours = total_hrs % workingHoursPerDay;
            
                        const totalWorkingMinutes = total_min + remainingHours * 60;
                        const leftOverHours = totalWorkingMinutes / 60;
                        const leftOverMins = totalWorkingMinutes % 60;
            
                        totalWorkingDays = ( total_hrs + leftOverHours ) / workingHoursPerDay;
            
                        const daysInMonth = 30;
                        const totalWorkingMonths = Math.floor(totalWorkingDays / daysInMonth);
                        const remainingWorkingDays = parseInt(totalWorkingDays % daysInMonth);
            
                        // console.log(`Total Working Months: ${totalWorkingMonths}`);
                        // console.log(`Remaining Working Minutes: ${totalWorkingMinutes}`);
            
                        $('#total_days').html(totalWorkingMonths+' Months '+remainingWorkingDays+' Days');
                    }
                });
    
                $('#employee,#projects,#from_date,#to_date').change(function() {
                    userDataTable.draw();
                });
            });
        </script>
    
        <script>
            $(document).ready(function() {
                var userDataTable = $('#emp_attendance_list').DataTable({
                    "responsive": true,
                    "orderSequence": ["desc", "asc"],
                    "orderable": true,
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'pageLength': '25',
                    'ajax': {
                        'url':base_url+'get_attendance_my_list',
                        'data': function(data) {
                                    data.search_year = $('#search_year').val();
                                    data.search_months = $('#search_months').val();
                                    // data.searchName = $('#searchName').val();
                        }
                    },
                    'columns': [
                        { data: 'date' },
                        { data: 'login_time' },
                        //{ data: 'services' },
                        { data: 'logout_time' },
                        { data: 'attendance_type' },
                        // { data: 'created_date' },
                        // { data: 'action' }
                    ],
                });
    
                $('#search_year,#search_months').change(function() {
                    userDataTable.draw();
                });
            });
        </script>
    
        <script>
            $(document).ready(function() {
                $('#leave_date_from').on('change', function() {
                    var fromDate = $('#leave_date_from').val();
                    var parts = fromDate.split('-');
                    var fromDateObj = new Date(parts[2], parts[1] - 1, parts[0]); // Year, month (0-indexed), day
            
                    var toDateObj = new Date(fromDateObj);
                    toDateObj.setDate(toDateObj.getDate() + 0);
            
                    var toDate = toDateObj.getDate() + '-' + (toDateObj.getMonth() + 1) + '-' + toDateObj.getFullYear();
    
                    $('#leave_date_to').val(toDate);
                });
            });
            
            /*********************************************************/
            
            $(document).ready(function() {
    
                $('#start_date_task').on('change', function() {
                    var fromDatee = $('#start_date_task').val();
                    
                    var partss = fromDatee.split('-');
                    var fromDateObjj = new Date(partss[2], partss[1] - 1, partss[0]); // Year, month (0-indexed), day
            
                    var toDateObjj = new Date(fromDateObjj);
                    toDateObjj.setDate(toDateObjj.getDate() + 1);
            
                    var toDatee = toDateObjj.getDate() + '-' + (toDateObjj.getMonth() + 1) + '-' + toDateObjj.getFullYear();
    
                    $('#end_date_task').val(toDatee);
                });
            });
        </script>
<?php include(APPPATH.'views/task_management/partials/workflow_status_time_modal.php'); ?>

<div class="modal fade" id="log_time_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log time for issue fix</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs-9"></span></button>
            </div>
            <div class="modal-body">
                <form id="log_time_form">
                    <input type="hidden" name="task_id" id="log_task_id">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Hours <span class="text-danger">*</span></label>
                            <input type="number" name="fix_hrs" id="log_fix_hrs" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Minutes <span class="text-danger">*</span></label>
                            <input type="number" name="fix_min" id="log_fix_min" class="form-control" value="0" min="0" max="59">
                        </div>
                    </div>
                    <p class="fs-10 text-danger mb-0 mt-2 d-none" id="log_time_error">Please enter time spent (at least 1 minute).</p>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" id="submit_log_time">Submit &amp; log time</button>
            </div>
        </div>
    </div>
</div>

<!-- Activity Log Modal -->
<div class="modal fade" id="activity_log_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Task Activity Timeline</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs-9"></span></button>
            </div>
            <div class="modal-body">
                <div class="timeline-vertical" id="activity_timeline">
                    <!-- Activities will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.ready-for-testing', function(e){
        e.preventDefault();
        var task_id = $(this).attr('id') || $(this).data('task-id');
        if (typeof window.openWorkflowStatusTimeModal === 'function') {
            openWorkflowStatusTimeModal({
                taskId: task_id,
                newStatus: 'Ready for Testing',
                role: 'developer'
            });
        }
    });

    $(document).on('click', '.log-time-btn', function(){
        var task_id = $(this).attr('data-task-id');
        $('#log_task_id').val(task_id);
        $('#log_fix_hrs').val(0);
        $('#log_fix_min').val(0);
        $('#log_time_error').addClass('d-none');
        $('#log_time_modal').modal('show');
    });

    $('#submit_log_time').click(function(){
        var hrs = parseInt($('#log_fix_hrs').val(), 10) || 0;
        var min = parseInt($('#log_fix_min').val(), 10) || 0;
        if ((hrs * 60) + min <= 0) {
            $('#log_time_error').removeClass('d-none');
            return;
        }
        $('#log_time_error').addClass('d-none');
        $.ajax({
            url: "<?php echo base_url(); ?>update_task_time",
            method: "POST",
            data: $('#log_time_form').serialize(),
            dataType: 'json',
            success: function(r) {
                r = typeof r === 'string' ? JSON.parse(r) : r;
                if (r.status === 'success') {
                    $('#log_time_modal').modal('hide');
                    location.reload();
                } else {
                    alert(r.msg || 'Could not log time');
                }
            }
        });
    });

    $(document).on('focus', '.change_status', function() {
        $(this).data('prev-status', $(this).val());
    });

    $(document).on('change', '.change_status', function() {
        var selectedValue = $(this).val();
        var selectBoxId = $(this).attr('id');
        var $sel = $(this);
        if (!selectedValue) {
            return;
        }
        if (selectedValue === 'Ready for Testing' && typeof window.openWorkflowStatusTimeModal === 'function') {
            openWorkflowStatusTimeModal({
                taskId: selectBoxId,
                newStatus: selectedValue,
                role: 'developer',
                onCancel: function() {
                    $sel.val($sel.data('prev-status') || '');
                }
            });
            return;
        }
        $.post("<?php echo base_url(); ?>update_task_status", {
            selectedValue: selectedValue,
            selectBoxId: selectBoxId
        }, function(r) {
            r = typeof r === 'string' ? JSON.parse(r) : r;
            if (r.status === 'success') {
                location.reload();
            } else {
                alert(r.msg || 'Could not update status');
                $sel.val($sel.data('prev-status') || '');
            }
        }, 'json');
    });

    $(document).on('focus', '.tester-change-status', function() {
        $(this).data('prev-status', $(this).val());
    });

    $(document).on('change', '.tester-change-status', function() {
        var $sel = $(this);
        var status = $sel.val();
        var task_id = $sel.data('task-id');
        if (!status) {
            return;
        }
        if ((status === 'Completed' || status === 'In Progress') && typeof window.openWorkflowStatusTimeModal === 'function') {
            openWorkflowStatusTimeModal({
                taskId: task_id,
                newStatus: status,
                role: 'tester',
                onCancel: function() {
                    $sel.val($sel.data('prev-status') || '');
                }
            });
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
                $sel.val($sel.data('prev-status') || '');
            }
        }, 'json');
    });

    $(document).on('click', '.mark-completed', function(e) {
        e.preventDefault();
        var task_id = $(this).data('id');
        if (typeof window.openWorkflowStatusTimeModal === 'function') {
            openWorkflowStatusTimeModal({
                taskId: task_id,
                newStatus: 'Completed',
                role: 'tester'
            });
        }
    });

    $(document).on('change', '.issue-status-select', function() {
        var $sel = $(this);
        var issue_id = $sel.data('issue-id');
        var status = $sel.val();
        var prev = $sel.data('prev-status') || $sel.find('option[selected]').val();
        if (!confirm('Change this issue status to "' + status + '"?')) {
            if (prev) $sel.val(prev);
            return;
        }
        $.post("<?php echo base_url(); ?>update-issue-status", {
            issue_id: issue_id,
            status: status
        }, function(r) {
            r = typeof r === 'string' ? JSON.parse(r) : r;
            if (r.status === 'success') {
                $sel.data('prev-status', status);
                location.reload();
            } else {
                alert(r.msg || 'Could not update issue status');
                if (prev) $sel.val(prev);
            }
        }, 'json').fail(function() {
            alert('Could not update issue status');
            if (prev) $sel.val(prev);
        });
    });

    $(document).on('focus', '.issue-status-select', function() {
        $(this).data('prev-status', $(this).val());
    });

    $(document).on('click', '.view-activity-log', function(){
        var task_id = $(this).attr('data-task-id');
        $.ajax({
            url: "<?php echo base_url(); ?>get_task_activity_log",
            method: "POST",
            data: {task_id: task_id},
            dataType: 'json',
            success: function(activities) {
                var html = '';
                if(activities.length > 0) {
                    activities.forEach(function(activity) {
                        html += '<div class="timeline-item pb-3 mb-3 border-bottom">';
                        html += '<div class="d-flex">';
                        html += '<div class="flex-1">';
                        html += '<h6 class="mb-1">' + activity.activity + '</h6>';
                        html += '<p class="fs-10 text-body-tertiary mb-0">' + activity.user_name + ' | ' + activity.created_on + '</p>';
                        html += '</div></div></div>';
                    });
                } else {
                    html = '<p class="text-center">No activity recorded yet.</p>';
                }
                $('#activity_timeline').html(html);
                $('#activity_log_modal').modal('show');
            }
        });
    });
</script>
<?php if (!empty($load_testing_queue_scripts)) {
    include(APPPATH.'views/task_management/partials/testing_queue_scripts.php');
} ?>
    </body>
</html>