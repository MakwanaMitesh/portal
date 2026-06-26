<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'views/common/header.php');
$issue = $issue;
$priority_class = [
    'Urgent' => 'danger',
    'High' => 'warning',
    'Normal' => 'primary',
    'Low' => 'secondary',
];
$prio = $priority_class[$issue->priority] ?? 'secondary';
$status_class = ($issue->status === 'Open') ? 'info' : 'success';
?>

<style>
.issue-gallery-img {
    aspect-ratio: 4/3;
    object-fit: cover;
    cursor: zoom-in;
    transition: transform 0.15s, box-shadow 0.15s;
}
.issue-gallery-thumb:hover .issue-gallery-img {
    transform: scale(1.02);
    box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.12);
}
#issuePreviewModal .modal-dialog {
    max-width: min(96vw, 1100px);
}
#issuePreviewModal .preview-stage {
    min-height: 280px;
    max-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--phoenix-emphasis-bg, #f5f7fa);
}
#issuePreviewModal .preview-stage img {
    max-width: 100%;
    max-height: 75vh;
    object-fit: contain;
}
</style>

<div class="mb-4">
    <br>
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis pt-6 border-top border-bottom pb-6">
        <?php if (!empty($flash_success)) { ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($flash_success); ?></div>
        <?php } ?>
        <?php if (!empty($flash_error)) { ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($flash_error); ?></div>
        <?php } ?>

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0 fs-10">
                <li class="breadcrumb-item"><a href="<?php echo base_url('my-task/' . rawurlencode('In Progress')); ?>">Issues to fix</a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url('task-issues/' . (int) $issue->task_id); ?>">Task issues</a></li>
                <li class="breadcrumb-item active">Issue #<?php echo (int) $issue->issue_id; ?></li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border border-translucent mb-4">
                    <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h4 class="mb-1"><?php echo htmlspecialchars($issue->issue_title); ?></h4>
                            <p class="fs-10 text-body-tertiary mb-0">
                                Reported <?php echo date('d M Y, H:i', strtotime($issue->created_on)); ?>
                                <?php if (!empty($issue->updated_at)) { ?>
                                · Updated <?php echo date('d M Y, H:i', strtotime($issue->updated_at)); ?>
                                <?php } ?>
                            </p>
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="badge badge-phoenix badge-phoenix-<?php echo $prio; ?>"><?php echo htmlspecialchars($issue->priority); ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="text-body-secondary text-uppercase fs-10 mb-2">Description</h6>
                        <div class="fs-9 mb-4 issue-desc-block">
                            <?php if (!empty(trim($issue->issue_desc ?? ''))) { ?>
                            <?php echo nl2br(htmlspecialchars($issue->issue_desc)); ?>
                            <?php } else { ?>
                            <span class="text-body-tertiary">No description provided.</span>
                            <?php } ?>
                        </div>

                        <h6 class="text-body-secondary text-uppercase fs-10 mb-3">
                            Screenshots
                            <?php if (!empty($issue->images)) { ?>
                            <span class="text-body-tertiary fw-normal">(<?php echo count($issue->images); ?> — click to preview)</span>
                            <?php } ?>
                        </h6>
                        <?php $this->load->view('task_management/partials/issue_image_gallery', [
                            'images' => $issue->images ?? [],
                            'gallery_id' => 'issueDetailGallery',
                        ]); ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border border-translucent mb-4">
                    <div class="card-header py-2"><h6 class="mb-0">Issue status</h6></div>
                    <div class="card-body fs-9">
                        <label class="form-label">Change this issue’s status</label>
                        <?php $this->load->view('task_management/partials/issue_status_select', [
                            'issue_id' => $issue->issue_id,
                            'current_status' => $issue->status,
                        ]); ?>
                        <p class="fs-10 text-body-tertiary mt-2 mb-0">
                            <strong>Open</strong> — not fixed yet.
                            <strong>Fixed</strong> — developer fixed (also set via Log Fix Time).
                            <strong>Closed</strong> — verified / done.
                            <strong>Reopened</strong> — issue came back after fix.
                        </p>
                    </div>
                </div>

                <div class="card border border-translucent mb-4">
                    <div class="card-header py-2"><h6 class="mb-0">Issue details</h6></div>
                    <div class="card-body fs-9">
                        <dl class="row mb-0">
                            <dt class="col-5 text-body-secondary">Parent task</dt>
                            <dd class="col-7">
                                <a href="<?php echo base_url('edit-task/' . (int) $issue->task_id); ?>"><?php echo htmlspecialchars($issue->task_heading); ?></a>
                                <br><span class="badge badge-phoenix badge-phoenix-warning mt-1"><?php echo htmlspecialchars($issue->task_status); ?></span>
                            </dd>
                            <dt class="col-5 text-body-secondary">Project</dt>
                            <dd class="col-7"><?php echo htmlspecialchars($issue->project_name ?? '—'); ?></dd>
                            <dt class="col-5 text-body-secondary">Service</dt>
                            <dd class="col-7"><?php echo htmlspecialchars($issue->service_name ?? '—'); ?></dd>
                            <?php if (!empty($issue->module_name)) { ?>
                            <dt class="col-5 text-body-secondary">Module</dt>
                            <dd class="col-7"><?php echo htmlspecialchars($issue->module_name); ?></dd>
                            <?php } ?>
                            <dt class="col-5 text-body-secondary">Reported by</dt>
                            <dd class="col-7"><?php echo htmlspecialchars($issue->reporter_name ?? '—'); ?></dd>
                            <dt class="col-5 text-body-secondary">Assigned developer</dt>
                            <dd class="col-7"><?php echo htmlspecialchars($issue->assigned_name ?? '—'); ?></dd>
                            <dt class="col-5 text-body-secondary">Developer fix time</dt>
                            <dd class="col-7">
                                <?php
                                $fh = (int) ($issue->time_spent_hrs ?? 0);
                                $fm = (int) ($issue->time_spent_min ?? 0);
                                echo ($fh || $fm) ? "{$fh}h {$fm}m" : '—';
                                ?>
                            </dd>
                        </dl>
                    </div>
                </div>

                <?php $this->load->view('task_management/partials/task_time_summary', ['time_summary' => $time_summary ?? null]); ?>

                <div class="d-flex flex-wrap gap-2">
                    <!-- <a href="<?php echo base_url('task-issues/' . (int) $issue->task_id); ?>" class="btn btn-phoenix-warning btn-sm">All issues for this task</a> -->
                    <!-- <a href="<?php echo base_url('edit-task/' . (int) $issue->task_id); ?>" class="btn btn-phoenix-primary btn-sm">Open parent task</a> -->
                    <!-- <?php if (!empty($can_add_issues) && $issue->task_status === 'Ready for Testing') { ?>
                    <a href="<?php echo base_url('report-issues/' . (int) $issue->task_id); ?>" class="btn btn-phoenix-danger btn-sm">Add more issues</a>
                    <?php } ?> -->
                    <a href="<?php echo base_url('task-issues/' . (int) $issue->task_id); ?>" class="btn btn-phoenix-secondary btn-sm">Back to issue list</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="issuePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Screenshot preview <span id="previewCounter" class="text-body-secondary fw-normal"></span></h6>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="previewOpenNew" class="btn btn-link btn-sm" target="_blank" rel="noopener">Open full size</a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn btn-phoenix-secondary position-absolute top-50 start-0 translate-middle-y ms-2 z-1" id="previewPrev" style="display:none;" aria-label="Previous">
                    <span class="fas fa-chevron-left"></span>
                </button>
                <div class="preview-stage">
                    <img src="" alt="Preview" id="previewImage">
                </div>
                <button type="button" class="btn btn-phoenix-secondary position-absolute top-50 end-0 translate-middle-y me-2 z-1" id="previewNext" style="display:none;" aria-label="Next">
                    <span class="fas fa-chevron-right"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php include(APPPATH.'views/common/footer.php'); ?>

<script>
(function() {
    var galleryUrls = <?php
        $urls = [];
        foreach ($issue->images ?? [] as $img) {
            $urls[] = !empty($img->url) ? $img->url : base_url('uploads/issue_images/' . ($img->file_name ?? ''));
        }
        echo json_encode($urls);
    ?>;
    var currentIdx = 0;
    var $modal = $('#issuePreviewModal');
    var $img = $('#previewImage');

    function showPreview(idx) {
        if (!galleryUrls.length) return;
        currentIdx = idx;
        if (currentIdx < 0) currentIdx = galleryUrls.length - 1;
        if (currentIdx >= galleryUrls.length) currentIdx = 0;
        var url = galleryUrls[currentIdx];
        $img.attr('src', url);
        $('#previewOpenNew').attr('href', url);
        $('#previewCounter').text('(' + (currentIdx + 1) + ' / ' + galleryUrls.length + ')');
        $('#previewPrev').toggle(galleryUrls.length > 1);
        $('#previewNext').toggle(galleryUrls.length > 1);
    }

    $(document).on('click', '.issue-gallery-thumb', function() {
        var idx = parseInt($(this).data('index'), 10) || 0;
        showPreview(idx);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('issuePreviewModal')).show();
    });

    $('#previewPrev').on('click', function() { showPreview(currentIdx - 1); });
    $('#previewNext').on('click', function() { showPreview(currentIdx + 1); });

    $(document).on('keydown', function(e) {
        if (!$modal.hasClass('show')) return;
        if (e.key === 'ArrowLeft') { showPreview(currentIdx - 1); }
        if (e.key === 'ArrowRight') { showPreview(currentIdx + 1); }
    });
})();
</script>
