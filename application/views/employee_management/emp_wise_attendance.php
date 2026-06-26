<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
    
?>

<h2 class="mb-4">Employee Attendance History</h2>

<div class="table-responsive ms-n1 ps-1 scrollbar">
    
    <div class="row">
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control" value="<?php echo $emp_id_value;?>" id="search_emp_num">
                <label for="year">Emp Number</label>
            </div>
        </div>
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
        
    </div>
            <br>   
	            <div class="table-responsive ms-n1 ps-1 scrollbar">
              <table class="table table-sm fs-9 mb-0 overflow-hidden table-hover" id="employee_attendance_history">
                <thead class="text-body">
                  <tr>
                    <!--<th class="" >Emp Name</th>  -->
                    <th class="" >Date</th>
                    <th class="" >Attendance</th>
                    <th class="" >Login</th>
                    <th class="" >Logout</th>
                    <!--<th class="" >TL Status</th>-->
                    
                    <!--<th class="" ></th>-->
                  </tr>
                </thead>
                
              </table>
    </div></div>

<?php include(APPPATH.'views/common/footer.php');?>

<script>
     $(document).ready(function(){
         var userDataTable = $('#employee_attendance_history').DataTable({
        //  $('#userTable').DataTable({
        "responsive": true,
        "orderSequence": ["desc", "asc"],
        "orderable": true,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'pageLength': '25',
        'ajax': {
        'url':base_url+'employees_attendance_history',
         'data': function(data){
             data.search_emp_num = $('#search_emp_num').val();
               data.search_year = $('#search_year').val();
              data.search_months = $('#search_months').val();
              //data.search_leave_status = $('#search_leave_status').val();
            }
          },
          'columns': [
            //  { data: 'emp_name' },
            { data: 'date' },
            { data: 'attendance' },
            { data: 'login' },
            { data: 'logout' },
           //  { data: 'tl_status' },
            
            
             
          ],
        });
         
        $('#search_emp_num,#search_year,#search_months').change(function(){
          userDataTable.draw();
       });
  
     });
    </script>
   