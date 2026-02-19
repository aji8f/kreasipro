<!-- Main Content Wrapper -->
<main class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center">
            <button class="btn btn-light d-lg-none me-3" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h4 class="mb-0 fw-bold"><?= isset($pageTitle) ? $pageTitle : 'Dashboard' ?></h4>
        </div>
        <div class="user-profile d-flex align-items-center gap-3">
            <a href="../" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                <i class="fas fa-external-link-alt me-1"></i> Visit Website
            </a>
            <div class="d-none d-md-flex align-items-center gap-2">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    <i class="fas fa-user small"></i>
                </div>
                <!-- <span class="fw-medium">Admin</span> -->
            </div>
        </div>
    </div>
