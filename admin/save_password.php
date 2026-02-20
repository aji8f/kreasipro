<?php
/**
 * Save New Admin Password
 * 
 * Verifies current password, validates new password, hashes and
 * writes the new hash back to config/admin.php.
 */

define('KREASI_PRO_LOADED', true);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/auth.php';

requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// CSRF check
if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token.']);
    exit;
}

$currentPassword = $_POST['current_password'] ?? '';
$newPassword     = $_POST['new_password']      ?? '';
$confirmPassword = $_POST['confirm_password']  ?? '';

// --- Validations ---
if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi.']);
    exit;
}

if ($newPassword !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Password baru dan konfirmasi tidak cocok.']);
    exit;
}

if (strlen($newPassword) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password baru minimal 8 karakter.']);
    exit;
}

// Load current config
$configFile = __DIR__ . '/../config/admin.php';
$config     = require $configFile;

// Verify current password
if (!password_verify($currentPassword, $config['password_hash'])) {
    echo json_encode(['success' => false, 'message' => 'Password saat ini salah.']);
    exit;
}

// Prevent reusing the same password
if (password_verify($newPassword, $config['password_hash'])) {
    echo json_encode(['success' => false, 'message' => 'Password baru tidak boleh sama dengan password saat ini.']);
    exit;
}

// Hash the new password
$newHash = password_hash($newPassword, PASSWORD_BCRYPT);

// Write back to config/admin.php
$newConfigContent = '<?php' . PHP_EOL
    . '/**' . PHP_EOL
    . ' * Admin Configuration' . PHP_EOL
    . ' */' . PHP_EOL
    . PHP_EOL
    . 'if (!defined(\'KREASI_PRO_LOADED\')) {' . PHP_EOL
    . '    die(\'Direct access not permitted\');' . PHP_EOL
    . '}' . PHP_EOL
    . PHP_EOL
    . 'return [' . PHP_EOL
    . '    \'username\'      => ' . var_export($config['username'], true) . ',' . PHP_EOL
    . '    \'password_hash\' => ' . var_export($newHash, true) . ',' . PHP_EOL
    . '];' . PHP_EOL;

if (file_put_contents($configFile, $newConfigContent) !== false) {
    // Also regenerate session to avoid stale state
    session_regenerate_id(true);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan password baru. Periksa permission file config/admin.php.']);
}
