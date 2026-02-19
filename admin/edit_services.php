<?php
define('KREASI_PRO_LOADED', true);
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$dataFile = __DIR__ . '/../data/content.json';
$data = json_decode(file_get_contents($dataFile), true);
$products = $data['products'];
$csrfToken = generateCsrfToken();

$pageTitle = "Manage Services";
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/topbar.php';
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-primary"></i> Service List</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" onclick="resetForm()">
            <i class="fas fa-plus me-2"></i> Add New Service
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">Image</th>
                        <th class="py-3 border-0">Service Details</th>
                        <th class="px-4 py-3 border-0 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $index => $product): ?>
                    <tr>
                        <td class="px-4" width="120">
                            <img src="../<?= escapeUrl($product['image']) ?>" class="rounded shadow-sm" style="width: 80px; height: 60px; object-fit: cover;">
                        </td>
                        <td>
                            <h6 class="mb-1 fw-bold text-dark"><?= escapeHtml($product['name']) ?></h6>
                            <small class="text-muted d-block text-truncate" style="max-width: 400px;"><?= escapeHtml($product['description']) ?></small>
                        </td>
                        <td class="px-4 text-end">
                            <button class="btn btn-sm btn-light text-primary me-2 edit-btn" 
                                    data-index="<?= $index ?>"
                                    data-name="<?= escapeAttr($product['name']) ?>"
                                    data-desc="<?= escapeAttr($product['description']) ?>"
                                    data-img="<?= escapeAttr($product['image']) ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-light text-danger delete-btn" data-index="<?= $index ?>">
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

<!-- Edit/Add Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="serviceForm">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="section" value="services">
                    <input type="hidden" name="index" id="serviceIndex" value="-1">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">SERVICE NAME</label>
                        <input type="text" name="name" id="serviceName" class="form-control" placeholder="e.g Custom Booth" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">DESCRIPTION</label>
                        <textarea name="description" id="serviceDesc" class="form-control" rows="4" placeholder="Brief description..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">IMAGE</label>
                        <div class="input-group">
                            <input type="text" name="image" id="serviceImg" class="form-control" placeholder="assets/..." required>
                            <button class="btn btn-outline-secondary" type="button" id="uploadBtn">
                                <i class="fas fa-upload"></i>
                            </button>
                        </div>
                        <input type="file" id="fileInput" class="d-none" accept="image/*">
                        <div id="imgPreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Modal Handling
        window.resetForm = function() {
            $('#serviceIndex').val('-1');
            $('#serviceName').val('');
            $('#serviceDesc').val('');
            $('#serviceImg').val('');
            $('#imgPreview').html('');
            $('.modal-title').text('Add New Service');
        }

        $('.edit-btn').click(function() {
            const btn = $(this);
            $('#serviceIndex').val(btn.data('index'));
            $('#serviceName').val(btn.data('name'));
            $('#serviceDesc').val(btn.data('desc'));
            $('#serviceImg').val(btn.data('img'));
            $('.modal-title').text('Edit Service');
            $('#editModal').modal('show');
        });

        // Image Upload
        $('#uploadBtn').click(function() {
            $('#fileInput').click();
        });

        $('#fileInput').change(function() {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('csrf_token', '<?= $csrfToken ?>');

            // Show loading
            $('#uploadBtn').html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: 'upload.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if(res.success) {
                        $('#serviceImg').val(res.path);
                    } else {
                        alert('Upload failed: ' + res.message);
                    }
                },
                complete: function() {
                    $('#uploadBtn').html('<i class="fas fa-upload"></i>');
                }
            });
        });

        // Save Data
        $('#serviceForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'save_product');

            $.ajax({
                url: 'save_array.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Network error.');
                }
            });
        });

        // Delete Handler
        $('.delete-btn').click(function() {
            if(!confirm('Are you sure you want to delete this service?')) return;
            
            const index = $(this).data('index');
            const csrf = '<?= $csrfToken ?>';

            $.ajax({
                url: 'save_array.php',
                type: 'POST',
                data: {
                    action: 'delete_product',
                    index: index,
                    csrf_token: csrf
                },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });
    });
</script>

<?php require_once 'layout/footer.php'; ?>
