<?php defined('BASEPATH') OR exit('No direct script access allowed');
$images = $images ?? [];
$issue_id = isset($issue_id) ? (int) $issue_id : 0;
if (empty($images)) {
    echo '—';
    return;
}
echo '<div class="d-flex flex-wrap gap-1 align-items-center">';
foreach ($images as $img) {
    $fn = is_object($img) ? ($img->file_name ?? '') : $img;
    if ($fn === '') {
        continue;
    }
    $url = base_url('uploads/issue_images/' . $fn);
    ?>
    <a href="<?php echo $url; ?>" target="_blank" rel="noopener" title="Open image" class="d-inline-block">
        <img src="<?php echo $url; ?>" alt="Screenshot" class="rounded border" style="height:40px;width:48px;object-fit:cover;">
    </a>
    <?php
}
if ($issue_id) {
    echo '<a href="' . base_url('issue-detail/' . $issue_id) . '" class="btn btn-link btn-sm p-0 ms-1 fs-10">Details</a>';
}
echo '</div>';
