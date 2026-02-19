<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <a href="index.php" class="sidebar-brand">
        <i class="fas fa-shapes"></i>
        <span>Kreasi Pro</span>
    </a>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="index.php" class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <span class="text-uppercase text-muted fw-bold small px-3 mt-3 mb-2 d-block" style="font-size: 0.75rem;">Content Management</span>
        </li>
        <li class="nav-item">
            <a href="edit_hero.php" class="nav-link <?= $currentPage == 'edit_hero.php' ? 'active' : '' ?>">
                <i class="fas fa-image"></i>
                <span>Hero Section</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="edit_about.php" class="nav-link <?= $currentPage == 'edit_about.php' ? 'active' : '' ?>">
                <i class="fas fa-info-circle"></i>
                <span>About & Features</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="edit_services.php" class="nav-link <?= $currentPage == 'edit_services.php' ? 'active' : '' ?>">
                <i class="fas fa-box"></i>
                <span>Products & Services</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="edit_portfolio.php" class="nav-link <?= $currentPage == 'edit_portfolio.php' ? 'active' : '' ?>">
                <i class="fas fa-images"></i>
                <span>Portfolio</span>
            </a>
        </li>
        <li class="nav-item">
            <span class="text-uppercase text-muted fw-bold small px-3 mt-3 mb-2 d-block" style="font-size: 0.75rem;">Settings</span>
        </li>
        <li class="nav-item">
            <a href="edit_seo.php" class="nav-link <?= $currentPage == 'edit_seo.php' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i>
                <span>SEO & Contact</span>
            </a>
        </li>
        <li class="nav-item mt-4">
            <a href="logout.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
