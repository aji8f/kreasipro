<?php
/**
 * Portfolio Save Handler
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

// CSRF Check
if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token.']);
    exit;
}

$dataFile = __DIR__ . '/../data/content.json';
$data = json_decode(file_get_contents($dataFile), true);
$action = $_POST['action'] ?? '';

// Backup with rotation (keep last 5, same pattern as save.php)
$backupFile = __DIR__ . '/../data/content_' . date('Y-m-d_H-i-s') . '.json';
file_put_contents($backupFile, file_get_contents($dataFile));
$backups = glob(__DIR__ . '/../data/content_*.json');
if ($backups && count($backups) > 5) {
    sort($backups);
    foreach (array_slice($backups, 0, count($backups) - 5) as $old) { @unlink($old); }
}

try {
    switch ($action) {
        case 'add_category':
            $folderId = sanitizeFilename($_POST['id']); // Slug-like ID
            $name = sanitizeInput($_POST['name']);

            if (empty($folderId) || empty($name)) {
                throw new Exception("Folder ID and Name are required.");
            }
            if (isset($data['portfolio']['categories'][$name])) { // JSON structure is Name -> ID
                 // Wait, structure is 'Display Name' => 'folder_id'. 
                 // We should probably check if folder_id exists as a value.
                 if (in_array($folderId, $data['portfolio']['categories'])) {
                     throw new Exception("Category Folder ID already exists.");
                 }
            }

            // Create Directory
            $targetDir = __DIR__ . '/../assets/img/porto/' . $folderId;
            if (!file_exists($targetDir)) {
                if (!mkdir($targetDir, 0755, true)) {
                    throw new Exception("Failed to create directory.");
                }
            }
            
            // Update JSON: 'Display Name' => 'folder_id'
            $data['portfolio']['categories'][$name] = $folderId;
            // Init captions array if not exists
            if (!isset($data['portfolio']['captions'][$folderId])) {
                $data['portfolio']['captions'][$folderId] = [];
            }
            break;

        case 'delete_category':
            $folderId = sanitizeFilename($_POST['id']);
            $displayName = '';
            
            // Find display name key
            foreach ($data['portfolio']['categories'] as $name => $fid) {
                if ($fid === $folderId) {
                    $displayName = $name;
                    break;
                }
            }
            
            if (!$displayName) throw new Exception("Category not found.");

            // Check if directory is empty
            $targetDir = __DIR__ . '/../assets/img/porto/' . $folderId;
            $files = glob($targetDir . '/*');
            if (count($files) > 0) {
                throw new Exception("Cannot delete category. Folder is not empty. Delete all images first.");
            }

            // Remove Directory
            if (is_dir($targetDir)) {
                rmdir($targetDir);
            }

            // Update JSON
            unset($data['portfolio']['categories'][$displayName]);
            unset($data['portfolio']['captions'][$folderId]);
            break;

        case 'update_category':
            $folderId = sanitizeFilename($_POST['id']);
            $oldName = sanitizeInput($_POST['old_name']);
            $newName = sanitizeInput($_POST['name']);

            if ($oldName !== $newName) {
                unset($data['portfolio']['categories'][$oldName]);
                $data['portfolio']['categories'][$newName] = $folderId;
            }
            break;

        case 'upload_image':
            $folderId = sanitizeFilename($_POST['category_id']);
            
            if (!isset($data['portfolio']['captions'][$folderId])) {
                 // Check if valid category even if no captions yet
                 $validCat = false;
                 foreach($data['portfolio']['categories'] as $nm => $fid) {
                     if($fid === $folderId) { $validCat = true; break; }
                 }
                 if(!$validCat) throw new Exception("Invalid category.");
            }

            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Image upload failed.");
            }

            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['image']['tmp_name']);
            if (!in_array($mime, $allowed)) throw new Exception("Invalid file type.");

            $basename  = uniqid('img_');
            $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $tmpName   = $basename . '.' . $ext;
            $webpName  = $basename . '.webp';
            $targetDir = __DIR__ . '/../assets/img/porto/' . $folderId;

            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

            $tmpPath  = $targetDir . '/' . $tmpName;
            $webpPath = $targetDir . '/' . $webpName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $tmpPath)) {
                throw new Exception("Failed to save file.");
            }

            // Convert to WebP
            $filename = $webpName; // Default to webp filename
            if (function_exists('imagewebp')) {
                $img = null;
                switch ($mime) {
                    case 'image/jpeg': $img = imagecreatefromjpeg($tmpPath); break;
                    case 'image/png':
                        $img = imagecreatefrompng($tmpPath);
                        imagepalettetotruecolor($img);
                        imagesavealpha($img, true);
                        break;
                    case 'image/gif':  $img = imagecreatefromgif($tmpPath);  break;
                    case 'image/webp': $img = imagecreatefromwebp($tmpPath); break;
                }
                if ($img && imagewebp($img, $webpPath, 85)) {
                    imagedestroy($img);
                    @unlink($tmpPath);
                } else {
                    // Fallback: rename as webp
                    rename($tmpPath, $webpPath);
                }
            } else {
                rename($tmpPath, $webpPath);
            }

            // Init caption
            if (!isset($data['portfolio']['captions'][$folderId])) {
                $data['portfolio']['captions'][$folderId] = [];
            }
            $data['portfolio']['captions'][$folderId][$filename] = "New Image";
            break;

        case 'delete_image':
            $folderId = sanitizeFilename($_POST['category_id']);
            $filename = sanitizeFilename($_POST['filename']);
            
            $path = __DIR__ . '/../assets/img/porto/' . $folderId . '/' . $filename;
            if (file_exists($path)) {
                unlink($path);
            }
            
            unset($data['portfolio']['captions'][$folderId][$filename]);
            break;

        case 'update_caption':
            $folderId = sanitizeFilename($_POST['category_id']);
            $filename = sanitizeFilename($_POST['filename']);
            $caption = sanitizeInput($_POST['caption']);
            
            if (isset($data['portfolio']['captions'][$folderId][$filename])) {
                $data['portfolio']['captions'][$folderId][$filename] = $caption;
            }
            break;

        default:
            throw new Exception("Invalid action.");
    }

    // Save JSON
    if (file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception("Failed to save data file.");
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
