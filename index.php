<?php
/**
 * Kreasi Pro - Main Index File
 * Event Equipment Rental Website
 * 
 * @package KreasiPro
 * @version 2.0.0 (Security Enhanced)
 */

// Define application loaded constant
define('KREASI_PRO_LOADED', true);

// Set error reporting (production mode)
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Load security utilities
require_once __DIR__ . '/config/security.php';

// Set security headers
setSecurityHeaders();

// Load configuration
require_once __DIR__ . '/config/config.php';

// Get safe base URL
$baseUrl = getSafeBaseUrl();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    
    <!-- Primary Meta Tags -->
    <title><?= escapeHtml($siteSettings['title']) ?></title>
    <meta name="title" content="<?= escapeHtml($siteSettings['title']) ?>">
    <meta name="description" content="<?= escapeHtml($siteSettings['description']) ?>">
    <meta name="keywords" content="<?= escapeHtml($siteSettings['keywords']) ?>">
    <meta name="author" content="<?= escapeHtml($siteSettings['author']) ?>">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="<?= escapeUrl($baseUrl) ?>/">
    
    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= escapeUrl($baseUrl) ?>/">
    <meta property="og:title" content="Kontraktor Event & Kontraktor Booth Profesional | Kreasi Pro">
    <meta property="og:description" content="Event vendor terpercaya untuk custom booth exhibition, sewa LED screen, videotron, dan perlengkapan event lengkap. Kontraktor booth profesional dengan layanan terbaik.">
    <meta property="og:image" content="<?= escapeUrl($baseUrl) ?>/assets/logo/logo.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Kreasi Pro - Event Vendor">
    <meta property="og:locale" content="id_ID">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= escapeUrl($baseUrl) ?>/">
    <meta name="twitter:title" content="Kontraktor Event & Booth Exhibition | Kreasi Pro">
    <meta name="twitter:description" content="Kontraktor event profesional dengan custom booth exhibition dan sewa perlengkapan event lengkap">
    <meta name="twitter:image" content="<?= escapeUrl($baseUrl) ?>/assets/logo/logo.png">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="geo.region" content="ID-JK">
    <meta name="geo.placename" content="Jakarta">
    <meta name="geo.position" content="-6.200000;106.816666">
    <meta name="ICBM" content="-6.200000, 106.816666">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&family=Rubik:wght@400;500&display=swap" rel="stylesheet">

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="assets/logo/logo-no-text.png">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.theme.default.min.css" rel="stylesheet">
    <link href="lib/magnific-popup.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <script src="lib/jquery.min.js"></script>
    <link rel="stylesheet" href="lib/magnific-popup.css" />
    <script src="lib/isotope.pkgd.js"></script>
    <script src="lib/jquery.magnific-popup.js"></script>
    <style>
        .nav-link.active {
            font-weight: bold;
            color: #007bff;
        }
    </style>
    
    <!-- Schema.org Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "@id": "<?= escapeJs($baseUrl) ?>#organization",
      "name": "Kreasi Pro - Kontraktor Event & Booth Exhibition",
      "alternateName": "Kreasi Pro Official",
      "description": "Kontraktor event dan event vendor profesional yang menyediakan custom booth exhibition, sewa LED screen, videotron, dan perlengkapan event lengkap untuk berbagai kebutuhan acara Anda.",
      "url": "<?= escapeJs($baseUrl) ?>/",
      "logo": "<?= escapeJs($baseUrl) ?>/assets/logo/logo.png",
      "image": "<?= escapeJs($baseUrl) ?>/assets/logo/logo.png",
      "telephone": "+<?= escapeJs(WHATSAPP_NUMBER) ?>",
      "email": "<?= escapeJs(EMAIL_ADDRESS) ?>",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Jl. Musyawarah No.84",
        "addressLocality": "Jakarta Selatan",
        "addressRegion": "DKI Jakarta",
        "postalCode": "12510",
        "addressCountry": "ID"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "-6.200000",
        "longitude": "106.816666"
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday"
        ],
        "opens": "08:00",
        "closes": "17:00"
      },
      "priceRange": "$$",
      "sameAs": [
        "<?= escapeJs($socialMedia['instagram']) ?>",
        "<?= escapeJs($socialMedia['youtube']) ?>"
      ],
      "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Event Equipment & Services",
        "itemListElement": [
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Custom Booth Exhibition",
              "description": "Jasa pembuatan dan penyewaan custom booth exhibition untuk pameran dan event"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Sewa LED Screen & Videotron",
              "description": "Layanan sewa LED screen, videotron, dan TV plasma untuk berbagai event"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Event Production",
              "description": "Jasa event production lengkap termasuk multimedia dan livestreaming"
            }
          },
          {
            "@type": "Offer",
            "itemOffered": {
              "@type": "Service",
              "name": "Sewa Backdrop Custom",
              "description": "Penyewaan backdrop custom dan photobooth untuk berbagai acara"
            }
          }
        ]
      },
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "150",
        "bestRating": "5",
        "worstRating": "1"
      }
    }
    </script>
    
    <!-- WebSite Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "<?= escapeJs($baseUrl) ?>/",
      "name": "<?= escapeJs($siteSettings['title']) ?>",
      "description": "<?= escapeJs($siteSettings['description']) ?>",
      "publisher": {
        "@type": "Organization",
        "name": "Kreasi Pro",
        "logo": {
          "@type": "ImageObject",
          "url": "<?= escapeJs($baseUrl) ?>/assets/logo/logo.png"
        }
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?= escapeJs($baseUrl) ?>/?s={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid header position-relative overflow-hidden" id="home">
        <nav class="navbar navbar-expand-lg fixed-top navbar-light px-4 px-lg-5 py-3 py-lg-0">
            <a href="#" class="navbar-brand p-0">
                <h1 class="display-6 text-primary m-0">
                    <img src="assets/logo/logo-no-text.png" alt="Logo Kreasi Pro">
                    <span class="text-secondary text-logo">Kreasi</span><span class="text-primary text-logo">Pro</span>
                </h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="#home" class="nav-item nav-link">Home</a>
                    <a href="#about" class="nav-item nav-link">Tentang Kami</a>
                    <div class="nav-item dropdown">
                        <a href="#service" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Layanan</a>
                        <div class="dropdown-menu m-0">
                            <?php foreach ($products as $product) : ?>
                                <?php 
                                    $pId = isset($product['id']) ? $product['id'] : str_replace(' ', '', $product['name']);
                                ?>
                                <a href="#service-<?= escapeAttr($pId); ?>" class="dropdown-item" onclick="activeService(this)"><?= escapeHtml($product['name']); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a href="#portfolio" class="nav-item nav-link">Portfolio</a>
                    <a href="#contact" class="nav-item nav-link">Kontak</a>
                </div>
            </div>
        </nav>
        
        <!-- Sub Headline Start -->
        <div class="container-fluid feature overflow-hidden py-md-4 py-2 bg-dark text-white with-bg-image headline-container" id="sub-headline">
            <div class="container py-5">
                <div class="text-center mx-auto mb-0 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                    <h1 class="display-5 mb-md-5 mb-3 text-white"><?= escapeHtml($heroSection['sub_headline']['title']) ?></h1>
                    <p class="mb-4 fs-5"><?= escapeHtml($heroSection['sub_headline']['text']) ?></p>
                    <a href="<?= escapeUrl($buttonWhatsapp); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-pill py-3 wow fadeInUp" data-wow-delay="0.7s"><?= escapeHtml($heroSection['sub_headline']['button_text']) ?></a>
                </div>
            </div>
        </div>
        <!-- Sub Headline End -->

        <!-- Hero Header Start -->
        <div class="container">
            <div class="hero-header overflow-hidden">
                <div class="rotate-img">
                    <img src="img/sty-1.png" class="img-fluid w-100" alt="decoration">
                    <div class="rotate-sty-2"></div>
                </div>
                <div class="row gy-5 align-items-center">
                    <div class="col-lg-7 wow fadeInRight" data-wow-delay="0.2s">
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <?php 
                                    $slideCount = count($heroSection['main_headline']['images']);
                                    for($i = 0; $i < $slideCount; $i++): 
                                ?>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-current="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $i+1 ?>"></button>
                                <?php endfor; ?>
                            </div>
                            <div class="carousel-inner" style="border-radius: 12px; overflow: hidden;">
                                <?php foreach($heroSection['main_headline']['images'] as $index => $imgData): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <?php 
                                        $absImg = escapeUrl($baseUrl . '/' . $imgData);
                                        $altText = 'Kreasi Pro Event Slide ' . ($index + 1);
                                    ?>
                                    <a href="<?= $absImg ?>" class="popup-image d-block" title="<?= $altText ?>">
                                        <div class="hero-img-frame" style="--bg: url('<?= $absImg ?>')">
                                            <img src="<?= $absImg ?>"
                                                 alt="<?= $altText ?>"
                                                 class="hero-slide-img"
                                                 loading="<?= $index === 0 ? 'eager' : 'lazy' ?>">
                                        </div>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-5 wow fadeInLeft mt-2" data-wow-delay="0.1s">
                        <h1 class="display-4 text-dark mb-2 mb-md-4 wow fadeInUp fs-1" data-wow-delay="0.3s"><?= escapeHtml($heroSection['main_headline']['title']) ?></h1>
                        <p class="fs-5 mb-4 wow fadeInUp sub-headline-text" data-wow-delay="0.5s"><?= escapeHtml($heroSection['main_headline']['text']) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero Header End -->
    </div>
    <!-- Navbar & Hero End -->

    <!-- About Start -->
    <div class="container-fluid overflow-hidden py-md-4 py-2" id="about">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="">
                        <img src="<?= escapeUrl($aboutSection['image']) ?>" class="img-fluid w-100 rounded" alt="Tentang Kreasi Pro" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <h4 class="mb-1 text-primary"><?= escapeHtml($aboutSection['title']) ?></h4>
                    <h1 class="display-5 mb-4"><?= escapeHtml($aboutSection['headline']) ?></h1>
                    <p class="mb-4"><?= escapeHtml($aboutSection['text']) ?></p>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Service Start -->
    <div class="container-fluid service py-md-4 py-2" id="service">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                <h4 class="mb-1 text-primary"><?= escapeHtml($servicesSection['title']) ?></h4>
                <h1 class="display-5 mb-4"><?= escapeHtml($servicesSection['headline']) ?></h1>
                <p class="mb-0"><?= escapeHtml($servicesSection['text']) ?></p>
            </div>
            <div class="row g-4 justify-content-center">
                <?php foreach ($products as $product) : ?>
                    <?php echo renderProductCard($product, $whatsappNo); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Service End -->

    <!-- Benefit Start -->
    <div class="container-fluid feature overflow-hidden py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px; visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                <h4 class="text-primary"><?= escapeHtml($featuresSection['title']) ?></h4>
                <h1 class="display-5 mb-4"><?= escapeHtml($featuresSection['headline']) ?></h1>
            </div>
            <div class="row justify-content-center text-center mb-5 g-4">
                <?php foreach($featuresSection['items'] as $index => $item): ?>
                <div class="col-md-6 col-lg-4 col-12 wow fadeInUp box-benefit" data-wow-delay="<?= 0.1 + ($index * 0.2) ?>s">
                    <div class="text-center">
                        <div class="d-inline-block rounded bg-light p-4 mb-4"><i class="<?= escapeAttr($item['icon']) ?> fa-5x text-secondary"></i></div>
                        <div class="feature-content">
                            <h4 class="h4"><?= escapeHtml($item['title']) ?></h4>
                            <p class="mt-2 mb-0"><?= escapeHtml($item['text']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                <h4>Siap Sukseskan Event Anda? Hubungi Kami untuk Menyewa Alat Terbaik!</h4>
                <a href="<?= escapeUrl($buttonWhatsapp); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-pill py-3 wow fadeInUp mt-3" data-wow-delay="0.7s">Hubungi Kami Sekarang</a>
            </div>
        </div>
    </div>
    <!-- Benefit End -->

    <div class="container-fluid overflow-hidden py-md-4 py-2" id="about">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="RotateMoveLeft">
                        <img src="img/features-1.png" class="img-fluid w-100 h-100" alt="Features">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <h1 class="display-5 mb-4 fs-2">Cocok untuk siapa produk ini?</h1>
                    <p class="mb-4">Penyewaan Alat Kami Cocok untuk Berbagai Jenis Event:</p>
                    <div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            <p class="fw-bold mb-0">Pameran dan Expo</p>
                        </div>
                        <p class="ms-4">Kualitas alat yang buruk atau tidak sesuai dapat merusak reputasi acara Anda.</p>

                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            <p class="fw-bold mb-0">Konferensi dan Seminar</p>
                        </div>
                        <p class="ms-4">Audio visual, mikrofon, dan alat presentasi canggih yang memudahkan komunikasi dengan audiens.</p>

                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            <p class="fw-bold mb-0">Acara Corporate</p>
                        </div>
                        <p class="ms-4">Dekorasi elegan dan peralatan profesional untuk acara perusahaan, peluncuran produk, atau workshop.</p>

                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            <p class="fw-bold mb-0">Pernikahan & Acara Sosial</p>
                        </div>
                        <p class="ms-4">Dekorasi pernikahan, lighting, dan sistem suara berkualitas untuk menciptakan suasana yang indah dan intim.</p>

                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            <p class="fw-bold mb-0">Acara Hiburan & Musik</p>
                        </div>
                        <p class="ms-4">Sistem suara dan pencahayaan yang akan menghidupkan suasana acara hiburan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid overflow-hidden py-md-4 py-2" id="about">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px; visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                <h1 class="display-5 mb-4">Apa saja yang akan didapatkan dengan memiliki produk ini?</h1>
            </div>
            <div class="pb-6 g-4 text-start">
                <div class="row justify-content-around ">
                    <div class="col-lg-6 col-md-6 wow fadeInLeft">
                        <div class="event-info-box align-items-center d-flex p-4 rounded bg-white shadow my-2">
                            <div class="text-center ">
                                <i class="fa fa-tasks bg-primary p-3 fs-2 text-white rounded-circle me-3"></i>
                            </div>
                            <div class="text-start">
                                <h5 class="mb-1">Perlengkapan Lengkap untuk Event</h5>
                                <small>Kami menyediakan segala yang Anda butuhkan dari stand pameran, kursi, meja, sistem suara, pencahayaan, hingga dekorasi premium.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 wow fadeInRight">
                        <div class="event-info-box align-items-center d-flex p-4 rounded bg-white shadow my-2">
                            <div class="text-center ">
                                <i class="fa fa-truck-loading bg-primary p-3 fs-2 text-white rounded-circle me-3"></i>
                            </div>
                            <div class="text-start">
                                <h5 class="mb-1">Layanan Pengiriman dan Instalasi</h5>
                                <small>Tim kami akan mengantarkan dan memasang alat tepat waktu, memastikan semuanya berfungsi dengan sempurna.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 wow fadeInLeft">
                        <div class="event-info-box align-items-center d-flex p-4 rounded bg-white shadow my-2">
                            <div class="text-center ">
                                <i class="fa fa-smile bg-primary p-3 fs-2 text-white rounded-circle me-3"></i>
                            </div>
                            <div class="text-start">
                                <h5 class="mb-1">Teknisi Berpengalaman</h5>
                                <small>Kami juga menyediakan teknisi terlatih yang siap membantu mengoperasikan alat jika diperlukan selama acara.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 wow fadeInRight">
                        <div class="event-info-box align-items-center d-flex p-4 rounded bg-white shadow my-2">
                            <div class="text-center ">
                                <i class="fa fa-shield-alt bg-primary p-3 fs-2 text-white rounded-circle me-3"></i>
                            </div>
                            <div class="text-start">
                                <h5 class="mb-1">Garansi Kualitas</h5>
                                <small>Jika ada masalah teknis, kami siap memberikan support langsung selama acara berlangsung.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Start -->
    <style>
    /* --- OWL BASIC --- */
    .owl-item {
      overflow: visible !important;
    }

    .owl-carousel .owl-nav {
      display: flex !important;
      justify-content: center;
      align-items: center;
      gap: 14px;
      margin-top: 10px;
    }

    .owl-carousel .owl-nav button {
      background: #f0f0f0 !important;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      color: #333 !important;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .owl-carousel .owl-nav button:hover {
      background: #007bff !important;
      color: #fff !important;
      transform: scale(1.1);
    }

    .owl-carousel .owl-nav.disabled {
      display: flex !important;
    }

    /* --- FIGURE STRUCTURE --- */
    .portfolio-item figure {
      margin: 0;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* --- WRAPPER FRAME UNTUK GAMBAR --- */
    .portfolio-item .img-frame {
      position: relative;
      width: 100%;
      aspect-ratio: 4 / 3;
      border-radius: 12px;
      overflow: hidden;
      background: #111;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* --- BACKGROUND BLUR UNTUK PORTRAIT --- */
    .portfolio-item .img-frame::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image: var(--bg);
      background-size: cover;
      background-position: center;
      filter: blur(15px) brightness(0.6);
      transform: scale(1.1);
      z-index: 1;
    }

    /* --- GAMBAR UTAMA --- */
    .portfolio-item .img-frame img {
      position: relative;
      z-index: 2;
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
      transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    /* --- HOVER EFEK --- */
    .portfolio-item .img-frame:hover img {
      transform: scale(1.04);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
    }

    /* --- CAPTION --- */
    .portfolio-item figcaption {
      margin-top: 10px;
      font-size: 14px;
      color: #6c757d;
      text-align: center;
    }
    </style>

    <div class="container-fluid feature overflow-hidden py-md-4 py-2" id="portfolio">
      <div class="container py-5">
        <div class="text-center mx-auto mb-5 wow animate__animated animate__fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
          <h1 class="display-5 mb-4">Portfolio Kami</h1>
          <p class="mb-0">Berbagai acara yang telah kami layani, mulai dari pengalaman menonton yang luar biasa hingga solusi terbaik untuk acara yang Anda inginkan.</p>
        </div>

        <?php
        foreach ($categories as $id => $title) {
            // ID is the folder name (e.g. 'led', 'backdrop')
            $folder = sanitizeFilename($id);
            $path = "assets/img/porto/$folder/";
            
            // Validate path exists
            if (!is_dir($path)) {
                continue;
            }
            
            // Get all images
            $images = glob($path . "*.{jpg,jpeg,png,webp}", GLOB_BRACE);

            if (count($images) > 0) {
                echo '<div class="portfolio-category mb-5 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">';
                echo '<h3 class="text-center mb-4 text-uppercase text-primary fw-bold">' . escapeHtml($title) . '</h3>'; // Title is the Display Name
                echo '<div class="owl-carousel owl-theme portfolio-item">';
                
                foreach ($images as $imgPath) {
                    $filename = basename($imgPath);
                    // Check caption using ID as key
                    $caption = isset($captions[$id][$filename]) ? $captions[$id][$filename] : '';
                    
                    // Clean up filename for alt text if caption missing
                    $altText = $caption ?: ucwords(str_replace(['-', '_', '.webp', '.jpg'], [' ', ' ', '', ''], $filename));
                    
                    $absolutePath = escapeUrl($baseUrl . '/' . $imgPath);

                    echo '<div class="item text-center p-2">';
                    echo '<figure>';
                    echo '<div class="img-frame" style="--bg:url(\'' . $absolutePath . '\')">';
                    echo '<a href="' . $absolutePath . '" class="popup-image" title="' . escapeAttr($caption) . '">';
                    echo '<img src="' . $absolutePath . '" alt="' . escapeAttr($altText) . '" loading="lazy">';
                    echo '</a>';
                    echo '</div>';
                    if ($caption) echo '<figcaption>' . escapeHtml($caption) . '</figcaption>';
                    echo '</figure>';
                    echo '</div>';
                }

                echo '</div></div>';
            }
        }
        ?>
      </div>
    </div>

    <script src="lib/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/magnific-popup/jquery.magnific-popup.js"></script>

    <script>
    new WOW().init();

    $(document).ready(function() {
      $(".owl-carousel").each(function() {
        $(this).owlCarousel({
          loop: $(this).find('.item').length > 1,
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
            0: { items: 1 },
            576: { items: 2 },
            768: { items: 3 },
            992: { items: 4 }
          }
        });
      });

      $('.popup-image').magnificPopup({
        type: 'image',
        gallery: { enabled: false },
        mainClass: 'mfp-fade',
        removalDelay: 300,
        closeOnContentClick: true
      });
    });
    </script>

   <!-- Portfolio End -->

    <div class="container-fluid">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-6">
                    <div class="text-start mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                        <h4>Harga Terjangkau untuk Semua Jenis Event - Sesuaikan dengan Anggaran Anda!</h4>
                        <p>Kami menawarkan berbagai paket penyewaan dengan harga yang sangat kompetitif dan transparan. Dapatkan penawaran sesuai dengan kebutuhan event Anda. Hubungi kami untuk mendapatkan harga terbaik yang sesuai dengan anggaran Anda!</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-start mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                        <h4>Jangan Biarkan Event Anda Terhambat oleh Kekurangan Perlengkapan!</h4>
                        <p>Dengan perlengkapan yang tepat, setiap event bisa sukses besar! Hubungi kami sekarang untuk memulai perjalanan acara Anda. Kami siap membantu Anda menciptakan acara yang luar biasa dengan perlengkapan profesional yang terbaik!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid feature overflow-hidden py-md-4 py-2 bg-dark text-white px-3">
        <div class="text-center mx-auto my-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
            <h2 class="text-white fw-bold">Event Anda Tidak Bisa Ditunda! Persiapkan Sekarang Sebelum Terlambat.</h2>
                <p>Waktu terus berjalan, dan banyak event yang membutuhkan perlengkapan serupa. Jangan sampai perlengkapan yang Anda butuhkan habis! Pastikan Anda mendapatkannya sebelum terlambat dan acara Anda berisiko. Hubungi kami sekarang dan pesan alat Anda!</p>
                <a href="<?= escapeUrl($buttonWhatsapp); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-pill py-3 wow fadeInUp mt-3" data-wow-delay="0.7s">Hubungi Kami Sekarang</a>
        </div>
    </div>

    <!-- Testimonial Start -->
    <div class="container-fluid testimonial py-md-4 py-2">
            <img src="assets/Tentang Kreasipro.jpg" class="ourclient img-fluid d-block mx-auto rounded shadow-sm" alt="Klien Kreasi Pro" loading="lazy">
    </div>
    <!-- Testimonial End -->

    <!-- Footer Start -->
    <div class="container-fluid footer py-md-4 py-2 wow fadeIn" data-wow-delay="0.2s" id="contact">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-6 mt-4">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="text-dark mb-4">Maps</h4>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d991.4007364593717!2d106.71247276962974!3d-6.3157737666392135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e500264411f5%3A0xbb06d8c0db44edca!2sgudang%20kerasipro!5e0!3m2!1sid!2sid!4v1770106032952!5m2!1sid!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Kreasi Pro"></iframe>
                    </div>
                </div>
                <div class="col-md-5 col-lg-5 col-xl-2 mt-4">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-dark">Quick Links</h4>
                        <a href="#home"> Home</a>
                        <a href="#about"> Tentang Kami</a>
                        <a href="#portfolio"> Portfolio</a>
                        <a href="#service"> Produk</a>
                        <a href="#contact"> Kontak</a>
                    </div>
                </div>
                <div class="col-md-7 col-lg-7 col-xl-4 mt-4">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-dark">Contact Info</h4>
                        <a href="javascript:void(0);"><i class="fa fa-map-marker-alt me-2"></i> <?= escapeHtml($alamat) ?></a>
                        <a href="<?= escapeUrl($emailLink); ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-envelope me-2"></i> <?= escapeHtml($email) ?></a>
                        <a href="<?= escapeUrl($buttonWhatsapp); ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-phone me-2"></i> <?= escapeHtml($whatsappNo) ?></a>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-share fa-2x text-secondary me-2"></i>
                            <a class="btn-square btn btn-primary rounded-circle mx-1" href="<?= escapeUrl($socialMedia['instagram']); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram Kreasipro"><i class="fab fa-instagram"></i></a>
                            <a class="btn-square btn btn-primary rounded-circle mx-1" href="<?= escapeUrl($socialMedia['youtube']); ?>" target="_blank" rel="noopener noreferrer" aria-label="Youtube Kreasipro"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Copyright Start -->
    <div class="container-fluid copyright py-md-4 py-2">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6 text-center text-md-start mb-md-0">
                    <span class="text-white"><a href="index.php"><i class="fas fa-copyright text-light me-2"></i>Kreasi Pro</a>, All right reserved | <?php echo date('Y'); ?></span>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <div class="wh-api">
        <div class="wh-fixed whatsapp-pulse">
            <a href="<?= escapeUrl($buttonWhatsapp); ?>" target="_blank" rel="noopener noreferrer">
                <button class="wh-ap-btn" aria-label="Hubungi kami melalui WhatsApp"></button>
            </a>
        </div>
    </div>
    <a href="#" class="btn btn-primary btn-lg-square back-to-top" aria-label="Kembali ke atas"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script>
        $('.portfolio-menu ul li').click(function() {
            $('.portfolio-menu ul li').removeClass('active');
            $(this).addClass('active');

            var selector = $(this).attr('data-filter');
            $('.portfolio-item').isotope({
                filter: selector
            });
            return false;
        });
        
        $(document).ready(function() {
            var popup_btn = $('.popup-btn');
            popup_btn.magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });

            // Hero carousel & portfolio click-to-enlarge
            $('.carousel').each(function() {
                $(this).find('.popup-image').magnificPopup({
                    type: 'image',
                    gallery: { enabled: true },
                    image: { titleSrc: 'title' },
                    mainClass: 'mfp-with-zoom',
                    zoom: {
                        enabled: true,
                        duration: 300,
                        easing: 'ease-in-out'
                    }
                });
            });

            // Portfolio gallery popup
            $('.portfolio-category').each(function() {
                $(this).find('.popup-image').magnificPopup({
                    type: 'image',
                    gallery: { enabled: true },
                    image: { titleSrc: 'title' }
                });
            });
        });

        function activeService(element) {
            $('.dropdown-item').removeClass('active');
            $(element).addClass('active');
        }
    </script>

</body>

</html>