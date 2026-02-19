<?php
define('KREASI_PRO_LOADED', true);
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$dataFile = __DIR__ . '/../data/content.json';
$data = json_decode(file_get_contents($dataFile), true);
$hero = $data['hero_section'];
$csrfToken = generateCsrfToken();

$pageTitle = "Edit Hero Section";
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/topbar.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <form id="heroForm">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <input type="hidden" name="section" value="hero">

            <!-- Sub Headline -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-layer-group me-2"></i> Sub Headline (Top Dark Bar)
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">TITLE</label>
                                <textarea name="sub_title" class="form-control" rows="2"><?= escapeHtml($hero['sub_headline']['title']) ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">BUTTON TEXT</label>
                                <input type="text" name="button_text" class="form-control" value="<?= escapeHtml($hero['sub_headline']['button_text']) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">TEXT DESCRIPTION</label>
                        <textarea name="sub_text" class="form-control" rows="3"><?= escapeHtml($hero['sub_headline']['text']) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Main Headline -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-heading me-2"></i> Main Hero (Carousel Section)
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">MAIN TITLE</label>
                        <textarea name="main_title" class="form-control fw-bold" rows="2"><?= escapeHtml($hero['main_headline']['title']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">MAIN TEXT</label>
                        <textarea name="main_text" class="form-control" rows="3"><?= escapeHtml($hero['main_headline']['text']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">CAROUSEL IMAGES</label>
                        <div class="row g-3">
                            <?php foreach($hero['main_headline']['images'] as $index => $img): ?>
                            <div class="col-6 col-md-4">
                                <div class="card h-100 border shadow-none">
                                    <div class="card-body p-2 text-center">
                                        <div class="mb-2 position-relative">
                                            <img src="../<?= escapeUrl($img) ?>" id="preview_<?= $index ?>" class="img-fluid rounded border shadow-sm" style="height: 120px; object-fit: cover; width: 100%;">
                                        </div>
                                        
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="text" name="images[<?= $index ?>]" id="input_<?= $index ?>" class="form-control" value="<?= escapeHtml($img) ?>" readonly>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-outline-primary w-100 upload-btn" data-index="<?= $index ?>">
                                            <i class="fas fa-upload me-1"></i> Change Image
                                        </button>
                                        <input type="file" class="d-none file-input" data-index="<?= $index ?>" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Handle Image Uploads
        $('.upload-btn').click(function() {
            const index = $(this).data('index');
            $(`.file-input[data-index="${index}"]`).click();
        });

        $('.file-input').change(function() {
            const index = $(this).data('index');
            const file = this.files[0];
            if (!file) return;

            // Show loading state
            const btn = $(`.upload-btn[data-index="${index}"]`);
            const width = btn.width();
            const originalContent = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>').width(width);

            const formData = new FormData();
            formData.append('image', file);
            formData.append('csrf_token', '<?= $csrfToken ?>');

            $.ajax({
                url: 'upload.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if(res.success) {
                        // Update preview and input value
                        $(`#preview_${index}`).attr('src', '../' + res.path);
                        $(`#input_${index}`).val(res.path);
                    } else {
                        alert('Upload failed: ' + res.message);
                    }
                },
                error: function() {
                    alert('Upload error occurred.');
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalContent);
                }
            });
        });

        // Handle Form Submit
        $('#heroForm').on('submit', function(e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            const originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Saving...');

            // Gather all image inputs into array for submission
            // Note: Data serialization handles array inputs like images[0], images[1] correctly
            
            $.ajax({
                url: 'save.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        alert('Saved successfully!');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Network error occurred.');
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
</script>

<?php require_once 'layout/footer.php'; ?>
