<?php
define('KREASI_PRO_LOADED', true);
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$dataFile = __DIR__ . '/../data/content.json';
$data = json_decode(file_get_contents($dataFile), true);
$categories = $data['portfolio']['categories'];
$captions = $data['portfolio']['captions'];
$csrfToken = generateCsrfToken();

// Get Current Selected Category for Gallery Tab
$selectedCatId = $_GET['cat'] ?? null;
// If not set, pick first one
if (!$selectedCatId && !empty($categories)) {
    $selectedCatId = reset($categories);
}

// Get Images for Selected Category
$currentImages = [];
if ($selectedCatId) {
    $dir = __DIR__ . '/../assets/img/porto/' . $selectedCatId;
    if (is_dir($dir)) {
        $files = glob($dir . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE);
        foreach ($files as $file) {
            $filename = basename($file);
            $caption = $captions[$selectedCatId][$filename] ?? '';
            $currentImages[] = [
                'filename' => $filename,
                'path' => 'assets/img/porto/' . $selectedCatId . '/' . $filename,
                'caption' => $caption
            ];
        }
    }
}

$pageTitle = "Portfolio Manager";
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/topbar.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active fw-bold" data-bs-toggle="tab" href="#galleryTab">
                            <i class="fas fa-images me-2"></i> Gallery Manager
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" data-bs-toggle="tab" href="#categoriesTab">
                            <i class="fas fa-folder me-2"></i> Categories
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-4">
                <div class="tab-content">
                    
                    <!-- GALLERY TAB -->
                    <div class="tab-pane fade show active" id="galleryTab">
                        <!-- Category Selector -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <label class="fw-bold text-muted">Select Category:</label>
                                <select class="form-select w-auto" id="catSelector" onchange="window.location.href='?cat='+this.value">
                                    <?php foreach ($categories as $id => $name): ?>
                                        <option value="<?= $id ?>" <?= $selectedCatId === $id ? 'selected' : '' ?>>
                                            <?= escapeHtml($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button class="btn btn-primary" onclick="$('#fileInput').click()">
                                <i class="fas fa-cloud-upload-alt me-2"></i> Upload Images
                            </button>
                            <input type="file" id="fileInput" class="d-none" accept="image/*">
                        </div>

                        <!-- Image Grid -->
                        <?php if (empty($currentImages)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-images fa-3x mb-3 text-light"></i>
                                <p>No images in this category yet. Upload some!</p>
                            </div>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach ($currentImages as $img): ?>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="card h-100 shadow-sm border position-relative group-hover">
                                        <div class="ratio ratio-1x1 bg-light rounded-top overflow-hidden">
                                            <img src="../<?= escapeUrl($img['path']) ?>" class="img-fluid object-fit-cover" loading="lazy">
                                        </div>
                                        <div class="card-body p-2">
                                            <input type="text" class="form-control form-control-sm border-0 bg-light mb-2 caption-input" 
                                                   value="<?= escapeHtml($img['caption']) ?>" 
                                                   placeholder="Add caption..."
                                                   data-filename="<?= $img['filename'] ?>">
                                            <button class="btn btn-xs btn-outline-danger w-100 delete-img-btn" data-filename="<?= $img['filename'] ?>">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- CATEGORIES TAB -->
                    <div class="tab-pane fade" id="categoriesTab">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-bold">Manage Categories</h5>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCatModal">
                                <i class="fas fa-plus me-1"></i> Add Category
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Display Name</th>
                                        <th>Folder ID</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $id => $name): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?= escapeHtml($name) ?></td>
                                        <td><code class="text-muted"><?= escapeHtml($id) ?></code></td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light text-primary edit-cat-btn" 
                                                    data-name="<?= escapeAttr($name) ?>" 
                                                    data-id="<?= escapeAttr($id) ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light text-danger delete-cat-btn" 
                                                    data-id="<?= escapeAttr($id) ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addCatForm">
                <div class="modal-header">
                    <h5 class="modal-title">New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_category">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">DISPLAY NAME</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Wedding Events" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">FOLDER ID (No spaces)</label>
                        <input type="text" name="id" class="form-control" placeholder="e.g. wedding" required pattern="[a-z0-9-_]+">
                        <div class="form-text">Lowercase only, use '-' for spaces.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editCatForm">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_category">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="id" id="editCatId">
                    <input type="hidden" name="old_name" id="editCatOldName">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">DISPLAY NAME</label>
                        <input type="text" name="name" id="editCatName" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const csrfToken = '<?= $csrfToken ?>';
        const currentCat = '<?= $selectedCatId ?>';

        // --- Category Logic ---
        
        // Add Category
        $('#addCatForm').submit(function(e) {
            e.preventDefault();
            $.post('save_portfolio.php', $(this).serialize(), function(res) {
                if(res.success) location.reload();
                else alert(res.message);
            });
        });

        // Edit Category
        $('.edit-cat-btn').click(function() {
            $('#editCatId').val($(this).data('id'));
            $('#editCatOldName').val($(this).data('name'));
            $('#editCatName').val($(this).data('name'));
            $('#editCatModal').modal('show');
        });

        $('#editCatForm').submit(function(e) {
            e.preventDefault();
            $.post('save_portfolio.php', $(this).serialize(), function(res) {
                if(res.success) location.reload();
                else alert(res.message);
            });
        });

        // Delete Category
        $('.delete-cat-btn').click(function() {
            if(!confirm('Delete this category? Folder must be empty first.')) return;
            $.post('save_portfolio.php', {
                action: 'delete_category',
                id: $(this).data('id'),
                csrf_token: csrfToken
            }, function(res) {
                if(res.success) location.reload();
                else alert(res.message);
            });
        });

        // --- Gallery Logic ---

        // Upload Image
        $('#fileInput').change(function() {
            if(!this.files.length) return;
            const file = this.files[0];
            const formData = new FormData();
            formData.append('action', 'upload_image');
            formData.append('image', file);
            formData.append('category_id', currentCat);
            formData.append('csrf_token', csrfToken);

            // Show global loader or something? For now just blocking alert
            // Ideally UI shoudl update. Reloading for simplicity.
            $.ajax({
                url: 'save_portfolio.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if(res.success) location.reload();
                    else alert(res.message);
                }
            });
        });

        // Delete Image
        $('.delete-img-btn').click(function() {
            if(!confirm('Delete this image?')) return;
            const filename = $(this).data('filename');
            $.post('save_portfolio.php', {
                action: 'delete_image',
                category_id: currentCat,
                filename: filename,
                csrf_token: csrfToken
            }, function(res) {
                if(res.success) location.reload();
                else alert(res.message);
            });
        });

        // Update Caption (Auto-save on blur)
        $('.caption-input').blur(function() {
            const filename = $(this).data('filename');
            const caption = $(this).val();
            // Don't send if empty/unchanged? We can send anyway.
            
            $.post('save_portfolio.php', {
                action: 'update_caption',
                category_id: currentCat,
                filename: filename,
                caption: caption,
                csrf_token: csrfToken
            }); 
            // Silent update
        });
    });
</script>

<?php require_once 'layout/footer.php'; ?>
