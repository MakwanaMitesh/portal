<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Home';

$route['index'] = 'Home/login';
$route['dashboard'] = 'Home/dashboard';
$route['testing'] = 'Home/testing_file';

$route['member-login'] = 'Get_access/login';
$route['member-logout'] = 'Get_access/logout';

$route['client-dashboard/(:any)'] = 'Client_management/dashboard/$1';
$route['client_login'] = 'Client_get_access/login';
$route['client-logout'] = 'Client_get_access/logout';
$route['get_client_project_task_list'] = 'Task_management/get_client_project_tasks_list';

$route['create-project'] = 'Project_management/create_project';
// $route['projects'] = 'Project_management/all_projects';
$route['project-list'] = 'Project_management/projects_list';
$route['project-detail/(:any)'] = 'Project_management/project_detail';
$route['get_project_list'] = 'Project_management/get_project_list';
$route['save-project'] = 'Project_management/save_project';
$route['edit-service'] = 'Project_management/edit_service';
$route['save_emp_hrs'] = 'Project_management/save_emp_hrs';
$route['delete_emp_hrs'] = 'Project_management/delete_emp_hrs';
$route['save_extra_hrs_request'] = 'Project_management/save_extra_hrs_request';
$route['save-service'] = 'Project_management/save_project_service';
$route['additional-hrsrequest'] = 'Project_management/additional_hrsrequest';
$route['update_extra_hrs_request'] = 'Project_management/update_extra_hrs_request';
$route['find-pname/(:any)'] = 'Project_management/get_project_name';

$route['my-profile'] = 'Employee_management/emp_profile';
$route['upload_pic'] = 'Employee_management/upload_pic';
$route['add_attendance_time'] = 'Employee_management/add_attendance_time';
$route['employee_attendence_list_admin'] = 'Employee_management/employee_attendence_list_admin';
$route['all-emp_attendance-counts'] = 'Employee_management/all_emp_attendance_counts';
$route['daily-attendance'] = 'Employee_management/daily_attendance';
$route['attendance-history'] = 'Employee_management/my_attendance_list';
$route['get_attendance_my_list'] = 'Employee_management/get_attendance_my_list';
$route['update_theme'] = 'Employee_management/update_theme';
$route['leave-request'] = 'Employee_management/leave_request';
$route['save-leave-request'] = 'Employee_management/save_apply_leave_form';
$route['get-leave-history'] = 'Employee_management/get_leave_history';
$route['my-leave-history'] = 'Employee_management/my_leave_history';
$route['employees-leave-request'] = 'Employee_management/employees_leave_request';
$route['get_employees_leave_history'] = 'Employee_management/get_employees_leave_history';
$route['hr_update_leave_status'] = 'Employee_management/hr_update_leave_status';
$route['tl_update_leave_status'] = 'Employee_management/tl_update_leave_status';
$route['remove_leave_request'] = 'Employee_management/remove_leave_request';
$route['create-employee'] = 'Employee_management/create_employee';
$route['tl-members-leave-request'] = 'Employee_management/tl_members_leave_request';
$route['tl-members-report'] = 'Team_management/team_reports';
$route['tl_employees_leave_history'] = 'Employee_management/tl_employees_leave_history';
$route['save-employee'] = 'Employee_management/save_employee_details';
$route['emp_wise_attendance'] = 'Employee_management/emp_wise_attendance';
$route['employees_attendance_history'] = 'Employee_management/employees_attendance_history';
$route['employee-list'] = 'Employee_management/emp_list';
$route['get_emp_list'] = 'Employee_management/get_emp_list';
$route['change_emp_status'] = 'Employee_management/change_emp_status';
$route['change_leave_status'] = 'Team_management/update_leave_status';
$route['change_task_approval_status'] = 'Team_management/update_task_approval_status';

$route['task-view'] = 'Task_management/task_view';
$route['get_service_list'] = 'Task_management/service_list';
$route['save-task'] = 'Task_management/save_task';
$route['get_module_list'] = 'Task_management/module_list';
$route['manage-modules'] = 'Task_management/manage_modules';
$route['save-module'] = 'Task_management/save_module';
$route['delete-module'] = 'Task_management/delete_module';
$route['delete-task'] = 'Task_management/remove_task';
$route['my-task/(:any)'] = 'Task_management/my_task';
$route['edit-task/(:any)'] = 'Task_management/edit_task';
$route['task-overview'] = 'Task_management/task_overview';
$route['task_overview_listing'] = 'Task_management/task_overview_listing';
$route['update_task_status'] = 'Task_management/update_task_status';
$route['update_task_time'] = 'Task_management/update_task_time';
$route['testing-tasks'] = 'Task_management/testing_tasks';
$route['testing-dashboard'] = 'Task_management/testing_dashboard';
$route['report-issues/(:num)'] = 'Task_management/report_issues/$1';
$route['task-issues/(:num)'] = 'Task_management/task_issues_list/$1';
$route['issue-detail/(:num)'] = 'Task_management/issue_detail/$1';
$route['save-issue'] = 'Task_management/save_issue';
$route['update-issue-status'] = 'Task_management/update_issue_status';
$route['assign-issue'] = 'Task_management/assign_issue';
$route['finalize-testing'] = 'Task_management/finalize_testing';
$route['check-module-required'] = 'Task_management/check_module_required';
$route['get-testers'] = 'Task_management/get_testers_json';
$route['get_task_activity_log'] = 'Task_management/get_task_activity_log';
$route['get_task_issues_json'] = 'Task_management/get_task_issues_json';

$route['generate_live_excel'] = 'Task_management/generate_live_excel';
$route['export_to_excel'] = 'Task_management/export_to_excel';
$route['test_excel_generation'] = 'Task_management/test_excel_generation';
$route['generate_live_excel_by_month'] = 'Task_management/generate_live_excel_by_month';
$route['get_task_data_json'] = 'Task_management/get_task_data_json';

$route['create-lead'] = 'Lead_management/create_lead';
$route['lead-list'] = 'Lead_management/lead_list';
$route['save-lead'] = 'Lead_management/save_lead';
$route['get_lead_list'] = 'Lead_management/get_lead_list';
$route['lead-detail'] = 'Lead_management/lead_detail';

$route['client-login'] = 'Client_management/index';

$route['create-candidate'] = 'Job_portal/create_candidate';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
