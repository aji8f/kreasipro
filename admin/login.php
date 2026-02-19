<?php
/**
 * Admin Login Page — with brute-force protection UI
 */

define('KREASI_PRO_LOADED', true);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/auth.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error       = '';
$lockoutSecs = getLockoutRemaining();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please refresh and try again.';
    } elseif ($lockoutSecs > 0) {
        $error = 'Too many failed attempts. Please wait ' . ceil($lockoutSecs / 60) . ' minute(s) before trying again.';
    } else {
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (attemptLogin($username, $password)) {
            header('Location: index.php');
            exit;
        } else {
            $lockoutSecs = getLockoutRemaining();
            if ($lockoutSecs > 0) {
                $error = 'Too many failed attempts. Your account is locked for ' . ceil($lockoutSecs / 60) . ' minute(s).';
            } else {
                $remainingAttempts = MAX_LOGIN_ATTEMPTS - ($_SESSION['login_attempts'] ?? 0);
                $error = 'Invalid username or password. ' . $remainingAttempts . ' attempt(s) remaining.';
            }
        }
    }
}

// Show timeout message if redirected from a stale session
$timeoutMsg = '';
if (isset($_GET['timeout'])) {
    $timeoutMsg = 'Your session has expired. Please log in again.';
}

$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kreasi Pro Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modern.css" rel="stylesheet">
</head>
<body class="login-body">
    <div class="auth-card">
        <div class="text-center mb-4">
            <img src="../assets/logo/logo.png" alt="Logo" style="width: 80px; margin-bottom: 1rem;">
            <h4 class="fw-bold">Welcome Back</h4>
            <p class="text-muted">Sign in to manage your website</p>
        </div>

        <?php if ($timeoutMsg): ?>
            <div class="alert alert-warning py-2 small">
                <i class="fas fa-clock me-1"></i> <?= escapeHtml($timeoutMsg) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small">
                <i class="fas fa-exclamation-triangle me-1"></i> <?= escapeHtml($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">USERNAME</label>
                <input type="text" name="username" class="form-control" placeholder="Enter your username"
                       required autofocus autocomplete="username"
                       <?= $lockoutSecs > 0 ? 'disabled' : '' ?>>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">PASSWORD</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••"
                       required autocomplete="current-password"
                       <?= $lockoutSecs > 0 ? 'disabled' : '' ?>>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mb-3"
                    <?= $lockoutSecs > 0 ? 'disabled' : '' ?>>
                <i class="fas fa-sign-in-alt me-2"></i>
                <?= $lockoutSecs > 0 ? 'Locked (' . ceil($lockoutSecs / 60) . 'm)' : 'Sign In' ?>
            </button>

            <div class="text-center">
                <a href="../" class="text-decoration-none small text-muted">
                    <i class="fas fa-arrow-left me-1"></i> Back to Website
                </a>
            </div>
        </form>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
