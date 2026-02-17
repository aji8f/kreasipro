# Dokumentasi Teknis - Kreasi Pro Website

Dokumentasi lengkap untuk developer yang akan maintain atau develop website Kreasi Pro.

## 📚 Daftar Isi

1. [Arsitektur Aplikasi](#arsitektur-aplikasi)
2. [Core Components](#core-components)
3. [Security Layer](#security-layer)
4. [Database Schema](#database-schema)
5. [API & Functions Reference](#api--functions-reference)
6. [Frontend Components](#frontend-components)
7. [Styling Guide](#styling-guide)
8. [JavaScript Modules](#javascript-modules)
9. [Best Practices](#best-practices)
10. [Performance Optimization](#performance-optimization)

---

## 🏗 Arsitektur Aplikasi

### High-Level Architecture

```
┌─────────────────────────────────────────────────┐
│                   Browser                        │
│  (HTML + CSS + JavaScript)                      │
└────────────────┬────────────────────────────────┘
                 │ HTTP Request
                 ▼
┌─────────────────────────────────────────────────┐
│              Apache Web Server                   │
│  ┌───────────────────────────────────────────┐ │
│  │          .htaccess                        │ │
│  │  • Security Headers                       │ │
│  │  • URL Rewriting                          │ │
│  │  • File Access Control                    │ │
│  └───────────────────────────────────────────┘ │
└────────────────┬────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────┐
│              index.php (Main)                    │
│  ┌───────────────────────────────────────────┐ │
│  │  1. Define Constants                      │ │
│  │  2. Load Security Module                  │ │
│  │  3. Set Security Headers                  │ │
│  │  4. Load Configuration                    │ │
│  │  5. Generate Safe Base URL                │ │
│  │  6. Render HTML                           │ │
│  └───────────────────────────────────────────┘ │
└────────────────┬────────────────────────────────┘
                 │
      ┌──────────┴──────────┐
      ▼                     ▼
┌──────────────┐    ┌─────────────────┐
│ config/      │    │ Frontend        │
│ security.php │    │ Assets          │
│ config.php   │    │ • CSS           │
└──────────────┘    │ • JavaScript    │
                    │ • Images        │
                    └─────────────────┘
```

### Application Layers

#### Layer 1: Server (Apache)
- **Fungsi:** Handle HTTP requests, security headers, URL rewriting
- **File:** `.htaccess`
- **Responsibilities:**
  - Set security headers (X-Frame-Options, CSP, etc)
  - Block access to sensitive files
  - Enable compression & caching
  - Force HTTPS (production)

#### Layer 2: PHP Backend
- **Fungsi:** Business logic, data preparation, security
- **Files:** 
  - `index.php` - Main application
  - `config/security.php` - Security utilities
  - `config/config.php` - Application config
- **Responsibilities:**
  - Load and validate configuration
  - Generate safe base URL
  - Sanitize file paths
  - Encode output for XSS prevention
  - Render HTML with data

#### Layer 3: Frontend
- **Fungsi:** User interface, interactions
- **Files:** 
  - `css/style.css` - Styles
  - `js/main.js` - Interactions
  - `lib/*` - External libraries
- **Responsibilities:**
  - Render responsive UI
  - Handle user interactions
  - Animate elements
  - Display images in carousel/lightbox

---

## 🧩 Core Components

### 1. index.php

**Purpose:** Main application file yang handle semua page rendering.

**Structure:**

```php
<?php
// ==========================================
// SECTION 1: INITIALIZATION (Lines 1-25)
// ==========================================
define('KREASI_PRO_LOADED', true);      // Security flag
error_reporting(E_ALL & ~E_NOTICE);      // Error handling
require_once 'config/security.php';      // Load security
setSecurityHeaders();                     // Set HTTP headers
require_once 'config/config.php';        // Load config
$baseUrl = getSafeBaseUrl();             // Get safe URL

// ==========================================
// SECTION 2: HTML HEAD (Lines 26-85)
// ==========================================
// Meta tags, stylesheets, scripts
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kreasi Pro - Sewa TV LED | TV Plasma | Videotron</title>
    <!-- All meta tags, CSS, fonts -->
</head>

<!-- ==========================================
     SECTION 3: BODY & NAVIGATION (Lines 86-130)
     ========================================== -->
<body>
    <!-- Spinner -->
    <!-- Navbar with dynamic product menu -->
    <!-- Hero carousel -->
</body>

<!-- ==========================================
     SECTION 4: ABOUT SECTION (Lines 256-272)
     ========================================== -->
<!-- Company information -->

<!-- ==========================================
     SECTION 5: PRODUCTS/SERVICES (Lines 274-303)
     ========================================== -->
<!-- Product cards generated from $products array -->
<?php foreach ($products as $product) : ?>
    <?php echo renderProductCard($product, $whatsappNo); ?>
<?php endforeach; ?>

<!-- ==========================================
     SECTION 6: PORTFOLIO (Lines 487-730)
     ========================================== -->
<!-- Portfolio galleries per category -->
<?php foreach ($categories as $title => $folder) : ?>
    <!-- Owl Carousel with images -->
<?php endforeach; ?>

<!-- ==========================================
     SECTION 7: FOOTER & CONTACT (Lines 872-910)
     ========================================== -->
<!-- Google Maps, contact info, social media -->

<!-- ==========================================
     SECTION 8: SCRIPTS (Lines 936-975)
     ========================================== -->
<!-- jQuery, Bootstrap, Plugins, Custom JS -->
</html>
```

**Key Functions Used:**

| Function | Purpose | Example |
|----------|---------|---------|
| `escapeHtml()` | Escape HTML content | `<?= escapeHtml($name) ?>` |
| `escapeAttr()` | Escape HTML attributes | `alt="<?= escapeAttr($text) ?>"` |
| `escapeUrl()` | Validate & escape URLs | `href="<?= escapeUrl($link) ?>"` |
| `sanitizeFilename()` | Sanitize file paths | `$safe = sanitizeFilename($folder)` |
| `getSafeBaseUrl()` | Generate safe base URL | `$baseUrl = getSafeBaseUrl()` |
| `renderProductCard()` | Render product HTML | `echo renderProductCard($product)` |

---

### 2. config/security.php

**Purpose:** Provide security utilities untuk protect against common web vulnerabilities.

**Functions Provided:**

#### Output Encoding Functions

```php
/**
 * escapeHtml($string)
 * 
 * @param string $string - String to escape
 * @return string - HTML-safe string
 * 
 * USE CASE: General HTML content
 * PREVENTS: XSS via HTML injection
 */
function escapeHtml($string) {
    if ($string === null || $string === '') return '';
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Usage:
echo escapeHtml($userInput);
echo '<h1>' . escapeHtml($productName) . '</h1>';
```

```php
/**
 * escapeAttr($string)
 * 
 * @param string $string - String to escape
 * @return string - Attribute-safe string
 * 
 * USE CASE: HTML tag attributes (alt, title, value, etc)
 * PREVENTS: XSS via attribute injection
 */
function escapeAttr($string) {
    if ($string === null || $string === '') return '';
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Usage:
echo '<img src="..." alt="' . escapeAttr($caption) . '">';
echo '<input type="text" value="' . escapeAttr($value) . '">';
```

```php
/**
 * escapeUrl($url)
 * 
 * @param string $url - URL to validate and escape
 * @return string - Safe URL or '#' if dangerous
 * 
 * USE CASE: href, src attributes
 * PREVENTS: XSS via javascript: or data: protocols
 */
function escapeUrl($url) {
    if ($url === null || $url === '') return '';
    
    // Block dangerous protocols
    $dangerous = ['javascript:', 'data:', 'vbscript:', 'file:'];
    $lower = strtolower(trim($url));
    
    foreach ($dangerous as $protocol) {
        if (strpos($lower, $protocol) === 0) {
            return '#'; // Safe fallback
        }
    }
    
    return htmlspecialchars($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Usage:
echo '<a href="' . escapeUrl($link) . '">Link</a>';
echo '<img src="' . escapeUrl($imagePath) . '">';
```

```php
/**
 * escapeJs($string)
 * 
 * @param string $string - String to escape
 * @return string - JavaScript-safe string
 * 
 * USE CASE: Embedding PHP values in inline JavaScript
 * PREVENTS: XSS via script injection
 */
function escapeJs($string) {
    if ($string === null || $string === '') return '';
    return addslashes($string);
}

// Usage:
<script>
    var productName = '<?= escapeJs($product['name']) ?>';
    console.log(productName);
</script>
```

#### Input Sanitization Functions

```php
/**
 * sanitizeInput($input)
 * 
 * @param string $input - User input to sanitize
 * @return string - Sanitized input
 * 
 * USE CASE: Form inputs, query parameters
 * REMOVES: Null bytes, excess whitespace
 */
function sanitizeInput($input) {
    if ($input === null || $input === '') return '';
    $input = str_replace("\0", '', $input);  // Remove null bytes
    $input = trim($input);                    // Trim whitespace
    return $input;
}

// Usage:
$name = sanitizeInput($_POST['name']);
$email = sanitizeInput($_GET['email']);
```

```php
/**
 * sanitizeFilename($filename)
 * 
 * @param string $filename - Filename to sanitize
 * @return string - Safe filename
 * 
 * USE CASE: File uploads, dynamic file paths
 * PREVENTS: Path traversal attacks (../../etc/passwd)
 */
function sanitizeFilename($filename) {
    if ($filename === null || $filename === '') return '';
    
    // Remove path traversal
    $filename = str_replace(['..', '/', '\\'], '', $filename);
    
    // Keep only alphanumeric, dash, underscore, dot
    $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $filename);
    
    return $filename;
}

// Usage:
$folder = sanitizeFilename($_GET['category']);
$path = "assets/img/porto/$folder/";
```

#### Validation Functions

```php
/**
 * validateHost($host)
 * 
 * @param string $host - HTTP host header
 * @return string|false - Validated host or false
 * 
 * USE CASE: Validate $_SERVER['HTTP_HOST']
 * PREVENTS: HTTP Host Header Injection
 */
function validateHost($host) {
    $host = preg_replace('/:\d+$/', '', $host);  // Remove port
    
    // Validate format
    if (!preg_match('/^[a-zA-Z0-9\-\.]+$/', $host)) {
        return false;
    }
    
    return $host;
}

// Usage:
$host = validateHost($_SERVER['HTTP_HOST']);
if ($host === false) {
    $host = 'localhost';  // Fallback
}
```

```php
/**
 * getSafeBaseUrl()
 * 
 * @return string - Validated base URL
 * 
 * USE CASE: Generate base URL for assets
 * PREVENTS: URL injection attacks
 */
function getSafeBaseUrl() {
    // Determine protocol
    $isHttps = false;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $isHttps = true;
    }
    $protocol = $isHttps ? 'https' : 'http';
    
    // Validate host
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $validatedHost = validateHost($host);
    
    if ($validatedHost === false) {
        $validatedHost = 'localhost';
    }
    
    // Get script directory
    $scriptDir = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
    $scriptDir = rtrim($scriptDir, '/\\');
    
    return $protocol . '://' . $validatedHost . $scriptDir;
}

// Usage:
$baseUrl = getSafeBaseUrl();
// Result: https://localhost/kreasi-pro-main
```

#### Security Headers

```php
/**
 * setSecurityHeaders()
 * 
 * Sets HTTP security headers for defense-in-depth
 * MUST be called before any output
 */
function setSecurityHeaders() {
    header('X-Frame-Options: SAMEORIGIN');          // Anti-clickjacking
    header('X-Content-Type-Options: nosniff');      // Anti-MIME sniffing
    header('X-XSS-Protection: 1; mode=block');      // XSS filter
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Content Security Policy (relaxed for compatibility)
    $csp = "default-src 'self'; " .
           "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://ajax.googleapis.com https://cdn.jsdelivr.net; " .
           "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
           "img-src 'self' data: https:; " .
           "frame-src https://www.google.com;";
    
    header("Content-Security-Policy: $csp");
    
    // HSTS (only if HTTPS)
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

// Usage (at top of index.php):
setSecurityHeaders();
```

#### CSRF Protection

```php
/**
 * generateCsrfToken()
 * 
 * @return string - CSRF token
 * 
 * Generates secure random token for CSRF protection
 */
function generateCsrfToken() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * validateCsrfToken($token)
 * 
 * @param string $token - Token to validate
 * @return bool - True if valid
 */
function validateCsrfToken($token) {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Usage (for forms):
// Generate token
$token = generateCsrfToken();
echo '<input type="hidden" name="csrf_token" value="' . $token . '">';

// Validate submission
if (isPostRequest()) {
    if (validateCsrfToken($_POST['csrf_token'])) {
        // Process form safely
    } else {
        // Invalid token - possible CSRF attack
    }
}
```

---

### 3. config/config.php

**Purpose:** Centralized configuration untuk contact info, products, dan portfolio.

**Structure:**

```php
<?php
// Security Check
if (!defined('KREASI_PRO_LOADED')) {
    die('Direct access not permitted');
}

// ==========================================
// CONTACT INFORMATION
// ==========================================
define('WHATSAPP_NUMBER', '6282298074293');
define('EMAIL_ADDRESS', 'info@kreasiproofficial.com');
define('PHYSICAL_ADDRESS', 'Jl. Musyawarah No.84, ...');

$whatsappNo = WHATSAPP_NUMBER;
$email = EMAIL_ADDRESS;
$alamat = PHYSICAL_ADDRESS;

// WhatsApp button URL
$whatsappText = "Halo admin Kreasi Pro, saya ingin tanya tentang sewa peralatan untuk event";
$buttonWhatsapp = "https://api.whatsapp.com/send?phone=" . $whatsappNo . "&text=" . urlencode($whatsappText);

// Email link
$emailLink = "mailto:" . $email;

// ==========================================
// SOCIAL MEDIA LINKS
// ==========================================
$socialMedia = [
    "instagram" => "https://www.instagram.com/kreasipro.id",
    "youtube" => "https://www.youtube.com/@kreasiproofficial",
];

// ==========================================
// PRODUCT CATALOG
// ==========================================
$products = [
    [
        "name" => "LED Screen & Smart TV LED",
        "description" => "Sewa LED Screen berkualitas tinggi...",
        "image" => "assets/img/products/led-screen.webp",
    ],
    // ... 7 more products
];

// ==========================================
// PORTFOLIO CATEGORIES
// ==========================================
$categories = [
    'Custom Backdrop & Custom Photobooth' => 'backdrop',
    'Custom Exhibition Booth & Partisi R8' => 'partisi',
    'Event Production' => 'produksi',
    'Videotron & LED Screen' => 'led',
    'Multimedia & Livestreaming' => 'livestreaming',
    'TV Plasma & Digital Signage' => 'tv'
];

// ==========================================
// PORTFOLIO CAPTIONS
// ==========================================
$captions = [
    'led' => [
        'ledartboard-1.webp' => 'Rapat Kerja Koni 2020',
        'ledartboard-2.webp' => 'Sidang Tertutup Unhan',
        // ... more captions
    ],
    'livestreaming' => [
        // ... captions
    ],
    // ... other categories
];

// ==========================================
// HELPER FUNCTIONS
// ==========================================

/**
 * Generate WhatsApp link for specific product
 */
function whatsappButton($whatsappNo, $productName) {
    $templateText = "Halo admin Kreasi Pro, saya ingin tanya tentang sewa " . $productName;
    return "https://api.whatsapp.com/send?phone=" . $whatsappNo . "&text=" . urlencode($templateText);
}

/**
 * Render product card HTML
 */
function renderProductCard($product, $whatsappNo) {
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
```

**Cara Memodifikasi:**

**Menambah Produk:**
```php
$products[] = [
    "name" => "Nama Produk Baru",
    "description" => "Deskripsi produk yang menjelaskan detail layanan...",
    "image" => "assets/img/products/nama-file.webp",
];
```

**Menambah Portfolio:**
```php
// 1. Upload gambar ke folder yang sesuai
// assets/img/porto/led/project-new.webp

// 2. Tambahkan caption
$captions['led']['project-new.webp'] = 'Nama Event 2026';
```

**Update Kontak:**
```php
define('WHATSAPP_NUMBER', '628123456789');  // Nomor baru
define('EMAIL_ADDRESS', 'contact@newdomain.com');  // Email baru
```

---

## 🔐 Security Layer

### Security Architecture

```
┌─────────────────────────────────────────────┐
│           User Input / Request              │
└───────────────┬─────────────────────────────┘
                │
                ▼
┌─────────────────────────────────────────────┐
│       Layer 1: Apache (.htaccess)           │
│  • Block dangerous file access              │
│  • Set security headers                     │
│  • Force HTTPS (production)                 │
└───────────────┬─────────────────────────────┘
                │
                ▼
┌─────────────────────────────────────────────┐
│       Layer 2: PHP (security.php)           │
│  • Validate server variables                │
│  • Sanitize file paths                      │
│  • Generate safe base URL                   │
└───────────────┬─────────────────────────────┘
                │
                ▼
┌─────────────────────────────────────────────┐
│       Layer 3: Output Encoding              │
│  • escapeHtml() for content                 │
│  • escapeAttr() for attributes              │
│  • escapeUrl() for links                    │
└───────────────┬─────────────────────────────┘
                │
                ▼
┌─────────────────────────────────────────────┐
│       Layer 4: Browser Security             │
│  • CSP prevents unauthorized scripts        │
│  • X-Frame-Options prevents clickjacking    │
│  • HSTS enforces HTTPS                      │
└─────────────────────────────────────────────┘
```

### Threat Model & Mitigations

| Threat | Risk | Mitigation | Implementation |
|--------|------|------------|----------------|
| **XSS (Cross-Site Scripting)** | HIGH | Output encoding | `escapeHtml()`, `escapeAttr()`, `escapeUrl()` |
| **Path Traversal** | MEDIUM | Path sanitization | `sanitizeFilename()` removes `../` |
| **HTTP Host Injection** | MEDIUM | Host validation | `validateHost()` validates format |
| **Clickjacking** | LOW | X-Frame-Options | `.htaccess` sets SAMEORIGIN |
| **MIME Sniffing** | LOW | X-Content-Type-Options | `.htaccess` sets nosniff |
| **CSRF** | MEDIUM | Token validation | `generateCsrfToken()`, `validateCsrfToken()` |

### Security Checklist

**Development:**
- [ ] All user input sanitized dengan `sanitizeInput()`
- [ ] All output escaped dengan `escapeHtml()` dll
- [ ] All file paths sanitized dengan `sanitizeFilename()`
- [ ] CSRF tokens implemented untuk forms
- [ ] Error messages tidak expose sensitive info

**Production:**
- [ ] HTTPS enabled dan enforced
- [ ] HSTS header enabled
- [ ] Error display disabled (`display_errors = Off`)
- [ ] Error logging enabled
- [ ] File permissions set correctly (755/644)
- [ ] Sensitive files blocked via `.htaccess`
- [ ] Security headers verified in browser DevTools

---

## 🎨 Frontend Components

### CSS Architecture

**File:** `css/style.css`

```css
/* ==========================================
   1. GLOBAL STYLES
   ========================================== */
:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    --text-dark: #212529;
    --text-light: #ffffff;
}

body {
    font-family: 'Rubik', sans-serif;
    color: var(--text-dark);
}

/* ==========================================
   2. NAVBAR STYLES
   ========================================== */
.navbar {
    transition: all 0.3s ease;
}

.navbar.sticky-top {
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* ==========================================
   3. HERO SECTION
   ========================================== */
.hero-header {
    padding: 100px 0;
}

.carousel-item img {
    border-radius: 12px;
}

/* ==========================================
   4. PRODUCT CARDS
   ========================================== */
.service-item {
    border: 1px solid #eee;
    transition: transform 0.3s ease;
}

.service-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

/* ==========================================
   5. PORTFOLIO GALLERY
   ========================================== */
.portfolio-item .img-frame {
    position: relative;
    aspect-ratio: 4 / 3;
    overflow: hidden;
    border-radius: 12px;
}

.portfolio-item .img-frame img {
    object-fit: contain;
    transition: transform 0.3s ease;
}

.portfolio-item .img-frame:hover img {
    transform: scale(1.05);
}

/* ==========================================
   6. FOOTER
   ========================================== */
.footer {
    background: #f8f9fa;
    padding: 60px 0 30px;
}

/* ==========================================
   7. RESPONSIVE
   ========================================== */
@media (max-width: 768px) {
    .hero-header {
        padding: 60px 0;
    }
    
    .service-item {
        margin-bottom: 20px;
    }
}
```

### JavaScript Modules

**File:** `js/main.js`

```javascript
(function ($) {
    "use strict";

    // ==========================================
    // 1. PAGE LOADER
    // ==========================================
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner(0);

    // ==========================================
    // 2. SCROLL ANIMATIONS
    // ==========================================
    new WOW().init();

    // ==========================================
    // 3. STICKY NAVBAR
    // ==========================================
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.navbar').addClass('sticky-top shadow-sm');
        } else {
            $('.navbar').removeClass('sticky-top shadow-sm');
        }
    });

    // ==========================================
    // 4. SMOOTH SCROLL
    // ==========================================
    $('.back-to-top').click(function () {
        $('html, body').animate({ 
            scrollTop: 0 
        }, 1500, 'easeInOutExpo');
        return false;
    });

    // ==========================================
    // 5. VIDEO MODAL (with validation)
    // ==========================================
    $(document).ready(function () {
        var $videoSrc;
        
        $('.btn-play').click(function () {
            $videoSrc = $(this).data("src");
            
            // Security: Only allow YouTube/Vimeo
            if ($videoSrc && typeof $videoSrc === 'string') {
                var isValid = $videoSrc.match(
                    /^https:\/\/(www\.)?(youtube\.com|youtu\.be|vimeo\.com)\//
                );
                
                if (!isValid) {
                    console.warn('Invalid video source blocked');
                    $videoSrc = '';
                    return false;
                }
            }
        });

        $('#videoModal').on('shown.bs.modal', function (e) {
            if ($videoSrc) {
                $("#video").attr('src', $videoSrc + "?autoplay=1&modestbranding=1&showinfo=0");
            }
        });

        $('#videoModal').on('hide.bs.modal', function (e) {
            if ($videoSrc) {
                $("#video").attr('src', $videoSrc);
            }
        });
    });

})(jQuery);
```

### Portfolio Carousel

```javascript
// In index.php inline script
$(document).ready(function() {
    $(".owl-carousel").each(function() {
        $(this).owlCarousel({
            loop: $(this).find('.item').length > 1,  // Only loop if multiple items
            margin: 10,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            smartSpeed: 700,
            rewind: true,
            navText: [
                '<i class="bi bi-chevron-left fs-4"></i>',
                '<i class="bi bi-chevron-right fs-4"></i>'
            ],
            responsive: {
                0: { items: 1 },      // Mobile
                576: { items: 2 },    // Small tablet
                768: { items: 3 },    // Tablet
                992: { items: 4 }     // Desktop
            }
        });
    });

    // Magnific Popup for lightbox
    $('.popup-image').magnificPopup({
        type: 'image',
        gallery: { enabled: false },
        mainClass: 'mfp-fade',
        removalDelay: 300,
        closeOnContentClick: true
    });
});
```

---

## 📊 Performance Optimization

### Image Optimization

**Lazy Loading:**
```html

<!-- All portfolio images -->
<img src="..." alt="..." loading="lazy">
```

**WebP Format:**
```
✓ Use WebP for photos (80% smaller than JPEG)
✓ Fallback to JPEG for compatibility
```

**Recommended Image Sizes:**
```
Product cards: 800x600px
Portfolio: 1200x900px (max)
Hero carousel: 1920x1080px
```

### Caching Strategy

**.htaccess configuration:**
```apache
# Images - 1 year cache
ExpiresByType image/jpeg "access plus 1 year"
ExpiresByType image/webp "access plus 1 year"

# CSS/JS - 1 month cache
ExpiresByType text/css "access plus 1 month"
ExpiresByType application/javascript "access plus 1 month"
```

### Compression

```apache
# GZIP compression
AddOutputFilterByType DEFLATE text/html text/css
AddOutputFilterByType DEFLATE application/javascript
```

---

## 🔧 Best Practices

### Code Style

**PHP:**
```php
// ✓ GOOD: Proper spacing, comments
function renderCard($data) {
    // Validate input
    if (empty($data)) {
        return '';
    }
    
    // Escape output
    $name = escapeHtml($data['name']);
    
    return $html;
}

// ✗ BAD: No validation, no escaping
function renderCard($data) {
    return '<div>' . $data['name'] . '</div>';
}
```

**JavaScript:**
```javascript
// ✓ GOOD: Null checks, validation
$(document).ready(function() {
    var element = $('#myElement');
    
    if (element.length > 0) {
        element.doSomething();
    }
});

// ✗ BAD: No checks
$(document).ready(function() {
    $('#myElement').doSomething();  // May error if not found
});
```

### Security

**Always:**
- ✓ Escape ALL output with appropriate function
- ✓ Validate ALL input before use
- ✓ Use prepared statements for database (future)
- ✓ Implement CSRF tokens for forms
- ✓ Keep dependencies updated

**Never:**
- ✗ Trust user input directly
- ✗ Use eval() or similar dangerous functions
- ✗ Expose error messages to users
- ✗ Store sensitive data in client-side
- ✗ Use deprecated functions

### Performance

**Do:**
- ✓ Lazy load images
- ✓ Minify CSS/JS for production
- ✓ Enable GZIP compression
- ✓ Use CDN for libraries (optional)
- ✓ Optimize database queries (if added)

**Don't:**
- ✗ Load all images at once
- ✗ Include unused libraries
- ✗ Make synchronous AJAX requests
- ✗ Query in loops
- ✗ Forget to cache assets

---

## 📈 Monitoring & Maintenance

### Error Logging

**Enable logging (php.ini):**
```ini
log_errors = On
error_log = /path/to/php-error.log
display_errors = Off  ; Production only
```

**Check logs:**
```bash
# Laragon
tail -f c:/laragon/logs/apache_error.log

# XAMPP
tail -f c:/xampp/apache/logs/error.log
```

### Security Audit

**Monthly tasks:**
```bash
# 1. Check PHP version
php -v

# 2. Test security functions
php test_security.php

# 3. Review error logs
grep -i "error\|warning" /path/to/error.log

# 4. Check file permissions
ls -la config/
ls -la index.php
```

### Backup Strategy

**Weekly backups:**
```bash
# 1. ZIP entire project
zip -r kreasi-pro-backup-$(date +%Y%m%d).zip kreasi-pro-main/

# 2. Store offsite
# Upload to cloud storage (Google Drive, Dropbox, etc)
```

---

## 🎓 Learning Resources

### PHP Security
- OWASP Top 10: https://owasp.org/www-project-top-ten/
- PHP Security Guide: https://www.php.net/manual/en/security.php

### Frontend Development
- Bootstrap Docs: https://getbootstrap.com/docs/
- jQuery API: https://api.jquery.com/

### Performance
- Google PageSpeed: https://pagespeed.web.dev/
- GTmetrix: https://gtmetrix.com/

---

**Document Version:** 1.0.0  
**Last Updated:** February 14, 2026  
**Maintained By:** Development Team
