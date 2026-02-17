# 🚀 Quick Start Guide - Kreasi Pro

Panduan singkat untuk developer baru yang ingin langsung memulai.

## ⚡ 5-Minute Setup

### Step 1: Copy Project (30 detik)
```bash
# Copy ke web server directory
# Laragon: c:/laragon/www/kreasi-pro-main/
# XAMPP: c:/xampp/htdocs/kreasi-pro-main/
```

### Step 2: Start Server (30 detik)
```bash
# Laragon: Click "Start All"
# XAMPP: Start Apache
```

### Step 3: Open Browser (30 detik)
```
http://localhost/kreasi-pro-main/
```

### Step 4: Verify (3 menit)
- [ ] Website loads tanpa error
- [ ] Images tampil semua
- [ ] Navigation berfungsi
- [ ] Portfolio carousel jalan
- [ ] WhatsApp button works

✅ **DONE!** Project ready to use.

---

## 📝 Common Tasks

### 1. Update Contact Info (2 menit)

**File:** `config/config.php`

```php
// Line 9-11
define('WHATSAPP_NUMBER', '628XXXXXXXXXX');  // ← Change this
define('EMAIL_ADDRESS', 'your@email.com');    // ← Change this
define('PHYSICAL_ADDRESS', 'Your Address');   // ← Change this
```

**Test:**
- Click WhatsApp button → Should open with new number
- Check footer → Should show new email/address

---

### 2. Add New Product (5 menit)

**File:** `config/config.php`

```php
// Add to $products array (around line 30)
$products[] = [
    "name" => "Nama Produk Baru",
    "description" => "Deskripsi lengkap tentang produk...",
    "image" => "assets/img/products/nama-file.webp",
];
```

**Upload Image:**
```
assets/img/products/nama-file.webp
Recommended size: 800x600px
Format: WebP or JPEG
```

**Test:**
- Refresh website
- Check "Layanan" section → New product appears
- Check navigation dropdown → Product in menu

---

### 3. Add Portfolio Images (3 menit)

**Step 1: Upload Image**
```
# Save to appropriate folder:
assets/img/porto/led/           ← LED Screen  projects
assets/img/porto/livestreaming/ ← Multimedia projects
assets/img/porto/backdrop/      ← Backdrop projects
assets/img/porto/partisi/       ← Booth projects
assets/img/porto/produksi/      ← Production projects
assets/img/porto/tv/            ← TV Plasma projects
```

**Step 2: Add Caption** (`config/config.php`)
```php
// Find $captions array (around line 110)
$captions = [
    'led' => [
        'your-image-name.webp' => 'Event Name 2026',  // ← Add here
    ],
];
```

**Test:**
- Scroll to Portfolio section
- Find category → New image in carousel
- Click image → Lightbox opens

---

### 4. Change Social Media Links (1 menit)

**File:** `config/config.php`

```php
// Line 25-28
$socialMedia = [
    "instagram" => "https://www.instagram.com/your_account",  // ← Change
    "youtube" => "https://www.youtube.com/@your_channel",     // ← Change
];
```

---

### 5. Update Google Maps (2 menit)

**Step 1: Get Embed Code**
1. Go to https://maps.google.com
2. Search your business location
3. Click "Share" → "Embed a map"
4. Copy iframe code

**Step 2: Update Code** (`index.php`)
```php
// Find around line 879
<iframe 
    src="YOUR_NEW_EMBED_URL_HERE" 
    width="100%" 
    height="450">
</iframe>
```

---

## 🔒 Security Checklist

Quick checks untuk pastikan website aman:

- [ ] **Output Encoding** - All `<?= ?>` use `escapeHtml()` or similar
- [ ] **File Paths** - Portfolio folders use `sanitizeFilename()`
- [ ] **URLs** - All links use `escapeUrl()`
- [ ] **Headers** - Check DevTools → Network → Security headers present

**Quick Test:**
```bash
php test_security.php
# Should show: ✓ PASS for all tests
```

---

## 🐛 Quick Troubleshooting

### Problem: Images Not Loading

**Check:**
```bash
# 1. File exists?
ls assets/img/products/your-image.webp

# 2. Correct path in config?
# Check $products array in config/config.php

# 3. Browser cache?
# CTRL+F5 to hard refresh
```

---

### Problem: Carousel Not Working

**Check:**
```html
<!-- 1. jQuery loaded? -->
<script src="lib/jquery.min.js"></script>

<!-- 2. Owl Carousel loaded? -->
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- 3. Browser console for errors -->
<!-- Press F12 → Console tab -->
```

---

### Problem: WhatsApp Button Not Working

**Check:**
```php
// 1. Valid number format?
define('WHATSAPP_NUMBER', '6282298074293');  // Must start with country code

// 2. Test URL manually:
https://api.whatsapp.com/send?phone=6282298074293&text=Test
```

---

### Problem: 500 Internal Server Error

**Check:**
```bash
# 1. PHP syntax errors?
php -l index.php

# 2. Check error log:
# Laragon: c:/laragon/logs/apache_error.log
# XAMPP: c:/xampp/apache/logs/error.log

# 3. File permissions?
# Files: 644
# Folders: 755
```

---

## 📚 Documentation Quick Links

| Document | Use For |
|----------|---------|
| [README.md](README.md) | Installation, configuration, deployment |
| [DOCUMENTATION.md](DOCUMENTATION.md) | Architecture, code structure, best practices |
| [API_REFERENCE.md](API_REFERENCE.md) | Function reference, code examples |
| [CHANGELOG.md](CHANGELOG.md) | Version history, upgrade notes |
| **→ THIS FILE** | Quick tasks, troubleshooting |

---

## 🎯 Development Workflow

**Making Changes:**

```bash
# 1. Edit files
# - config/config.php (for content)
# - css/style.css (for styling)
# - js/main.js (for JavaScript)

# 2. Test locally
http://localhost/kreasi-pro-main/

# 3. Check browser console (F12)
# Look for errors

# 4. Verify security
php test_security.php

# 5. Push to production
# (See README.md Deployment section)
```

---

## ⚙️ Essential Commands

```bash
# Check PHP syntax
php -l filename.php

# Test security functions
php test_security.php

# View error log (Laragon)
tail -f c:/laragon/logs/apache_error.log

# Check file permissions
ls -la config/
```

---

## 📞 Need Help?

1. **Check Documentation** - Start with README.md
2. **Search This File** - Common tasks covered here
3. **Check DOCUMENTATION.md** - For technical details
4. **Review API_REFERENCE.md** - For function usage
5. **Contact Team** - If still stuck

---

## 🎨 Code Snippets

### Add WhatsApp Button in HTML

```php
$whatsappUrl = escapeUrl(whatsappButton(WHATSAPP_NUMBER, 'Product Name'));
?>
<a href="<?= $whatsappUrl ?>" 
   target="_blank" 
   rel="noopener noreferrer"
   class="btn btn-success">
    Contact WhatsApp
</a>
```

### Display Safe HTML Content

```php
$productName = escapeHtml($product['name']);
$productDesc = escapeHtml($product['description']);
?>
<h2><?= $productName ?></h2>
<p><?= $productDesc ?></p>
```

### Add Image with Lazy Loading

```html
<img src="<?= escapeUrl($imagePath) ?>" 
     alt="<?= escapeAttr($caption) ?>" 
     loading="lazy"
     class="img-fluid">
```

### Create Product Card

```php
foreach ($products as $product) {
    echo renderProductCard($product, WHATSAPP_NUMBER);
}
```

---

## 🚢 Production Deployment Checklist

Before going live:

- [ ] Update contact information
- [ ] Test all features
- [ ] Enable HTTPS
- [ ] Set `display_errors = Off`
- [ ] Uncomment HSTS in `.htaccess`
- [ ] Check file permissions (644/755)
- [ ] Remove `test_security.php` (optional)
- [ ] Backup files
- [ ] Test on production server
- [ ] Verify security headers

---

## 💡 Pro Tips

1. **Always escape output:** Use `escapeHtml()`, `escapeAttr()`, `escapeUrl()`
2. **Lazy load images:** Add `loading="lazy"` to all images
3. **Test after changes:** View in browser, check console for errors
4. **Keep backups:** `index.php.backup` is your safety net
5. **Use version control:** Git recommended for tracking changes

---

## 📊 File Structure (Essential)

```
kreasi-pro-main/
├── config/
│   ├── security.php      ← Security functions
│   └── config.php        ← YOUR SETTINGS HERE
├── assets/img/
│   ├── products/         ← Product images
│   └── porto/            ← Portfolio by category
├── index.php             ← Main application
├── .htaccess            ← Security headers
└── Documentation/
    ├── README.md         ← User guide
    ├── DOCUMENTATION.md  ← Technical docs
    ├── API_REFERENCE.md  ← Function reference
    ├── CHANGELOG.md      ← Version history
    └── QUICKSTART.md     ← THIS FILE
```

---

**Remember:** 
- 🔒 Security ALWAYS comes first
- 📝 Test after EVERY change
- 💾 Keep backups
- 📖 Read documentation when stuck

**Happy Coding! 🚀**

---

**Version:** 2.0.0  
**Last Updated:** February 14, 2026
