<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (empty($time_summary)) {
    return;
}
$s = $time_summary;
?>
<div class="card border border-translucent mb-4">
    <div class="card-body py-3">
        <h6 class="text-body-secondary text-uppercase fs-10 mb-2">Task time summary</h6>
        <div class="row g-2 fs-9">
            <div class="col-sm-4">
                <span class="text-body-tertiary">Developer</span>
                <strong class="d-block"><?php echo (int) $s['developer_hrs']; ?>h <?php echo (int) $s['developer_min']; ?>m</strong>
            </div>
            <div class="col-sm-4">
                <span class="text-body-tertiary">Tester (QA)</span>
                <strong class="d-block"><?php echo (int) $s['tester_hrs']; ?>h <?php echo (int) $s['tester_min']; ?>m</strong>
            </div>
            <div class="col-sm-4">
                <span class="text-body-tertiary">Issue fixes</span>
                <strong class="d-block"><?php echo (int) $s['issue_fix_hrs']; ?>h <?php echo (int) $s['issue_fix_min']; ?>m</strong>
            </div>
            <div class="col-12 pt-1 border-top border-translucent">
                <span class="text-body-tertiary">Total tracked</span>
                <strong class="d-block text-primary"><?php echo (int) $s['total_hrs']; ?>h <?php echo (int) $s['total_min']; ?>m</strong>
            </div>
        </div>
    </div>
</div>
