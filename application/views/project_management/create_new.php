<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
    //echo $project_info->project_category;
    $categoryArray = explode(',', $project_info->project_category);
?>

<h2 class="mb-4"><?php if($prorow_id != ''){?>Update <?php }else{?>Create a<?php }?> project</h2>
        <div class="row">
          <div class="col-xl-9">
            <form class="row g-3 mb-6" id="project_create_form" method="post" >
                <?php if($prorow_id != ''){?>
                <input hidden  type="text" value="<?php echo $project_info->project_id;?>" name="project_id">
                <?php }?>
                <div class="col-sm-6 col-md-8">
                <select class="form-select" id="project_category" name="project_category[]" data-choices="data-choices" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}' >
                  <option value="">Project Category</option>
                  <?php
                        if(!empty($get_project_category)){
                        foreach ($get_project_category as $project_category)
                        { 
                        ?>
                    <option value="<?php echo $project_category->category_name;?>" <?php if (in_array($project_category->category_name, $categoryArray)){echo 'selected';} ?>><?php echo $project_category->category_name;?></option>
                    <?php }}?>
                </select>
                
              </div>
              
              
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><select class="form-select" name="project_phase" id="project_phase" required>
                    
                    <?php
                        if(!empty($get_project_phases)){
                        foreach ($get_project_phases as $phases_list)
                        { 
                        ?>
                    <option value="<?php echo $phases_list->phase_name;?>" <?php if($project_info->project_phase == $phases_list->phase_name){echo 'selected';} ?>><?php echo $phases_list->phase_name;?></option>
                    <?php }}?>
                  </select><label for="project_phase">Defult Project Status</label></div>
              </div>
              <div class="col-sm-6 col-md-12">
                <div class="form-floating"><input class="form-control" value="<?php echo $project_info->project_name?>" name="project_title" id="project_title" type="text" placeholder="Project title" /><label for="project_title" required>Project title</label></div>
              </div>
              <div class="col-sm-6 col-md-8">
                <div class="form-floating"><input class="form-control" value="<?php echo $project_info->client_name?>" name="client_name" id="client_name" type="text" placeholder="Client Name" /><label for="client_name" required>Client Name</label></div>
              </div>
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><input class="form-control" value="<?php echo $project_info->contact_number?>" name="client_number" id="client_number" type="text" placeholder="Contact Number" /><label for="client_number">Contact Number</label></div>
              </div>
               <div class="col-sm-12 col-md-12">
                   <select class="form-select" id="services" name="services[]" data-choices="data-choices" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}' >
                  <option value="">--Select Services--</option>  
                     <?php
                        if(!empty($get_proservice_list)){
                        foreach ($get_proservice_list as $proservice_list)
                        {
                            $this->db->select("*");
		                    $this->db->from('project_service_list');  
		                    $this->db->where('category_name',$proservice_list->category_name ); 
		                    $this->db->order_by('service_name', 'asc');
		                    $queryp = $this->db->get();
		                    $totalre = $queryp->num_rows() ;
                        ?>     
                      <optgroup label="<?php echo $proservice_list->category_name;?>">
                          <?php
        
                    		foreach($queryp->result() as $row)
                    		{
                         ?>
                        <option value="<?php echo $row->service_name ?>"><?php echo $row->service_name ?></option>
                        <?php } ?>
                      </optgroup>
                    <?php }} ?>
                 
                </select>
         </div>
              <div class="col-12 gy-6">
                <div class="form-floating"><textarea class="form-control" id="project_overview" name="project_overview" placeholder="Leave a comment here" style="height: 100px"><?php echo $project_info->project_overview?></textarea><label for="project_overview">project overview</label></div>
              </div>
              
              
              <div class="col-12 gy-6">
                <div class="row g-3 justify-content-end">
                  <div class="col-auto"><a href="<?php echo base_url();?>project-list" class="btn btn-phoenix-primary px-5">Cancel</a></div>
                  <div class="col-auto"><button type="submit" class="btn btn-primary px-5 px-sm-15"><?php if($prorow_id != ''){?>Update <?php }else{?>Create <?php }?> Project</button></div>
                </div>
              </div>
            </form>
            <div id="form_msg"></div>
            
            
          </div>
        </div>

<?php include(APPPATH.'views/common/footer.php'); ?>
<script>
    $("#project_create_form").submit(function(e) {
    e.preventDefault(); 
    $("#form_msg").show();
    var form = $(this);
    var actionUrl = "<?php echo base_url();?>save-project";
    
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(), 
        success: function(data)
        {
         
         // alert(data); 
         $('#form_msg').html(data);
        setTimeout(function() { $("#form_msg").hide(); }, 5000);
$('#project_create_form').trigger("reset");
location.reload();
          
        }
    });
    
});
</script>