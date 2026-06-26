<div class="modal fade" id="finalize_testing_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log QA testing time</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal"><span class="fas fa-times fs-9"></span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="finalize_task_id">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Tester hours <span class="text-danger">*</span></label>
                        <input type="number" id="tester_hrs" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Tester minutes <span class="text-danger">*</span></label>
                        <input type="number" id="tester_min" class="form-control" value="0" min="0" max="59">
                    </div>
                    <div class="col-12">
                        <p class="fs-10 text-danger mb-0 d-none" id="finalize_time_error">Please enter testing time (at least 1 minute).</p>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="mark_complete_check">
                            <label class="form-check-label" for="mark_complete_check">Also mark task as <strong>Completed</strong> (only if no open issues)</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="submit_finalize_testing">Save</button>
            </div>
        </div>
    </div>
</div>
