<?php defined('BASEPATH') OR exit('No direct script access allowed');
$images = $images ?? [];
$gallery_id = $gallery_id ?? 'issueGallery';
if (empty($images)) {
    echo '<p class="text-body-secondary fs-9 mb-0">No screenshots attached.</p>';
    return;
}
?>
<div class="issue-image-gallery row g-3" id="<?php echo htmlspecialchars($gallery_id); ?>">
    <?php foreach ($images as $idx => $img) {
        $fn = is_object($img) ? ($img->file_name ?? '') : $img;
        $url = !empty($img->url) ? $img->url : base_url('uploads/issue_images/' . $fn);
        if ($fn === '') {
            continue;
        }
    ?>
    <div class="col-6 col-md-4 col-lg-3">
        <button type="button"
            class="btn p-0 border-0 w-100 issue-gallery-thumb"
            data-gallery="<?php echo htmlspecialchars($gallery_id); ?>"
            data-index="<?php echo (int) $idx; ?>"
            data-url="<?php echo htmlspecialchars($url); ?>"
            title="Click to preview">
            <img src="<?php echo htmlspecialchars($url); ?>" alt="Screenshot <?php echo (int) $idx + 1; ?>"
                class="img-fluid rounded border w-100 issue-gallery-img">
        </button>
    </div>
    <?php } ?>
</div>
