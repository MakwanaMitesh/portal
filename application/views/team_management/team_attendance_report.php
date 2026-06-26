<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
?>
<h2 class="mb-4">Team Members Overview</h2>

<div class="accordion" id="accordionExample">
<?php
    if(!empty($team_members_details) ) {
        foreach($team_members_details as $i => $team_member_detail) {
?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $team_member_detail->employee_no; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $team_member_detail->employee_no; ?>" style="background-color:#000; color:#fff;">
                <table>
                    <tr>
                        <td width=""></td>
                        <td width="300"><?php echo $team_member_detail->name; ?></td>
                        <td width="200"><label class="small">Attendance Date : </label>&nbsp;<span class="small"><?php echo $team_member_detail->attendence_date; ?></span></td>
                        <?php if($team_member_detail->wasOnLeave==1) { ?>
                            <td width="400"><label class="small border-danger">Remark : </label>&nbsp;<span class="small">On Leave</span></td>
                        <?php } else { ?>
                            <td width="200"><label class="small">Login Time : </label>&nbsp;<span class="small"><?php echo $log_time = ($team_member_detail->login_time=='')? " Missing" : $team_member_detail->login_time; ?></span></td>
                            <td width="200"><label class="small">Logout Time : </label>&nbsp;<span class="small"><?php echo $out_time = ($team_member_detail->logout_time=='')? " Missing " : $team_member_detail->logout_time; ?></span></td>
                        <?php } ?>
                    </tr>
                </table>
            </button>
        </h2>
        <div id="collapse_<?php echo $team_member_detail->employee_no; ?>" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <table border="0">
                    <?php
                    if(!empty($team_member_detail->emp_tasks) && $team_member_detail->emp_tasks!="") {
                        ?>
                        <colgroup>
                        <col style="width: 130px;">
                        <col style="width: 400px;">
                        <col style="width: 100px;">
                        <col style="width: 100px;">
                        <col style="width: 400px;">
                    </colgroup>
                    <thead>
                        <tr class="bg-info text-white">
                            <th width="130" class="small p-1" style="text-align:center;">CLIENT NAME</th>
                            <th width="400" class="small p-1" style="text-align:center;">TASKS</th>
                            <th width="200" class="small p-1" style="text-align:center;">TASK TIME</th>
                            <th width="120" class="small p-1" style="text-align:center;">TASK STATUS</th>
                            <th width="400" class="small p-1" style="text-align:center;">ACTIONS</th>
                        </tr>
                    </thead>
                    <?php
                        foreach($team_member_detail->emp_tasks as $j => $emp_task) {
                    ?>
                            <tr>
                                <td width="130" style="text-align:left;"><span class="small"><?php echo $emp_task['project_name']; ?></span></td>
                                <td width="400" style="text-align:justify;"><span class="small"><?php echo $j+1; ?>) &nbsp;</span><span class="small"><?php echo nl2br($emp_task['task_heading']); ?></span></td>
                                <td width="200" style="text-align:center;"><span class="small"><?php echo $emp_task['allotted_hrs']." Hrs ". $emp_task['allotted_min']. " Mins "; ?></span></td>
                                <td width="120" style="text-align:center;"><span class="small"><?php echo $emp_task['task_status']; ?></span></td>
                                <td width="400" style="text-align:center;">
                                    <div class="row mt-2">
                                        <form method="post" name="task_approval_form" id="task_approval_form_<?php echo $emp_task['task_id']; ?>">
                                            <input type="hidden" name="project_id" value="<?php echo $emp_task['project_id']; ?>"/>
                                            <input type="hidden" name="emp_id" value="<?php echo $emp_task['assignee']; ?>"/>
                                            <input type="hidden" name="tl_id" value="<?php echo $team_member_detail->Teamleader; ?>"/>
                                            <input type="hidden" name="task_id" value="<?php echo $emp_task['task_id']; ?>"/>
                                            <textarea class="col-6" name="comment_for_approval" id="comment_input_<?php echo $emp_task['task_id']; ?>" rows="1" cols="20"></textarea>
                                            <input type="button" class="btn btn-outline-primary btn-sm col-4" name="approve_with_comment" id="task_comment_<?php echo $emp_task['task_id']; ?>" value="Approve" style="margin-top: -21px;">
                                        </form>
                                        <div id="message_<?php echo $emp_task['task_id']; ?>" style="height:30px; padding:5px; padding-bottom:7px; width: 50%; vertical-align:middle; display:none; margin: 3px auto;"></div>
                                    </div>
                                </td>
                            </tr>

                    <?php }
                    } else { ?>
                    <table>
                        <tr class="bg-info text-white">
                            <th width="400" class="small p-1">TASKS</th>
                            <th width="200" class="small">TASK TIME</th>
                            <th width="120" class="small">TASK STATUS</th>
                            <th width="480" colspan="2" class="small" style="text-align:center;">ACTIONS</th>
                        </tr>
                        <tr>
                            <td colspan="4" class="small p-2" style="text-align:center;">No Tasks For Approval.</td>
                        </tr>
                    </table>
                    <?php
                    }
                    ?>
                </table>
                <?php if(isset($team_member_detail->leaves) && !empty($team_member_detail->leaves)) { ?>
                    <hr/>
                    <table border="0">
                        <tr class="bg-warning text-white">
                            <th width="200" class="small p-1">LEAVE DATE</th>
                            <th width="380" class="small p-1">LEAVE DETAILS</th>
                            <th width="200" class="small p-1">LEAVE TYPE</th>
                            <th class="small p-1" style="text-align:center;">ACTIONS</th>
                        </tr>
                        <?php
                            $m = 1;
                            foreach($team_member_detail->leaves as $j => $emp_leaves) {
                        ?>
                            <tr>
                                <td width="200"><span class="small"><?php echo $m . ")&nbsp;" . nl2br($emp_leaves['attendence_date']); ?></span></td>
                                <td width="380"><span class="small"><?php echo $emp_leaves['leave_details']; ?></span></td>
                                <td width="200"><span class="small"><?php echo $emp_leaves['reason_for_leave']; ?></span></td>
                                <td width="400" align="center" class="p-2">
                                    <div class="row">
                                        <form method="post" name="leave_approval_form" id="leave_approval_form_<?php echo $emp_leaves['attendence_id']; ?>" style="width:48%">
                                            <input type="hidden" name="emp_id" value="<?php echo $emp_leaves['employee_no']; ?>"/>
                                            <input type="hidden" name="approved_by" value="tl"/>
                                            <input type="hidden" name="leave_id" value="<?php echo $emp_leaves['attendence_id']; ?>"/>
                                            <input type="submit" class="btn btn-outline-success btn-sm" name="approve_leave_btn" approve="1" id="approve_leave_id_<?php echo $emp_leaves['attendence_id']; ?>" value="Approve Leave" style="margin-top: 2px;">
                                        </form>
                                        <form method="post" name="leave_reject" id="leave_reject_<?php echo $emp_leaves['attendence_id']; ?>" style="width:48%">
                                            <input type="hidden" name="emp_id" value="<?php echo $emp_leaves['employee_no']; ?>"/>
                                            <input type="hidden" name="rejected_by" value="tl"/>
                                            <input type="hidden" name="leave_id" value="<?php echo $emp_leaves['attendence_id']; ?>"/>
                                            <input type="button" class="btn btn-outline-danger btn-sm" name="reject_leave_btn" reject="2" id="reject_leave_id_<?php echo $emp_leaves['attendence_id']; ?>" value="Reject Leave" style="margin-top: 2px;">
                                        </form>
                                        <div id="leave_message_<?php echo $emp_leaves['attendence_id']; ?>" style="height:30px; padding:5px; padding-bottom:7px; width: 50%; vertical-align:middle; display:none; margin: 3px auto;"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php $m++;
                            } ?>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
        <?php }
        } ?>
</div>

<?php include(APPPATH.'views/common/footer.php');?>