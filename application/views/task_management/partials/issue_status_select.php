<?php defined('BASEPATH') OR exit('No direct script access allowed');
$issue_id = (int) ($issue_id ?? 0);
$current = $current_status ?? 'Open';
$statuses = ['Open', 'Fixed', 'Closed', 'Reopened'];
?>
<select class="form-select form-select-sm issue-status-select" data-issue-id="<?php echo $issue_id; ?>" title="Change issue status">
    <?php foreach ($statuses as $st) { ?>
    <option value="<?php echo $st; ?>" <?php echo $current === $st ? 'selected' : ''; ?>><?php echo $st; ?></option>
    <?php } ?>
</select>
