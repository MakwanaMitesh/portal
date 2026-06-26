<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');

    $has_task_rows = !empty($my_task_list);
?>

<div class="mb-4">
<br>

<div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">



        <?php if (!empty($flash_success)) { ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($flash_success); ?></div>
        <?php } ?>
        <?php if (!empty($flash_error)) { ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($flash_error); ?></div>
        <?php } ?>

<div class="row g-3">

                <div class="col-auto">

                   <h4> <?php echo $list;?> Task </h4>
                   <?php if (!empty($is_tester_qa_panel) && !empty($developers)) { ?>
                   <p class="fs-9 text-body-secondary mb-0">
                     <?php
                     $qa_hints = [
                         'Ready for Testing' => 'Tasks waiting for QA. Add issues, log time, or mark complete when verified.',
                         'Pending' => 'Tasks with developer (Pending / In Progress). Use Need discussion or move back to QA when ready to retest.',
                         'Need Discussion' => 'Blocked tasks — use Reopen for testing when discussion is resolved.',
                         'Completed' => 'QA completed tasks. Use Reopen if testing is needed again.',
                     ];
                     echo $qa_hints[$list] ?? 'QA task queue.';
                     ?>
                   </p>
                   <?php } elseif (!empty($is_testing_queue)) { ?>
                   <p class="fs-9 text-body-secondary mb-0">Your tasks sent to the testing team.</p>
                   <?php } elseif ($list === 'In Progress') { ?>
                   <p class="fs-9 text-body-secondary mb-0">
                     Main tasks only. <strong>Ready for Testing</strong> requires development time. Use <strong>Show issues</strong> to review tester reports.
                   </p>
                   <?php } elseif (!empty($is_tester_qa_panel)) { ?>
                   <p class="fs-9 text-body-secondary mb-0">Main tasks only — issues are listed under <strong>Show issues</strong>, not as extra rows here.</p>
                   <?php } elseif (in_array($list, ['Pending', 'Today', 'To Do', 'Completed'], true)) { ?>
                   <p class="fs-9 text-body-secondary mb-0">Main tasks only (one row per task). Open <strong>Show issues</strong> to see tester-reported issues.</p>
                   <?php } ?>

                </div>

                <div class="col-auto scrollbar overflow-hidden-y flex-grow-1"></div>

                <div class="col-auto"><button data-bs-toggle="modal" data-bs-target="#add_task_modal" class="btn btn-subtle-primary me-1 mb-1 "><span class="fas fa-plus me-2"></span>Add Task</button></div>

              </div>

<div id="taskListWrapper" <?php if ($has_task_rows) { ?>data-list='{"valueNames":["task","project","priority","start_date","end_date","assign_by"],"page":100,"pagination":true}'<?php } ?>>

  <?php if ($has_task_rows) { ?>
  <div class="search-box mb-3 ">
    <form class="position-relative" data-bs-toggle="search" data-bs-display="static"><input class="form-control search-input search form-control-sm" type="search" placeholder="Search" aria-label="Search" />
      <span class="fas fa-search search-box-icon"></span>
    </form>
  </div>
  <?php } ?>

<div class="table-responsive ms-n1 ps-1 scrollbar">

              <table class="table table-sm fs-9 mb-0" id="userTable_admin">

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

                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" ></th>

                  </tr>

                </thead>

                <tbody class="list" id="taskListBody">



                    <?php  if(!empty($my_task_list)){

        foreach ($my_task_list as $serv_task)
        {

        if($serv_task->priority == '1'){$color = 'danger';$text = 'Urgent';}else if($serv_task->priority == '2'){$color = 'warning';$text = 'High';}else if($serv_task->priority == '3'){$color = 'primary';$text = 'Normal';}else{$color = 'secondary';$text = 'Low';}

        ?>

                  <tr class="position-static">

                      <td class="align-middle white-space-nowrap align-middle priority"><span class="badge badge-phoenix fs-10 badge-phoenix-<?php echo $color;?>"><b><?php echo $text;?><span class="ms-1" data-feather="flag" style="height:12.8px;width:12.8px;"></b></span></td>

                    <td class="align-middle time white-space-nowrap ps-0 task ">
                        <?php if (!empty($is_testing_queue) || $list === 'In Progress') { ?>
                        <span class="badge badge-phoenix badge-phoenix-<?php echo $list === 'In Progress' ? 'success' : 'warning'; ?>"><?php echo $serv_task->task_status;?></span> &nbsp;
                        <?php } ?>
                        <a class="fw-bold " href="<?php echo base_url();?>edit-task/<?php echo $serv_task->task_id;?>"><?php echo substr($serv_task->task_heading, 0, 100);?></a>
                        <?php if ((int) ($serv_task->total_issues_count ?? $serv_task->open_issues_count ?? 0) > 0) { ?>
                            <a href="<?php echo base_url('task-issues/' . (int) $serv_task->task_id); ?>" class="badge badge-phoenix badge-phoenix-danger text-decoration-none" title="View all issues">
                                <?php echo (int) $serv_task->open_issues_count; ?> open / <?php echo (int) ($serv_task->total_issues_count ?? 0); ?> total
                            </a>
                        <?php } ?>
                        <a href="javascript:void(0)" class="ms-2 view-activity-log" data-task-id="<?php echo $serv_task->task_id;?>" title="Activity Log"><span class="fas fa-history"></span></a>
                    </td>



                     <td class="align-middle white-space-nowrap project ps-3 ">
                         <?php if (!empty($is_tester_qa_panel) && !empty($developers)) { ?>
                    <select class="form-select form-select-sm tester-change-status" data-task-id="<?php echo $serv_task->task_id; ?>" style="min-width:140px;">
                        <option value="Ready for Testing" <?php echo $serv_task->task_status === 'Ready for Testing' ? 'selected' : ''; ?>>Ready for Testing</option>
                        <option value="Pending" <?php echo $serv_task->task_status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo $serv_task->task_status === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Need Discussion" <?php echo $serv_task->task_status === 'Need Discussion' ? 'selected' : ''; ?>>Need Discussion</option>
                        <option value="Completed" <?php echo $serv_task->task_status === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                         <?php } elseif ($serv_task->task_type != 'Recurring' && empty($is_testing_queue)) { ?>
                    <select id="<?php echo $serv_task->task_id;?>" class="change_status">
                        <option value="" >----</option>
                        <option value="Pending" <?php if($serv_task->task_status == 'Pending'){echo 'selected';}?>>Pending</option>
                        <option value="In Progress" <?php if($serv_task->task_status == 'In Progress'){echo 'selected';}?>>In Progress </option>
                        <option value="Ready for Testing" <?php if($serv_task->task_status == 'Ready for Testing'){echo 'selected';}?>>Ready for Testing </option>
                        <option value="Need Discussion" <?php if($serv_task->task_status == 'Need Discussion'){echo 'selected';}?>>Need Discussion</option>
                        <option value="Completed" <?php if($serv_task->task_status == 'Completed'){echo 'selected';}?>>Completed </option>
                    </select>
                         <?php } ?>
                </td>



                   <td class="align-middle white-space-nowrap project ps-3 ">

                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $serv_task->service_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>

                    </td>

                    <td class="align-middle white-space-nowrap project ps-3 ">

                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php echo $serv_task->project_name;?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>

                    </td>

                    <td class="align-middle white-space-nowrap assign_by ps-3 ">

                        <?php if (!empty($is_testing_queue) && !empty($developers)) { ?>
                      <p class="mb-0 fs-9 text-body"><?php echo $serv_task->developer_name ?? ''; ?></p>
                        <?php } elseif ($serv_task->task_assign_by != $empnumber) { ?>
                      <p class="mb-0 fs-9 text-body"><?php echo $serv_task->emp_name;?></p>
                        <?php } ?>

                    </td>

                    <td class="align-middle white-space-nowrap start_date ps-3 ">

                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($serv_task->task_start_date));?></p>

                    </td>

                    <td class="align-middle white-space-nowrap end_date ps-3 ">

                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($serv_task->task_end_date));?></p>

                    </td>

                    <td>
                        <?php if (!empty($is_tester_qa_panel) && !empty($developers)) { ?>
                            <?php $this->load->view('task_management/partials/testing_qa_task_actions', ['serv_task' => $serv_task]); ?>
                        <?php } else { ?>
                            <?php $this->load->view('task_management/partials/developer_task_actions', ['task' => $serv_task]); ?>
                        <?php } ?>
                    </td>

                  </tr>

              <?php }}?>

              <?php if (!$has_task_rows) { ?>
                  <tr>
                    <td colspan="8" class="text-center py-4 text-body-secondary">
                      <?php if (!empty($is_tester_qa_panel)) { ?>
                        No tasks in this QA queue.
                      <?php } elseif ($list === 'Ready for Testing') { ?>
                        No tasks ready for testing right now.
                      <?php } else { ?>
                        No tasks found in this list.
                      <?php } ?>
                    </td>
                  </tr>
              <?php } ?>

                </tbody>

              </table>

            </div>

            <?php if ($has_task_rows) { ?>
            <div class="d-flex flex-wrap align-items-center justify-content-between py-3 pe-0 fs-9 border-bottom border-translucent">
              <div class="d-flex">
                <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p>
                <a class="fw-semibold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                <a class="fw-semibold d-none" href="#!" data-list-view="less">View Less<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
              </div>
              <div class="d-flex">
                <button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="mb-0 pagination"></ul>
                <button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
              </div>
            </div>
            <?php } ?>

            <br><br>

            </div>

        </div>



      </div>

<?php
if (!empty($is_tester_qa_panel) && !empty($developers)) {
    include(APPPATH.'views/task_management/partials/testing_finalize_modal.php');
    $load_testing_queue_scripts = true;
}
?>

<?php include(APPPATH.'views/common/footer.php');?>



