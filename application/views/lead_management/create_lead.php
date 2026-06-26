<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
?>

<h2 class="mb-4"><?php if($lead_row_id != ''){?>Update <?php }else{?>Create a<?php }?> Lead</h2>

        <div class="row">

          <div class="col-xl-9">

            <form class="row g-3 mb-6" id="lead_create_form" method="post" >

                <?php if($lead_row_id != ''){?>

                <input hidden  type="text" value="<?php echo $get_lead_row->lead_id;?>" name="lead_id">

                <?php }?>

                

              <div class="col-sm-6 col-md-8">

                <div class="form-floating"><input class="form-control" value="<?php echo $get_lead_row->person_name;?>" name="person_name" id="person_name" type="text" placeholder="Person/Company Name" /><label for="person_name" required>Person / Company Name</label></div>

              </div>

              <div class="col-sm-6 col-md-4">

                <div class="form-floating"><input class="form-control" value="<?php echo $get_lead_row->mobile;?>" name="mobile_num" id="mobile_num" type="text" placeholder="Contact Number" /><label for="mobile_num" required>Contact Number</label></div>

              </div>


              <div class="col-sm-6 col-md-4">

                <div class="form-floating"><select class="form-select" name="lead_status" id="lead_status" >

                   <?php

                        if(!empty($get_lead_status)){

                        foreach ($get_lead_status as $lead_status)

                        { 

                        ?>

                    <option value="<?php echo $lead_status->status_name;?>" <?php if($get_lead_row->lead_status == $lead_status->status_name){echo 'selected';}?>><?php echo $lead_status->status_name;?></option>

                    <?php }}?> 

                    

                  </select><label for="lead_status">Lead Status</label></div>

              </div>

              

              <div class="col-sm-6 col-md-4">

                <div class="form-floating"><select class="form-select" name="lead_type" id="lead_type" >

                    <option value="">--Select--</option>

                   <?php

                        if(!empty($get_lead_type)){

                        foreach ($get_lead_type as $lead_type)

                        { 

                        ?>

                    <option value="<?php echo $lead_type->type;?>" <?php if($get_lead_row->lead_type == $lead_type->type){echo 'selected';}?>><?php echo $lead_type->type;?></option>

                    <?php }}?> 

                    

                  </select><label for="lead_type">Lead Type</label></div>

              </div>

              

              <div class="col-sm-6 col-md-4">

                <div class="form-floating"><select class="form-select" name="lead_source" id="lead_source" >

                    <option value="">--Select--</option>

                   <?php

                        if(!empty($get_lead_source)){

                        foreach ($get_lead_source as $lead_source)

                        { 

                        ?>

                    <option value="<?php echo $lead_source->sources_name;?>" <?php if($get_lead_row->lead_source == $lead_source->sources_name){echo 'selected';}?>><?php echo $lead_source->sources_name;?></option>

                    <?php }}?> 

                    

                  </select><label for="lead_source">Lead Source</label></div>

              </div>


              <div class="col-sm-12 col-md-12">
                <div class="form-floating">
                  <textarea class="form-control" name=remark id="remark" placeholder="" style="height: 100px"><?php echo $get_lead_row->remark;?></textarea>
                  <label for="remark">Remark</label>
                </div>
              </div>

              <div class="col-12 gy-6">

                <div class="row g-3 justify-content-end">

                  <div class="col-auto"><a href="<?php echo base_url();?>lead-list" class="btn btn-phoenix-primary px-5">Cancel</a></div>

                  <div class="col-auto"><button type="submit" class="btn btn-primary px-5 px-sm-15"><?php if($lead_row_id != ''){?>Update <?php }else{?>Create <?php }?> Lead</button></div>

                </div>

              </div>

            </form>

            <div id="lead_form_msg"></div>

          </div>

        </div>

<?php include(APPPATH.'views/common/footer.php'); ?>

<script>

    $("#project_create_form").submit(function(e) {

    e.preventDefault(); 

    $("#form_msg").show();

    var form = $(this);

    var actionUrl = "<?php echo base_url();?>save-lead";

    

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