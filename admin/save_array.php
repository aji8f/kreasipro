<?php
/**
 * Product & Portfolio Array Handler
 * 
 * Extends save logic to handle array manipulations
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

$action = $_POST['action'] ?? '';
$dataFile = __DIR__ . '/../data/content.json';

// Load Data
$jsonData = file_get_contents($dataFile);
$data = json_decode($jsonData, true);

if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'Error reading data.']);
    exit;
}

// Backup with rotation (keep last 5)
file_put_contents(__DIR__ . '/../data/content_' . date('Y-m-d_H-i-s') . '.json', $jsonData);
$backups = glob(__DIR__ . '/../data/content_*.json');
if ($backups && count($backups) > 5) {
    sort($backups);
    foreach (array_slice($backups, 0, count($backups) - 5) as $oldFile) {
        @unlink($oldFile);
    }
}

try {
    if ($action === 'save_product') {
        $index = (int)$_POST['index'];
        $name = sanitizeInput($_POST['name']);
        $desc = sanitizeInput($_POST['description']);
        $img = sanitizeInput($_POST['image']);
        
        $newItem = [
            "name" => $name,
            "description" => $desc,
            "image" => $img,
            "id" => strtolower(str_replace(' ', '-', $name))
        ];

        if ($index >= 0) {
            // Edit
            $data['products'][$index] = $newItem;
        } else {
            // Add New
            $data['products'][] = $newItem;
        }

    } elseif ($action === 'delete_product') {
        $index = (int)$_POST['index'];
        if (isset($data['products'][$index])) {
            array_splice($data['products'], $index, 1);
        }

    // --- FEATURES ACTIONS ---
    } elseif ($action === 'save_feature') {
        $index = (int)$_POST['index'];
        $icon = sanitizeInput($_POST['icon']);
        $title = sanitizeInput($_POST['title']);
        $text = sanitizeInput($_POST['text']);
        
        $newItem = [
            "icon" => $icon,
            "title" => $title,
            "text" => $text
        ];

        if ($index >= 0) {
            // Edit
            $data['features_section']['items'][$index] = $newItem;
        } else {
            // Add New
            $data['features_section']['items'][] = $newItem;
        }

    } elseif ($action === 'delete_feature') {
        $index = (int)$_POST['index'];
        if (isset($data['features_section']['items'][$index])) {
            array_splice($data['features_section']['items'], $index, 1);
        }
    } 
    
    // Save
    if (file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Write failed']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
