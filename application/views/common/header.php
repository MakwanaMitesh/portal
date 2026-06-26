<?php
    $base_url = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);

    if($this->session->userdata('user_id') == NULL){
      echo "<meta http-equiv='refresh' content='0; URL=".base_url()."' />";
    }

    error_reporting(0);

    $dept = '';
    $empnumber=$this->session->userdata('user_id') ;
    $uri = $_SERVER['REQUEST_URI'];

    if(isset($get_login_user) && !empty($get_login_user)) {
      $dept = strtolower(trim($get_login_user->department));
    }

    if(isset($get_login_user->join_date) && $get_login_user->join_date!='') {
			$joining_date = $this->Common_model->formatDateWithSuffix($get_login_user->join_date);
		}
?>
<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="en-US" dir="ltr" data-bs-theme="<?php echo $get_login_user->portal_theme;?>">

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sanpurple Employee Portal</title>
    <meta name="theme-color" content="#ffffff">
    <script src="<?php echo base_url();?>vendors/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="<?php echo base_url();?>vendors/simplebar/simplebar.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/config.js"></script>

    <link rel="prechonnect" href="https://fonts.googleapis.com/">
    <link rel="prechonnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="<?php echo base_url();?>vendors/simplebar/simplebar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/line.css">
    <link href="<?php echo base_url();?>assets/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="<?php echo base_url();?>assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
    <link href="<?php echo base_url();?>assets/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="<?php echo base_url();?>assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
    <link href="<?php echo base_url();?>assets/css/mystyle.css?v=<?php echo uniqid();?>" type="text/css" rel="stylesheet" >
    <link href="<?php echo base_url();?>vendors/choices/choices.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>vendors/prism/prism-okaidia.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
    <link href="<?php echo base_url();?>vendors/flatpickr/flatpickr.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script>
      var phoenixIsRTL = window.config.config.phoenixIsRTL;
      if (phoenixIsRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
      } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
      }
    </script>
    <link href="<?php echo base_url();?>vendors/leaflet/leaflet.css" rel="stylesheet">
    <link href="<?php echo base_url();?>vendors/leaflet.markercluster/MarkerCluster.css" rel="stylesheet">
    <link href="<?php echo base_url();?>vendors/leaflet.markercluster/MarkerCluster.Default.css" rel="stylesheet">

  </head>

  <body>

    <main class="main" id="top">
      <nav class="navbar navbar-vertical navbar-expand-lg" >
        <script>
          var navbarStyle = window.config.config.phoenixNavbarStyle;
          if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('body').classList.add(`navbar-${navbarStyle}`);
          }
        </script>
        <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
          <!-- scrollbar removed-->
          <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
              <li class="nav-item">
                <div class="nav-item-wrapper"><a class="nav-link label-1" href="<?php echo base_url();?>dashboard" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="home"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Dashboard</span></span></div>
                  </a>
                </div>

                <div class="nav-item-wrapper">
                    <a class="nav-link dropdown-indicator label-1" href="#nv-home" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-home">
                        <div class="d-flex align-items-center">
                          <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="pie-chart"></span></span><span class="nav-link-text">Task</span>
                        </div>
                    </a>
                    <div class="parent-wrapper label-1">
                    <ul class="nav collapse parent show" data-bs-parent="#navbarVerticalCollapse" id="nv-home">
                      <li class="collapsed-nav-item-title d-none">Task</li>
                      <li class="nav-item"><a class="nav-link " href="<?php echo base_url();?>my-task/Today" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Today's Task</span></div>
                        </a>
                      </li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>my-task/To Do" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">To Do Task</span></div>
                        </a>
                      </li>
                      <?php if (strtolower(trim($get_login_user->department ?? '')) !== 'software testing') { ?>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url('my-task/' . rawurlencode('Pending')); ?>" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Pending Task</span></div>
                        </a>
                      </li>
                      <?php } ?>
                      <?php if (strtolower(trim($get_login_user->department ?? '')) !== 'software testing') { ?>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url('my-task/' . rawurlencode('In Progress')); ?>" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Issues to fix</span></div>
                        </a>
                      </li>
                      <?php } ?>
                      <?php if (strtolower(trim($get_login_user->department ?? '')) === 'software testing' || strtolower($get_login_user->admin_section ?? '') === 'yes') { ?>
                      <li class="collapsed-nav-item-title d-none">QA Testing</li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url('my-task/' . rawurlencode('Ready for Testing')); ?>">
                          <div class="d-flex align-items-center"><span class="nav-link-text text-primary">Ready for Testing</span></div>
                        </a></li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url('my-task/' . rawurlencode('Pending')); ?>">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Pending / With developer</span></div>
                        </a></li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url('my-task/' . rawurlencode('Need Discussion')); ?>">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Need Discussion</span></div>
                        </a></li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url('my-task/' . rawurlencode('Completed')); ?>">
                          <div class="d-flex align-items-center"><span class="nav-link-text">QA Completed</span></div>
                        </a></li>
                      <?php } ?>
                      <?php if (strtolower(trim($get_login_user->department ?? '')) !== 'software testing') { ?>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>my-task/Completed" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Completed Task</span></div>
                        </a>
                      </li>
                      <?php } ?>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>my-task/Recurring" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Recurring Task</span></div>
                        </a>
                      </li>
                      <?php if(strtolower($get_login_user->admin_section ?? '') == 'yes'){ ?>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>testing-dashboard" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Testing Dashboard</span></div>
                        </a>
                      </li>
                      <?php } ?>
                      <?php
                        $ci =& get_instance();
                        $ci->load->model('Common_model');
                        if ($ci->Common_model->can_manage_modules($get_login_user)) {
                      ?>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>manage-modules" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Manage Modules</span></div>
                        </a>
                      </li>
                      <?php } ?>
                    </ul>
                    </div>
                </div>
              </li>

            <li class="nav-item">
                <div class="nav-item-wrapper">
                    <a class="nav-link label-1" href="#" role="button" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="folder"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Projects</span></span></div>
                    </a>
                </div>
                <!--<div class="nav-item-wrapper">-->
                <!--    <a class="nav-link dropdown-indicator label-1" href="#nv-project" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-home">-->
                <!--        <div class="d-flex align-items-center">-->
                <!--            <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="folder"></span></span><span class="nav-link-text">Projects</span>-->
                <!--        </div>-->
                <!--    </a>-->
                <!--    <?php if(!empty($projects_list)) { ?>-->
                <!--    <div class="parent-wrapper label-1">-->
                <!--        <ul class="nav collapse parent show" data-bs-parent="#navbarVerticalCollapse" id="nv-project">-->
                <!--            <li class="collapsed-nav-item-title d-none">Projects</li>-->
                <!--            <?php foreach($projects_list as $project) { ?>-->
                <!--                <li class="nav-item" id="<?php echo 'project_'.$project->id; ?>">-->
                <!--                    <a class="nav-link" href="#" data-bs-toggle="" aria-expanded="false">-->
                <!--                        <div class="d-flex align-items-center">-->
                <!--                            <span class="nav-link-text"><?php echo $project->project_name; ?></span>-->
                <!--                        </div>-->
                <!--                    </a>-->
                <!--                </li>-->
                <!--            <?php } ?>-->
                <!--        </ul>-->
                <!--    </div>-->
                <!--    <?php } ?>-->
                <!--</div>-->
            </li>

                <hr class="navbar-vertical-line" />

                 <?php
                  $this->db->select("*,project_services.project_id as porj_id");
                  $this->db->from('project_services');
                  $this->db->join('project_list', 'project_services.project_id  = project_list.project_id');
                  $this->db->where('assignees like','%'.$get_login_user->employee_no.'%' );
                  $this->db->group_by('project_services.project_id' );
                  $qu_pro = $this->db->get();
                  $totals = $qu_pro->num_rows();

                        if($totals >0){
                        foreach($qu_pro->result() as $prorows)
                        {
                            $this->db->select("*");
		                    $this->db->from('project_services');
	$this->db->where('project_id',$prorows->porj_id );
	$this->db->where('assignees like','%'.$get_login_user->employee_no.'%' );
		                    $querypci = $this->db->get();
		                   // $totalres = $querypci->num_rows() ;
                        ?>
                  <div class="nav-item-wrapper parentchild1">
                    <a class="nav-link dropdown-indicator label-1 " href="#nv-<?php echo $prorows->porj_id;?>" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-<?php echo $prorows->porj_id;?>">
                    <div class="d-flex align-items-center ">

                      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
                      <span class="nav-link-icon"><span data-feather="package"></span></span>
                      <span class="nav-link-text"><?php echo $prorows->project_name;?> </span>

                    </div>
                  </a>

                  <div class="parent-wrapper label-1">
                    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-<?php echo $prorows->porj_id;?>">
                        <li class="collapsed-nav-item-title d-none"><?php echo $prorows->project_name;?></li>
                      <?php

		foreach($querypci->result() as $rowci)
		{
			 ?>
                      <li class="nav-item"><a class="nav-link small_link" href="<?php echo base_url();?>task-view?serv=<?php echo $rowci->service_name?>&serv_id=<?php echo $rowci->service_id ?>&proj=<?php echo $prorows->project_name;?>&proj_id=<?php echo $prorows->project_id;?>" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text"><span data-feather="corner-down-right"></span>&nbsp;&nbsp;<?php echo $rowci->service_name?></span></div>
                        </a>
                      </li>
              <?php } ?>
                    </ul>
                  </div>

                  <a href="<?php echo base_url();?>project-detail/<?php echo $prorows->porj_id;?>" class="add_protimeline_icon btn btn-subtle-warning " data-bs-toggle="tooltip" data-bs-placement="top" title="Project Timeline" id="<?php echo $prorows->porj_id;?>" ><span data-feather="eye"></span></a>
                  <button class="add_service_icon btn btn-subtle-primary " data-bs-toggle="tooltip" data-bs-placement="top" title="Add Service" id="<?php echo $prorows->porj_id;?>" onClick="reply_click(this.id)"><span data-feather="plus-square"></span></button>
                </div>

                <?php }} ?>


              </li>

              <?php if($get_login_user->admin_section == 'yes'){?>

              <li class="nav-item">
                <!-- label-->
                <p class="navbar-vertical-label">Admin Section</p>
                <hr class="navbar-vertical-line" />

                <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-project-management" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-project-management">
                    <div class="d-flex align-items-center">
                      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="clipboard"></span></span><span class="nav-link-text">Projects management</span>
                    </div>
                  </a>
                  <div class="parent-wrapper label-1">
                    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-project-management">
                      <li class="collapsed-nav-item-title d-none">Projects management</li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>create-project" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Create new Project</span></div>
                        </a><!-- more inner pages-->
                      </li>

                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>project-list" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Projects list View</span></div>
                        </a><!-- more inner pages-->
                      </li>

                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>additional-hrsrequest" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Extra Hours Request</span></div>
                        </a><!-- more inner pages-->
                      </li>

                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>task-overview" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Task Overview</span></div>
                        </a><!-- more inner pages-->
                      </li>

                    </ul>
                  </div>
                </div>

                <div class="nav-item-wrapper">
                  <a class="nav-link dropdown-indicator label-1" href="#nv-employee-management" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-employee-management">
                    <div class="d-flex align-items-center">
                      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="user"></span></span><span class="nav-link-text">Employees management</span>
                    </div>
                  </a>

                  <div class="parent-wrapper label-1">
                    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-employee-management">
                      <li class="collapsed-nav-item-title d-none">Employees management</li>
                      <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url();?>create-employee" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Create New</span></div>
                        </a>
                      </li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>employee-list" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Employee list View</span></div>
                        </a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Employee Attendence</span></div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>

                <!-- <div class="nav-item-wrapper">
                  <a class="nav-link" href="<?php echo base_url(); ?>team-management" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-text">Team Management</span></div>
                  </a>
                </div> -->

              </li>
              <?php }?>


              <li class="nav-item">
                <!-- label-->
                <p class="navbar-vertical-label">Common Section</p>
                <hr class="navbar-vertical-line" />
                <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-myAttendence" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-myAttendence">
                    <div class="d-flex align-items-center">
                      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="clock"></span></span><span class="nav-link-text">My Attendence</span>
                    </div>
                  </a>
                  <div class="parent-wrapper label-1">
                    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-myAttendence">
                      <li class="collapsed-nav-item-title d-none">My Attendence</li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>daily-attendance" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Daily Attendence</span></div>
                        </a><!-- more inner pages-->
                      </li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>attendance-history" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">History</span></div>
                        </a><!-- more inner pages-->
                      </li>
                    </ul>
                  </div>
                </div><!-- parent pages-->
                <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-leave" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-leave">
                    <div class="d-flex align-items-center">
                      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="briefcase"></span></span><span class="nav-link-text">Leave Request</span><span class="fa-solid fa-circle text-info ms-1 new-page-indicator" style="font-size: 6px"></span>
                    </div>
                  </a>
                  <div class="parent-wrapper label-1">
                    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-leave">
                      <li class="collapsed-nav-item-title d-none">Leave Request</li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>leave-request" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Apply for leave</span></div>
                        </a><!-- more inner pages-->
                      </li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>my-leave-history" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">leave History</span></div>
                        </a><!-- more inner pages-->
                      </li>
                    </ul>
                  </div>
                </div><!-- parent pages-->
             </li>

             <?php if($get_login_user->hr_section == 'yes'){ ?>
             <li class="nav-item">
                <!-- label-->
                <p class="navbar-vertical-label">HR Section</p>
                <hr class="navbar-vertical-line" />
                <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-employee-management" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-employee-management">
                    <div class="d-flex align-items-center">
                      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="user"></span></span><span class="nav-link-text">Employees management</span>
                    </div>
                  </a>
                  <div class="parent-wrapper label-1">
                    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-employee-management">
                      <li class="collapsed-nav-item-title d-none">Employees management</li>


                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>create-employee" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Create New</span></div>
                        </a><!-- more inner pages-->
                      </li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>employee-list" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Employee list View</span></div>
                        </a><!-- more inner pages-->
                      </li>

                      <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Employee Attendence</span></div>
                        </a><!-- more inner pages-->
                      </li>

                    </ul>
                  </div>
                </div>
                 <div class="nav-item-wrapper"><a class="nav-link label-1" href="<?php echo base_url();?>all-emp_attendance-counts" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="clock"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Employees Attendance Count</span></span></div>
                  </a></div>
                 <div class="nav-item-wrapper"><a class="nav-link label-1" href="<?php echo base_url();?>employees-leave-request" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="briefcase"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Employees Leave Request</span></span></div>
                  </a></div>

                  <div class="nav-item-wrapper"><a class="nav-link label-1" href="<?php echo base_url();?>task-overview" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="users"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Employees Task Overview</span></span></div>
                  </a></div>
             </li>


              <?php } ?>

              <?php if($get_login_user->tl_section == 'yes'){ ?>
             <li class="nav-item">
                <!-- label-->
                <p class="navbar-vertical-label">TL Section</p>
                <hr class="navbar-vertical-line" />

                 <div class="nav-item-wrapper"><a class="nav-link label-1" href="<?php echo base_url();?>task-overview" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="users"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Employees Task Overview</span></span></div>
                  </a></div>

                 <div class="nav-item-wrapper"><a class="nav-link label-1" href="<?php echo base_url();?>all-emp_attendance-counts" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="clock"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Employees Attendance Count</span></span></div>
                  </a></div>

                  <div class="nav-item-wrapper"><a class="nav-link label-1" href="<?php echo base_url();?>tl-members-leave-request" role="button" data-bs-toggle="" aria-expanded="false">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="briefcase"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">My Team Leave Request</span></span></div>
                  </a></div>

             </li>

             <li class="nav-item">
                <!-- label-->
                  <p class="navbar-vertical-label">Reporting</p>
                  <hr class="navbar-vertical-line" />
                  <div class="nav-item-wrapper">
                    <a class="nav-link label-1" href="<?php echo base_url(); ?>tl-members-report" role="button" data-bs-toggle="" aria-expanded="false">
                      <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="users"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Team Member Reports</span></span></div>
                    </a>
                </div>
              </li>

              <?php } ?>

              <?php if($get_login_user->lead_section == 'yes'){?>
              <li class="nav-item">
                <!-- label-->
                <p class="navbar-vertical-label">Lead Section</p>
                <hr class="navbar-vertical-line" />

                <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-lead-management" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-lead-management">
                    <div class="d-flex align-items-center">
                      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="user-check"></span></span><span class="nav-link-text">Lead management</span>
                    </div>
                  </a>
                  <div class="parent-wrapper label-1">
                    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-lead-management">
                      <li class="collapsed-nav-item-title d-none">Lead management</li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>create-lead" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Create new</span></div>
                        </a><!-- more inner pages-->
                      </li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>lead-list" data-bs-toggle="" aria-expanded="false">
                          <div class="d-flex align-items-center"><span class="nav-link-text">Lead list</span></div>
                        </a><!-- more inner pages-->
                      </li>

                    </ul>
                  </div>
                </div>

              </li>
            <?php } ?>
            </ul>
          </div>
        </div>
        <div class="navbar-vertical-footer"><button class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center"><span class="uil uil-left-arrow-to-left fs-8"></span><span class="uil uil-arrow-from-right fs-8"></span><span class="navbar-vertical-footer-text ms-2">Collapsed View</span></button></div>
      </nav>
      <nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault" >
        <div class="collapse navbar-collapse justify-content-between">
          <div class="navbar-logo">
            <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
            <a class="navbar-brand me-1 me-sm-3" href="">
              <div class="d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <!--<img src="<?php echo base_url();?>assets/img/icons/logo.png" alt="phoenix" width="27" />-->
                  <p class="logo-text ms-2 d-none d-sm-block">Sanpurple Inc</p>
                </div>
              </div>
            </a>
          </div>

          <ul class="navbar-nav navbar-nav-icons flex-row">
            <li class="nav-item">
              <?php if(isset($get_login_user) && $get_login_user->join_date==date('Y-m-d')) { ?>
                <span class="mx-5 mt-1 text text-success"><b>Welcome Onboard - <?php echo $get_login_user->name; ?> !</b></span>
              <?php } else if(isset($get_login_user) && $get_login_user->join_date!='') {
                ?>
                <span class="mx-5 mt-1 text text-info"><b>Date of Joining : <?php echo $joining_date; ?></b></span>
              <?php } ?>
            </li>
            <li class="nav-item">
              <div class="theme-control-toggle fa-icon-wait px-2">
  <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="<?php echo $get_login_user->portal_theme;?>" id="themechontrolToggle" />

    <label class="mb-0 theme-control-toggle-label theme-control-toggle-light choose_theme" for="themechontrolToggle" data-id="light" data-url="<?php echo $uri;?>" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to light theme"><span class="icon" data-feather="moon"></span></label>

    <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark choose_theme" for="themechontrolToggle" data-id="dark" data-url="<?php echo $uri;?>" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to dark theme"><span class="icon" data-feather="sun"></span></label>

    </div>
            </li>

          <li class="nav-item dropdown">
              <a class="nav-link" href="#" style="min-width: 2.25rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside"><span data-feather="bell" style="height:20px;width:20px;"></span></a>
              <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
                <div class="card position-relative border-0">
                  <div class="card-header p-2">
                    <div class="d-flex justify-content-between">
                      <h5 class="text-body-emphasis mb-0">Notificatons</h5>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="scrollbar-overlay" style="height: 27rem;">
                   <?php
    $this->db->select("*");
	$this->db->from('notification');
	$this->db->where('tl_id',$empnumber );
	$qnoti = $this->db->get();
	$total_noti = $qnoti->num_rows() ;

                        if($total_noti >0){
                        foreach($qnoti->result() as $row_noti)
                        {
                   ?>
                        <a href="<?php echo base_url();?>notification" style="text-decoration:none;">
                      <div class="px-2 px-sm-3 py-3 notification-card position-relative read border-bottom">
                        <div class="d-flex align-items-center justify-content-between position-relative">
                          <div class="d-flex">
                            <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="<?php echo base_url();?>assets/img/57.webp" alt="" /></div>
                            <div class="flex-1 me-sm-3">
                              <h4 class="fs-9 text-body-emphasis"><?php echo $row_noti->heading;?></h4>
                              <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'></span><?php echo $row_noti->comment;?></p>
                              <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><?php echo $row_noti->created_date;?></p>
                            </div>
                          </div>

                        </div>
                      </div>
                      </a>
                     <?php }} ?>
                    </div>
                  </div>
                  <div class="card-footer p-0 border-top border-translucent border-0">
                    <div class="my-2 text-center fw-bold fs-10 text-body-tertiary text-opactity-85"><a class="fw-bolder" href="pages/notifications.html">Notification history</a></div>
                  </div>
                </div>
              </div>
            </li>

            <li class="nav-item dropdown"><a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-l ">
                  <img class="rounded-circle " src="<?php echo base_url();?><?php if($get_login_user->profile_photo != ''){echo 'uploads/'.$get_login_user->profile_photo;}else{ echo 'assets/img/user_image.jpg';}?>" alt="" />
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
                <div class="card position-relative border-0">
                  <div class="card-body p-0">
                    <div class="text-center pt-4 pb-3">
                      <div class="avatar avatar-xl ">
                        <img class="rounded-circle " src="<?php echo base_url();?><?php if($get_login_user->profile_photo != ''){echo 'uploads/'.$get_login_user->profile_photo;}else{ echo 'assets/img/user_image.jpg';}?>" alt="" />
                      </div>
                      <h6 class="mt-2 text-body-emphasis"><?php echo $get_login_user->name;?></h6>
                    </div>

                  </div>
                  <div class="overflow-auto scrollbar" style="height: 10rem;">
                    <ul class="nav d-flex flex-column mb-2 pb-1">
                      <li class="nav-item"><a class="nav-link px-3" href="<?php echo base_url();?>my-profile"> <span class="me-2 text-body" data-feather="user"></span><span>Profile</span></a></li>
                      <li class="nav-item"><a class="nav-link px-3" href="<?php echo base_url();?>dashboard"><span class="me-2 text-body" data-feather="pie-chart"></span>Dashboard</a></li>
                      <!--<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="lock"></span>Posts &amp; Activity</a></li>-->
                      <!--<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="settings"></span>Settings &amp; Privacy </a></li>-->
                      <!--<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="help-circle"></span>Help Center</a></li>-->
                      <!--<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="globe"></span>Language</a></li>-->
                    </ul>
                  </div>
                  <div class="card-footer p-0 border-top border-translucent">
                    <!--<ul class="nav d-flex flex-column my-3">-->
                    <!--  <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="user-plus"></span>Add another account</a></li>-->
                    <!--</ul>-->
                    <br>
                    <div class="px-3"><a class="btn btn-phoenix-sechondary d-flex flex-center w-100" href="<?php echo base_url();?>member-logout"> <span class="me-2" data-feather="log-out"> </span>Sign out</a></div>
                    <br>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </nav>


      <div class="content">