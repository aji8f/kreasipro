<?php
/**
 * Admin Dashboard
 */

define('KREASI_PRO_LOADED', true);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/auth.php';

requireLogin();

// Load Data for Stats
$dataFile = __DIR__ . '/../data/content.json';
$data = json_decode(file_get_contents($dataFile), true);
$isWritable = is_writable($dataFile);

// Calculate Stats
$serviceCount = count($data['products'] ?? []);
$portfolioCount = 0;
foreach($data['portfolio']['captions'] ?? [] as $cat) {
    if(is_array($cat)) $portfolioCount += count($cat);
}
$socialCount = count(array_filter($data['social_media'] ?? []));

$pageTitle = "Dashboard";
require_once 'layout/header.php';
require_once 'layout/sidebar.php';
require_once 'layout/topbar.php';
?>

<!-- Content -->
<?php if (!$isWritable): ?>
    <div class="alert alert-danger mb-4 shadow-sm border-0">
        <i class="fas fa-exclamation-triangle me-2"></i> 
        <strong>Warning:</strong> Data file (<code>data/content.json</code>) is not writable! Changes cannot be saved.
        Please check file permissions.
    </div>
<?php endif; ?>

<div class="row g-4 mb-5">
    <!-- Stats Cards -->
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card h-100">
            <div class="stats-icon bg-soft-primary">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <h3 class="mb-0 fw-bold"><?= $serviceCount ?></h3>
                <span class="text-muted small">Active Services</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card h-100">
            <div class="stats-icon bg-soft-success">
                <i class="fas fa-images"></i>
            </div>
            <div>
                <h3 class="mb-0 fw-bold"><?= $portfolioCount ?></h3>
                <span class="text-muted small">Portfolio Photos</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card h-100">
            <div class="stats-icon bg-soft-info">
                <i class="fas fa-share-alt"></i>
            </div>
            <div>
                <h3 class="mb-0 fw-bold"><?= $socialCount ?></h3>
                <span class="text-muted small">Social Profiles</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card h-100">
            <div class="stats-icon bg-soft-warning">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h3 class="mb-0 fw-bold">Active</h3>
                <span class="text-muted small">System Status</span>
            </div>
        </div>
    </div>
</div>

<h5 class="mb-3 fw-bold text-muted">Quick Actions</h5>
<div class="row g-4">
    <div class="col-md-6 col-lg-4">
        <a href="edit_hero.php" class="card h-100 text-decoration-none">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-light p-3 me-3 text-primary">
                    <i class="fas fa-image fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-1 text-dark fw-bold">Edit Hero Section</h6>
                    <small class="text-muted">Change banners & headlines</small>
                </div>
                <i class="fas fa-chevron-right ms-auto text-muted"></i>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="edit_services.php" class="card h-100 text-decoration-none">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-light p-3 me-3 text-success">
                    <i class="fas fa-box fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-1 text-dark fw-bold">Manage Services</h6>
                    <small class="text-muted">Add or remove services</small>
                </div>
                <i class="fas fa-chevron-right ms-auto text-muted"></i>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="edit_seo.php" class="card h-100 text-decoration-none">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-light p-3 me-3 text-info">
                    <i class="fas fa-search fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-1 text-dark fw-bold">SEO Settings</h6>
                    <small class="text-muted">Update meta tags & contacts</small>
                </div>
                <i class="fas fa-chevron-right ms-auto text-muted"></i>
            </div>
        </a>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
