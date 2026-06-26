<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include('common/header.php');
$todaydate =date('Y-m-d');
?>

        <div class="pb-5">
            <div class="row g-4">
            <div class="col-12 col-xxl-8">
               <div class="mb-8">
                <h2 class="mb-2"> Dashboard</h2>
                <h5 class="text-body-tertiary fw-semibold">Believe in your abilities, stay positive, and success will be the echo of your unwavering self-confidence.</h5>
              </div>
              <div class="row align-items-center g-4">
                <div class="col-6 col-md-auto">
                  <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;"><span class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-success-light" data-fa-transform="down-4 rotate--10 left-4"></span><span class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-success" data-fa-transform="up-4 right-3 grow-2"></span><span class="fa-stack-1x fa-solid fa-star text-success " data-fa-transform="shrink-2 up-8 right-6"></span></span>
                    <div class="ms-3">
                      <h4 class="mb-0"><?php echo $today_task_count;?></h4>
                      <p class="text-body-secondary fs-9 mb-0">Today's Task</p>
                    </div>
                  </div>
                </div>
                <div class="col-6 col-md-auto">
                  <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;"><span class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-warning-light" data-fa-transform="down-4 rotate--10 left-4"></span><span class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-warning" data-fa-transform="up-4 right-3 grow-2"></span><span class="fa-stack-1x fa-solid fa-clipboard-list text-warning " data-fa-transform="shrink-2 up-8 right-6"></span></span>
                    <div class="ms-3">
                      <h4 class="mb-0"><?php echo $todo_task_count;?></h4>
                      <p class="text-body-secondary fs-9 mb-0">To Do Tasks</p>
                    </div>
                  </div>
                </div>
                <div class="col-6 col-md-auto">
                  <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;"><span class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-danger-light" data-fa-transform="down-4 rotate--10 left-4"></span><span class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-danger" data-fa-transform="up-4 right-3 grow-2"></span><span class="fa-stack-1x fa-solid fa-asterisk text-danger " data-fa-transform="shrink-2 up-8 right-6"></span></span>
                    <div class="ms-3">
                      <h4 class="mb-0"><?php echo $urgent_task_count;?></h4>
                      <p class="text-body-secondary fs-9 mb-0">Urgent Task</p>
                    </div>
                  </div>
                </div>
                <div class="col-6 col-md-auto">
                  <div class="d-flex align-items-center"><span class="fa-stack" style="min-height: 46px;min-width: 46px;"><span class="fa-solid fa-square fa-stack-2x dark__text-opacity-50 text-danger-light" data-fa-transform="down-4 rotate--10 left-4"></span><span class="fa-solid fa-circle fa-stack-2x stack-circle text-stats-circle-danger" data-fa-transform="up-4 right-3 grow-2"></span><span class="fa-stack-1x fa-solid fa-calendar-check text-danger " data-fa-transform="shrink-2 up-8 right-6"></span></span>
                    <div class="ms-3">
                      <h4 class="mb-0">15</h4>
                      <p class="text-body-secondary fs-9 mb-0">Leaves Remaining</p>
                    </div>
                  </div>
                </div>
              </div>


        </div>

            </div></div>

       <div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">

<div class="row g-3">
    <div class="col-md-10"><h4> Today's Task </h4></div>
    <div class="col-md-2"><button style="margin-top:-10px;" data-bs-toggle="modal" data-bs-target="#add_task_modal" class="btn btn-subtle-primary me-1 mb-1 "><span class="fas fa-plus me-2"></span>Add Task</button></div>

              </div>
             <br>
<div id="userTable_admin" >

<div class="table-responsive ms-n1 ps-1 scrollbar">
              <table class="table table-sm fs-9 mb-0" >
                <thead class="text-body">
                <colgroup>
                  <col style="width: 130px;">
                  <col style="width: 180px;">
                  <col style="width: 180px;">
                  <col style="width: 350px;">
                  <col style="width: 180px;">
                  <col style="width: 200px;">
                  <col style="width: 180px;">
                  <col style="width: 190px;">
                </colgroup>
                  <tr>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="priority">PRIORITY</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="start_date">START DATE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="end_date">DEADLINE</th>
                    <th class="sort white-space-nowrap ps-3 align-middle ps-0" scope="col" data-sort="task">TASK</th>
                    <th class="sort white-space-nowrap ps-3 align-middle ps-0" scope="col" data-sort="status">STATUS</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="service">SERVICE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="project">PROJECT</th>
                    <th class="sort white-space-nowrap ps-3 align-middle ps-0" scope="col" data-sort="assign_by">ASSIGNED BY</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list" >
       <!-- Recurring Task List -->

        <?php  if(!empty($my_recurring_task)){
        foreach ($my_recurring_task as $recurring_task)
        {
$startDate = new DateTime($recurring_task->task_start_date);
$endDate = new DateTime($recurring_task->task_end_date);
$interval = new DateInterval($recurring_task->recurring_type);
$endDate->modify('+1 day');
$dateRange = new DatePeriod($startDate, $interval, $endDate);

foreach ($dateRange as $date) {

 $currentDate = $date->format('Y-m-d');


  if($todaydate == $currentDate){
    $recurring_task->task_heading;
    if($recurring_task->priority == '1'){$color1 = 'danger';$text1 = 'Urgent';}else if($recurring_task->priority == '2'){$color1 = 'warning';$text1 = 'High';}else if($recurring_task->priority == '3'){$color1 = 'primary';$text1 = 'Normal';}else{$color1 = 'secondary';$text1 = 'Low';}
 ?>

<tr class="position-static">
<td class="align-middle white-space-nowrap align-middle priority"><span class="badge badge-phoenix fs-10 badge-phoenix-<?php echo $color1;?>"><b><?php echo $text1;?><span class="ms-1" data-feather="flag" style="height:12.8px;width:12.8px;"></b></span></td>
<td class="align-middle white-space-nowrap start_date ps-3 ">
  <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($recurring_task->task_start_date));?></p>
</td>
<td class="align-middle white-space-nowrap end_date ps-3 ">
  <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($recurring_task->task_end_date));?></p>
</td>
<td class="align-middle time white-space-nowrap ps-0 task "><a class="fw-bold " href="<?php echo base_url();?>edit-task/<?php echo $recurring_task->task_id;?>"><?php echo substr($recurring_task->task_heading, 0, 100);?></a></td>
<td class="align-middle white-space-nowrap project ps-3 ">
    <!--<select id="<?php echo $recurring_task->task_id;?>" class="change_status">-->
        <!--<option value="" >----</option>-->
    <!--    <option value="Today" >Today </option>-->
    <!--    <option value="Doing" >Doing </option>-->
    <!--    <option value="To Do" >To Do </option>-->
    <!--    <option value="Completed" >Completed </option>-->
    <!--</select>-->
    -
</td>
<td class="align-middle white-space-nowrap project ps-3 ">
    <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $recurring_task->service_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
</td>
<td class="align-middle white-space-nowrap project ps-3 ">
<span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $recurring_task->project_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
</td>
<td class="align-middle white-space-nowrap assign_by ps-3 ">
                       <p class="mb-0 fs-9 text-body"><?php if($recurring_task->assignee != $recurring_task->task_assign_by){$nameArray2 = explode(" ", $recurring_task->emp_name);echo $nameArray2[0];}?></p>
                    </td>
<td><a id="<?php echo $recurring_task->task_id;?>" class="delete badge badge-phoenix badge-phoenix-danger">Remove</td>
</tr>
<?php
}  } }}?>

        <?php  if(!empty($doing_task_list)){
        foreach ($doing_task_list as $doing_task)
        {
        if($doing_task->priority == '1'){$color = 'danger';$text = 'Urgent';}else if($doing_task->priority == '2'){$color = 'warning';$text = 'High';}else if($doing_task->priority == '3'){$color = 'primary';$text = 'Normal';}else{$color = 'secondary';$text = 'Low';}
        ?>
                  <tr class="position-static">
                    <td class="align-middle white-space-nowrap align-middle priority"><span class="badge badge-phoenix fs-10 badge-phoenix-<?php echo $color;?>"><b><?php echo $text;?><span class="ms-1" data-feather="flag" style="height:12.8px;width:12.8px;"></b></span></td>
                    <td class="align-middle white-space-nowrap start_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($doing_task->task_start_date));?></p>
                    </td>
                    <td class="align-middle white-space-nowrap end_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($doing_task->task_end_date));?></p>
                    </td>
                    <td class="align-middle time white-space-nowrap ps-0 task ">
                        <span class="badge badge-phoenix badge-phoenix-success"><?php echo $doing_task->task_status;?></span> &nbsp;
                        <a class="fw-bold " href="<?php echo base_url();?>edit-task/<?php echo $doing_task->task_id;?>"><?php echo substr($doing_task->task_heading, 0, 100);?></a>
                        <?php if($doing_task->open_issues_count > 0){ ?>
                            <a href="<?php echo base_url('task-issues/' . (int) $doing_task->task_id); ?>" class="badge badge-phoenix badge-phoenix-danger text-decoration-none">Issues: <?php echo (int) $doing_task->open_issues_count; ?></a>
                        <?php } ?>
                        <a href="javascript:void(0)" class="ms-2 view-activity-log" data-task-id="<?php echo $doing_task->task_id;?>" title="Activity Log"><span class="fas fa-history"></span></a>
                    </td>
                    <td class="align-middle white-space-nowrap project ps-3 ">
                        <?php if($doing_task->task_status == 'In Progress'){ ?>
                            <button class="btn btn-phoenix-primary btn-xs ready-for-testing" id="<?php echo $doing_task->task_id;?>">Ready for Testing</button>
                        <?php } ?>
                        <button class="btn btn-phoenix-info btn-xs log-time-btn" data-task-id="<?php echo $doing_task->task_id;?>">Log Fix Time</button>
                    </td>
                    <td class="align-middle white-space-nowrap project ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $doing_task->service_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>
                    <td class="align-middle white-space-nowrap project ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $doing_task->project_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>

                    <td class="align-middle white-space-nowrap assign_by ps-3 ">
                       <p class="mb-0 fs-9 text-body"><?php if($doing_task->assignee != $doing_task->task_assign_by){$nameArray2 = explode(" ", $doing_task->emp_name);echo $nameArray2[0];}?></p>
                    </td>

                    <td><a id="<?php echo $doing_task->task_id;?>" class="delete badge badge-phoenix badge-phoenix-danger">Remove</td>
                  </tr>
              <?php }}?>


        <?php  if(!empty($today_task_list)){
        foreach ($today_task_list as $serv_task)
        {
        if($serv_task->priority == '1'){$color = 'danger';$text = 'Urgent';}else if($serv_task->priority == '2'){$color = 'warning';$text = 'High';}else if($serv_task->priority == '3'){$color = 'primary';$text = 'Normal';}else{$color = 'secondary';$text = 'Low';}
        ?>
                  <tr class="position-static">
                    <td class="align-middle white-space-nowrap align-middle priority"><span class="badge badge-phoenix fs-10 badge-phoenix-<?php echo $color;?>"><b><?php echo $text;?><span class="ms-1" data-feather="flag" style="height:12.8px;width:12.8px;"></b></span></td>
                    <td class="align-middle white-space-nowrap start_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($serv_task->task_start_date));?></p>
                    </td>
                    <td class="align-middle white-space-nowrap end_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($serv_task->task_end_date));?></p>
                    </td>
                    <td class="align-middle time white-space-nowrap ps-0 task">
                        <a class="fw-bold " href="<?php echo base_url();?>edit-task/<?php echo $serv_task->task_id;?>"><?php echo substr($serv_task->task_heading, 0, 100);?></a>
                        <?php if($serv_task->open_issues_count > 0){ ?>
                            <a href="<?php echo base_url('task-issues/' . (int) $serv_task->task_id); ?>" class="badge badge-phoenix badge-phoenix-danger text-decoration-none">Issues: <?php echo (int) $serv_task->open_issues_count; ?></a>
                        <?php } ?>
                        <a href="javascript:void(0)" class="ms-2 view-activity-log" data-task-id="<?php echo $serv_task->task_id;?>" title="Activity Log"><span class="fas fa-history"></span></a>
                    </td>
                    <td class="align-middle white-space-nowrap project ps-3 ">
                    <!--<select id="<?php echo $serv_task->task_id;?>" class="change_status">-->
                    <!--    <option value="" >----</option>-->
                    <!--    <option value="Doing" <?php if($serv_task->task_status == 'Doing'){echo 'selected';}?>>Doing </option>-->
                    <!--    <option value="To Do" <?php if($serv_task->task_status == 'To Do'){echo 'selected';}?>>To Do </option>-->
                    <!--    <option value="Completed" <?php if($serv_task->task_status == 'Completed'){echo 'selected';}?>>Completed </option>-->
                    <!--</select>-->
                    <td>
        <?php if($serv_task->task_status == 'In Progress'){ ?>
            <button class="btn btn-phoenix-primary btn-xs ready-for-testing" id="<?php echo $serv_task->task_id;?>">Ready for Testing</button>
        <?php } ?>
        <button class="btn btn-phoenix-info btn-xs log-time-btn" data-task-id="<?php echo $serv_task->task_id;?>">Log Fix Time</button>
    </td>
                    <td class="align-middle white-space-nowrap project ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $serv_task->service_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>
                    <td class="align-middle white-space-nowrap project ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $serv_task->project_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>

                    <td class="align-middle white-space-nowrap assign_by ps-3 ">
                       <p class="mb-0 fs-9 text-body"><?php if($serv_task->assignee != $serv_task->task_assign_by){$nameArray2 = explode(" ", $serv_task->emp_name);echo $nameArray2[0];}?></p>
                    </td>
                    <td><a id="<?php echo $serv_task->task_id;?>" class="delete badge badge-phoenix badge-phoenix-danger">Remove</td>
                  </tr>
              <?php }}?>
                </tbody>
              </table>
            </div>

            <br><br>
            </div>
        </div>

     <br>
     <div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">

        <div class="row g-3">
                <div class="col-auto">
                   <h4> To Do Task </h4>
                </div>

              </div><br>
<div id="tableExample3" >

    <div class="table-responsive ms-n1 ps-1 scrollbar">
              <table class="table table-sm fs-9 mb-0" >
                <thead class="text-body">
                  <tr>
                     <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="priority" >PRIORITY</th>

                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="task" >TASK</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="status" >STATUS</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="service" >SERVICE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="project" >PROJECT</th>
                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="assign_by" >ASSIGN BY</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="start_date" >START DATE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="end_date" >DEADLINE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col"  ></th>
                  </tr>
                </thead>
                <tbody class="list" >
                    <?php  if(!empty($todo_task_list)){
        foreach ($todo_task_list as $todo_task)
        {
        if($todo_task->priority == '1'){$color = 'danger';$text = 'Urgent';}else if($todo_task->priority == '2'){$color = 'warning';$text = 'High';}else if($todo_task->priority == '3'){$color = 'primary';$text = 'Normal';}else{$color = 'secondary';$text = 'Low';}
        ?>
                  <tr class="position-static">
                      <td class="align-middle white-space-nowrap align-middle priority"><span class="badge badge-phoenix fs-10 badge-phoenix-<?php echo $color;?>"><b><?php echo $text;?><span class="ms-1" data-feather="flag" style="height:12.8px;width:12.8px;"></b></span></td>
                    <td class="align-middle time white-space-nowrap ps-0 task ">
                        <a class="fw-bold " href="<?php echo base_url();?>edit-task/<?php echo $todo_task->task_id;?>"><?php echo substr($todo_task->task_heading, 0, 100);?></a>
                        <?php if($todo_task->open_issues_count > 0){ ?>
                            <a href="<?php echo base_url('task-issues/' . (int) $todo_task->task_id); ?>" class="badge badge-phoenix badge-phoenix-danger text-decoration-none">Issues: <?php echo (int) $todo_task->open_issues_count; ?></a>
                        <?php } ?>
                        <a href="javascript:void(0)" class="ms-2 view-activity-log" data-task-id="<?php echo $todo_task->task_id;?>" title="Activity Log"><span class="fas fa-history"></span></a>
                    </td>
                  <td class="align-middle white-space-nowrap project ps-3 ">
                    <select id="<?php echo $todo_task->task_id;?>" class="change_status">
                        <option value="" >----</option>
                        <option value="Doing" <?php if($todo_task->task_status == 'Doing'){echo 'selected';}?>>Doing </option>
                        <option value="Today" <?php if($todo_task->task_status == 'Today'){echo 'selected';}?>>Today </option>
                        <option value="Completed" <?php if($todo_task->task_status == 'Completed'){echo 'selected';}?>>Completed </option>
                    </select>
                </td>

                   <td class="align-middle white-space-nowrap project ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $todo_task->service_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>

                    <td class="align-middle white-space-nowrap project ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $todo_task->project_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>
                    <td class="align-middle white-space-nowrap assign_by ps-3 ">
                       <p class="mb-0 fs-9 text-body"><?php if($todo_task->assignee != $todo_task->task_assign_by){$nameArray2 = explode(" ", $todo_task->emp_name);echo $nameArray2[0];}?></p>
                    </td>
                    <td class="align-middle white-space-nowrap start_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($todo_task->task_start_date));?></p>
                    </td>
                    <td class="align-middle white-space-nowrap end_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($todo_task->task_end_date));?></p>
                    </td>
                    <td><a  id="<?php echo $todo_task->task_id;?>" class="delete badge badge-phoenix badge-phoenix-danger">Remove</td>
                  </tr>
              <?php }}?>
                </tbody>
              </table>
            </div>

            <br><br>
            </div>
        </div>
        <br>
     <div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">

        <div class="row g-3">
                <div class="col-auto">
                   <h4> Recurring Task </h4>
                </div>

              </div><br>
<div id="tableExample3" >

    <div class="table-responsive ms-n1 ps-1 scrollbar">
              <table class="table table-sm fs-9 mb-0" >
                <thead class="text-body">
                  <tr>
                     <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="priority" >PRIORITY</th>

                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="task" >TASK</th>

                    <th class="sort white-space-nowrap ps-3 align-middle ps-0" scope="col" data-sort="status" >STATUS</th>
                     <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="service" >SERVICE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="project" >PROJECT</th>
                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="assign_by" >ASSIGN BY</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="start_date" >START DATE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="end_date" >DEADLINE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col"  ></th>
                  </tr>
                </thead>
                <tbody class="list" >
                    <?php  if(!empty($my_recurring_task)){
        foreach ($my_recurring_task as $recurring_task)
        {
        if($recurring_task->priority == '1'){$color = 'danger';$text = 'Urgent';}else if($recurring_task->priority == '2'){$color = 'warning';$text = 'High';}else if($recurring_task->priority == '3'){$color = 'primary';$text = 'Normal';}else{$color = 'secondary';$text = 'Low';}
        ?>
                  <tr class="position-static">
                      <td class="align-middle white-space-nowrap align-middle priority"><span class="badge badge-phoenix fs-10 badge-phoenix-<?php echo $color;?>"><b><?php echo $text;?><span class="ms-1" data-feather="flag" style="height:12.8px;width:12.8px;"></b></span></td>
                    <td class="align-middle time white-space-nowrap ps-0 task ">
                        <a class="fw-bold " href="<?php echo base_url();?>edit-task/<?php echo $recurring_task->task_id;?>"><?php echo substr($recurring_task->task_heading, 0, 100);?></a>
                        <?php if($recurring_task->open_issues_count > 0){ ?>
                            <a href="<?php echo base_url('task-issues/' . (int) $recurring_task->task_id); ?>" class="badge badge-phoenix badge-phoenix-danger text-decoration-none">Issues: <?php echo (int) $recurring_task->open_issues_count; ?></a>
                        <?php } ?>
                        <a href="javascript:void(0)" class="ms-2 view-activity-log" data-task-id="<?php echo $recurring_task->task_id;?>" title="Activity Log"><span class="fas fa-history"></span></a>
                    </td>
                   <td class="align-middle white-space-nowrap project ps-3 ">
                       -
                    <!--<select id="<?php echo $recurring_task->task_id;?>" class="change_status">-->
                    <!--    <option value="" >----</option>-->
                    <!--    <option value="Doing" <?php if($recurring_task->task_status == 'Doing'){echo 'selected';}?>>Doing </option>-->
                    <!--    <option value="Today" <?php if($recurring_task->task_status == 'Today'){echo 'selected';}?>>Today </option>-->
                    <!--    <option value="To Do" <?php if($recurring_task->task_status == 'To Do'){echo 'selected';}?>>To Do </option>-->
                    <!--    <option value="Completed" <?php if($recurring_task->task_status == 'Completed'){echo 'selected';}?>>Completed </option>-->
                    <!--</select>-->
                </td>
                   <td class="align-middle white-space-nowrap project ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $recurring_task->service_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>

                    <td class="align-middle white-space-nowrap project ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $recurring_task->project_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>
                    <td class="align-middle white-space-nowrap assign_by ps-3 ">
                       <p class="mb-0 fs-9 text-body"><?php if($recurring_task->assignee != $recurring_task->task_assign_by){$nameArray2 = explode(" ", $recurring_task->emp_name);echo $nameArray2[0];}?></p>
                    </td>
                    <td class="align-middle white-space-nowrap start_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($recurring_task->task_start_date));?></p>
                    </td>
                    <td class="align-middle white-space-nowrap end_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($recurring_task->task_end_date));?></p>
                    </td>
                    <td><a id="<?php echo $recurring_task->task_id;?>" class="delete badge badge-phoenix badge-phoenix-danger">Remove</td>
                  </tr>
              <?php }}?>
                </tbody>
              </table>
            </div>

            <br><br>
            </div>
        </div>


    <?php include('common/footer.php');?>



