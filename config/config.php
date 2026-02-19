<?php
/**
 * Application Configuration for Kreasi Pro
 * 
 * Dynamic configuration loading from JSON data file.
 * This allows the Admin Panel to update content without modifying PHP code.
 * 
 * @package KreasiPro
 * @version 2.0.0
 */

// Prevent direct access
if (!defined('KREASI_PRO_LOADED')) {
    die('Direct access not permitted');
}

// Path to data file
$dataFile = __DIR__ . '/../data/content.json';

// Load data from JSON
if (file_exists($dataFile)) {
    $jsonData = file_get_contents($dataFile);
    $data = json_decode($jsonData, true);
    if ($data === null) {
        // Fallback or error if JSON is invalid
        die('Error decoding content configuration.');
    }
} else {
    die('Content configuration file not found.');
}

// Extract data for easy access
$siteSettings = $data['site_settings'] ?? [];
$contactInfo = $data['contact_info'] ?? [];
$socialMedia = $data['social_media'] ?? [];
$heroSection = $data['hero_section'] ?? [];
$aboutSection = $data['about_section'] ?? [];
$servicesSection = $data['services_section'] ?? [];
$featuresSection = $data['features_section'] ?? [];
$products = $data['products'] ?? [];
$portfolioCategories = $data['portfolio']['categories'] ?? [];
$portfolioCaptions = $data['portfolio']['captions'] ?? [];

// Compatibility mapping for existing index.php structure
$categories = $portfolioCategories;
$captions = $portfolioCaptions;

// Contact Information Constants (for backward compatibility if needed)
if (!defined('WHATSAPP_NUMBER')) define('WHATSAPP_NUMBER', $contactInfo['whatsapp']);
if (!defined('EMAIL_ADDRESS')) define('EMAIL_ADDRESS', $contactInfo['email']);
if (!defined('PHYSICAL_ADDRESS')) define('PHYSICAL_ADDRESS', $contactInfo['address']);

// WhatsApp Configuration
$whatsappNo = $contactInfo['whatsapp'];
$whatsappText = "Halo admin Kreasi Pro, saya ingin tanya tentang sewa peralatan untuk event";
$buttonWhatsapp = "https://api.whatsapp.com/send?phone=" . $whatsappNo . "&text=" . urlencode($whatsappText);

// Email Configuration
$email = $contactInfo['email'];
$emailLink = "mailto:" . $email;

// Address
$alamat = $contactInfo['address'];
$mapsLink = $contactInfo['maps_link'];

/**
 * Helper function to generate WhatsApp link for specific product
 * 
 * @param string $whatsappNo WhatsApp number
 * @param string $productName Product name
 * @return string WhatsApp URL
 */
function whatsappButton($whatsappNo, $productName)
{
    $templateText = "Halo admin Kreasi Pro, saya ingin tanya tentang sewa " . $productName;
    return "https://api.whatsapp.com/send?phone=" . $whatsappNo . "&text=" . urlencode($templateText);
}

/**
 * Render a product card
 * 
 * @param array $product Product data
 * @param string $whatsappNo WhatsApp number
 * @return string HTML for product card
 */
function renderProductCard($product, $whatsappNo)
{
    $serviceName = escapeHtml($product['name']);
    $serviceId = isset($product['id']) ? escapeAttr($product['id']) : str_replace(' ', '', $serviceName);
    $serviceDescription = escapeHtml($product['description']);
    $serviceImage = escapeUrl($product['image']);
    $whatsappUrl = escapeUrl(whatsappButton($whatsappNo, "Product " . $product['name']));
    
    $html = '<div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s" id="service-' . $serviceId . '">';
    $html .= '<div class="service-item text-center rounded">';
    $html .= '<div class="service-img">';
    $html .= '<img src="' . $serviceImage . '" class="img-fluid w-100" alt="Product ' . $serviceDescription . '" loading="lazy">';
    $html .= '</div>';
    $html .= '<div class="service-content p-4">';
    $html .= '<h4 class="mb-2 text-start">' . $serviceName . '</h4>';
    $html .= '<p class="mb-4 text-start">' . $serviceDescription . '</p>';
    $html .= '</div>';
    $html .= '<div class="service-footer pb-4">';
    $html .= '<a href="' . $whatsappUrl . '" target="_blank" class="btn btn-light text-secondary rounded-pill py-2 px-4">Selengkapnya</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}
