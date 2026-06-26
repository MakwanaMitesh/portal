<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
    
?>
<style>
    .edit_image{position:absolute;bottom:10px;width:32px; height:32px}
</style>
<div class="pb-9">
          <div class="row align-items-center justify-content-between g-3 mb-4">
            <div class="col-12 col-md-auto">
              <h2 class="mb-0">My Profile</h2>
            </div>
            <div class="col-12 col-md-auto d-flex"><a href="<?php echo base_url();?>create-employee?edit_myprofile=yes" class="btn btn-phoenix-warning px-3 px-sm-5 me-2"><span class="fa-solid fa-edit me-sm-2"></span><span class=" d-sm-inline"> Edit Profile</span></a>
              
            </div>
          </div>
    
          <div class="row g-4 g-xl-6">
            <div class="col-xl-5 col-xxl-4">
              <div class="sticky-leads-sidebar">
                <div class="card mb-3">
                    <div class="card-body">
                      <div class="row align-items-center g-3 text-center text-xxl-start">
                        <div class="col-12 col-xxl-auto">
                          <div class="avatar avatar-5xl" style="position:relative"><img class="rounded-circle" src="<?php echo base_url();?><?php if($get_login_user->profile_photo != ''){echo 'uploads/'.$get_login_user->profile_photo;}else{ echo 'assets/img/user_image.jpg';}?>" alt="" /><a id="uploadIcon" class="d-flex edit_image bg-secondary-subtle rounded flex-center me-3 mb-sm-3 mb-md-0 mb-xl-3 mb-xxl-0"><span class="text-primary-dark" data-feather="edit" style="width:16px; height:16px"></span><br></a></div>
                          <input type="file" id="profile_fileInput" class="file-input" name="profile_fileInput" style="display:none;" accept="image/*">
                        <div class="progress " style="height: 15px ! important;" id="progressbarr">
                <div style="height: 15px ! important;padding-top:2px;font-size:13px ! important;text-align:center" class="progress-bar progress-bar-striped progress-bar-animated bg-success"></div>
            </div>
            <div id="uploadStatus"></div>
                        
                        </div>
                        <div class="col-12 col-sm-auto flex-1">
                          <h3 class="fw-bolder mb-2"><?php echo $get_login_user->name;?></h3>
                         
                          <p class="mb-0"></p><a  href="#!"><?php echo $get_login_user->gender;?></a>
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="card">
                  <div class="card-body">
                    <h4 class="mb-5">Change Password</h4>
                    <form id="change_password_form" method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                      <div class="col-12">
                        <div class="mb-4">
                          
                          <div class="form-floating"><input disabled class="form-control" value="<?php echo $get_login_user->usercode;?>" name="current_passsword" id="current_passsword" type="text" placeholder="current password" /><label for="current_passsword" required>Current  Password</label></div>
                        </div>
                        <div class="mb-4">
                          <div class="form-floating"><input class="form-control" name="new_password" id="new_password" type="text" placeholder="new Password" /><label for="new_password" required>New Password</label></div>
                        </div>
                        
                        <div class="mb-4">
                          <button type="submit" class="btn btn-primary px-5 px-sm-15">Change Password</button>
                        </div>
                      </div>
                    </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-7 col-xxl-8">
              <div class="card mb-5">
                <div class="card-body">
                  <div class="row g-4 g-xl-1 g-xxl-3 justify-content-between">
                    <div class="col-sm-auto">
                      <div class="d-sm-block d-inline-flex d-md-flex flex-xl-column flex-xxl-row align-items-center align-items-xl-start align-items-xxl-center">
                        <div class="d-flex bg-success-subtle rounded flex-center me-3 mb-sm-3 mb-md-0 mb-xl-3 mb-xxl-0" style="width:32px; height:32px"><span class="text-success-dark" data-feather="user" style="width:24px; height:24px"></span></div>
                        <div>
                          <p class="fw-bold mb-1">Employee No.</p>
                          <h4 class="fw-bolder text-nowrap"><?php echo $get_login_user->employee_no;?></h4>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-auto">
                      <div class="d-sm-block d-inline-flex d-md-flex flex-xl-column flex-xxl-row align-items-center align-items-xl-start align-items-xxl-center border-start-sm ps-sm-5 border-translucent">
                        <div class="d-flex bg-info-subtle rounded flex-center me-3 mb-sm-3 mb-md-0 mb-xl-3 mb-xxl-0" style="width:32px; height:32px"><span class="text-info-dark" data-feather="codesandbox" style="width:24px; height:24px"></span></div>
                        <div>
                          <p class="fw-bold mb-1">Department</p>
                          <h4 class="fw-bolder text-nowrap"><?php echo $get_login_user->department;?></h4>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-auto">
                      <div class="d-sm-block d-inline-flex d-md-flex flex-xl-column flex-xxl-row align-items-center align-items-xl-start align-items-xxl-center border-start-sm ps-sm-5 border-translucent">
                        <div class="d-flex bg-primary-subtle rounded flex-center me-3 mb-sm-3 mb-md-0 mb-xl-3 mb-xxl-0" style="width:32px; height:32px"><span class="text-primary-dark" data-feather="award" style="width:24px; height:24px"></span></div>
                        <div>
                          <p class="fw-bold mb-1">Designation</p>
                          <h4 class="fw-bolder text-nowrap"><?php echo $get_login_user->designation;?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="px-xl-4 mb-7">
                <div class="row mx-0 mx-sm-3 mx-lg-0 px-lg-0">
                  <div class="col-sm-12 col-xxl-6 border-bottom border-end-xxl border-translucent py-3">
                    <table class="w-100 table-stats table-stats">
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                      <tr>
                        <td class="py-2">
                          <div class="d-inline-flex align-items-center">
                            <div class="d-flex bg-primary-subtle rounded-circle flex-center me-3" style="width:24px; height:24px"><span class="text-primary-dark" data-feather="phone" style="width:16px; height:16px"></span></div>
                            <p class="fw-bold mb-0">Phone</p>
                          </div>
                        </td>
                        <td class="py-2 d-none d-sm-block pe-sm-2">:</td>
                        <td class="py-2">
                          <p class="ps-6 ps-sm-0 fw-semibold mb-0 mb-0 pb-3 pb-sm-0"><?php echo $get_login_user->mobile_no;?></p>
                        </td>
                      </tr>
                      <tr>
                        <td class="py-2">
                           <div class="d-flex align-items-center">
                            <div class="d-flex bg-warning-subtle rounded-circle flex-center me-3" style="width:24px; height:24px"><span class="text-warning-dark" data-feather="phone" style="width:16px; height:16px"></span></div>
                            <p class="fw-bold mb-0">Alter Number</p>
                          </div>
                        </td>
                        <td class="py-2 d-none d-sm-block pe-sm-2">:</td>
                        <td class="py-2">
                          <p class="ps-6 ps-sm-0 fw-semibold mb-0"><?php echo $get_login_user->alter_mobile;?></p>
                        </td>
                      </tr>
                    </table>
                  </div>
                  
                  <div class="col-sm-12 col-xxl-6 border-bottom border-translucent py-3">
                    <table class="w-100 table-stats">
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                      <tr>
                        <td class="py-2">
                          <div class="d-inline-flex align-items-center">
                            <div class="d-flex bg-primary-subtle rounded-circle flex-center me-3" style="width:24px; height:24px"><span class="text-primary-dark" data-feather="mail" style="width:16px; height:16px"></span></div>
                            <p class="fw-bold mb-0">Email</p>
                          </div>
                        </td>
                        <td class="py-2 d-none d-sm-block pe-sm-2">:</td>
                        <td class="py-2"><a class="ps-6 ps-sm-0 fw-semibold mb-0 pb-3 pb-sm-0 text-body" ><?php echo $get_login_user->email;?></a></td>
                      </tr>
                      <tr>
                        <td class="py-2">
                          <div class="d-flex align-items-center">
                            <div class="d-flex bg-warning-subtle rounded-circle flex-center me-3" style="width:24px; height:24px"><span class="text-warning-dark" data-feather="calendar" style="width:16px; height:16px"></span></div>
                            <p class="fw-bold mb-0">Date of birth</p>
                          </div>
                        </td>
                        <td class="py-2 d-none d-sm-block pe-sm-2">:</td>
                        <td class="py-2"><a class="ps-6 ps-sm-0 fw-semibold mb-0 text-body" ><?php echo $get_login_user->dob;?></a></td>
                      </tr>
                    </table>
                  </div>
                  
                  <div class="col-sm-12 col-xxl-10  py-3 ">
                    <table class="w-100 table-stats">
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                      <tr>
                        <td class="py-2">
                          <div class="d-flex align-items-center">
                            <div class="d-flex bg-primary-subtle rounded-circle flex-center me-3" style="width:24px; height:24px"><span class="text-primary-dark" data-feather="calendar" style="width:16px; height:16px"></span></div>
                            <p class="fw-bold mb-0">Joining Date</p>
                          </div>
                        </td>
                        <td class="py-2 d-none d-sm-block pe-sm-2">:</td>
                        <td class="py-2"><a class="ps-6 ps-sm-0 fw-semibold mb-0 text-body" ><?php echo $get_login_user->join_date;?></a></td>
                      </tr>
                      <tr>
                        <td class="py-2">
                          <div class="d-flex align-items-center">
                            <div class="d-flex bg-warning-subtle rounded-circle flex-center me-3" style="width:24px; height:24px"><span class="text-warning-dark" data-feather="map-pin" style="width:16px; height:16px"></span></div>
                            <p class="fw-bold mb-0">Permanent Address</p>
                          </div>
                        </td>
                        <td class="py-2 d-none d-sm-block pe-sm-2">:</td>
                        <td class="py-2"><a class="ps-6 ps-sm-0 fw-semibold mb-0 text-body" ><?php echo $get_login_user->address;?></a></td>
                      </tr>
                    </table>
                  </div>
                  
                </div>
              </div>
              
              
            
            </div>
          </div>
        </div>      
          
<?php
    
    include(APPPATH.'views/common/footer.php');
    
?>
<script>
$(document).ready(function(){
    $('#progressbarr').hide();

    $('#profile_fileInput').on('change', function(){
        var allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        var file = this.files[0];
        var fileType = file.type;
        
        if(!allowedTypes.includes(fileType)){
            alert('Please select a valid file (JPEG / JPG / PNG).');
            $("#profile_fileInput").val('');
            return false;
        } else {
            var form_data = new FormData();
            form_data.append("profile_fileInput", file);

            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = ((evt.loaded / evt.total) * 100).toFixed(0);
                            $(".progress-bar").width(percentComplete + '% ');
                            $(".progress-bar").html(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                type: 'POST',
                url: 'upload_pic?u=<?php echo $get_login_user->employee_no;?>',  // Update this line with your correct URL
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#progressbarr').show();  
                    $(".progress-bar").width('0%');
                },
                error: function() {
                    $('#uploadStatus').html('<p style="color:#EA4335;">Image upload failed, please try again.</p>');
                },
                success: function(resp) {
               //alert(resp);
                    if(resp == 1){
                        location.reload();
                    } else if(resp == 0){
                        $('#uploadStatus').html('<p style="color:#EA4335;">Please select a valid file to upload.</p>');
                    }
                    $('#progressbarr').hide();
                    $(".progress-bar").width('0%');
                }
            });
        }
    });
});

</script>