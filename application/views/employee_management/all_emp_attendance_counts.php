<?php include(APPPATH.'views/common/header.php');
error_reporting(0);


?>
<h2 class="mb-4">Employess attendance Counts</h2>

<div class="table-responsive ms-n1 ps-1 scrollbar">
                    <table class="table table-sm fs-9 mb-0 overflow-hidden table-hover" id="cardTable" >
                        <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Jan</th>
                                        <th>Feb</th>
                                        <th>Mar</th>
                                        <th>Apr</th>
                                        <th>May</th>
                                        <th>Jun</th>
                                        <th>Jul</th>
                                        <th>Aug</th>
                                        <th>Sep</th>
                                        <th>Oct</th>
                                        <th>Nov</th>
                                        <th>Dec</th>
                                        <th>Leave</th>
                                        <!--<th class="text-center">Action</th>-->
                                    </tr>
                        </thead>
                        <tbody>
                                  
                        </tbody>    
                    </table>
        </div>
<?php include(APPPATH.'views/common/footer.php');?>


<script type="text/javascript">
    
/**********************************************/
    $(document).ready(function(){
var userDataTable = $('#cardTable').DataTable({
        //  $('#userTable').DataTable({
            "responsive": true,
         "orderSequence": ["desc", "asc"],
         "pageLength": 50,
        "orderable": true,
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
             'url':'employee_attendence_list_admin',
               'data': function(data){
            }
          },
          'columns': [
            { data: 'name' },
            { data: 'jan' },
            { data: 'feb' },
            { data: 'mar' },
            { data: 'apr' },
            { data: 'may' },
            { data: 'jun' },
            { data: 'jul' },
            { data: 'aug' },
            { data: 'sep' },
            { data: 'oct' },
            { data: 'nov' },
            { data: 'dec' },
            { data: 'leave' },
          ],
    
        });
       
    });
    </script>