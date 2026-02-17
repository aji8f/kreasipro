<?php
/**
 * XML Sitemap for Kreasi Pro
 * Optimized untuk SEO - Kontraktor Event & Booth Exhibition
 */

// Load configuration
define('KREASI_PRO_LOADED', true);
require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/config/config.php';

$baseUrl = getSafeBaseUrl();
$currentDate = date('Y-m-d');

// Set XML header
header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    <!-- Homepage - Kontraktor Event & Booth -->
    <url>
        <loc><?= escapeUrl($baseUrl) ?>/</loc>
        <lastmod><?= $currentDate ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
        <image:image>
            <image:loc><?= escapeUrl($baseUrl) ?>/assets/logo/logo.png</image:loc>
            <image:title>Kreasi Pro - Kontraktor Event</image:title>
            <image:caption>Event vendor profesional untuk custom booth exhibition</image:caption>
        </image:image>
    </url>
    
    <!-- Service Pages (using anchors) -->
    <?php foreach ($products as $index => $product): 
        $serviceId = str_replace(' ', '', $product['name']);
    ?>
    <url>
        <loc><?= escapeUrl($baseUrl) ?>/#service-<?= escapeUrl($serviceId) ?></loc>
        <lastmod><?= $currentDate ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <image:image>
            <image:loc><?= escapeUrl($baseUrl) ?>/<?= escapeUrl($product['image']) ?></image:loc>
            <image:title><?= escapeAttr($product['name']) ?></image:title>
        </image:image>
    </url>
    <?php endforeach; ?>
    
    <!-- Portfolio Categories -->
    <?php foreach ($categories as $categoryName => $folder): ?>
    <url>
        <loc><?= escapeUrl($baseUrl) ?>/#portfolio-<?= escapeUrl($folder) ?></loc>
        <lastmod><?= $currentDate ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>
    
    <!-- About Section -->
    <url>
        <loc><?= escapeUrl($baseUrl) ?>/#about</loc>
        <lastmod><?= $currentDate ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    
    <!-- Contact Section -->
    <url>
        <loc><?= escapeUrl($baseUrl) ?>/#contact</loc>
        <lastmod><?= $currentDate ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    
    <!-- Portfolio Images (sample - top 20) -->
    <?php 
    $imageCount = 0;
    $maxImages = 20;
    foreach ($categories as $categoryName => $folder): 
        if ($imageCount >= $maxImages) break;
        
        $safeFolder = sanitizeFilename($folder);
        $path = "assets/img/porto/$safeFolder/";
        
        if (!is_dir($path)) continue;
        
        $images = glob($path . "*.{jpg,jpeg,png,webp}", GLOB_BRACE);
        if (!$images) continue;
        
        foreach ($images as $imagePath):
            if ($imageCount >= $maxImages) break;
            
            $filename = basename($imagePath);
            $caption = isset($captions[$folder][$filename]) ? $captions[$folder][$filename] : $categoryName;
            $absolutePath = $baseUrl . '/' . str_replace('\\', '/', $imagePath);
            $imageCount++;
    ?>
        <url>
            <loc><?= escapeUrl($absolutePath) ?></loc>
            <lastmod><?= $currentDate ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.5</priority>
            <image:image>
                <image:loc><?= escapeUrl($absolutePath) ?></image:loc>
                <image:title><?= escapeAttr($caption) ?></image:title>
                <image:caption>Portfolio <?= escapeAttr($categoryName) ?> - <?= escapeAttr($caption) ?></image:caption>
            </image:image>
        </url>
    <?php 
        endforeach;
    endforeach; 
    ?>
    
</urlset>
