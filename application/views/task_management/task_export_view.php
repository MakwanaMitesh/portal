<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  include(APPPATH.'views/common/header.php');
?>

<div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">
  
  <!-- Filter Row -->
  <div class="row">
    <div class="col-md-2 mb-4">
      <div class="form-floating">
        <select class="form-select" id="employee">
          <option value="">--All--</option>
          <?php if(!empty($get_members_list)){ foreach ($get_members_list as $emp_list) { ?>
          <option value="<?php echo $emp_list->employee_no;?>"><?php echo $emp_list->name;?></option>
          <?php }}?>
        </select>
        <label>Employee</label>
      </div>
    </div>

    <div class="col-md-2 mb-4">
      <div class="form-floating">
        <select class="form-select" id="projects">
          <option value="">--All--</option>
          <?php if(!empty($get_project_list)){ foreach ($get_project_list as $project) { ?>
          <option value="<?php echo $project->project_id;?>"><?php echo $project->project_name;?></option>
          <?php }}?>
        </select>
        <label>Project</label>
      </div>
    </div>

    <div class="col-md-2 mb-4">
      <div class="flatpickr-input-container">
        <div class="form-floating">
          <input class="form-control datetimepicker" id="from_date" type="text" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />
          <label class="ps-6">From Date</label>
          <span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span>
        </div>
      </div>
    </div>

    <div class="col-md-2 mb-4">
      <div class="flatpickr-input-container">
        <div class="form-floating">
          <input class="form-control datetimepicker" id="to_date" type="text" data-options='{"disableMobile":true,"dateFormat":"d-m-Y"}' />
          <label class="ps-6">To Date</label>
          <span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="col-md-2 mb-4">
      <button type="button" class="btn btn-primary w-100" id="searchBtn">
        <i class="fas fa-search"></i> Search
      </button>
    </div>

    <div class="col-md-2 mb-4">
      <button type="button" class="btn btn-success w-100" id="exportBtn">
        <i class="fas fa-download"></i> Download Excel
      </button>
    </div>
  </div>

  <!-- Live Excel Link -->
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="alert alert-info">
        <strong>📊 Live Excel Link:</strong>
        <a href="<?php echo base_url('uploads/excel/task_overview.xls'); ?>" 
           target="_blank" id="liveExcelLink" class="btn btn-sm btn-info ms-2">
          <i class="fas fa-file-excel"></i> Open Live Excel
        </a>
        <button type="button" class="btn btn-sm btn-warning ms-2" id="refreshExcelBtn">
          <i class="fas fa-sync-alt"></i> Refresh Excel
        </button>
      </div>
    </div>
  </div>

  <!-- DataTable -->
  <div class="table-responsive ms-n1 ps-1 scrollbar">
    <table class="table table-sm fs-9 mb-0 table-hover" id="task_overviewtable">
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
          <th>Start Date</th>
          <th>End Date</th>
          <th>Task Heading</th>
          <th>Employee</th>
          <th>Project</th>
          <th>Task Status</th>
          <th>Hrs</th>
          <th>Mins</th>
          <th>Total Days</th>
        </tr>
      </thead>
      <tbody></tbody>
      <tfoot style="background-color:#43A047">
        <tr>
          <td colspan="5"></td>
          <td class="text-white">Totals</td>
          <td class="text-white" id="total_hrs">0</td>
          <td class="text-white" id="total_min">0</td>
          <td class="text-white">-</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<script>
$(document).ready(function() {
    
    // DataTable
    var table = $('#task_overviewtable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo base_url('task_overview_listing'); ?>',
            type: 'POST',
            data: function (d) {
                d.employee = $('#employee').val();
                d.projects = $('#projects').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [
            { data: 'task_start_date' },
            { data: 'task_end_date' },
            { data: 'task_heading' },
            { data: 'employee' },
            { data: 'project' },
            { data: 'task_status' },
            { data: 'hrs' },
            { data: 'min' },
            { data: 'total_days' }
        ],
        drawCallback: function(settings) {
            var json = this.api().ajax.json();
            $('#total_hrs').text(json.total_hrs[0]?.allotted_hrs || 0);
            $('#total_min').text(json.total_min[0]?.allotted_min || 0);
        }
    });

    // Search Button
    $('#searchBtn').click(function() {
        table.draw();
    });

    // Download Excel Button
    $('#exportBtn').click(function() {
        var form = $('<form></form>')
            .attr('method', 'POST')
            .attr('action', '<?php echo base_url('export_to_excel'); ?>');
        
        form.append($('<input>').attr('type', 'hidden').attr('name', 'employee').val($('#employee').val()));
        form.append($('<input>').attr('type', 'hidden').attr('name', 'projects').val($('#projects').val()));
        form.append($('<input>').attr('type', 'hidden').attr('name', 'from_date').val($('#from_date').val()));
        form.append($('<input>').attr('type', 'hidden').attr('name', 'to_date').val($('#to_date').val()));
        
        $('body').append(form);
        form.submit();
        form.remove();
    });

    // Refresh Live Excel
    $('#refreshExcelBtn').click(function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        $.ajax({
            url: '<?php echo base_url('generate_live_excel'); ?>',
            type: 'POST',
            data: {
                employee: $('#employee').val(),
                projects: $('#projects').val(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val()
            },
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Refresh Excel');
                alert('✅ Excel updated successfully at ' + response.generated_at);
                // Auto open
                window.open(response.file_url, '_blank');
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Refresh Excel');
                alert('❌ Error updating Excel!');
            }
        });
    });
});
</script>
<?php

  include(APPPATH.'views/common/footer.php');
?>
