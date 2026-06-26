<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
?>

<h2 class="mb-4"><?php if($lead_row_id != ''){?>Update <?php }else{?>Create a <?php }?> candidate</h2>

        <div class="row">
            

          <div class="col-xl-9">

            <form class="row g-3 mb-6" id="candidate_create_form" method="post" enctype="multipart/form-data">

                <?php if($lead_row_id != ''){?>

                <input hidden  type="text" value="<?php echo $get_lead_row->lead_id;?>" name="emp_id">

                <?php }?>

                
          
              <div class="col-sm-12 col-md-12 ">
                  <hr>
    <p class="text-danger">Personal information</p>
</div>
              <div class="col-sm-6 col-md-8">
                <div class="form-floating"><input class="form-control" value="<?php ?>" name="candidate_name" id="candidate_name" type="text" placeholder="candidate Name" /><label for="candidate_name" required>Candidate Name</label></div>
             </div>
             
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><select class="form-select" name="gender" id="gender" >
                    <option value="">--Select--</option>
                    <option value="Male" >Male</option>
                    <option value="Female" >Female</option>
                     </select><label for="gender">Gender</label></div>
              </div>
             
              
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><input class="form-control" value="<?php ?>" name="mobile_num" id="mobile_num" type="text" placeholder="Contact Number" /><label for="mobile_num" required>Contact Number</label></div>
              </div>
                
                <div class="col-sm-6 col-md-4">
                <div class="form-floating"><input class="form-control" value="<?php ?>" name="emergency_mobile_num" id="alter_mobile_num" type="text" placeholder="alter Mobile Number" /><label for="alter_mobile_num" required>Alter Mobile Number</label></div>
              </div>
              
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><input class="form-control" value="<?php ?>" name="email_id" id="email_id" type="text" placeholder="Enter Email" /><label for="email_id" required>Email id</label></div>
              </div>

             <div class="col-sm-6 col-md-4">
                <div class="flatpickr-input-container">
                  <div class="form-floating">
                      <input class="form-control datetimepicker" name="dob"  id="dob" type="text" placeholder="Birth Date" data-options='{"disableMobile":true,"dateFormat":"Y-m-d"}' />
                      <label class="ps-6" for="dob">Birth Date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>
                </div>
              </div>

              
              <div class="col-sm-6 col-md-4">
                <div class="code-to-copy" style="border:1px solid #373e53;background-color:#141824;height: calc(2.875rem + 2px);
    min-height: calc(2.875rem + 2px);
    padding: 9.6px 0px;border-radius:6px">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">Married</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input show_aniversary_div" id="married1" type="radio" value="yes" name="married"/>
                        <label class="form-check-label" for="married1">Yes</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input show_aniversary_div" id="married2" type="radio" value="no" name="married"/>
                        <label class="form-check-label" for="married2">No</label>
                      </div>
                      
                </div>
              </div>

                
              <div class="col-sm-12 col-md-12">
                <div class="form-floating">
                  <textarea class="form-control" name=address id="address" placeholder="" style="height: 100px" maxlength="250"></textarea>
                  <label for="remark">Permanent Address</label>
                </div>
              </div>
<div class="col-sm-12 col-md-12">
    <hr/>
    <p class="text-danger">Professional  information</p>
</div>
            
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><input readonly class="form-control" value="<?php echo $get_last_emp_num->candidate_no + 1;?>" name="emp_number" id="emp_number" type="text" placeholder="candidate Number" /><label for="emp_number" required>candidate Number</label></div>
              </div>
              <div class="col-sm-6 col-md-4" >
                <div class="flatpickr-input-container">
                  <div class="form-floating">
                      <input class="form-control datetimepicker" name="joining_date"  id="joining_date" type="text" placeholder="Joining Date" data-options='{"disableMobile":true,"dateFormat":"Y-m-d"}' />
                      <label class="ps-6" for="joining_date">Joining Date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>
                </div>
              </div>
              
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><select class="form-select" name="department" id="department" >
                    <option value="">--Select--</option>
                    <?php
                        if(!empty($get_departments_list)){
                        foreach ($get_departments_list as $departments_list)
                        { 
                        ?>
                    <option value="<?php echo $departments_list->name;?>" ><?php echo $departments_list->name;?></option>
                    <?php }} ?>
                     </select><label for="department">Department</label></div>
              </div>
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><input class="form-control" value="" name="emp_designation" id="emp_designation" type="text" placeholder="Designation" /><label for="emp_designation" required>Designation</label></div>
              </div>
              
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><select class="form-select" name="team_type" id="team_type" >
                    <option value="">--Select--</option>
                    <?php
                        if(!empty($get_team_type)){
                        foreach ($get_team_type as $team_type)
                        { 
                        ?>
                    <option value="<?php echo $team_type->category_name;?>" ><?php echo $team_type->category_name;?></option>
                    <?php }} ?>
                     </select><label for="department">Team</label></div>
              </div>
              <div class="col-sm-6 col-md-4">
                <div class="form-floating"><input class="form-control" value="" name="login_password" id="login_password" type="text" placeholder="Login password" /><label for="login_password" required>Login password</label></div>
              </div>
            <div class="col-sm-12 col-md-12">     
                <div class="mb-3">
                  <label class="form-label" for="emp_photo">Google Drive link Profile Image</label>
                  <input class="form-control form-control-sm" id="emp_image" name="emp_image" type="text" />
                  <input hidden type="text" class="form-control form-control-sm" name="emp_image_link"  id="emp_image_link">
                </div>
            </div>

              <div class="col-12 gy-6">

                <div class="row g-3 justify-content-end">
                  <div class="col-auto"><button type="submit" class="btn btn-primary px-5 px-sm-15"><?php if($lead_row_id != ''){?>Update <?php }else{?>Create <?php }?> candidate</button></div>
                </div>

              </div>

            </form>

            <div id="emp_form_msg"></div>

          </div>

        </div>

<?php include(APPPATH.'views/common/footer.php'); ?>

<script>

    $("#candidate_create_form").submit(function(e) {
    e.preventDefault(); 
    
    $("#form_msg").show();
    var form = $(this);
     
    var actionUrl = "<?php echo base_url();?>save-candidate";
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(), 
        success: function(data)
        {
         $('#emp_form_msg').html(data);
        setTimeout(function() { $("#form_msg").hide(); }, 5000);
//$('#candidate_create_form').trigger("reset");
location.reload();
}

    });
});

function removeWords(sentence, numWordsStart, numWordsEnd) {
    // Split the sentence into an array of words
    let words = sentence.split('/');

    words = words.slice(numWordsStart);

    if (numWordsEnd > 0) {
        words = words.slice(0, -numWordsEnd);
    }
    return words.join(' ');
}
$(function() {
    $('#emp_image').keyup(function() {
        var value = $(this).val();
        
let result = removeWords(value, 5, 1);
$('#emp_image_link').val(result);
    }).keyup();
});

</script>