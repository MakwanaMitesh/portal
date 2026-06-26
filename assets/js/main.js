
 var base_url = window.location.origin + '/';
// console.log(base_url);
 var short_base_url = window.location.origin;
 //console.log(base_url);
 function reply_click(clicked_id)
  {
      //alert(clicked_id);
      $('#add_service_modal').modal('show');
      $("#project_id").val(clicked_id);
       $.ajax({
        url: base_url+"find-pname/"+clicked_id,
        success: function(data)
        {
          $("#project_name").html(data);
        }
    });
  }

  function emp_status_change(clicked_id,dataid)
  {
     //alert(clicked_id);alert(dataid);

  $.ajax({
    url:base_url+"change_emp_status",
    method:"POST",
    data:{emp_id:clicked_id,emp_status:dataid},
    success:function(data)
    {
    alert('Status Updated');
    location.reload();
    }
  });

  }

 /***********************************************************/
  $("#project_service_form").submit(function(e) {
    e.preventDefault();
    $("#form_msg_service").show();
    var form = $(this);
    var actionUrl = base_url+"save-service";

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(),
        success: function(data)
        {

        $('#form_msg_service').html(data);
        setTimeout(function() { $("#form_msg_service").hide(); }, 5000);
        $('#project_service_form').trigger("reset");
       location.reload();
        }
    });
});
/***********************************************************/
$('.show_aniversary_div').on('change', function() {
        if ($(this).val() === 'yes') {
            $('#aniversary_div').show();
        } else {
            $('#aniversary_div').hide();
        }
    });
/******************************************************/
 $('.show_recurring_div').on('change', function() {
        if ($(this).val() === 'Recurring') {
            $('#recurring_div').show();
        } else {
            $('#recurring_div').hide();
        }
    });

/***********************************************/
   function refreshModuleRequirement() {
    var proj_id = $('#proj_list').val();
    var service_id = $('#service_list').val();
    if (!proj_id) {
      return;
    }
    $.ajax({
      url: base_url + "check-module-required",
      method: "POST",
      data: { project_id: proj_id, service_id: service_id },
      dataType: "json",
      success: function(res) {
        if (res.required) {
          $('#module_label').text('Module (Required)');
          $('#module_hint').text('Development project/service — select a module.');
          $('#module_list').prop('required', true);
        } else {
          $('#module_label').text('Module (Optional)');
          $('#module_hint').text('Digital marketing — module is optional.');
          $('#module_list').prop('required', false);
        }
      }
    });
  }

   $('#proj_list').change(function(){
  var proj_id = $('#proj_list').val();
  if(proj_id !== '')
  {
    $.ajax({
     url:base_url+"get_service_list",
     method:"POST",
     data:{proj_id:proj_id},
     success:function(data)
     {
      $('#service_list').html(data);
      refreshModuleRequirement();
     }
    });

    $.ajax({
     url:base_url+"get_module_list",
     method:"POST",
     data:{proj_id:proj_id},
     success:function(data)
     {
      $('#module_list').html(data);
     }
    });
  }
  else
  {
    $('#service_list').html('<option value="">--Select--</option>');
    $('#module_list').html('<option value="">--Select--</option>');
  }
 });

 $('#service_list').change(function() {
   refreshModuleRequirement();
 });
  /***********************************************************/

    $("#task_save_form").submit(function(e) {
        e.preventDefault(); // Prevent the default form submission

        let projId = $('#proj_list').val();
        let serviceList = $('#service_list').val();

        if (projId !== '' && serviceList !== '') {
            $("#form_msg_task").show();
            let form = $(this);
            let actionUrl = base_url + "save-task";

            $.ajax({
                type: "POST",
                url: actionUrl,
                data: form.serialize(),
                dataType: 'json',
                success: function(data) {
                    // Show the response message
                    $('#form_msg_task').html(data.msg);

                    // Hide the message after 6 seconds
                    if (data.code == 200) {

                          $('#form_msg_task h5')
                            .removeClass("alert alert-outline-danger text text-danger")
                            .addClass("alert alert-outline-success text text-success")
                            .html(data.msg)
                            .fadeIn()
                            .delay(2000)
                            .fadeOut(function () {
                                $(this).html('');
                            });
        
                          let projectValue = $('#proj_list').val();
        
                          $('#service_list').val('');
                          $('#task_title').val('');
        
                          $('#task_status').prop('selectedIndex', 0);
                          $('#task_type').prop('selectedIndex', 0);
                          $('#task_priority').prop('selectedIndex', 0);
        
                          let d = new Date();
                          let today = ("0" + d.getDate()).slice(-2) + "-" +
                                      ("0" + (d.getMonth()+1)).slice(-2) + "-" +
                                      d.getFullYear();
        
                          $('#start_date_task').val(today);
                          $('#end_date_task').val(today);
        
                          $('#allotted_hrs').val('0');
                          $('#allotted_min').val('0');
        
                          if ($('#proj_list')[0].choices) {
                              $('#proj_list')[0].choices.removeActiveItems();
                          }
        
                          $('#proj_list').val(projectValue);
        
                          $('#task_title').focus();
                          
                           setTimeout(function() {
                            $('#form_msg_task').fadeOut(400, function() {
                                $(this).html('').hide();
                            });
                        }, 2000);
                      }
                },
                error: function(xhr, status, error) {
                    // Handle the error here
                    console.error("AJAX Error: ", status, error);
                    $('#form_msg_task').html("An error occurred. Please try again.");
                    setTimeout(function() {
                        $("#form_msg_task").hide();
                    }, 6000);
                }
            });
        } else {
            alert("Project name and service name are required");
        }
    });


    $('#add_task_modal').on('hidden.bs.modal', function () {
      location.reload();
    });


  /***********************************************************/
  $("#leave_request_form").submit(function(e) {
    e.preventDefault();
    var leave_date_from = $('#leave_date_from').val();
    var leave_date_to = $('#leave_date_to').val();
    var leave_reason = $("input[name=leave_reason]:checked").val();

    if(leave_date_from !== '' && leave_date_to !== '' && leave_reason !== ''){
    $("#message_leave").show();
    var form = $(this);
    var actionUrl = base_url+"save-leave-request";

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(),
        success: function(data)
        {

        $('#message_leave').html(data);
        setTimeout(function() { $("#message_leave").hide(); }, 5000);
       $('#leave_request_form').trigger("reset");
      // location.reload();
        }
    });
    }else{
        alert("All fields are required");
    }
});
 /***********************************************/
 $('#submit_login').submit(function(e){
            e.preventDefault(); // Prevent Default Submission
            $.ajax({
              url: base_url+'member-login',
              type: 'POST',
              data: new FormData(this),// it will serialize the form data
              contentType:false,
              processData:false,
            })
            .done(function(data){
                if(data == '1')
                {
                    window.location.href = "daily-attendance";
                }else if(data == '0'){
                  $("#msg").html('Incorrect Credentials');
                }else if(data == '2'){
                  $('#msg').html("Enter employee number and password");
                }
            })
            .fail(function(){
              alert('Try again later');
});
 });
/***********************************************/
/***********************************************/
$('#submit_clogin').submit(function(e) {
  e.preventDefault(); // Prevent Default Submission
  var username = $("#username").val().toLowerCase();

  $.ajax({
    url: base_url+'client_login',
    type: 'POST',
    data: new FormData(this),// it will serialize the form data
    contentType:false,
    processData:false,
  })
  .done(function(data) {
      if(data == '1')
      {
        window.location.href = "client-dashboard/"+username;
      }else if(data == '0'){
        $("#msg").html('Incorrect Credentials');
      }else if(data == '2'){
        $('#msg').html("Enter user name and password");
      }
  })
  .fail(function() {
    alert('Try again later');
  });
});
/***********************************************/

   $(document).on("click", ".delete", function(){
     var id = $(this).attr("id");
     var string = id;
    $.post(base_url+"delete-task", {string: string}, function(data) {
        location.reload();
    });

    });
/***********************************************/
function update_task(clicked_id)
  {
      //alert(clicked_id);
      $('#add_task_modal').modal('show');
      $("#task_id").val(clicked_id);
       $.ajax({
        url: base_url+"find-task/"+clicked_id,
        success: function(data)
        {
          console.log(data);
        }
    });
  }
  /***********************************************/
   $('#login_time_add').click(function(){
  var attendance_date = $('#attendance_date').val();
  var attendance_type = $('input[name="attendance_type"]:checked').val();
  var login_time = $('#login_time').val();
  if(attendance_date !== '' && attendance_type !== '' && login_time !== '')
  {
  $.ajax({
    url:base_url+"add_attendance_time",
    method:"POST",
    data:{attendance_date:attendance_date,attendance_type:attendance_type,login_time:login_time},
    success:function(data)
    {
     $('#msg_atten').html(data);
    }
  });
  }
  else
  {
      alert('All fields are required');
   //$('#service_list').html('<option value="">--Select--</option>');
  }
 });

 /***********************************************/
   $('#add_emp_hrs').click(function(){

  var emp_assign = $('#emp_assign').val();
  var emp_allotted_hrs = $('#emp_allotted_hrs').val();
  var projectid = $('#projectid').val();
  var serviceid = $('#serviceid').val();
  var total_hrs = $('#total_hrs').val();
  var assigned_total_hrs = $('#allotted_hrs').val();

  if(emp_assign !== '' && emp_allotted_hrs !== '' && projectid !== '' && serviceid !== '')
  {
  $.ajax({
    url:base_url+"save_emp_hrs",
    method:"POST",
    data:{emp_assign:emp_assign,emp_allotted_hrs:emp_allotted_hrs,projectid:projectid,serviceid:serviceid,total_hrs:total_hrs,assigned_total_hrs:assigned_total_hrs},
    success:function(data)
    { alert(data);
        location.reload();

     //$('#msg_atten').html(data);
    }
  });
  }
  else
  {
      alert('All fields are required');
   //$('#service_list').html('<option value="">--Select--</option>');
  }
 });

   /***********************************************/
   $('#logout_time_add').click(function(){
  var attendance_date = $('#attendance_date').val();
  var attendance_type = $('input[name="attendance_type"]:checked').val();
  var login_time = $('#login_time').val();
  var logout_time = $('#logout_time').val();
  //alert(logout_time);
  if(attendance_date !== '' && attendance_type !== '' && login_time !== '' && logout_time !== '')
  {
  $.ajax({
    url:base_url+"add_attendance_time",
    method:"POST",
    data:{attendance_date:attendance_date,attendance_type:attendance_type,login_time:login_time,logout_time:logout_time},
    success:function(data)
    {
     $('#msg_atten').html(data);
    }
  });
  }
  else
  {
      alert('All fields are required');
   //$('#service_list').html('<option value="">--Select--</option>');
  }
 });
/***********************************************************/
$("input[name='approve_with_comment']").click(function() {
  var btn_id = $(this).attr('id');
  var parts = btn_id.split('_');
  var task_id = parts[2];
  var form = $("#task_approval_form_"+task_id);

  $.ajax({
    url: base_url+"change_task_approval_status",
    method: "POST",
    data: form.serialize(),
    success:function(data)
    {
      if(data==1){
        $("#comment_input_"+task_id).hide();
        $("#task_comment_"+task_id).hide();
        $("#message_"+task_id).html("Task Approved");
        $("#message_"+task_id).addClass('alert');
        $("#message_"+task_id).addClass('alert-success');
        $("#message_"+task_id).addClass('small');
        $("#message_"+task_id).show();
      } else{
        $("#message_"+task_id).html("Error");
        $("#message_"+task_id).addClass('alert');
        $("#message_"+task_id).addClass('alert-danger');
        $("#message_"+task_id).addClass('small');
        $("#message_"+task_id).show();
      }
    }
  });
  return false;
});
/*************************************************************/
$('input[name="approve_leave_btn"]').click(function() {
  var btn_id = $(this).attr('id');
  // alert(btn_id);
  // debugger;
  var parts = btn_id.split('_');
  var l_id = parts[3];
  var approve_leave_form = $("#leave_approval_form_"+l_id);

  $.ajax({
    url: base_url+"change_leave_status",
    method: "POST",
    data: approve_leave_form.serialize(),
    success:function(data)
    {
      if(data==1) {
        $("#approve_leave_id_"+l_id).hide();
        $("#reject_leave_id_"+l_id).hide();
        $("#leave_message_"+l_id).html("Leave Approved");
        $("#leave_message_"+l_id).addClass('alert');
        $("#leave_message_"+l_id).addClass('alert-success');
        $("#leave_message_"+l_id).addClass('small');
        $("#leave_message_"+l_id).show();
      } else{
        $("#leave_message_"+l_id).html("Error");
        $("#leave_message_"+l_id).addClass('alert');
        $("#leave_message_"+l_id).addClass('alert-danger');
        $("#leave_message_"+l_id).addClass('small');
        $("#leave_message_"+l_id).show();
      }
    }
  });
  return false;
});
/**************************************************************/
$('input[name="reject_leave_btn"]').click(function(){
  var btn_id =$(this).attr('id');
  // alert(btn_id);
  // debugger;
  var parts = btn_id.split('_');
  var l_id = parts[3];
  var reject_leave_form = $("#leave_reject_"+l_id);

  $.ajax({
    url: base_url+"change_leave_status",
    method: "POST",
    data: reject_leave_form.serialize(),
    success:function(response)
    {
      if(response==1) {
        $("#approve_leave_id_"+l_id).hide();
        $("#reject_leave_id_"+l_id).hide();
        $("#leave_message_"+l_id).html("Leave Rejected");
        $("#leave_message_"+l_id).addClass('alert');
        $("#leave_message_"+l_id).addClass('alert-danger');
        $("#leave_message_"+l_id).addClass('small');
        $("#leave_message_"+l_id).show();
      } else {
        $("#leave_message_"+l_id).html("Error");
        $("#leave_message_"+l_id).addClass('alert');
        $("#leave_message_"+l_id).addClass('alert-danger');
        $("#leave_message_"+l_id).addClass('small');
        $("#leave_message_"+l_id).show();
      }
    }
  });
});
/*************************************************************/
$("#lead_create_form").submit(function(e) {
  e.preventDefault();
  $("#lead_form_msg").show();
  var form = $(this);
  var actionUrl = base_url+"save-lead";

  $.ajax({
      type: "POST",
      url: actionUrl,
      data: form.serialize(),
      success: function(data)
      {

      $('#lead_form_msg').html(data);
      setTimeout(function() { $("#lead_form_msg").hide(); }, 5000);
      $('#lead_create_form').trigger("reset");
      location.reload();
      }
  });
});
   /***********************************************/

   $(document).on("click", ".choose_theme", function(){
    var theme = $(this).attr("data-id");
    var url = $(this).attr("data-url");
   $.post(base_url+"update_theme", {theme: theme}, function(data) {
        window.location.href = short_base_url+url;
   });

   });
   /***********************************************/

   $(document).on("click", ".delete_emp_hrs", function(){
    var row_id = $(this).attr("id");
    //var url = $(this).attr("data-url");
   $.post(base_url+"delete_emp_hrs", {row_id: row_id}, function(data) {
         location.reload();
   });

   });
  /***********************************************/
  $(document).ready(function () {
    // Status + time prompts are handled in common/footer.php (.change_status)

    /***********************************************/
  });
  /***********************************************/

   $(document).on("click", ".hours_request_modal", function(){
    var theme = $(this).attr("data-id");
    var url = $(this).attr("data-url");
   $.post(base_url+"update_theme", {theme: theme}, function(data) {
        window.location.href = short_base_url+url;
   });

   });
    /***********************************************/
    /***********************************************/
  document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('profile_fileInput');
    const uploadIcon = document.getElementById('uploadIcon');

    if(uploadIcon) {
      uploadIcon.addEventListener('click', function() {
          fileInput.click();
      });
    }
});
  /***********************************************/