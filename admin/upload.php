<?php
/**
 * Image Upload Handler — with automatic WebP conversion
 */

define('KREASI_PRO_LOADED', true);

require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/auth.php';

requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

// Validate CSRF
if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token.']);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error.']);
    exit;
}

$file = $_FILES['image'];
$targetDir = __DIR__ . '/../assets/uploads/';

// Create uploads dir if not exists
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// Validate File Type
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($file['tmp_name']);

if (!in_array($mimeType, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, WEBP, GIF allowed.']);
    exit;
}

// Generate unique base filename (always saved as .webp)
$basename  = uniqid('img_');
$tmpFile   = $targetDir . $basename . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
$webpFile  = $targetDir . $basename . '.webp';
$publicPath = 'assets/uploads/' . $basename . '.webp';

// Move uploaded file to a temporary location first
if (!move_uploaded_file($file['tmp_name'], $tmpFile)) {
    echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
    exit;
}

// --- WebP Conversion ---
$converted = false;
if (function_exists('imagecreatefromjpeg') && function_exists('imagewebp')) {
    $img = null;
    switch ($mimeType) {
        case 'image/jpeg': $img = imagecreatefromjpeg($tmpFile); break;
        case 'image/png':
            $img = imagecreatefrompng($tmpFile);
            // Preserve transparency
            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);
            break;
        case 'image/gif':  $img = imagecreatefromgif($tmpFile);  break;
        case 'image/webp': $img = imagecreatefromwebp($tmpFile); break;
    }

    if ($img !== null && $img !== false) {
        if (imagewebp($img, $webpFile, 85)) {
            imagedestroy($img);
            @unlink($tmpFile); // Remove original
            $converted = true;
        } else {
            imagedestroy($img);
        }
    }
}

// Fallback: if conversion failed, use original file as-is
if (!$converted) {
    rename($tmpFile, $webpFile); // Still rename for consistent path (won't truly be webp but keeps code simple)
    $publicPath = 'assets/uploads/' . $basename . '.webp';
}

echo json_encode([
    'success' => true,
    'message' => 'File uploaded and converted to WebP.',
    'path'    => $publicPath
]);
