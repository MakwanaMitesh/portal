<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');

    $empArray = explode(',', $get_serv_details->assignees);
?>
<h3>Project Name: <span class="text-warning"><?php echo $get_project_details->project_name;?></span></h3>

         
        <div class="row">
          <div class="col-xl-8">
              <hr/>
              <h5 class="mb-4"><?php if($service_id != ''){?>Update <?php }else{?>Create a<?php }?> Service</h5>
            <form class="row g-3 mb-6" id="project_service_form" method="post" >
                <input hidden id="service_id" name="service_id" value="<?php echo $get_serv_details->service_id;?>">
                <input hidden id="project_id" name="project_id" value="<?php echo $get_serv_details->project_id;?>">
              <div class="col-sm-6 col-md-8">
                  <div class="form-floating">
                      <input class="form-select" name="service_name" id="service_name" value="<?php echo $get_serv_details->service_name;?>" type="text" placeholder="Services" /><label for="service_name" >Project Service</label>
                      </div>
               
              </div>
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><select class="form-select" name="service_status" id="service_status" >
                    
                    <option value="Ongoing" <?php if($get_serv_details->service_name == 'Ongoing'){echo 'selected';}?>>Ongoing</option>
                    <option value="Completed" <?php if($get_serv_details->service_name == 'Completed'){echo 'selected';}?>>Completed </option>
                    <option value="Stuck" <?php if($get_serv_details->service_name == 'Stuck'){echo 'selected';}?>>Stuck </option>
                    
                  </select><label for="service_status">Default Status</label></div>
              </div>
              <div class="col-sm-6 col-md-4">
                <div class="flatpickr-input-container">
                  <div class="form-floating">
                      <input class="form-control datetimepicker" value="<?php if($get_serv_details->service_start_date != '0000-00-00'){echo date("Y-m-d", strtotime($get_serv_details->service_start_date));}?>" name="start_date"  id="start_date" type="text" placeholder="Start date" data-options='{"disableMobile":true,"dateFormat":"Y-m-d"}' />
                      <label class="ps-6" for="start_date">Start date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>
                </div>
              </div>
              <div class="col-sm-6 col-md-4">
                <div class="flatpickr-input-container">
                  <div class="form-floating">
                      <input class="form-control datetimepicker" value="<?php  if($get_serv_details->service_due_date != '0000-00-00'){echo date("Y-m-d", strtotime($get_serv_details->service_due_date));}?>"  name="end_date_t"  id="end_date_t" type="text" placeholder="deadline" data-options='{"disableMobile":true,"dateFormat":"Y-m-d"}' />
                      <label class="ps-6" for="end_date_t">Due Date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>
                </div>
              </div>
              
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><input class="form-control" value="<?php echo $get_serv_details->total_hrs_alloted;?>" name="allotted_hrs" id="allotted_hrs" type="text" placeholder="Allotted Hrs" /><label for="allotted_hrs" >Project Allotted Hrs</label></div>
              </div>
              
              
              <div class="col-sm-12 col-md-12">
               <select class="form-select" id="assignees" name="assignees[]" data-choices="data-choices" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}' >
                  <option value="">Assign Members...</option>
                  <?php
                  
                        if(!empty($get_members_list)){
                        foreach ($get_members_list as $emp_list)
                        { 
                        ?>
                  <option value="<?php echo $emp_list->employee_no;?>" <?php if (in_array($emp_list->employee_no, $empArray)){echo 'selected';} ?> ><?php echo $emp_list->name;?></option>
                  <?php }} ?>
                 
                </select>
              </div>
              
              
              <div class="col-12 gy-6">
                <div class="row g-3 justify-content-end">
                  <!--<div class="col-auto"><a href="<?php echo base_url();?>project-list"  class="btn btn-phoenix-primary px-5">Cancel</a></div>-->
                  <div class="col-auto"><button type="submit" class="btn btn-primary px-5 px-sm-15"><?php if($service_id != ''){?>Update <?php }else{?>Create <?php }?> Service</button></div>
                </div>
              </div>
              <div id="form_msg_service"></div>
            </form>
            
            <hr>
              <h5>Assign Members and total Working Hours</h5><br>
              <div class="row ">
              <input hidden type="text" value="<?php echo $_GET['projectid'];?>"  id="projectid" >
              <input hidden type="text" value="<?php echo $_GET['serviceid'];?>"  id="serviceid" >
              <div class="col-sm-6 col-md-4  g-3">
                <div class="form-floating"><select class="form-select" name="emp_assign" id="emp_assign" >
                    <option value="">--Select--</option>
                  <?php
                  foreach($empArray as $key) {    
                    //echo '"'.$key.'"<br/>';    
                    $this->db->select('*');
                    $this->db->from('employees')->where('employee_no',  $key);
                    $query =  $this->db->get();
                     $totals = $query->num_rows() ;
	
                        if($totals >0){
                        foreach($query->result() as $prorows)
                        {
                            $name = $prorows->name;
                        }
                        }
                        ?>
                  <option value="<?php echo $key;?>"><?php echo $name;?></option>
                  <?php } ?>
                  </select><label for="service_status">Assign Member</label></div>
              </div>
              <div class="col-sm-6 col-md-4  g-3">
                <div class="form-floating"><input class="form-control" value="" name="emp_allotted_hrs" id="emp_allotted_hrs" type="text" placeholder="Allotted Hrs" /><label for="allotted_hrs" >Allotted Hrs</label></div>
              </div>
              
              <div class="col-sm-6 col-md-4  g-3">
                <button id="add_emp_hrs" class="btn  btn-outline-success me-1 mb-1 " style="height:47px;">Add</button>
              </div>
              
              </div>
              <br>
              <div class="card">
                <div class="card-body">
               <div class="table-responsive scrollbar mx-n1 px-1">
                    <table class="table table-sm fs-9 leads-table">
                  <tr>
                      <th>Emp Name</th>
                      <th>Hrs Alloted</th>
                      <th>Action</th>
                  </tr>
                  <?php
                        if(!empty($get_emp_hrs)){
                        foreach ($get_emp_hrs as $emp_hrs_list)
                        { 
                        ?>
                  <tr>
                      
                      <td class="fs-9  py-0 ps-0"><?php echo $emp_hrs_list->name;?></td>
                      <td class="fs-9  py-0 ps-0"><?php echo $emp_hrs_list->hrs_allotted; $totalvalue += $emp_hrs_list->hrs_allotted;?></td>
                      <td class="fs-9  py-0 ps-0"><a id="<?php echo $emp_hrs_list->emp_hrs_id;?>" class="delete_emp_hrs"><span class="badge badge-phoenix fs-10 badge-phoenix-danger"><span class="badge-label">Delete</span><span class="ms-1" data-feather="x" style="height:12.8px;width:12.8px;"></span></span></a></td>
                      
                  </tr>
                  <?php }} ?>
                  
                  <tr >
                      <td class="fs-9  py-0 ps-0"><span class="text-info"><b>Total</b></span></td>
                      <td class="fs-9  py-0 ps-0"><span class="text-info"><b><?php echo $totalvalue;?></span></b>
                      <input hidden type="text" value="<?php  if($totalvalue){echo $totalvalue;}else{echo 0;} ?>" id="total_hrs">
                      </td>
                      <td></td>
                  </tr>
              </table>
               </div>
                 </div></div>       
                        
                      <br><br>  
            
            
          </div>
        </div>

<?php include(APPPATH.'views/common/footer.php'); ?>


<script>
    function calculateDueDate(totalHours, workingHours) {
    const millisecondsInHour = 3600000; // 1 hour = 3600000 milliseconds
    const currentDate = new Date();
    let remainingHours = totalHours;
    let dueDate = new Date(currentDate);

    while (remainingHours > 0) {
        // If it's a working day (Monday to Friday)
        if (dueDate.getDay() !== 0 && dueDate.getDay() !== 6) {
            // Calculate remaining hours for the day
            const remainingHoursInDay = (workingHours <= remainingHours) ? workingHours : remainingHours;
            // Calculate milliseconds for the remaining hours
            const remainingMilliseconds = remainingHoursInDay * millisecondsInHour;
            // Add milliseconds to the due date
            dueDate.setTime(dueDate.getTime() + remainingMilliseconds);
            // Subtract remaining hours
            remainingHours -= remainingHoursInDay;
        }
        // Move to the next day
        dueDate.setDate(dueDate.getDate() + 1);
    }
    
    const date = new Date(dueDate);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
    
}

// Example usage:allotted_hrs
const totalHours = $("#allotted_hrs").val();
const workingHours = 8;
const dueDate = calculateDueDate(totalHours, workingHours);

$("#end_date_t").val(dueDate);
//$("#end_date_t").val(dueDate);
console.log("Due Date:", dueDate);

</script>