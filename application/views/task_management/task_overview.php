<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  include(APPPATH.'views/common/header.php');
?>

<style>
  .task-heading {
    text-align: justify;
    word-spacing: 0.1px;
    letter-spacing: 0.1px;
  }
</style>

<div class="mb-4">
  <h4>Task Timeline</h4>
  <br>

<div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">
  <div class="row">
    <div class="col-md-3 mb-4">
      <div class="form-floating">
        <select class="form-select" id="employee" required>
          <option value="">--Select--</option>

                    <?php

                        if(!empty($get_members_list)){

                        foreach ($get_members_list as $emp_list)

                        {

                        ?>

                    <option value="<?php echo $emp_list->employee_no;?>" ><?php echo $emp_list->name;?></option>

                    <?php }}?>

                  </select><label for="employee">Employee</label>

                  </div>

        </div>

        <div class="col-md-3 mb-4">

             <div class="form-floating"><select class="form-select"  id="projects" required>

                     <option value="">--Select--</option>

                    <?php

                        if(!empty($get_project_list)){

                        foreach ($get_project_list as $project)

                        {

                        ?>

                    <option value="<?php echo $project->project_id;?>"><?php echo $project->project_name;?></option>

                    <?php }}?>

                  </select><label for="project_phase">Project</label>

                  </div>

        </div>

        <div class="col-md-3 mb-4">

             <div class="flatpickr-input-container">

                  <div class="form-floating">

                      <input class="form-control datetimepicker" id="from_date" type="text" placeholder="Start date" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />

                      <label class="ps-6" for="start_date">From date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>
            </div>

        </div>

        <div class="col-md-3">

             <div class="flatpickr-input-container">

                  <div class="form-floating">

                      <input class="form-control datetimepicker"  id="to_date" type="text" placeholder="Start date" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />

                      <label class="ps-6" for="start_date">To date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>

            </div>

        </div>

    </div>

<div class="table-responsive ms-n1 ps-1 scrollbar">



            <br>



              <table class="table table-sm fs-9 mb-0 overflow-hidden table-hover" id="task_overviewtable">
              <colgroup>
                <col style="width: 130px;">
                <col style="width: 130px;">
                <col style="width: 350px;">
                <col style="width: 180px;">
                <col style="width: 160px;">
                <col style="width: 130px;">
                <col style="width: 120px;">
                <col style="width: 120px;">
                <col style="width: 160px;">
              </colgroup>
                <thead class="text-body">
                  <tr>
                    <th class="">Start Date</th>
                    <th class="">End Date</th>
                    <th class="">Task Heading</th>
                    <th class="">Employee</th>
                    <th class="">Project</th>
                    <th class="">Task Status</th>
                    <th class="">Hrs</th>
                    <th class="">Mins</th>
                    <th class="">Total Days</th>
                  </tr>
                </thead>

                <tbody>
                </tbody>

                <tfoot style="background-color:#43A047">

            		<tr>

            			<td colspan="5"></td>

            			<td class="text-white">Totals</td>

            			<td class="text-white" id="total_hrs"></td>

            			<td class="text-white" id="total_min"></td>

            			<td class="text-white" id="total_days"></td>

            		</tr>

            	</tfoot>

            </table>
          </div>
        </div>
      </div>



<?php include(APPPATH.'views/common/footer.php');?>

