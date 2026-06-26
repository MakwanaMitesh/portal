<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
?>

<h2 class="mb-4">Add Attendance</h2>
        <div class="row">
          <div class="col-xl-6">

            <div class="row g-3 mb-6"  >

                 <div class="col-sm-6 col-md-12">

                    <div class="flatpickr-input-container">

                      <div class="form-floating">

                          <input readonly class="form-control datetimepicker" name="attendance_date" value="<?php echo date("d-m-Y");?>" id="attendance_date" type="text" placeholder="Start date" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />

                          <label class="ps-6" for="attendance_date">Attendance Date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>

                    </div>

                 </div>

              <div class="col-sm-12 col-md-12">

              <div class="form-check form-check-inline">

                  <input class="form-check-input attendance_type" id="w1" type="radio" name="attendance_type" value="Full" checked/>

                  <label class="form-check-label" for="w1">Full Day</label>

                </div>

                <div class="form-check form-check-inline">

                  <input class="form-check-input attendance_type" id="w2" type="radio" name="attendance_type" value="Half" />

                  <label class="form-check-label" for="w2">Half Day</label>

                </div>

                <!-- <div class="form-check form-check-inline">

                  <input class="form-check-input attendance_type" id="w3" type="radio" name="attendance_type" value="Leave" />

                  <label class="form-check-label" for="w3">Leave</label>

                </div> -->

            </div>

              <div class="col-sm-3 col-md-10">

                <div  class="form-floating"><input readonly class="form-control" name="login_time" id="login_time" type="time"  value="<?php if($get_my_attendance->login_time){echo $get_my_attendance->login_time;}else{echo date("H:i");}?>"/><label for="login_time" >Login Time</label></div>

              </div>

              <div class="col-sm-3 col-md-2">
                <?php if($get_my_attendance->login_time){$disabled = 'disabled';}else{$disabled = '';}?>
                <button <?php echo $disabled?> id="login_time_add" class="btn  btn-outline-success me-1 mb-1" style="height:48px;">Add</button>

              </div>

              

              <div class="col-sm-3 col-md-10">

                <div class="form-floating"><input readonly class="form-control" name="logout_time" id="logout_time" type="time" value="<?php echo date("H:i");?>" /><label for="logout_time" >Logout Time</label></div>

              </div>

              <div class="col-sm-3 col-md-2">

                <button id="logout_time_add" class="btn  btn-outline-success me-1 mb-1 " style="height:48px;">Add</button>

              </div>

              

            </div>

            <div id="msg_atten"></div>


          </div>

        </div>



<?php

    include(APPPATH.'views/common/footer.php');

?>