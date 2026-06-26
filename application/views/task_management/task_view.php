<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
    $today = date('Y-m-d');
    //echo date('Y-m-d', strtotime($today. ' + 1 week'))
?>

<div class="mb-4">
              <div class="row g-3">
                <div class="col-auto">
                   <h4> <?php echo $project_name;?> <span data-feather="chevrons-right" style="height: 30px; width: 30px;"></span> <span class="small_link"><?php echo $service_name;?></span> <a class="badge text-bg-info" href="edit-service?serviceid=<?php echo $get_service_details->service_id;?>&projectid=<?php echo $get_service_details->project_id;?>" >Edit <span  data-feather="edit" style=" width: 11px;"></span></a></h4>
                   
                   <?php  if($get_service_details->assignees != ''){?>
                   <h6>Project Associates</h6>
                   <?php 
                    
                    $array = explode(",", $get_service_details->assignees);
                    foreach ($array as $emp) {
                            $this->db->select("*");
		                    $this->db->from('employees');  
		                    $this->db->where('employee_no',$emp); 
		                    $querypci = $this->db->get();
		           foreach($querypci->result() as $rowci)
		{
		    echo '<span class="badge text-bg-secondary">'.$rowci->name.'</span>&nbsp;'; 
		}
                    }} ?>
                    
                </div>
                <div class="col-auto scrollbar overflow-hidden-y flex-grow-1"></div>
                <div class="col-auto"><button data-bs-toggle="modal" data-bs-target="#add_task_modal" class="btn btn-subtle-primary me-1 mb-1 "><span class="fas fa-plus me-2"></span>Add Task</button></div>
              </div>

<br>
<div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">
<h3 style="margin-bottom:15px">Today's Task</h3>
<div id="tableExample3" data-list='{"valueNames":["task","task_type","assinee","priority","start_date","end_date","assign_by"],"page":10,"pagination":true}'>
  <div class="search-box mb-3 ">
    <form class="position-relative" data-bs-toggle="search" data-bs-display="static"><input class="form-control search-input search form-control-sm" type="search" placeholder="Search" aria-label="Search" />
      <span class="fas fa-search search-box-icon"></span>
    </form>
  </div>
<div class="table-responsive ms-n1 ps-1 scrollbar">
              <table class="table table-sm fs-9 mb-0" id="userTable_admin">
                <thead class="text-body">
                  <tr>
                     <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="priority" style="width:8%;">Priority</th> 
                    
                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="task" style="width:50%;">TASK</th>
                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="task_type" style="width:10%;">TASK TYPE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="assinee" style="width:15%;">ASSIGNESS</th>
                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="assign_by" >ASSIGN BY</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="start_date" style="width:10%;">START DATE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="end_date" style="width:15%;">DEADLINE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col"  style="width:5%;"></th>
                  </tr>
                </thead>
                <tbody class="list" id="tableExample3">
                    <?php  if(!empty($todays_task)){
        foreach ($todays_task as $serv_task)
        { 
        if($serv_task->priority == '1'){$color = 'danger';$text = 'Urgent';}else if($serv_task->priority == '2'){$color = 'warning';$text = 'High';}else if($serv_task->priority == '3'){$color = 'primary';$text = 'Normal';}else{$color = 'secondary';$text = 'Low';}
        ?>
                  <tr class="position-static">
                      <td class="align-middle white-space-nowrap align-middle priority"><span class="badge badge-phoenix fs-10 badge-phoenix-<?php echo $color;?>"><b><?php echo $text;?><span class="ms-1" data-feather="flag" style="height:12.8px;width:12.8px;"></b></span></td>
                    <td class="align-middle time white-space-nowrap ps-0 task "><a class="fw-bold " href="<?php echo base_url();?>edit-task/<?php echo $serv_task->task_id;?>"><?php echo substr($serv_task->task_heading, 0, 100);?></a></td>
                   <td lass="align-middle time white-space-nowrap ps-0 task_type "><?php echo $serv_task->task_type;?></td>
                   
                    <td class="align-middle white-space-nowrap assinee ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php $name2 = explode(" ", $serv_task->name);echo $name2[0];?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>
                     <td class="align-middle white-space-nowrap assign_by ps-3 ">
                        
                      <p class="mb-0 fs-9 text-body"><?php if($serv_task->assignee != $serv_task->task_assign_by){$nameArray2 = explode(" ", $serv_task->assign_by_name);echo $nameArray2[0];}?></p>
                      
                    </td>
                    <td class="align-middle white-space-nowrap start_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($serv_task->task_start_date));?></p>
                    </td>
                    <td class="align-middle white-space-nowrap end_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($serv_task->task_end_date));?></p>
                    </td>
                    <td><a href="#" id="<?php echo $serv_task->task_id;?>" class="delete badge badge-phoenix badge-phoenix-danger">Remove</td>
                  </tr>
              <?php }}?>    
                </tbody>
              </table>
            </div>
            <div class="d-flex flex-wrap align-items-center justify-content-between py-3 pe-0 fs-9 border-bottom border-translucent">
              <div class="d-flex">
                <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p><a class="fw-semibold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semibold d-none" href="#!" data-list-view="less">View Less<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
              </div>
              <div class="d-flex"><button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="mb-0 pagination"></ul><button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
              </div>
            </div>
            <br><br>
            </div>
        </div>
        
        
        
<br>
<div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">
<h3 style="margin-bottom:15px">To Do Task</h3>
<div id="tableExample" data-list='{"valueNames":["task","assinee","priority","start_date","end_date","assign_by"],"page":10,"pagination":true}'>
  <div class="search-box mb-3 ">
    <form class="position-relative" data-bs-toggle="search" data-bs-display="static"><input class="form-control search-input search form-control-sm" type="search" placeholder="Search" aria-label="Search" />
      <span class="fas fa-search search-box-icon"></span>
    </form>
  </div>
<div class="table-responsive ms-n1 ps-1 scrollbar">
              <table class="table table-sm fs-9 mb-0" >
                <thead class="text-body">
                  <tr>
                     <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="priority" style="width:8%;">Priority</th> 
                    
                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="task" style="width:50%;">TASK</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="assinee" style="width:15%;">ASSIGNESS</th>
                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="assign_by" >ASSIGN BY</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="start_date" style="width:10%;">START DATE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="end_date" style="width:15%;">DEADLINE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col"  style="width:5%;"></th>
                  </tr>
                </thead>
                <tbody class="list" id="tableExample">
                    <?php  if(!empty($todo_task)){
        foreach ($todo_task as $todotask)
        { 
        if($todotask->priority == '1'){$color = 'danger';$text = 'Urgent';}else if($todotask->priority == '2'){$color = 'warning';$text = 'High';}else if($todotask->priority == '3'){$color = 'primary';$text = 'Normal';}else{$color = 'secondary';$text = 'Low';}
        ?>
                  <tr class="position-static">
                      <td class="align-middle white-space-nowrap align-middle priority"><span class="badge badge-phoenix fs-10 badge-phoenix-<?php echo $color;?>"><b><?php echo $text;?><span class="ms-1" data-feather="flag" style="height:12.8px;width:12.8px;"></b></span></td>
                    <td class="align-middle time white-space-nowrap ps-0 task "><a class="fw-bold " href="<?php echo base_url();?>edit-task/<?php echo $todotask->task_id;?>"><?php echo substr($todotask->task_heading, 0, 100);?></a></td>
                    <td class="align-middle white-space-nowrap assinee ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php  $name1 = explode(" ", $todotask->name);echo $name1[0];?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>
                     <td class="align-middle white-space-nowrap assign_by ps-3 ">
                        
                      <p class="mb-0 fs-9 text-body"><?php if($todotask->assignee != $todotask->task_assign_by){$nameArray1 = explode(" ", $todotask->assign_by_name);echo $nameArray1[0];}?></p>
                      
                    </td>
                    <td class="align-middle white-space-nowrap start_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($todotask->task_start_date));?></p>
                    </td>
                    <td class="align-middle white-space-nowrap end_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($todotask->task_end_date));?></p>
                    </td>
                    <td><a href="#" id="<?php echo $todotask->task_id;?>" class="delete badge badge-phoenix badge-phoenix-danger">Remove</td>
                  </tr>
              <?php }}?>    
                </tbody>
              </table>
            </div>
            <div class="d-flex flex-wrap align-items-center justify-content-between py-3 pe-0 fs-9 border-bottom border-translucent">
              <div class="d-flex">
                <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p><a class="fw-semibold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semibold d-none" href="#!" data-list-view="less">View Less<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
              </div>
              <div class="d-flex"><button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="mb-0 pagination"></ul><button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
              </div>
            </div>
            <br><br>
            </div>
        </div>
        

<br>
<div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">
<h3 style="margin-bottom:15px">Completed Task</h3>
<div id="tableExample" data-list='{"valueNames":["task","assinee","priority","start_date","end_date","assign_by"],"page":10,"pagination":true}'>
  <div class="search-box mb-3 ">
    <form class="position-relative" data-bs-toggle="search" data-bs-display="static"><input class="form-control search-input search form-control-sm" type="search" placeholder="Search" aria-label="Search" />
      <span class="fas fa-search search-box-icon"></span>
    </form>
  </div>
<div class="table-responsive ms-n1 ps-1 scrollbar">
              <table class="table table-sm fs-9 mb-0" >
                <thead class="text-body">
                  <tr>
                     <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="priority" >Priority</th> 
                    
                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="task" >TASK</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="assinee" >ASSIGNESS</th>
                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="assign_by" >ASSIGN BY</th>
                    <th class="sort white-space-nowrap align-middle ps-0" scope="col" data-sort="hrs_min" >Hrs-min</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="start_date" >START DATE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" data-sort="end_date" >DEADLINE</th>
                    <th class="sort align-middle ps-3 white-space-nowrap" scope="col" ></th>
                  </tr>
                </thead>
                <tbody class="list" id="tableExample">
                    <?php  if(!empty($complete_task)){
        foreach ($complete_task as $completetask)
        { 
        if($completetask->priority == '1'){$color = 'danger';$text = 'Urgent';}else if($completetask->priority == '2'){$color = 'warning';$text = 'High';}else if($completetask->priority == '3'){$color = 'primary';$text = 'Normal';}else{$color = 'secondary';$text = 'Low';}
        ?>
                  <tr class="position-static">
                      <td class="align-middle white-space-nowrap align-middle priority"><span class="badge badge-phoenix fs-10 badge-phoenix-<?php echo $color;?>"><b><?php echo $text;?><span class="ms-1" data-feather="flag" style="height:12.8px;width:12.8px;"></b></span></td>
                    <td class="align-middle time white-space-nowrap ps-0 task "><a class="fw-bold " href="<?php echo base_url();?>edit-task/<?php echo $completetask->task_id;?>"><?php echo substr($completetask->task_heading, 0, 100);?></a></td>
                    <td class="align-middle white-space-nowrap assinee ps-3 ">
                      <span class="badge badge-phoenix fs-10 badge-phoenix-secondary"><span class="badge-label"><?php $name3 = explode(" ", $completetask->name);echo $name3[0];?></span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span>
                    </td>
                    <td class="align-middle white-space-nowrap assign_by ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php  if($completetask->assignee != $completetask->task_assign_by){ $nameArray = explode(" ", $completetask->assign_by_name);echo $nameArray[0];}?></p>
                    </td>
                    <td class=" white-space-nowrap assign_by ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo $completetask->allotted_hrs.' hrs '.$completetask->allotted_min.' min ';?></p>
                    </td>
                    <td class="align-middle white-space-nowrap start_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($completetask->task_start_date));?></p>
                    </td>
                    <td class="align-middle white-space-nowrap end_date ps-3 ">
                      <p class="mb-0 fs-9 text-body"><?php echo date("M d, Y", strtotime($completetask->task_end_date));?></p>
                    </td>
                    <td><a href="#" id="<?php echo $completetask->task_id;?>" class="delete badge badge-phoenix badge-phoenix-danger">Remove</td>
                  </tr>
              <?php }}?>    
                </tbody>
              </table>
            </div>
            <div class="d-flex flex-wrap align-items-center justify-content-between py-3 pe-0 fs-9 border-bottom border-translucent">
              <div class="d-flex">
                <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p><a class="fw-semibold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semibold d-none" href="#!" data-list-view="less">View Less<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
              </div>
              <div class="d-flex"><button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="mb-0 pagination"></ul><button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
              </div>
            </div>
            <br><br>
            </div>
        </div>
<?php include(APPPATH.'views/common/footer.php');?>
