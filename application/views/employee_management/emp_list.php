<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
    
?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display:none;
}
</style>
<h2 class="mb-4">Employee List</h2>

<div class="table-responsive ms-n1 ps-1 scrollbar">
    
    <div class="row">
        
        <div class="col-md-3">
            <div class="form-floating"><select class="form-select"  id="search_gender" >
              <option value="" >--Select Gender--</option>      
              <option value="Male" >Male</option> 
              <option value="Female" >Female</option> 
               </select><label for="search_gender">Gender</label>
            </div>
        </div>
        <div class="col-md-3">
                <div class="form-floating">
                    <select class="form-select"  id="search_department" >
                 <option value="">--Select--</option>
                    <?php
                        if(!empty($get_departments_list)){
                        foreach ($get_departments_list as $departments_list)
                        { 
                        ?>
                    <option value="<?php echo $departments_list->name;?>" ><?php echo $departments_list->name;?></option>
                    <?php }} ?>
                  </select>
                  <label for="search_department">Department</label>
                </div>
        </div>
         <div class="col-md-3">
            <div class="form-floating"><select class="form-select"  id="search_status" >
              <option value="" >--Select Status--</option>      
              <option value="1" selected>Active</option> 
              <option value="0" >Deactive</option> 
               </select><label for="search_status">Status</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input class="form-control"  value="" type="text" placeholder="Search" id="search_all">
             <label for="search_all">Search by name, designation, mobile</label>
            </div>
        </div>
    </div>
            <br>   
	            <div class="table-responsive ms-n1 ps-1 scrollbar">
              <table class="table table-sm fs-9 mb-0 overflow-hidden table-hover" id="emplist">
                <thead class="text-body">
                  <tr>
                    <!--<th class="" >Emp Name</th>  -->
                    <th class="" >Emp No.</th>
                    <th class="" >Name</th>
                    <th class="" >Mobile</th>
                    <th class="" >Department</th>
                    <th class="" >Designation</th>
                    <th class="" >Status</th>
                    
                    <th class="" ></th>
                  </tr>
                </thead>
                
              </table>
    </div></div>

<?php include(APPPATH.'views/common/footer.php');?>

<script>
     $(document).ready(function(){
         var empDataTable = $('#emplist').DataTable({
        //  $('#userTable').DataTable({
        "responsive": true,
        "orderSequence": ["desc", "asc"],
        "orderable": true,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'pageLength': '25',
        //'searching': 'false'
        'ajax': {
        'url':base_url+'get_emp_list',
         'data': function(data){
             data.search_gender = $('#search_gender').val();
               data.search_department = $('#search_department').val();
              data.search_status = $('#search_status').val();
              data.search_all = $('#search_all').val();
            }
          },
          'columns': [
            //  { data: 'emp_name' },
            { data: 'empno' },
            { data: 'name' },
            { data: 'mobile' },
            { data: 'department' },
            { data: 'designation' },
            { data: 'status' },
            { data: 'action' },
          ],
        });
         
        $('#search_gender,#search_department,#search_status').change(function(){
          empDataTable.draw();
       });
       $('#search_all').keyup(function(){
          empDataTable.draw();
       });
  
  
  
     });
    </script>
   