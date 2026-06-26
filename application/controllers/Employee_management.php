<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_management extends CI_Controller {
    public function __construct(){

		parent::__construct();
        $this->load->model("Common_model");
        $this->load->model("Employee_model");
        $this->load->model("Project_model");
		$this->load->model("Task_model");
        date_default_timezone_set('Asia/Kolkata');
	}

	public function daily_attendance()
	{
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();

	    $data['get_my_attendance'] =  $this->Employee_model->get_my_attendance($get_login_user->employee_no,date('Y-m-d'));
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $this->load->view('employee_management/daily_attendance',$data);
	}

	public function my_attendance_list()
	{
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();

	    //$data['my_attendance'] =  $this->Employee_model->my_attendance_list($get_login_user->employee_no,date('Y-m-d'));
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $this->load->view('employee_management/my_attendance_list',$data);
	}

		public function emp_list()
	{
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    $data['get_departments_list'] = $this->Employee_model->get_departments_list();
	    //$data['my_attendance'] =  $this->Employee_model->my_attendance_list($get_login_user->employee_no,date('Y-m-d'));
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $this->load->view('employee_management/emp_list',$data);
	}

	public function emp_wise_attendance()
	{

		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	     $data['emp_id_value'] = $this->input->get("emp_id");
	   // $data['my_attendance'] =  $this->Employee_model->get_my_attendance($get_login_user->employee_no,date('Y-m-d'));
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $this->load->view('employee_management/emp_wise_attendance',$data);
	}

	public function leave_request()
	{
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();

	    //$data['my_attendance'] =  $this->Employee_model->my_attendance_list($get_login_user->employee_no,date('Y-m-d'));
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $this->load->view('employee_management/leave_request',$data);
	}

	public function my_leave_history()
	{
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();

	    //$data['my_attendance'] =  $this->Employee_model->my_attendance_list($get_login_user->employee_no,date('Y-m-d'));
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $this->load->view('employee_management/my_leave_history',$data);
	}

	public function employees_leave_request()
	{
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $this->load->view('employee_management/employees_leave_request',$data);
	}

	public function tl_members_leave_request()
	{
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $this->load->view('employee_management/tl_members_leave_request',$data);
	}

	public function all_emp_attendance_counts()
	{
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $this->load->view('employee_management/all_emp_attendance_counts',$data);
	}

	public function create_employee()
	{
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_project_phases'] = $this->Project_model->get_project_phases();
	    $data['get_project_list'] = $this->Common_model->get_project_list();
	    $data['get_members_list'] = $this->Common_model->get_members_list();
	    $data['get_proservice_list'] = $this->Common_model->get_categorywise_service_list();
	    $data['get_last_emp_num'] = $this->Employee_model->get_last_emp_num();
	    $data['get_departments_list'] = $this->Employee_model->get_departments_list();
	    $data['get_team_type'] = $this->Project_model->get_project_category();
	    //$data['get_emp'] = $this->Employee_model->get_emp($empid);
	    $editmyprofile = $this->input->get("edit_myprofile");

	    if( $editmyprofile == 'yes'){
	        $empid = $get_login_user->employee_no;
	    }else{
	        $empid = $editmyprofile;
	    }
	    $data['get_emp'] = $getemp = $this->Employee_model->get_emp($empid);
	    $this->load->view('employee_management/create_new_emp',$data);
	}

		public function get_attendance_my_list()
	{
            $postData = $this->input->post();
            $data = $this->Employee_model->get_attendance_my_list($postData);
            echo json_encode($data);
	}

		public function get_emp_list()
	{
            $postData = $this->input->post();
            $data = $this->Employee_model->get_emp_list($postData);
            echo json_encode($data);
	}

		public function get_leave_history()
	{
            $postData = $this->input->post();
            $data = $this->Employee_model->get_leave_history($postData);
            echo json_encode($data);
	}

		public function get_employees_leave_history()
	{
            $postData = $this->input->post();
            $data = $this->Employee_model->get_employees_leave_history($postData);
            echo json_encode($data);
	}

		public function employees_attendance_history()
	{
            $postData = $this->input->post();
            $data = $this->Employee_model->get_employees_attendance_history($postData);
            echo json_encode($data);
	}

		public function tl_employees_leave_history()
	{
            $postData = $this->input->post();
            $data = $this->Employee_model->get_tl_employees_leave_history($postData);
            echo json_encode($data);
	}

	public function hr_update_leave_status()
	{
           $atend_id = $this->input->post("atend_id");
	       $value = $this->input->post("value");
           $data = array(
           'leave_approved_by_HR' => $value,
           );
           $this->db->where('id', $atend_id);
           $this->db->update('attendence', $data);

	}
	public function change_emp_status()
	{
          $emp_id = $this->input->post("emp_id");
	       $emp_status = $this->input->post("emp_status");
	    // die;
          $data = array(
          'status' => $emp_status,
          );
          $this->db->where('id', $emp_id);
          $update = $this->db->update('employees', $data);
         if($update){echo 1;}else{echo 0;}

	}
	public function update_theme()
	{
           $emp_num = $this->session->userdata('user_id');
	       $value = $this->input->post("theme");
           $data = array(
           'portal_theme' => $value,
           );
           $this->db->where('employee_no', $emp_num);
           $update = $this->db->update('employees', $data);
         if($update){echo 1;}else{echo 0;}
	}

	public function add_attendance_time()
	{
		$get_login_user = $this->Common_model->get_login_user();

		$todays_work_hours_flag = $this->check_todays_tasks();
		$login_session_hours = $this->Employee_model->get_working_hours();

		if(isset($get_login_user) && $get_login_user->join_date == date('Y-m-d')) {
			$attendance_date = date("Y-m-d", strtotime($this->input->post('attendance_date')));
			$attendance_type = $this->input->post('attendance_type');
			$login_time = $this->input->post('login_time');
			$logout_time = $this->input->post('logout_time');
			$emp_num = $this->session->userdata('user_id');

			$today_date = date('Y-m-d');
			if($today_date == $attendance_date) {
				$con['table_name'] = 'attendence';
				$con['returnType'] = 'single';
				$con['conditions'] = array(
						'attendence_date' => $attendance_date,
						'emp_id' => $emp_num,
					);
				$user = $this->Common_model->getRows($con);

				if($attendance_date != '' && $attendance_type != '' && $login_time != '' && $emp_num != '') {
					$data = array(
						'emp_id' => $emp_num,
						'attendence_date' => $attendance_date,
						'login_time' => $login_time,
						'attendance_type' => $attendance_type,
						'logout_time' => $logout_time,
					);
					if($logout_time != '') {
						if($todays_work_hours_flag==TRUE) {
							if($login_session_hours==TRUE) {
								$this->db->where('emp_id', $emp_num);
								$this->db->where('attendence_date', $attendance_date);
								$update = $this->db->update('attendence', $data);

								//echo $this->db->last_query();

								if($update)
								{
									echo '<h4 class="alert alert-outline-success ">Logout time successfully added.<h4>';
								}
								else
								{
									echo '<h4 class="alert alert-outline-danger">Something went wrong, try again later<h4>';
								}
							} else {
								echo "<h4 class='alert alert-outline-danger'>Your work hours are not completed yet!</h4>";
							}
						} else {
							echo "<h4 class='alert alert-outline-danger'>Please first add working hours of Today's working day</h4>";
						}
					} else {
						if($user['emp_id'] != $emp_num && $user['attendence_date'] != $attendance_date ) {
							$insert = $this->db->insert('attendence', $data);

							if($insert) {
								echo '<h4 class="alert alert-outline-success">Login time successfully added.<h4>';
							}
							else {
								echo '<h4 class="alert alert-outline-danger">Something went wrong, try again later<h4>';
							}
						} else {
							echo '<h4 class="alert alert-outline-warning">The login time has already been recorded.<h4>';
						}
					}
				} else {
					echo '<h4 class="alert alert-outline-danger">All fields are required<h4>';
				}
			}
		} else {
				//check yesterday's task hours added or not
				$yesterday_work_hours_flag = $this->check_yesterday_tasks();

				if($yesterday_work_hours_flag==TRUE) {
					$attendance_date = date("Y-m-d", strtotime($this->input->post('attendance_date')));
					$attendance_type = $this->input->post('attendance_type');
					$login_time = $this->input->post('login_time');
					$logout_time = $this->input->post('logout_time');
					$emp_num = $this->session->userdata('user_id');

					$today_date = date('Y-m-d');
					if($today_date == $attendance_date) {
						$con['table_name'] = 'attendence';
						$con['returnType'] = 'single';
						$con['conditions'] = array(
							'attendence_date' => $attendance_date,
							'emp_id' => $emp_num,
							);
						$user = $this->Common_model->getRows($con);

						if($attendance_date != '' && $attendance_type != '' && $login_time != '' && $emp_num != '') {
							$data = array(
								'emp_id' => $emp_num,
								'attendence_date' => $attendance_date,
								'login_time' => $login_time,
								'attendance_type' => $attendance_type,
								'logout_time' => $logout_time,
							);
							if($logout_time != '') {
								if($todays_work_hours_flag==TRUE) {
									if($login_session_hours==TRUE) {
										$this->db->where('emp_id', $emp_num);
										$this->db->where('attendence_date', $attendance_date);
										$update = $this->db->update('attendence', $data);

										if($update)
										{
											echo '<h4 class="alert alert-outline-success ">Logout time successfully added.<h4>';
										}
										else
										{
											echo '<h4 class="alert alert-outline-danger ">Something went wrong, try again later<h4>';
										}
									} else {
										echo "<h4 class='alert alert-outline-danger'>Your work hours are not completed yet!</h4>";
									}
								} else {
									echo "<h4 class='alert alert-outline-danger'>Please first add working hours of Today's working day</h4>";
								}
							} else {
								if($user['emp_id'] != $emp_num && $user['attendence_date'] != $attendance_date ) {
									$insert = $this->db->insert('attendence', $data);

									if($insert) {
										echo '<h4 class="alert alert-outline-success ">Login time successfully added.<h4>';
									}
									else {
										echo '<h4 class="alert alert-outline-danger ">Something went wrong, try again later<h4>';
									}
								} else {
									echo '<h4 class="alert alert-outline-warning ">The login time has already been recorded.<h4>';
								}
							}
					} else {
						echo '<h4 class="alert alert-outline-danger">All fields are required<h4>';
					}
				} else {
					echo '<h4 class="alert alert-outline-danger">Please first add working hours of previous working day</h4>';
				}
			}
		}
	}

	public function check_yesterday_tasks() {
		return $this->Task_model->get_yesterday_task_hours();
	}

	public function check_todays_tasks() {
		return $this->Task_model->get_todays_task_hours();
	}

	public function save_apply_leave_form()
	{
	    $emp_num = $this->session->userdata('user_id');
	    $startdate = date("Y-m-d", strtotime($this->input->post('leave_date_from')));
	    $enddate =   date("Y-m-d", strtotime($this->input->post('leave_date_to')));

	    if($startdate != '' && $enddate != ''){
	   for ($date = $startdate; $date <= $enddate; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {

	    $con['table_name'] = 'attendence';
		$con['returnType'] = 'single';
		$con['conditions'] = array(
			'attendence_date' => $date,
			'emp_id' => $emp_num,
			'attendance_type' => 'Leave',
			);
		$user = $this->Common_model->getRows($con);
		if($user['attendence_date'] != $date){

	    $data = array(
         'emp_id' => $emp_num,
         'attendence_date' => $date,
         'attendance_type' => 'Leave',
         'leave_details' => $this->input->post('comment'),
         'reason_for_leave' => $this->input->post('leave_reason'),
         'leave_approved_by_HR' => '0',
         'leave_approved_by_TL' => '0',
        //  'leave_from_date' => $startdate,
        //  'leave_from_date' => $enddate,
        );

	    $insert = $this->db->insert('attendence', $data);

	    $con1['table_name'] = 'employees';
		$con1['returnType'] = 'single';
		$con1['conditions'] = array(
			'employee_no' => $emp_num,
			);
		$user2 = $this->Common_model->getRows($con1);
		$e_name = $user2['name'];
		$e_Teamleader = $user2['Teamleader'];
	    $data2 = array(
	     'tl_id' => $e_Teamleader,
         'heading' => 'Leave Request Notification',
         'comment' => $e_name.' has submitted a leave request',
         'created_date' => date('Y-m-d H:i:s'),
         'emp_num' => $emp_num,
        );
	    $insert2 = $this->db->insert('notification', $data2);

		}else{
		    echo '<h4 class="alert alert-outline-warning ">Leave requests already sent<h4>';
		}

        }
	    if($insert){echo '<h4 class="alert alert-outline-success ">Leave requests are forwarded to both HR and your team leader for approval.<h4>';}
	      else{echo '<h4 class="alert alert-outline-danger ">Something went wrong, try again later<h4>';}
	    }else{
	        echo '<h4 class="alert alert-outline-danger ">Provide Leave From-Date and To-Date<h4>';
	    }

	}



	public function remove_leave_request()
	{
	$id = $this->input->post("row_id1");
    $delete_task = $this->Common_model->delete_data($id,'id','attendence');
    //echo ($delete_task) ? 1 : 2;
	}

	public function employee_attendence_list_admin()
	{
		//header('Access-Control-Allow-Origin: *');
            $postData = $this->input->post();
            $data = $this->Employee_model->getemploye_atendence_admin($postData);
            echo json_encode($data);
	}


	 public function save_employee_details()
	{
	    $emp_number = $this->input->post('emp_number');
	    $emp_name = $this->input->post('employee_name');
	    $emp_id = $this->input->post('emp_id');
	    if($emp_number != '' && $emp_name != ''){
	       //$emp_image_link= trim($this->input->post('emp_image_link'));

	    $data = array(
         'gender' => $this->input->post('gender'),
         'name' => $this->input->post('employee_name'),
         'email' => $this->input->post('email_id'),
         'mobile_no' => $this->input->post('mobile_num'),
         'alter_mobile' => $this->input->post('emergency_mobile_num'),
         'department' => $this->input->post('department'),
         'designation' => $this->input->post('emp_designation'),
         'dob' => $this->input->post('dob'),
         'married' => $this->input->post('married'),
         'married_date' => $this->input->post('anniversary_date'),
         'address' => $this->input->post('address'),
          'employee_no' => $this->input->post('emp_number'),
         'join_date' => $this->input->post('joining_date'),
         'team_type' => $this->input->post('team_type'),
         'password' => sha1($this->input->post('login_password')),
         'usercode' => $this->input->post('login_password'),
         //'profile_photo' => $emp_image_link,
         'created_on' => date('Y-m-d'),
        );

	    if($emp_id != ''){
	    $this->db->where('employee_no', $emp_id);
        $this->db->update('employees', $data);
	    }else{
	        $insert = $this->db->insert('employees', $data);
	        if($insert){echo '<h4 class="alert alert-outline-success ">Great, Employee added successfully<h4>';}
	        else{echo '<h4 class="alert alert-outline-danger ">Something went wrong, try again later<h4>';}
	   }
	    }else{
	        echo '<h4 class="alert alert-outline-danger ">Provide Employee details<h4>';
	    }

	}




		public function emp_profile()
	{   $empid = $data['empid'] = $this->input->get("empid");
		$data['get_login_user'] = $get_login_user = $this->Common_model->get_login_user();
	    $data['get_emp'] = $this->Employee_model->get_emp($empid);
	   // $data['get_last_emp_num'] = $this->Employee_model->get_last_emp_num();
	    $this->load->view('employee_management/emp_profile',$data);
	}

	public function upload_pic()
{
    $user_id = $this->input->get('u');
    $get_emp = $this->Employee_model->get_emp($user_id);

    // Delete existing file if it exists
    $filePath = './uploads/' . $get_emp->profile_photo;
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $config['upload_path'] = './uploads/';
    $config['allowed_types'] = 'jpg|jpeg|png';
    $config['max_size'] = '20480'; // Set max size to 20 MB

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('profile_fileInput')) {
        $error = array('error' => $this->upload->display_errors());
        echo $error['error'];
        return;
    }

    $uploaded_file_path = $this->upload->data('full_path');
    $filename = $_FILES['profile_fileInput']['tmp_name'];

    // Update user profile photo in the database
    $userData = array(
        'profile_photo' => $this->upload->data('file_name')
    );

    $this->db->where('employee_no', $user_id);
    if (!$this->db->update('employees', $userData)) {
        echo "Error updating the database.";
        return;
    }

    // Check if EXIF data exists and rotate image if needed
    // $imgdata = false;
    // if (file_exists($uploaded_file_path)) {
    //     try {
    //         $imgdata = @exif_read_data($uploaded_file_path);
    //     } catch (Exception $e) {
    //         // Handle the exception or log the error
    //         $imgdata = false;
    //     }
    // }

   // $orientation = isset($imgdata['Orientation']) ? $imgdata['Orientation'] : null;

    list($width, $height) = getimagesize($filename);
    $config['image_library'] = 'gd2';
    $config['source_image'] = $uploaded_file_path;
    $config['maintain_ratio'] = true;
    $config['master_dim'] = ($width > $height) ? 'width' : 'height';
    $config['width'] = ($width > $height) ? 300 : null;
    $config['height'] = ($width > $height) ? null : 300;

    $this->load->library('image_lib', $config);

    if (!$this->image_lib->resize()) {
        echo "Error resizing the image.";
        return;
    }

    $this->image_lib->clear();

    // Rotate the image based on EXIF orientation
    // if ($orientation) {
    //     switch ($orientation) {
    //         case 3:
    //             $config['rotation_angle'] = '180';
    //             break;
    //         case 6:
    //             $config['rotation_angle'] = '270';
    //             break;
    //         case 8:
    //             $config['rotation_angle'] = '90';
    //             break;
    //         default:
    //             $config['rotation_angle'] = '0';
    //     }

    //     if ($config['rotation_angle'] != '0') {
    //         $this->image_lib->initialize($config);
    //         if (!$this->image_lib->rotate()) {
    //             echo "Error rotating the image.";
    //             return;
    //         }
    //     }
    // }

    echo '1';
}



}
