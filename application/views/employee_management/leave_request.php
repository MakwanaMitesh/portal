<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
?>
      <div class="mb-4">
        <h2 class="mb-4">Apply for Leave</h2><br>
        <div class="row">
            
          <div class="col-xl-6">
              <form class="row g-3 mb-6" id="leave_request_form" method="post">
               <div class="row">
                   
              <div class="col-sm-6 col-md-6">
                    <div class="flatpickr-input-container">
                      <div class="form-floating">
                          <input class="form-control datetimepicker leave_date" name="leave_date_from" value="" id="leave_date_from" type="text" placeholder="From date" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />
                          <label class="ps-6" for="leave_date_from">From Date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>
                    </div>
            </div>
            <div class="col-sm-6 col-md-6">
                    <div class="flatpickr-input-container">
                      <div class="form-floating">
                          <input class="form-control datetimepicker leave_date" name="leave_date_to" value="" id="leave_date_to" type="text" placeholder="To date" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />
                          <label class="ps-6" for="leave_date_to">To Date</label><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span></div>
                    </div>
            </div>
            
            <div class="col-sm-12 col-md-12 gy-3">
                
            <p><b>REASON FOR LEAVE</b></p>
            <div class="form-check">
              <input class="form-check-input leave_reason" id="leave_1" type="radio" name="leave_reason"  value="Sick Leave"/>
              <label class="form-check-label" for="leave_1">Sick Leave</label>
            </div>
            <div class="form-check">
              <input class="form-check-input leave_reason" id="leave_2" type="radio" name="leave_reason"  value="Annual Leave"/>
              <label class="form-check-label" for="leave_2">Annual/Vacation Leave</label>
            </div>
            <div class="form-check">
              <input class="form-check-input leave_reason" id="leave_3" type="radio" name="leave_reason"  value="Maternity Leave"/>
              <label class="form-check-label" for="leave_3">Maternity Leave</label>
            </div>
            <div class="form-check">
              <input class="form-check-input leave_reason" id="leave_4" type="radio" name="leave_reason"   value="Parental Leave"/>
              <label class="form-check-label" for="leave_4">Parental Leave</label>
            </div>
            <div class="form-check">
              <input class="form-check-input leave_reason" id="leave_5" type="radio" name="leave_reason"  value="Bereavement Leave"/>
              <label class="form-check-label" for="leave_5">Bereavement Leave</label>
            </div>
            <div class="form-check">
              <input class="form-check-input leave_reason" id="leave_6" type="radio" name="leave_reason"  value="Marriage Leave"/>
              <label class="form-check-label" for="leave_6">Marriage Leave</label>
            </div>
            <div class="form-check">
              <input class="form-check-input leave_reason" id="leave_7" type="radio" name="leave_reason"  value="Personal Time Off Leave"/>
              <label class="form-check-label" for="leave_7">Personal Time Off (PTO) Leave</label>
            </div>
            <div class="form-check">
              <input class="form-check-input leave_reason" id="leave_8" type="radio" name="leave_reason"  value="Study Leave"/>
              <label class="form-check-label" for="leave_8">Study Leave</label>
            </div>
            </div>
            
            <div class="col-sm-12 col-md-12 gy-3">
                <div class="form-floating"><textarea class="form-control" id="comment" name="comment" placeholder="Leave a comment here" style="height: 100px" maxlength="200"></textarea><label for="comment">Comment</label></div>    
            </div>
            
            <div class="col-12 gy-3">
                <div class="row g-3 justify-content-start">
                  
                  <div class="col-auto"><button type="submit" class="btn btn-primary px-5 px-sm-15">Send Request</button></div>
                </div>
              </div>
            
            </div>
             </form> 
            <div id="message_leave"></div>
          </div>

        </div>
    </div>
<?php
    include(APPPATH.'views/common/footer.php');
?>