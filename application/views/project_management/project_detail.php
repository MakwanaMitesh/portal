<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
    if($project_info->project_phase == 'Complete'){$color = 'primary';}else if($project_info->project_phase == 'Stuck'){$color = 'danger';}else{$color = 'success';}
   
?>
        <div class="row">
          <div class="col-12 col-xxl-8 px-0 bg-body">
            <div class="px-4 px-lg-6 pt-6 ">
              <div class="mb-5">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                  <h2 class="text-body-emphasis fw-bolder mb-2"><?php echo $project_info->project_name?></h2>
                  <?php
                    $ci =& get_instance();
                    $ci->load->model('Common_model');
                    if ($ci->Common_model->can_manage_modules($get_login_user)) {
                  ?>
                  <a href="<?php echo base_url('manage-modules?project_id=' . $project_info->project_id); ?>" class="btn btn-phoenix-primary btn-sm">
                    <span class="fas fa-layer-group me-1"></span> Manage modules
                  </a>
                  <?php } ?>
                </div><span class="badge badge-phoenix badge-phoenix-<?php echo $color?>"><?php echo $project_info->project_phase;?><span class="ms-1 uil uil-stopwatch"></span></span>
              </div>
            </div>
          </div>
        </div>
     
     
      <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xxl-4 g-3 mb-9">
          <?php
                        if(!empty($get_proj_services)){
                        foreach ($get_proj_services as $proj_services)
                        { 
                            
                        ?>
                        
          <div class="col">
            <div class="card h-100 hover-actions-trigger">
              <div class="card-body">
                <div class="d-flex align-items-center">
                    
                  <h4 class="mb-2 line-clamp-1 lh-sm flex-1 me-5"><?php echo $proj_services->service_name;?></h4>
                  <?php if($get_login_user->admin_section == 'yes'){?>
                  <div class="hover-actions top-0 end-0 mt-4 me-4"><a href="<?php echo base_url();?>edit-service?serviceid=<?php echo $proj_services->service_id;?>&projectid=<?php echo $proj_services->project_id;?>" class="btn btn-primary btn-icon flex-shrink-0" ><span class="fa-solid fa-edit"></span></a></div>
                <?php }?>
                </div>
                <span class="badge badge-phoenix fs-10 mb-4 badge-phoenix-success"><?php echo $proj_services->service_status;?></span>
               
                <!--<div class="d-flex justify-content-between text-body-tertiary fw-semibold">-->
                <!--  <p class="mb-2"> Progress</p>-->
                <!--  <p class="mb-2 text-body-emphasis">10%</p>-->
                <!--</div>-->
                <!--<div class="progress bg-success-subtle">-->
                <!--  <div class="progress-bar rounded bg-success" role="progressbar" aria-label="Success example" style="width: 10%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="10"></div>-->
                <!--</div>-->
                
                <br><h5 class="text-warning">Assigned Members</h5>
               
                <div id="tableExample" >
  <div class="table-responsive">
    <table class="table table-sm fs-9 mb-0">
      <thead>
        <tr>
          <th class="sort border-top border-translucent ps-3" data-sort="name">Name</th>
          <th class="sort border-top border-translucent" data-sort="email">Hrs Alloted</th>
          <th class="sort border-top border-translucent" data-sort="age">Hrs worked</th>
          <th class="sort border-top border-translucent" data-sort="age">Hrs Remain</th>
          <!--<th class="sort border-top border-translucent" data-sort="age">Action</th>-->
        </tr>
      </thead>
               <tbody class="list">   
                  <?php
                   //   echo $get_login_user->employee_no;
   $this->db->select('project_assign_emp_hrs.*, employees.employee_no , employees.name');
   $this->db->from('project_assign_emp_hrs');
   $this->db->join('employees', 'project_assign_emp_hrs.emp_num = employees.employee_no', 'left');
   $this->db->where('service_id', $proj_services->service_id);
   $this->db->where('project_id', $proj_services->project_id);
    
   //$this->db->where('emp_num', $key);
   $qu_pro2=  $this->db->get();
   $totals2 = $qu_pro2->num_rows() ;
   if($totals2 >0){
   foreach($qu_pro2->result() as $prorows2)
                        {
                        ?>
                  <tr>
                      
                      <td class="align-middle  ps-3 "><?php echo $prorows2->name;?></td>
                      <td class="align-middle  "><?php echo $prorows2->hrs_allotted; $totalvalue += $prorows2->hrs_allotted;?></td>
                      
                      <td class="align-middle  ">
                          <?php
        
        $query = $this->db->select_sum('allotted_hrs')->select_sum('allotted_min')->where('project_id', $proj_services->project_id)->where('service_id', $proj_services->service_id)->where('assignee', $prorows2->emp_num)->where('task_status', 'Completed')->get('task_list');
//echo $this->db->last_query();
// Check if there are any results
if ($query->num_rows() > 0) {
    // Fetch the sum
    $row = $query->row();
    $sum_hrs = $row->allotted_hrs;
    $sum_mins = $row->allotted_min;
    
    //$sum_hrs += floor($sum_mins / 60);
    $sum_mins = $sum_mins % 60;

    // Now $sum_hrs and $sum_mins contain the sum of hours and minutes respectively
    echo  $sum_hrs . "." . $sum_mins;
    
    $total_worked_minutes = ($sum_hrs * 60) + $sum_mins;

    // Subtract total worked hours from total allotted hours
    $remaining_hours = floor($prorows2->hrs_allotted - ($total_worked_minutes / 60));
    $remaining_minutes = $total_worked_minutes % 60;


} else {
    // No results found
    echo "No results found.";
}
                          ?>
                      </td>
<td class="align-middle  "><?php echo $remaining_hours.'.'.$remaining_minutes;?></td>
<!--
    <td class="align-middle  "><a id="<?php echo $prorows2->emp_hrs_id;?>" class="delete_emp_hrs"><span class="badge badge-phoenix fs-10 badge-phoenix-danger"><span class="ms-1" data-feather="x" style="height:12.8px;width:12.8px;"></span></span></a></td>
  -->                    
                  </tr>
                  <?php }}else{
                  $totalvalue = '0';
                  }?>
                  <?php if($get_login_user->admin_section != 'yes'){?>
                  <tr>
                      <td colspan="4"><a data-bs-toggle="collapse" href="#<?php echo $proj_services->service_id?>" role="button" aria-expanded="false" aria-controls="<?php echo $proj_services->service_id?>" class="text-primary">Request for additional hours</a>
            <div class="collapse" id="<?php echo $proj_services->service_id?>">
              <div class="border border-translucent p-3 rounded">
                 <form class="myForm"> 
                 <input hidden type="text" name="service_id" value="<?php echo $proj_services->service_id?>">
                  <div class="form-floating">
                  <textarea class="form-control" name="resaon_box" placeholder="Enter Reason" style="height:100px;"></textarea>
                  <label for="<?php echo $proj_services->service_id?>">Enter Reason</label>
                </div><br>
                <div class="form-floating">
                  <input type="number" class="form-control" name="additional_hrs" placeholder="Enter additional hours">
                  <label for="<?php echo $proj_services->service_id?>">Enter additional hours</label>
                </div>
                <br>
                <div class=" "><button type="submit"  class="btn btn-primary px-5">Send Request</button></div>
                </form>
              </div>
            </div>
                      
                      </td>
                  </tr>
                  <?php }?>
                  </tbody>
              </table>
               </div></div>
               
                 <div class="d-flex align-items-center mt-4">
                  <p class="mb-0 fw-bold fs-9">Total Hours Alloted :<span class="fw-semibold text-body-tertiary text-opactity-85 ms-1"><span class="text-info"><?php echo $proj_services->total_hrs_alloted;?> Hrs</span></span></p>
                </div>
                
               <div class="d-flex align-items-center mt-2">
                  <p class="mb-0 fw-bold fs-9">Started :<span class="fw-semibold text-body-tertiary text-opactity-85 ms-1"><span class="text-info"><?php echo $proj_services->service_start_date;?></span></span></p>
                </div>
              <br>
              <?php
     
   $this->db->select('*');
   $this->db->from('extra_hrs_request');
   $this->db->where('service_id', $proj_services->service_id);
   $this->db->where('emp_num', $emp_num_ses);
   $this->db->order_by('created_on', 'desc');
   $this->db->limit('1');
   $qu_pro3=  $this->db->get();
   //echo $this->db->last_query();
   $totals12 = $qu_pro3->num_rows() ;
   if($totals12 >0){
   foreach($qu_pro3->result() as $pro2)
                        { 
     if($pro2->status == '0'){
                        ?>
        
              <span class="badge badge-phoenix badge-phoenix-danger">Your request for additional hours is pending.</span>
            <?php }else{ ?>
         <span class="badge badge-phoenix badge-phoenix-success">Your request for additional hours is Approved.</span>
         <?php } ?>   
            <?php }}?>
              </div>
            </div>
          </div>
          <?php }} ?>
    </div> 
          
     
     
            
        <?php  include(APPPATH.'views/common/footer.php');?>
        
        <script>
            $(document).ready(function() {
    $('.myForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST', 
            url: '<?php echo base_url();?>save_extra_hrs_request',
            data: formData,
            success: function(response) {
                // Handle success response
                alert(response);
               // location.reload();
            },
            error: function(xhr, status, error) {
                // Handle error response
                alert('Form submission failed:', error);
            }
        });
    });
});

        </script>