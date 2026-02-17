<?php
/**
 * Application Configuration for Kreasi Pro
 * 
 * Centralized configuration for application settings,
 * contact information, products, and portfolio data.
 * 
 * @package KreasiPro
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('KREASI_PRO_LOADED')) {
    die('Direct access not permitted');
}

// Contact Information
define('WHATSAPP_NUMBER', '6282298074293');
define('EMAIL_ADDRESS', 'info@kreasiproofficial.com');
define('PHYSICAL_ADDRESS', 'Jl. Musyawarah No.84, Serua, Kec. Ciputat, Kota Tangerang Selatan, Banten 15414');

// WhatsApp Configuration
$whatsappNo = WHATSAPP_NUMBER;
$whatsappText = "Halo admin Kreasi Pro, saya ingin tanya tentang sewa peralatan untuk event";
$buttonWhatsapp = "https://api.whatsapp.com/send?phone=" . $whatsappNo . "&text=" . urlencode($whatsappText);

// Email Configuration
$email = EMAIL_ADDRESS;
$emailLink = "mailto:" . $email;

// Address
$alamat = PHYSICAL_ADDRESS;

// Social Media Links
$socialMedia = [
    "instagram" => "https://www.instagram.com/kreasipro.id",
    "youtube" => "https://www.youtube.com/@kreasiproofficial",
];

// Product Catalog
$products = [
    [
        "name" => "LED Screen & Smart TV LED",
        "description" => "Sewa LED Screen berkualitas tinggi untuk acara perusahaan, konser, dan pernikahan dengan berbagai pilihan tipe.",
        "image" => "assets/img/products/led-screen.webp",
    ],
    [
        "name" => "Multimedia",
        "description" => "Layanan multimedia inovatif untuk konferensi, seminar, dan pameran dengan teknologi terkini.",
        "image" => "assets/img/products/multimedia.webp",
    ],
    [
        "name" => "Custom Gate",
        "description" => "Gate custom yang sesuai dengan keinginan anda untuk berbagai keperluan, event Run, Expo, Festival dan lain sebagainya.",
        "image" => "assets/img/products/gate.jpeg",
    ],
    [
        "name" => "Custom Backdrop",
        "description" => "Custom backdrop untuk konferensi, pameran, dan pernikahan dengan desain menarik dan bahan berkualitas.",
        "image" => "assets/img/products/custom-backdrop.webp",
    ],
    [
        "name" => "Partisi Pameran R8",
        "description" => "Sewa Partisi Pameran R8 untuk pameran dan konferensi, menciptakan ruang yang rapi dan profesional.",
        "image" => "assets/img/products/partisi-r8.webp",
    ],
    [
        "name" => "Booth Pameran Custom",
        "description" => "Booth pameran custom untuk promosi dan presentasi, dirancang sesuai identitas merek dan tema acara.",
        "image" => "assets/img/products/booth-pameran-custom.webp",
    ],
    [
        "name" => "Live Streaming",
        "description" => "Layanan Live Streaming profesional untuk konferensi dan pernikahan dengan kualitas gambar dan suara jernih.",
        "image" => "assets/img/products/live-streaming.webp",
    ],
    [
        "name" => "Penyewaan Tenda",
        "description" => "Sewa tenda berkualitas tinggi untuk pernikahan, seminar, dan event outdoor, melindungi dari cuaca buruk.",
        "image" => "assets/img/products/tenda.webp",
    ],
];

// Portfolio Categories
$categories = [
    'Custom Backdrop & Custom Photobooth' => 'backdrop',
    'Custom Exhibition Booth & Partisi R8' => 'partisi',
    'Event Production' => 'produksi',
    'Videotron & LED Screen' => 'led',
    'Multimedia & Livestreaming' => 'livestreaming',
    'TV Plasma & Digital Signage' => 'tv'
];

// Portfolio Captions
$captions = [
    'led' => [
        'ledartboard-1.webp' => 'Rapat Kerja Koni 2020',
        'ledartboard-2.webp' => 'Sidang Tertutup Unhan',
        'ledartboard-3.webp' => 'Town Hall DJPHU',
        'ledartboard-4.webp' => 'MeTime Series',
        'ledartboard-5.webp' => 'Milad HNI 2025',
        'ledartboard-6.webp' => 'Makna&Co Series',
        'ledartboard-7.webp' => 'Mandiri Public Expose 2025',
        'ledartboard-8.webp' => 'LED Screen',
        'ledartboard-9.webp' => 'Rapat Kerja',
        'ledartboard-10.webp' => 'Wisuda UB',
        'ledartboard-11.webp' => 'Beelieves Propolis',
        'ledartboard-12.webp' => 'LED Screen',
        'ledartboard-13.webp' => 'Hari AntiKorupsi 2022',
        'ledartboard-14.webp' => 'Indonesia Visionary Leader',
    ],
    'livestreaming' => [
        'liveartboard-1.webp' => 'Mandiri Public Expose 2025',
        'liveartboard-2.webp' => 'Makna&Co Series',
        'liveartboard-3.webp' => 'Wisuda Maskanul Huffadz',
        'liveartboard-4.webp' => 'Livestream Set',
        'liveartboard-5.webp' => 'SalamFest',
        'liveartboard-6.webp' => 'Webinar HRN 2025',
        'liveartboard-8.webp' => 'Jimmy Jib',
        'liveartboard-9.webp' => 'MeTime Series',
    ],
    'tv' => [
        'tvartboard-1.webp' => 'Matador Rapat Kerja',
        'tvartboard-2.webp' => 'PLN 2025',
        'tvartboard-3.webp' => 'TV Display',
        'tvartboard-10.webp' => 'Digital Signage',
        'tvartboard-4.webp' => 'TV Display',
        'tvartboard-5.webp' => 'Sertijab KAI',
        'tvartboard-11.webp' => 'Digital Signage',
        'tvartboard-6.webp' => 'Birthday Party',
        'tvartboard-12.webp' => 'Digital Signage',
        'tvartboard-7.webp' => 'Stage Live Matador Timer',
        'tvartboard-8.webp' => 'TV Display Sarulla',
        'tvartboard-9.webp' => 'TV Display ORMAT',
        'tvartboard-13.webp' => 'Digital Signage',
        'tvartboard-14.webp' => 'Stage Live Matador Direct',
        'tvartboard-15.webp' => 'Digital Signage PLN Like 2025',
    ],
    'backdrop' => [          
        'bgartboard-2.webp' => 'IKAHAN Golf Tournament 2025',
        'bgartboard-3.webp' => 'Berlalri Bersama Teman Tuli 2025',
        'bgartboard-4.webp' => 'Racepack Collection RFH Trail 2025',
        'bgartboard-5.webp' => 'Rapimnas HIPELKI 2025',
        'bgartboard-6.webp' => 'Press Conference RFH Trail 2025',
        'bgartboard-8.webp' => 'Sprint Your Impact 2025',
        'bgartboard-9.webp' => 'Demo Day Batch X',
        'bgartboard-10.webp' => 'Binloop Ultra',
        'bgartboard-11.webp' => 'Empower Run',
        'bgartboard-12.webp' => 'HairCon Indonesia',
        'bgartboard-13.webp' => 'Finisher Photobooth',
        'bgartboard-14.webp' => 'Palestina Run',
        'bgartboard-15.webp' => 'Retro Run',
        'bgartboard-16.webp' => 'Run & Rise 2025',
        'bgartboard-17.webp' => 'Walk of Dreams',
    ],
    'partisi' => [
        'rartboard-1.webp' => 'Partisi R8',
        'rartboard-2.webp' => 'Partisi R8',
        'rartboard-3.webp' => 'Partisi R8',
        'rartboard-4.webp' => 'Partisi R8',
        'rartboard-5.webp' => 'Partisi R8',
        'rartboard-6.webp' => 'Partisi R8',
        'rartboard-7.webp' => 'Partisi R8',
        'rartboard-8.webp' => 'Partisi R8',
        'rartboard-9.webp' => 'Booth Exhibtion Run to Miles',
        'rartboard-10.webp' => 'Booth Display QUPRO | AKSI',
        'rartboard-11.webp' => 'Booth Exhibtion Wondr BNI',
        'rartboard-12.webp' => 'Booth Exhibtion Panin Dubai',
        'rartboard-13.webp' => 'Partisi R8',
        'rartboard-14.webp' => 'Partisi R8',
        'rartboard-15.webp' => 'Tenda 3x3',
        'rartboard-16.webp' => 'Tenda 3x3',
        'rartboard-17.webp' => 'Tenda 5x5 | 3x3 | 2x2',
        'rartboard-18.webp' => 'Tenda',
    ],   
    'produksi' => [
        'pdartboard-1.webp' => 'Custom Gate RFH Solo',
        'pdartboard-2.webp' => 'Custom Gate Maulid Fest',
        'pdartboard-3.webp' => 'Custom Gate Run & Rise',
        'pdartboard-4.webp' => 'Custom Gate Palestina Run',
        'pdartboard-5.webp' => 'Custom Stage & Decoration Khadija Fest',
        'pdartboard-6.jpg' => 'Birthday Setup',
    ]
];

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
    $serviceId = str_replace(' ', '', $serviceName);
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
