<?php
/**
 * Save Data Handler
 * 
 * Receives POST data from admin forms, updates specific sections of 
 * content.json, and saves the file.
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

// Validate CSRF
if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token.']);
    exit;
}

$section = $_POST['section'] ?? '';
$dataFile = __DIR__ . '/../data/content.json';

if (!file_exists($dataFile)) {
    echo json_encode(['success' => false, 'message' => 'Data file not found.']);
    exit;
}

// Load current data
$jsonData = file_get_contents($dataFile);
$data = json_decode($jsonData, true);

if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'Error reading current data.']);
    exit;
}

// Create Backup (rotating — keep last 5)
$backupFile = __DIR__ . '/../data/content_' . date('Y-m-d_H-i-s') . '.json';
file_put_contents($backupFile, $jsonData);

// Rotate: delete oldest backups if more than 5 exist
$backups = glob(__DIR__ . '/../data/content_*.json');
if ($backups && count($backups) > 5) {
    sort($backups); // oldest first
    $toDelete = array_slice($backups, 0, count($backups) - 5);
    foreach ($toDelete as $oldFile) {
        @unlink($oldFile);
    }
}

// Update Data based on section
try {
    switch ($section) {
        case 'hero':
            $data['hero_section']['sub_headline']['title'] = sanitizeInput($_POST['sub_title']);
            $data['hero_section']['sub_headline']['text'] = sanitizeInput($_POST['sub_text']);
            $data['hero_section']['sub_headline']['button_text'] = sanitizeInput($_POST['button_text']);
            
            $data['hero_section']['main_headline']['title'] = sanitizeInput($_POST['main_title']);
            $data['hero_section']['main_headline']['text'] = sanitizeInput($_POST['main_text']);
            
            // Save Hero Images
            if (isset($_POST['images']) && is_array($_POST['images'])) {
                $images = [];
                foreach ($_POST['images'] as $img) {
                    if (!empty($img)) {
                        $images[] = sanitizeInput($img);
                    }
                }
                $data['hero_section']['main_headline']['images'] = $images;
            }
            break;

        case 'about':
            $data['about_section']['title'] = sanitizeInput($_POST['title']);
            $data['about_section']['headline'] = sanitizeInput($_POST['headline']);
            $data['about_section']['text'] = sanitizeInput($_POST['text']);
            // Save About Image
            if (isset($_POST['image'])) {
                 $data['about_section']['image'] = sanitizeInput($_POST['image']);
            }
            break;

        case 'seo':
            $data['site_settings']['title'] = sanitizeInput($_POST['site_title']);
            $data['site_settings']['description'] = sanitizeInput($_POST['site_description']);
            $data['site_settings']['keywords'] = sanitizeInput($_POST['site_keywords']);
            $data['site_settings']['author'] = sanitizeInput($_POST['site_author']);
            
            // Save Logo/Icon if posted
            if (isset($_POST['logo'])) $data['site_settings']['logo'] = sanitizeInput($_POST['logo']);
            if (isset($_POST['icon'])) $data['site_settings']['icon'] = sanitizeInput($_POST['icon']);
            
            $data['contact_info']['whatsapp'] = sanitizeInput($_POST['whatsapp']);
            $data['contact_info']['email']     = sanitizeInput($_POST['email']);
            $data['contact_info']['address']   = sanitizeInput($_POST['address']);
            $data['contact_info']['maps_link']  = sanitizeInput($_POST['maps_link']);

            $data['social_media']['instagram'] = sanitizeInput($_POST['instagram']);
            $data['social_media']['youtube']   = sanitizeInput($_POST['youtube']);
            $data['social_media']['facebook']  = sanitizeInput($_POST['facebook']);
            $data['social_media']['tiktok']    = sanitizeInput($_POST['tiktok']);
            break;
            
        case 'services':
             // Should be handled in specific service add/edit logic
             // This block might be used for batch updates if needed
             break;

        default:
            echo json_encode(['success' => false, 'message' => 'Unknown section.']);
            exit;
    }

    // Save back to file
    if (file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true, 'message' => 'Changes saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to write to data file.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
