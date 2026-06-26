<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
   
?>
<div class="mb-4">
<h4>Edit Task</h4>
<div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">

  
        <div class="row">
          <div class="col-xl-12">
           <form class="row g-3 mb-6" id="task_save_form" method="post" >
                <input hidden type="text" value="<?php echo $edit_task_list->task_id;?>" name="task_id">
            <div class="col-sm-12 col-md-6">
                
                <label class="mylabel" for="proj_list">Project</label><select name="proj_name" class="form-select" id="proj_list" data-choices="data-choices" data-options='{"removeItemButton":true,"placeholder":true}'>
              <option value="">Select Project</option>
                     <?php
                     //$project_id = '';
                        if(!empty($get_project_list)){
                        foreach ($get_project_list as $proj_list)
                        { 
                        ?>
                    <option value="<?php echo $proj_list->project_id;?>" <?php if($edit_task_list->project_id == $proj_list->project_id){echo 'selected';}?>><?php echo $proj_list->project_name;?></option>
                    <?php }} ?>
            </select>
            
            </div>
            
            <div class="col-sm-12 col-md-6">
                
                <label class="mylabel" for="service_list">Services</label>
                <select name="service_name" class="form-select" id="service_list" style="height:48px">
                <option value="">Select Service</option>
                     <?php
                        if(!empty($get_service_list)){
                        foreach ($get_service_list as $serv_list)
                        { 
                        ?>
                    <option value="<?php echo $serv_list->service_id;?>" <?php if($edit_task_list->service_id == $serv_list->service_id){echo 'selected';}?>><?php echo $serv_list->service_name;?></option>
                    <?php }} ?>
            </select>
            
            </div>

            <div class="col-sm-12 col-md-6">
                <label class="mylabel" for="tester_id">Assign Tester</label>
                <select name="tester_id" class="form-select" id="tester_id" style="height:48px">
                <option value="">Select tester</option>
                <?php if (!empty($get_testers)) {
                    foreach ($get_testers as $t) { ?>
                <option value="<?php echo $t->employee_no; ?>" <?php if (($edit_task_list->tester_id ?? '') == $t->employee_no) echo 'selected'; ?>><?php echo $t->name; ?></option>
                <?php }} ?>
                </select>
            </div>
            <div class="col-sm-12 col-md-12">
                <label class="mylabel" for="module_list" id="module_label">Module (Optional)</label>
                <select name="module_id" class="form-select" id="module_list" style="height:48px">
                <option value="">Select Module</option>
                     <?php
                        if(!empty($get_module_list)){
                        foreach ($get_module_list as $mod_list)
                        { 
                        ?>
                    <option value="<?php echo $mod_list->module_id;?>" <?php if($edit_task_list->module_id == $mod_list->module_id){echo 'selected';}?>><?php echo $mod_list->module_name;?></option>
                    <?php }} ?>
            </select>
            </div>
            
              <div class="col-sm-12 col-md-12">
                <div class="form-floating">
                  <textarea class="form-control" name=task_title id="task_title" placeholder="" style="height: 100px"><?php echo $edit_task_list->task_heading;?></textarea>
                  <label for="task_title">Task Title</label>
                </div>
              </div>
              <div class="col-sm-12 col-md-12">
                <div class="form-floating">
                  <textarea class="form-control" name="task_desc" id="task_desc" style="height: 80px"><?php echo htmlspecialchars($edit_task_list->task_desc ?? ''); ?></textarea>
                  <label for="task_desc">Description</label>
                </div>
              </div>
              <?php if (!empty($time_summary)) { ?>
              <div class="col-12">
                <div class="alert alert-outline-info fs-9 mb-0">
                  <strong>Time summary:</strong>
                  Developer <?php echo floor($time_summary['developer_minutes']/60); ?>h <?php echo $time_summary['developer_minutes']%60; ?>m |
                  Tester <?php echo floor($time_summary['tester_minutes']/60); ?>h <?php echo $time_summary['tester_minutes']%60; ?>m |
                  Issues <?php echo floor($time_summary['issue_minutes']/60); ?>h <?php echo $time_summary['issue_minutes']%60; ?>m |
                  <strong>Total <?php echo floor($time_summary['total_minutes']/60); ?>h <?php echo $time_summary['total_minutes']%60; ?>m</strong>
                </div>
              </div>
              <?php } ?>
              <div class="col-sm-6 col-md-3">
                <div class="form-floating"><select class="form-select" name="task_status" id="task_status" >
                    
                    <option value="Pending" <?php if($edit_task_list->task_status == 'Pending'){echo 'selected';}?>>Pending</option>
                    <option value="In Progress" <?php if($edit_task_list->task_status == 'In Progress'){echo 'selected';}?>>In Progress </option>
                    <option value="Ready for Testing" <?php if($edit_task_list->task_status == 'Ready for Testing'){echo 'selected';}?>>Ready for Testing </option>
                    <option value="Need Discussion" <?php if($edit_task_list->task_status == 'Need Discussion'){echo 'selected';}?>>Need Discussion</option>
                    <option value="Completed" <?php if($edit_task_list->task_status == 'Completed'){echo 'selected';}?>>Completed </option>
                    
                  </select><label for="task_status">Default Status</label></div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="form-floating"><select class="form-select show_recurring_div" name="task_type" id="task_type" >
                    
                    <option value="Regular" <?php if($edit_task_list->task_type == 'Regular'){echo 'selected';}?>>Regular</option>
                    <option value="Recurring" <?php if($edit_task_list->task_type == 'Recurring'){echo 'selected';}?>>Recurring</option>
                    
                  </select><label for="task_type">Task Type</label></div>
              </div>
              <div class="col-sm-6 col-md-3" id="recurring_div" style="display: <?php if($edit_task_list->task_type != 'Recurring'){echo 'none';}else{echo 'block';}?>;">
                <div class="form-floating"><select class="form-select" name="recurring_task" id="recurring_task" >
                    
                    <option value="P1D" <?php if($edit_task_list->recurring_type == 'P1D'){echo 'selected';}?>>Daily</option>
                    <option value="P1W" <?php if($edit_task_list->recurring_type == 'P1W'){echo 'selected';}?>>Weekly</option>
                    <option value="P1M" <?php if($edit_task_list->recurring_type == 'P1M'){echo 'selected';}?>>Monthly</option>
                    <option value="P1Y" <?php if($edit_task_list->recurring_type == 'P1Y'){echo 'selected';}?>>Yearly</option>
                    
                  </select><label for="recurring_task">Recurring Type</label></div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="flatpickr-input-container">
                  <div class="form-floating">
                      <input class="form-control datetimepicker" name="start_date" value="<?php echo date("d-m-Y", strtotime($edit_task_list->task_start_date));?>" id="start_date" type="text" placeholder="Start date" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />
                      <label class="ps-6" for="start_date">Start date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="flatpickr-input-container">
                  <div class="form-floating">
                      <input class="form-control datetimepicker" name="end_date"  id="end_date" value="<?php echo date("d-m-Y", strtotime($edit_task_list->task_end_date)) ;?>" type="text" placeholder="deadline" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />
                      <label class="ps-6" for="end_date">Deadline</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="form-floating"><input class="form-control" name="allotted_hrs" id="allotted_hrs" type="text" value="<?php echo $edit_task_list->allotted_hrs;?>" placeholder="Allotted Hrs" /><label for="allotted_hrs" >Allotted Hrs</label></div>
              </div>
               <div class="col-sm-6 col-md-3">
                <div class="form-floating"><input class="form-control" name="allotted_min" id="allotted_min" type="text" value="<?php echo $edit_task_list->allotted_min;?>" placeholder="Allotted Minute" /><label for="allotted_min" >Allotted Minute</label></div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="form-floating"><input class="form-control" name="actual_hrs" type="number" min="0" value="<?php echo (int) ($edit_task_list->actual_hrs ?? 0);?>" placeholder="Dev work hrs" /><label>Developer work (hrs)</label></div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="form-floating"><input class="form-control" name="actual_min" type="number" min="0" max="59" value="<?php echo (int) ($edit_task_list->actual_min ?? 0);?>" placeholder="Dev work min" /><label>Developer work (min)</label></div>
              </div>
              <div class="col-sm-6 col-md-3">
                 <div class="form-floating"><select class="form-select" name="task_priority" id="task_priority" >
                    
                    <option value="1" <?php if($edit_task_list->priority == '1'){echo 'selected';}?>>Urgent</option>
                    <option value="2" <?php if($edit_task_list->priority == '2'){echo 'selected';}?>>High</option>
                    <option value="3" <?php if($edit_task_list->priority == '3'){echo 'selected';}?>>Normal</option>
                    <option value="4" <?php if($edit_task_list->priority == '4'){echo 'selected';}?>>Low</option>
                    
                  </select><label for="task_priority">Priority</label></div>
              </div>
              <div class="col-sm-12 col-md-12">
               <select class="form-select" id="assignees" name="assignees[]" data-choices="data-choices" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}' >
                  <option value="">Assign Members...</option>
                  <?php
                        if(!empty($get_members_list)){
                        foreach ($get_members_list as $emp_list)
                        { 
                        ?>
                  <option value="<?php echo $emp_list->employee_no;?>" <?php if($edit_task_list->assignee == $emp_list->employee_no){echo 'selected';} ?>><?php echo $emp_list->name;?></option>
                  <?php }} ?>
                 
                </select>
              </div>
              
              
              <div class="col-12 gy-6">
                <div class="row g-3 justify-content-end">
                  <div class="col-auto"><a type="button" data-bs-dismiss="modal" class="btn btn-subtle-danger px-5">Cancel</a></div>
                  <div class="col-auto"><button type="submit" class="btn btn-primary px-5 px-sm-15">Save Task</button></div>
                </div>
              </div>
            </form>

            <?php $this->load->view('task_management/partials/task_time_summary', ['time_summary' => $time_summary ?? null]); ?>

            <?php if(!empty($task_issues)){ ?>
            <div class="mt-6">
                <h5 class="mb-3">Reported Issues</h5>
                <div class="table-responsive">
                    <table class="table table-sm fs-9">
                        <thead>
                            <tr>
                                <th>TITLE</th>
                                <th>DESC</th>
                                <th>PRIORITY</th>
                                <th>STATUS</th>
                                <th>REPORTER</th>
                                <th>FIX TIME</th>
                                <th>IMAGE</th>
                                <th>DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($task_issues as $issue){ ?>
                            <tr>
                                <td>
                                    <a href="<?php echo base_url('issue-detail/' . (int) $issue->issue_id); ?>" class="fw-semibold">
                                        <?php echo htmlspecialchars($issue->issue_title); ?>
                                    </a>
                                </td>
                                <td><?php echo $issue->issue_desc; ?></td>
                                <td><span class="badge badge-phoenix badge-phoenix-warning"><?php echo $issue->priority; ?></span></td>
                                <td><?php $this->load->view('task_management/partials/issue_status_select', [
                                    'issue_id' => $issue->issue_id,
                                    'current_status' => $issue->status,
                                ]); ?></td>
                                <td><?php echo $issue->reporter_name; ?></td>
                                <td><?php
                                    $fh = (int) ($issue->time_spent_hrs ?? 0);
                                    $fm = (int) ($issue->time_spent_min ?? 0);
                                    echo ($fh || $fm) ? "{$fh}h {$fm}m" : '—';
                                ?></td>
                                <td><?php $this->load->view('task_management/partials/issue_images_thumbs', ['images' => $issue->images ?? [], 'issue_id' => $issue->issue_id]); ?></td>
                                <td><?php echo date('d M, Y', strtotime($issue->created_on)); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>
            <div id="form_msg_task"></div>
            
            
            
          </div>
        </div>
</div>
</div>        

<?php include(APPPATH.'views/common/footer.php');?>
