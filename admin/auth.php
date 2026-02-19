<?php
/**
 * Admin Authentication Helper
 * 
 * Handles login check, session management, brute-force protection,
 * and automatic session timeout.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,          // Session cookie (expires on browser close)
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,       // Prevent JS access to session cookie
        'samesite' => 'Strict',   // Prevent CSRF via cookie
    ]);
    session_start();
}

// ─── Constants ───────────────────────────────────────────────────────────────
define('SESSION_TIMEOUT',    1800);   // 30 minutes of inactivity = auto-logout
define('MAX_LOGIN_ATTEMPTS', 5);      // Max failed attempts before lockout
define('LOCKOUT_DURATION',   900);    // 15-minute lockout in seconds

/**
 * Check session timeout — call this at the top of every protected page.
 */
function checkSessionTimeout()
{
    if (isset($_SESSION['last_activity'])) {
        if ((time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
            // Session expired — destroy and redirect
            $_SESSION = [];
            session_destroy();
            header('Location: login.php?timeout=1');
            exit;
        }
    }
    // Refresh last activity timestamp
    $_SESSION['last_activity'] = time();
}

/**
 * Check if admin is logged in.
 * Redirects to login page if not, also enforces session timeout.
 */
function requireLogin()
{
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
    checkSessionTimeout();
}

/**
 * Check if the current IP is locked out due to too many failed login attempts.
 *
 * @return int Seconds remaining in lockout, or 0 if not locked out
 */
function getLockoutRemaining()
{
    $attempts   = $_SESSION['login_attempts']   ?? 0;
    $lockedAt   = $_SESSION['locked_at']        ?? 0;

    if ($attempts >= MAX_LOGIN_ATTEMPTS && $lockedAt > 0) {
        $elapsed   = time() - $lockedAt;
        $remaining = LOCKOUT_DURATION - $elapsed;
        if ($remaining > 0) {
            return $remaining;
        }
        // Lockout expired — reset counters
        $_SESSION['login_attempts'] = 0;
        $_SESSION['locked_at']      = 0;
    }
    return 0;
}

/**
 * Attempt login with brute-force protection.
 *
 * @param string $username
 * @param string $password
 * @return bool True on success
 */
function attemptLogin($username, $password)
{
    // Reject if locked out
    if (getLockoutRemaining() > 0) {
        return false;
    }

    $config = require __DIR__ . '/../config/admin.php';

    if ($username === $config['username'] && password_verify($password, $config['password_hash'])) {
        // Successful login — reset counters, regenerate session ID
        $_SESSION['login_attempts'] = 0;
        $_SESSION['locked_at']      = 0;
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['last_activity']   = time();
        return true;
    }

    // Failed — increment attempt counter
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
        $_SESSION['locked_at'] = time();
    }

    return false;
}

/**
 * Logout admin cleanly.
 */
function logout()
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'],    $params['domain'],
            $params['secure'],  $params['httponly']
        );
    }
    session_destroy();
    header('Location: login.php');
    exit;
}
