<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
?>
<style>
    th{text-transform:uppercase;}
</style>
<div class="row gx-6">
          <div class="col-12 col-xl-12">
            
              <div class="mb-5 mt-7">
                <h3>Additional Hours Request </h3>
                
              </div>
              <div class="table-responsive scrollbar">
                <table class="table fs-10 mb-0">
                  <thead>
                    <tr>
                      <th class=" border-top border-translucent  align-middle"   style="width:20%">Emp Name</th>
                      <th class=" border-top border-translucent align-middle" >Project</th>
                      <th class=" border-top border-translucent  align-middle" >Service</th>
                      <th class=" border-top border-translucent  align-middle" >Extra Hrs</th>
                      <th class=" border-top border-translucent  align-middle"  >Reason</th>
                      <th class=" border-top border-translucent  align-middle"  >Action</th>
                    </tr>
                  </thead>
                  
                  <tbody class="list" id="table-regions-by-revenue">
                      <?php
                        if(!empty($get_extra_hrs_request)){
                        foreach ($get_extra_hrs_request as $extra_hrs)
                        { 
                        $this->db->select('*');
                        $this->db->from('project_list')->where('project_id',  $extra_hrs->project_id);
                        $query =  $this->db->get();
                        $roes =  $query->row(); 
                            
                        ?>
                    <tr>
                      <td class="white-space-nowrap ps-0 " >
                        <h6 class="text-primary"><?php echo $extra_hrs->name;?></h6>
                      </td>
                      <td class=" ps-0 " >
                        <h6 class="mb-0"><?php echo $roes->project_name;?></h6>
                      </td>
                      <td class=" ps-0 " >
                        <h6 class="mb-0"><?php echo $extra_hrs->service_name;?></h6>
                      </td>
                      <td class=" ps-0 text-center" style="width:10%">
                        <h6 class="mb-0 text-warning"><?php echo $extra_hrs->extra_hrs;?></h6>
                      </td>
                      
                      <td class=" ps-0 " style="width:300px">
                        <p style="font-size:12px" class="mb-0"><?php echo $extra_hrs->reason;?></p>
                      </td>
                      
                      <td class="ps-0 " >
                        <div class="border border-translucent p-3 rounded">
                 <form class="myForm2"> 
                 <input hidden type="text" name="row_id" value="<?php echo $extra_hrs->id ;?>">
                 <input hidden type="text" name="service_id" value="<?php echo $extra_hrs->service_id ;?>">
                 <input hidden type="text" name="project_id" value="<?php echo $roes->project_id;?>">
                 <input hidden type="text" name="emp_num" value="<?php echo $extra_hrs->emp_num;?>">
                  <div class="form-floating">
                  <select class="form-control" name="status" >
                      <option value="0">Pending</option>
                      <option value="1">Approved</option>
                  </select>
                  <label for="">Status</label>
                </div><br>
                <div class="form-floating">
                  <input type="number" class="form-control" name="allowed_hrs" value="<?php echo $extra_hrs->extra_hrs;?>">
                  <label for="">Allowed hours</label>
                </div>
                <br>
                <div class=" "><button type="submit"  class="btn btn-primary px-5">Update Request</button></div>
                </form>
              </div>
                      </td>
                      
                      
                    </tr>
                     <?php }} ?>
                    
                    
                  </tbody>
                </table>
              </div>
              
            
          </div>
          
        </div>
<?php include(APPPATH.'views/common/footer.php'); ?>

<script>
            $(document).ready(function() {
    $('.myForm2').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST', 
            url: '<?php echo base_url();?>update_extra_hrs_request',
            data: formData,
            success: function(response) {
                // Handle success response
                alert(response);
                location.reload();
            },
            error: function(xhr, status, error) {
                // Handle error response
                alert('Form submission failed:', error);
            }
        });
    });
});

        </script>
