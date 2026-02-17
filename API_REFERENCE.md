# API & Function Reference

Dokumentasi lengkap untuk semua functions yang tersedia di project Kreasi Pro.

## 📚 Daftar Isi

- [Security Functions](#security-functions)
- [Helper Functions](#helper-functions)
- [Configuration Variables](#configuration-variables)
- [JavaScript API](#javascript-api)

---

## 🔐 Security Functions

### escapeHtml()

**File:** `config/security.php`

```php
function escapeHtml(string $string): string
```

**Deskripsi:**  
Escape HTML special characters untuk mencegah XSS attacks dalam HTML content.

**Parameters:**
- `$string` (string) - String yang akan di-escape

**Returns:**  
`string` - HTML-safe string dengan special characters terkonversi ke HTML entities

**Example:**
```php
$userInput = '<script>alert("XSS")</script>';
echo escapeHtml($userInput);
// Output: &lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;

$productName = 'LED Screen & TV';
echo '<h1>' . escapeHtml($productName) . '</h1>';
// Output: <h1>LED Screen &amp; TV</h1>
```

**Use Cases:**
- Menampilkan user input di HTML
- Product names, descriptions dalam HTML tags
- Any dynamic content inserted into HTML

---

### escapeAttr()

**File:** `config/security.php`

```php
function escapeAttr(string $string): string
```

**Deskripsi:**  
Escape string untuk digunakan dalam HTML attributes.

**Parameters:**
- `$string` (string) - String untuk HTML attribute

**Returns:**  
`string` - Attribute-safe string

**Example:**
```php
$imageCaption = 'Event "Special" 2026';
echo '<img alt="' . escapeAttr($imageCaption) . '">';
// Output: <img alt="Event &quot;Special&quot; 2026">

$inputValue = 'Value with "quotes"';
echo '<input type="text" value="' . escapeAttr($inputValue) . '">';
// Output: <input type="text" value="Value with &quot;quotes&quot;">
```

**Use Cases:**
- `alt` attributes dalam `<img>`
- `title` attributes
- `value` dalam form inputs
- `data-*` attributes

---

### escapeUrl()

**File:** `config/security.php`

```php
function escapeUrl(string $url): string
```

**Deskripsi:**  
Validate dan escape URLs, blocking dangerous protocols seperti `javascript:` dan `data:`.

**Parameters:**
- `$string` (string) - URL to validate

**Returns:**  
`string` - Safe URL atau '#' jika dangerous

**Example:**
```php
// Safe URL
$goodUrl = 'https://example.com/page?id=123';
echo '<a href="' . escapeUrl($goodUrl) . '">Link</a>';
// Output: <a href="https://example.com/page?id=123">Link</a>

// Dangerous URL - blocked
$badUrl = 'javascript:alert(1)';
echo '<a href="' . escapeUrl($badUrl) . '">Link</a>';
// Output: <a href="#">Link</a>

// WhatsApp URL
$whatsappUrl = "https://api.whatsapp.com/send?phone=628123456789";
echo '<a href="' . escapeUrl($whatsappUrl) . '">WhatsApp</a>';
```

**Blocked Protocols:**
- `javascript:`
- `data:`
- `vbscript:`
- `file:`

**Use Cases:**
- `href` dalam `<a>` tags
- `src` dalam `<img>`, `<iframe>`
- WhatsApp links
- Social media links

---

### escapeJs()

**File:** `config/security.php`

```php
function escapeJs(string $string): string
```

**Deskripsi:**  
Escape string untuk embedding di inline JavaScript.

**Parameters:**
- `$string` (string) - String untuk JavaScript

**Returns:**  
`string` - JavaScript-safe string

**Example:**
```php
$productName = "Product's Name";

echo '<script>';
echo 'var name = "' . escapeJs($productName) . '";';
echo 'console.log(name);';
echo '</script>';
// Output: var name = "Product\'s Name";
```

**Use Cases:**
- PHP values embedded dalam `<script>` tags
- Dynamic JavaScript variable assignment
- AJAX data preparation

---

### sanitizeInput()

**File:** `config/security.php`

```php
function sanitizeInput(string $input): string
```

**Deskripsi:**  
Sanitize user input dengan menghapus null bytes dan trimming whitespace.

**Parameters:**
- `$input` (string) - User input to sanitize

**Returns:**  
`string` - Sanitized string

**Example:**
```php
// Form input
$name = sanitizeInput($_POST['name']);
$email = sanitizeInput($_POST['email']);

// Query parameter
$category = sanitizeInput($_GET['category']);

// Before: "  John Doe\0  "
// After: "John Doe"
```

**Removes:**
- Null bytes (`\0`)
- Leading/trailing whitespace

**Use Cases:**
- All form inputs
- Query string parameters
- Any user-provided data

---

### sanitizeFilename()

**File:** `config/security.php`

```php
function sanitizeFilename(string $filename): string
```

**Deskripsi:**  
Sanitize filename untuk mencegah path traversal attacks.

**Parameters:**
- `$filename` (string) - Filename to sanitize

**Returns:**  
`string` - Safe filename

**Example:**
```php
// Prevent path traversal
$unsafe = '../../../etc/passwd';
$safe = sanitizeFilename($unsafe);
// Result: 'etcpasswd'

// Portfolio category from URL
$category = sanitizeFilename($_GET['category']);
$path = "assets/img/porto/$category/";
// Safe: can't escape porto directory

// Keep valid filenames
$valid = 'product-image_123.webp';
$result = sanitizeFilename($valid);
// Result: 'product-image_123.webp' (unchanged)
```

**Removes:**
- Path traversal attempts (`../`, `..\\`)
- Directory separators (`/`, `\\`)
- Special characters (keeps: `a-z`, `A-Z`, `0-9`, `-`, `_`, `.`)

**Use Cases:**
- File uploads (validation)
- Dynamic file paths
- Portfolio categories dari URL
- Image filenames dari user input

---

### validateHost()

**File:** `config/security.php`

```php
function validateHost(string $host): string|false
```

**Deskripsi:**  
Validate HTTP Host header untuk mencegah injection attacks.

**Parameters:**
- `$host` (string) - HTTP host to validate

**Returns:**  
`string|false` - Validated host without port, or false if invalid

**Example:**
```php
// Valid hosts
validateHost('localhost');           // 'localhost'
validateHost('example.com');         // 'example.com'
validateHost('sub.example.com');     // 'sub.example.com'
validateHost('example.com:8080');    // 'example.com' (port removed)

// Invalid hosts
validateHost('<script>alert(1)</script>'); // false
validateHost('host with spaces');          // false

// Usage in code
$host = validateHost($_SERVER['HTTP_HOST']);
if ($host === false) {
    $host = 'localhost';  // Fallback
}
```

**Validation Rules:**
- Must contain only: `a-z`, `A-Z`, `0-9`, `-`, `.`
- Port numbers automatically removed
- No spaces or special characters

**Use Cases:**
- Validating `$_SERVER['HTTP_HOST']`
- Building base URLs
- HTTP Host Header Injection prevention

---

### getSafeBaseUrl()

**File:** `config/security.php`

```php
function getSafeBaseUrl(): string
```

**Deskripsi:**  
Generate safe base URL dengan validation terhadap server variables.

**Parameters:**  
None

**Returns:**  
`string` - Safe base URL (e.g., `https://localhost/kreasi-pro-main`)

**Example:**
```php
$baseUrl = getSafeBaseUrl();
echo $baseUrl;
// Local: http://localhost/kreasi-pro-main
// Production: https://yourdomain.com

// Use for assets
$imagePath = $baseUrl . '/assets/img/products/led.webp';
echo '<img src="' . escapeUrl($imagePath) . '">';
```

**Logic:**
1. Detect HTTPS vs HTTP
2. Validate HTTP_HOST
3. Get script directory
4. Combine into safe URL

**Use Cases:**
- Asset URLs (images, CSS, JS)
- Portfolio image paths
- Dynamic resource loading

---

### setSecurityHeaders()

**File:** `config/security.php`

```php
function setSecurityHeaders(): void
```

**Deskripsi:**  
Set HTTP security headers untuk defense-in-depth protection.

**Parameters:**  
None

**Returns:**  
`void`

**Example:**
```php
// Call at top of index.php, before any output
setSecurityHeaders();
```

**Headers Set:**

| Header | Value | Purpose |
|--------|-------|---------|
| `X-Frame-Options` | `SAMEORIGIN` | Prevent clickjacking |
| `X-Content-Type-Options` | `nosniff` | Prevent MIME sniffing |
| `X-XSS-Protection` | `1; mode=block` | Enable XSS filter |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Control referrer info |
| `Content-Security-Policy` | (see below) | Restrict resource loading |
| `Strict-Transport-Security` | `max-age=31536000` | Enforce HTTPS (if enabled) |

**CSP Policy:**
```
default-src 'self';
script-src 'self' 'unsafe-inline' https://ajax.googleapis.com https://cdn.jsdelivr.net;
style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;
img-src 'self' data: https:;
frame-src https://www.google.com;
```

**IMPORTANT:**  
Must be called before any HTML output!

---

### generateCsrfToken()

**File:** `config/security.php`

```php
function generateCsrfToken(): string
```

**Deskripsi:**  
Generate secure CSRF token untuk form protection.

**Parameters:**  
None

**Returns:**  
`string` - 64-character hex token

**Example:**
```php
// In form HTML
$token = generateCsrfToken();
?>
<form method="POST" action="/submit.php">
    <input type="hidden" name="csrf_token" value="<?= $token ?>">
    <input type="text" name="email">
    <button type="submit">Submit</button>
</form>
```

**Token Storage:**
- Stored in `$_SESSION['csrf_token']`
- Auto-generated on first call
- Reused for same session

**Use Cases:**
- Contact forms
- Newsletter signup
- Any POST form submission

---

### validateCsrfToken()

**File:** `config/security.php`

```php
function validateCsrfToken(string $token): bool
```

**Deskripsi:**  
Validate submitted CSRF token against session token.

**Parameters:**
- `$token` (string) - Token dari form submission

**Returns:**  
`bool` - True if valid, false otherwise

**Example:**
```php
// Form processing
if (isPostRequest()) {
    if (validateCsrfToken($_POST['csrf_token'])) {
        // Token valid - process form
        $email = sanitizeInput($_POST['email']);
        // Send email...
    } else {
        // Invalid token - possible CSRF attack
        die('Security error: Invalid token');
    }
}
```

**Security:**
- Uses `hash_equals()` to prevent timing attacks
- Requires exact match

**Use Cases:**
- Validating form submissions
- AJAX POST requests
- Any state-changing operations

---

### isPostRequest()

**File:** `config/security.php`

```php
function isPostRequest(): bool
```

**Deskripsi:**  
Check if current request is POST method.

**Returns:**  
`bool` - True if POST, false otherwise

**Example:**
```php
if (isPostRequest()) {
    // Handle form submission
    $name = sanitizeInput($_POST['name']);
} else {
    // Show form
}
```

---

### isAjaxRequest()

**File:** `config/security.php`

```php
function isAjaxRequest(): bool
```

**Deskripsi:**  
Check if current request is AJAX.

**Returns:**  
`bool` - True if AJAX, false otherwise

**Example:**
```php
if (isAjaxRequest()) {
    // Return JSON
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
} else {
    // Return HTML
    include 'page.html';
}
```

---

## 🛠 Helper Functions

### whatsappButton()

**File:** `config/config.php`

```php
function whatsappButton(string $whatsappNo, string $productName): string
```

**Deskripsi:**  
Generate WhatsApp URL untuk specific product.

**Parameters:**
- `$whatsappNo` (string) - WhatsApp number with country code
- `$productName` (string) - Product name for message

**Returns:**  
`string` - WhatsApp URL

**Example:**
```php
$url = whatsappButton('6282298074293', 'LED Screen');
// Result: https://api.whatsapp.com/send?phone=6282298074293&text=Halo%20admin...

echo '<a href="' . escapeUrl($url) . '">Contact via WhatsApp</a>';
```

---

### renderProductCard()

**File:** `config/config.php`

```php
function renderProductCard(array $product, string $whatsappNo): string
```

**Deskripsi:**  
Render HTML untuk product card dengan proper escaping.

**Parameters:**
- `$product` (array) - Product data dengan keys: `name`, `description`, `image`
- `$whatsappNo` (string) - WhatsApp number for contact button

**Returns:**  
`string` - Complete HTML untuk product card

**Example:**
```php
$product = [
    'name' => 'LED Screen',
    'description' => 'High quality LED...',
    'image' => 'assets/img/products/led.webp'
];

echo renderProductCard($product, '6282298074293');
```

**Output:**
```html
<div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" id="service-LEDScreen">
    <div class="service-item text-center rounded">
        <div class="service-img">
            <img src="assets/img/products/led.webp" alt="..." loading="lazy">
        </div>
        <div class="service-content p-4">
            <h4>LED Screen</h4>
            <p>High quality LED...</p>
        </div>
        <div class="service-footer pb-4">
            <a href="..." target="_blank" class="btn...">Selengkapnya</a>
        </div>
    </div>
</div>
```

---

## ⚙️ Configuration Variables

### Contact Information

```php
// config/config.php

define('WHATSAPP_NUMBER', '6282298074293');
define('EMAIL_ADDRESS', 'info@kreasiproofficial.com');
define('PHYSICAL_ADDRESS', 'Jl. Musyawarah No.84, ...');

$whatsappNo = WHATSAPP_NUMBER;      // string
$email = EMAIL_ADDRESS;              // string
$alamat = PHYSICAL_ADDRESS;          // string
$emailLink = "mailto:" . $email;     // string
```

### Social Media

```php
$socialMedia = [
    "instagram" => "https://www.instagram.com/kreasipro.id",
    "youtube" => "https://www.youtube.com/@kreasiproofficial",
];
```

### Products Array

```php
$products = [
    [
        "name" => "Product Name",           // string
        "description" => "Description...",  // string
        "image" => "assets/img/...",        // string (relative path)
    ],
    // ... more products
];
```

**Structure:**
- Array of product objects
- Each product has 3 keys: `name`, `description`, `image`
- Used for generating product cards

### Portfolio Categories

```php
$categories = [
    'Display Name' => 'folder-name',
    // Example:
    'Videotron & LED Screen' => 'led',
    'Multimedia & Livestreaming' => 'livestreaming',
];
```

**Structure:**
- Associative array
- Key = Display name (shown to user)
- Value = Folder name in `assets/img/porto/`

### Portfolio Captions

```php
$captions = [
    'category-folder' => [
        'image-filename.webp' => 'Caption Text',
        // Example:
        'ledartboard-1.webp' => 'Event Name 2026',
    ],
];
```

**Structure:**
- Nested associative array
- First level = category folder name
- Second level = filename => caption

---

## 💻 JavaScript API

### WOW.js Animation

```javascript
// Initialize scroll animations
new WOW().init();
```

**Usage in HTML:**
```html
<div class="wow fadeInUp" data-wow-delay="0.1s">
    Content will animate on scroll
</div>
```

**Available Animations:**
- `fadeIn`, `fadeInUp`, `fadeInDown`, `fadeInLeft`, `fadeInRight`
- `slideInUp`, `slideInDown`
- `zoomIn`, `zoomOut`

### Owl Carousel

```javascript
$(".owl-carousel").owlCarousel({
    loop: true,           // boolean
    margin: 10,           // number (px)
    nav: true,            // boolean
    dots: false,          // boolean
    autoplay: true,       // boolean
    autoplayTimeout: 4000, // number (ms)
    responsive: {
        0: { items: 1 },      // Mobile
        576: { items: 2 },    // Tablet
        768: { items: 3 },    // Desktop
        992: { items: 4 }     // Large desktop
    }
});
```

### Magnific Popup

```javascript
$('.popup-image').magnificPopup({
    type: 'image',                    // 'image' | 'iframe' | 'inline'
    gallery: { enabled: false },      // boolean
    mainClass: 'mfp-fade',           // CSS class for animation
    closeOnContentClick: true         // boolean
});
```

**HTML:**
```html
<a href="image.jpg" class="popup-image">
    <img src="thumbnail.jpg">
</a>
```

---

## 📋 Constants

```php
// Defined in index.php
define('KREASI_PRO_LOADED', true);  // Security flag

// Error reporting (development/production)
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', '0');  // '1' for development
ini_set('log_errors', '1');
```

---

## 🔍 Error Handling

### PHP Errors

```php
// Development mode
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Production mode (default)
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
```

### JavaScript Errors

```javascript
// Global error handler
window.onerror = function(msg, url, line) {
    console.error('Error: ' + msg + ' at ' + url + ':' + line);
    return false;
};

// Try-catch for risky operations
try {
    $('.owl-carousel').owlCarousel({ /*...*/ });
} catch (e) {
    console.warn('Carousel initialization failed:', e);
}
```

---

**Last Updated:** February 14, 2026  
**Version:** 2.0.0
