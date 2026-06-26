<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
    
?>

<h2 class="mb-4">Employees Leave History</h2>

<div class="table-responsive ms-n1 ps-1 scrollbar">
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-floating"><select class="form-select"  id="search_year" >
              <option value="" >--Select Year--</option>      
                    <?php
                    for ($i=2023; $i <= 2030 ; $i++) { ?>
                    <option value="<?php echo $i; ?>" <?php if(date('Y') == $i){echo 'selected';}?>><?php echo $i; ?></option>
                    <?php } ?>
               </select><label for="year">Defult Year</label>
            </div>
        </div>
        <div class="col-md-3">
             <div class="form-floating"><select class="form-select"  id="search_months" >
                     <option value="">--Select--</option>

                    <option value="1" <?php if(date('m') == '1'){echo 'selected';}?>>January</option>
                    <option value="2" <?php if(date('m') == '2'){echo 'selected';}?>>February</option>
                    <option value="3" <?php if(date('m') == '3'){echo 'selected';}?>>March</option>
                    <option value="4" <?php if(date('m') == '4'){echo 'selected';}?>>April</option>
                    <option value="5" <?php if(date('m') == '5'){echo 'selected';}?>>May</option>
                    <option value="6" <?php if(date('m') == '6'){echo 'selected';}?>>June</option>
                    <option value="7" <?php if(date('m') == '7'){echo 'selected';}?>>July</option>
                    <option value="8" <?php if(date('m') == '8'){echo 'selected';}?>>August</option>
                    <option value="9" <?php if(date('m') == '9'){echo 'selected';}?>>September</option>
                    <option value="10" <?php if(date('m') == '10'){echo 'selected';}?>>October</option>
                    <option value="11" <?php if(date('m') == '11'){echo 'selected';}?>>November</option>
                    <option value="12" <?php if(date('m') == '12'){echo 'selected';}?>>December</option>
                   
                  </select><label for="project_phase">Months</label>
                  </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating"><select class="form-select"  id="search_leave_status" >
                <option value="0" >Pending</option>      
                <option value="1" >Approved</option>     
               </select><label for="year">Leave Status</label>
            </div>
        </div>
    </div>
            <br>   
	            <div class="table-responsive ms-n1 ps-1 scrollbar">
              <table class="table table-sm fs-9 mb-0 overflow-hidden table-hover" id="employee_leave_history">
                <thead class="text-body">
                  <tr>
                    <th class="" >Emp Name</th>  
                    <th class="" >Leave Date</th>
                    <th class="" > Reason</th>
                    <th class="" >Comment</th>
                    <th class="" >Leave Status</th>
                    <!--<th class="" >TL Status</th>-->
                    
                    <!--<th class="" ></th>-->
                  </tr>
                </thead>
                
              </table>
    </div></div>

<?php include(APPPATH.'views/common/footer.php');?>
<script>
  
     $(document).ready(function(){
         var userDataTable = $('#employee_leave_history').DataTable({
        //  $('#userTable').DataTable({
        "responsive": true,
        "orderSequence": ["desc", "asc"],
        "orderable": true,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'pageLength': '25',
        'ajax': {
        'url':base_url+'get_employees_leave_history',
         'data': function(data){
               data.search_year = $('#search_year').val();
              data.search_months = $('#search_months').val();
              data.search_leave_status = $('#search_leave_status').val();
            }
          },
          'columns': [
             { data: 'emp_name' },
            { data: 'date' },
            { data: 'reason' },
            { data: 'comment' },
            { data: 'hr_status' },
           //  { data: 'tl_status' },
            
            
             
          ],
        });
         
        $('#search_year,#search_months,#search_leave_status').change(function(){
          userDataTable.draw();
       });
    /*********************************************************/
    
    // $(".hr_update_leave_status").on("click", function () {
    // var leave_statusValue = $(this).attr("status-value");
    // var attendanceBoxId = $(this).attr("atend-id");
    // alert("Selected Value: " + leave_statusValue + " from Select Box with ID: " + attendanceBoxId);
    // return false;
//     $.post(base_url+"update_leave_status", {leave_statusValue: leave_statusValue,attendanceBoxId: attendanceBoxId}, function(data) {
//       location.reload();
//   });
    
 // });
    
    /*********************************************************/
     });
    </script>
    
    <script>
  $(document).on("click", ".hr_update_leave_status", function(event){
 event.preventDefault();
 event.stopPropagation();
 attendanceBoxId = $(this).attr("atend-id");
 leave_statusValue = $(this).attr("status-value");
 //alert('id='+attendanceBoxId+' value='+leave_statusValue);
     $.post(base_url+"hr_update_leave_status", {value: leave_statusValue,atend_id: attendanceBoxId}, function(data) {
     var table =  $('#employee_leave_history');
    table.DataTable().ajax.reload(null, false );
    });
  });
//});
    </script>