<?php
define('KREASI_PRO_LOADED', true);
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$dataFile = __DIR__ . '/../data/content.json';
$data = json_decode(file_get_contents($dataFile), true);
$site = $data['site_settings'];
$contact = $data['contact_info'];
$social = $data['social_media'];
$csrfToken = generateCsrfToken();

$pageTitle = "SEO & Contact Settings";
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/topbar.php';
?>

<form id="seoForm">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    <input type="hidden" name="section" value="seo">

    <div class="row">
        <!-- SEO Settings -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <i class="fas fa-search me-2 text-primary"></i> SEO Configuration
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">SITE TITLE</label>
                        <input type="text" name="site_title" class="form-control" value="<?= escapeHtml($site['title']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">META DESCRIPTION</label>
                        <textarea name="site_description" class="form-control" rows="4"><?= escapeHtml($site['description']) ?></textarea>
                        <small class="text-muted">Recommended: 150-160 characters</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">KEYWORDS</label>
                        <textarea name="site_keywords" class="form-control" rows="2"><?= escapeHtml($site['keywords']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">AUTHOR</label>
                        <input type="text" name="site_author" class="form-control" value="<?= escapeHtml($site['author']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Social -->
        <div class="col-lg-6 mb-4">
            
            <!-- Contact Info -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <i class="fas fa-address-book me-2 text-success"></i> Contact Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">WHATSAPP</label>
                            <input type="text" name="whatsapp" class="form-control" value="<?= escapeHtml($contact['whatsapp']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">EMAIL</label>
                            <input type="email" name="email" class="form-control" value="<?= escapeHtml($contact['email']) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">ADDRESS</label>
                        <textarea name="address" class="form-control" rows="2"><?= escapeHtml($contact['address']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">GOOGLE MAPS EMBED/LINK</label>
                        <input type="text" name="maps_link" class="form-control" value="<?= escapeUrl($contact['maps_link']) ?>">
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="card">
                <div class="card-header bg-white">
                    <i class="fas fa-share-alt me-2 text-info"></i> Social Media
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0"><i class="fab fa-instagram text-danger"></i></span>
                        <input type="text" name="instagram" class="form-control border-start-0" placeholder="Instagram URL" value="<?= escapeUrl($social['instagram']) ?>">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0"><i class="fab fa-youtube text-danger"></i></span>
                        <input type="text" name="youtube" class="form-control border-start-0" placeholder="YouTube URL" value="<?= escapeUrl($social['youtube']) ?>">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0"><i class="fab fa-facebook text-primary"></i></span>
                        <input type="text" name="facebook" class="form-control border-start-0" placeholder="Facebook URL" value="<?= escapeUrl($social['facebook'] ?? '') ?>">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0"><i class="fab fa-tiktok text-dark"></i></span>
                        <input type="text" name="tiktok" class="form-control border-start-0" placeholder="TikTok URL" value="<?= escapeUrl($social['tiktok'] ?? '') ?>">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-end mb-5">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save me-2"></i> Save All Settings
        </button>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#seoForm').on('submit', function(e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            const originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Saving...');

            $.ajax({
                url: 'save.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        alert('Saved successfully!');
                        location.reload();
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
