<?php
define('KREASI_PRO_LOADED', true);
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$dataFile = __DIR__ . '/../data/content.json';
$data = json_decode(file_get_contents($dataFile), true);
$about = $data['about_section'];
$features = $data['features_section'];
$csrfToken = generateCsrfToken();

$pageTitle = "Edit About & Features";
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/topbar.php';
?>

<div class="row">
    <!-- About Section -->
    <div class="col-lg-6 mb-4">
        <form id="aboutForm">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <input type="hidden" name="section" value="about">
            
            <div class="card h-100">
                <div class="card-header bg-white">
                    <i class="fas fa-info-circle me-2 text-primary"></i> About Section
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">SECTION TITLE (SMALL)</label>
                        <input type="text" name="title" class="form-control" value="<?= escapeHtml($about['title']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">MAIN HEADLINE (BIG)</label>
                        <textarea name="headline" class="form-control fw-bold" rows="3"><?= escapeHtml($about['headline']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">DESCRIPTION TEXT</label>
                        <textarea name="text" class="form-control" rows="6"><?= escapeHtml($about['text']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">IMAGE</label>
                        <div class="card border mb-2">
                            <div class="card-body p-2 text-center">
                                <img src="../<?= escapeUrl($about['image']) ?>" id="aboutPreview" class="img-fluid rounded shadow-sm mb-3" style="max-height: 200px;">
                                <div class="input-group">
                                    <input type="text" name="image" id="aboutImageInput" class="form-control" value="<?= escapeHtml($about['image']) ?>" readonly>
                                    <button class="btn btn-primary" type="button" id="uploadAboutBtn">
                                        <i class="fas fa-upload me-2"></i> Upload Photo
                                    </button>
                                </div>
                                <input type="file" id="aboutFileInput" class="d-none" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">
                        <i class="fas fa-save me-2"></i> Save About
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Features Section -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <i class="fas fa-star me-2 text-warning"></i> Why Us / Features
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">TITLE</label>
                    <input type="text" class="form-control" value="<?= escapeHtml($features['title']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">HEADLINE</label>
                    <textarea class="form-control" rows="2" readonly><?= escapeHtml($features['headline']) ?></textarea>
                </div>
                
                <!-- Features List -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold">Active Features</h6>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill" onclick="openFeatureModal()">
                        <i class="fas fa-plus me-1"></i> Add New
                    </button>
                </div>

                <div class="list-group list-group-flush border rounded">
                    <?php if (!empty($features['items'])): ?>
                        <?php foreach ($features['items'] as $index => $item): ?>
                        <div class="list-group-item d-flex align-items-center justify-content-between p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="<?= escapeAttr($item['icon']) ?> text-primary fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-dark fw-bold"><?= escapeHtml($item['title']) ?></h6>
                                    <small class="text-muted d-block text-truncate" style="max-width: 250px;">
                                        <?= escapeHtml($item['text']) ?>
                                    </small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                    <li>
                                        <button class="dropdown-item" onclick="editFeature(<?= $index ?>, '<?= escapeAttr($item['icon']) ?>', '<?= escapeAttr($item['title']) ?>', '<?= escapeAttr($item['text']) ?>')">
                                            <i class="fas fa-edit text-primary me-2"></i> Edit
                                        </button>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item text-danger" onclick="deleteFeature(<?= $index ?>)">
                                            <i class="fas fa-trash me-2"></i> Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No features added yet.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Feature Modal -->
<div class="modal fade" id="featureModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="featureModalTitle">Add Feature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="featureForm">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="action" value="save_feature">
                    <input type="hidden" name="index" id="featureIndex" value="-1">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">ICON CLASS (FontAwesome)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-icons" id="iconPreview"></i></span>
                            <input type="text" name="icon" id="featureIcon" class="form-control" placeholder="fas fa-check" oninput="$('#iconPreview').attr('class', this.value)">
                        </div>
                        <div class="form-text">Example: <code>fas fa-check</code>, <code>fas fa-star</code></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">TITLE</label>
                        <input type="text" name="title" id="featureTitle" class="form-control" required placeholder="Ex: Premium Quality">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">DESCRIPTION</label>
                        <textarea name="text" id="featureText" class="form-control" rows="3" required placeholder="Short description..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Feature</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- Image Upload Logic ---
        $('#uploadAboutBtn').click(function() {
            $('#aboutFileInput').click();
        });

        $('#aboutFileInput').change(function() {
            const file = this.files[0];
            if (!file) return;

            const btn = $('#uploadAboutBtn');
            const originalContent = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

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
                        $('#aboutImageInput').val(res.path);
                        $('#aboutPreview').attr('src', '../' + res.path);
                    } else {
                        alert('Upload failed: ' + res.message);
                    }
                },
                error: function() {
                    alert('Upload error.');
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalContent);
                }
            });
        });

        // --- Save About Form ---
        $('#aboutForm').on('submit', function(e) {
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
                        // Toast or Alert
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'position-fixed top-0 end-0 p-3';
                        alertDiv.style.zIndex = '9999';
                        alertDiv.innerHTML = `
                            <div class="toast show align-items-center text-white bg-success border-0" role="alert">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        <i class="fas fa-check-circle me-2"></i> About section updated!
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(alertDiv);
                        setTimeout(() => alertDiv.remove(), 3000);
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

        // --- Feature Form Submit ---
        $('#featureForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'save_array.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if(res.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + res.message);
                    }
                }
            });
        });
    });

    // --- Feature Helpers ---
    function openFeatureModal() {
        $('#featureModalTitle').text('Add Feature');
        $('#featureIndex').val('-1');
        $('#featureForm')[0].reset();
        $('#iconPreview').attr('class', 'fas fa-icons');
        var myModal = new bootstrap.Modal(document.getElementById('featureModal'));
        myModal.show();
    }

    function editFeature(index, icon, title, text) {
        $('#featureModalTitle').text('Edit Feature');
        $('#featureIndex').val(index);
        $('#featureIcon').val(icon);
        $('#featureTitle').val(title);
        $('#featureText').val(text);
        $('#iconPreview').attr('class', icon);
        var myModal = new bootstrap.Modal(document.getElementById('featureModal'));
        myModal.show();
    }

    function deleteFeature(index) {
        if(!confirm('Are you sure you want to delete this feature?')) return;

        $.post('save_array.php', {
            action: 'delete_feature',
            index: index,
            csrf_token: '<?= $csrfToken ?>'
        }, function(res) {
            if(res.success) {
                location.reload();
            } else {
                alert('Error: ' + res.message);
            }
        }, 'json');
    }
</script>

<?php require_once 'layout/footer.php'; ?>
