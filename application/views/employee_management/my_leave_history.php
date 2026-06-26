<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
    
?>

<h2 class="mb-4">My Leave History</h2>

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
	            
              <table class="table table-sm fs-9 mb-0 overflow-hidden table-hover" id="my_leave_history">
                <thead class="text-body">
                  <tr>
                     <th class="" style="width:100px;">Date</th>
                    <th class="" >Reason</th>
                    <th class="" >Comment</th>
                    <!--<th class="" >TL Status</th>-->
                    <th class="" style="width:100px;">Leave Status</th>
                    <th class="" style="width:100px;"></th>
                  </tr>
                </thead>
                
              </table>
    </div>

<?php include(APPPATH.'views/common/footer.php');?>
<script>
     $(document).ready(function(){
         var userDataTable = $('#my_leave_history').DataTable({
        //  $('#userTable').DataTable({
        "responsive": true,
        "orderSequence": ["desc", "asc"],
        "orderable": true,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'pageLength': '25',
        'ajax': {
        'url':base_url+'get-leave-history',
         'data': function(data){
               data.search_year = $('#search_year').val();
              data.search_months = $('#search_months').val();
               data.search_leave_status = $('#search_leave_status').val();
            }
          },
          'columns': [
            { data: 'date' },
            { data: 'reason' },
            //{ data: 'services' },
            { data: 'comment' },
            //  { data: 'tl_status' },
            { data: 'hr_status' },
             { data: 'action' }
             
          ],
        });
         
        $('#search_year,#search_months,#search_leave_status').change(function(){
          userDataTable.draw();
       });
     });
    </script>
<script>
     $(document).on("click", ".remove_leave_request", function(event){
 event.preventDefault();
 event.stopPropagation();
    var selectBoxId1 = $(this).attr("id");
    $.post(base_url+"remove_leave_request", {row_id1: selectBoxId1}, function(data) {
         var table =  $('#my_leave_history');
        table.DataTable().ajax.reload(null, false );
   });
    //alert("Selected Value: " + selectedValue + " from Select Box with ID: " + selectBoxId);
  });
</script>