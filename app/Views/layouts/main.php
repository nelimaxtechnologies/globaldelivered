<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $metaDescription ?? 'Global Delivered Logistics - Worldwide Shipping & Courier Services' ?>">
    <meta name="keywords" content="logistics, courier, shipping, freight, delivery, express, international shipping, cargo">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="base-url" content="<?= BASE_URL ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= $pageTitle ?? 'Global Delivered Logistics' ?>">
    <meta property="og:description" content="<?= $metaDescription ?? 'Premium worldwide shipping and logistics solutions' ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= BASE_URL ?>">
    <meta property="og:image" content="<?= asset('images/og-image.jpg') ?>">
    
    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $pageTitle ?? 'Global Delivered Logistics' ?>">
    <meta name="twitter:description" content="<?= $metaDescription ?? 'Premium worldwide shipping and logistics solutions' ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= canonical_url() ?>">
    
    <title><?= $pageTitle ?? 'Global Delivered Logistics' ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    
    <!-- Main Stylesheet -->
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/favicon.svg">
    <link rel="apple-touch-icon" href="<?= asset('images/apple-touch-icon.png') ?>">
    
    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Global Delivered Logistics",
        "url": "<?= BASE_URL ?>",
        "logo": "<?= asset('images/logo.png') ?>",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+254729373801",
            "contactType": "customer service",
            "availableLanguage": ["English"]
        },
        "sameAs": [
            "https://facebook.com/globaldelivered",
            "https://twitter.com/globaldelivered",
            "https://linkedin.com/company/globaldelivered"
        ]
    }
    </script>
</head>
<body>
    <!-- ============================================================ -->
    <!-- TOP BAR -->
    <!-- ============================================================ -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="top-bar-info">
                        <span><i class="bi bi-envelope-fill"></i> track@globaldelivered.biz</span>
                        <span><i class="bi bi-telephone-fill"></i> +254729373801</span>
                        <span><i class="bi bi-clock-fill"></i> 24/7 Support</span>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="top-bar-links">
                        <a href="<?= BASE_URL ?>/tracking"><i class="bi bi-box-seam"></i> Track</a>
                        <a href="<?= BASE_URL ?>/quote"><i class="bi bi-calculator"></i> Get Quote</a>
                        <a href="<?= BASE_URL ?>/admin"><i class="bi bi-shield-lock"></i> Admin</a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="<?= BASE_URL ?>/dashboard"><i class="bi bi-person"></i> Dashboard</a>
                            <a href="<?= BASE_URL ?>/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/login"><i class="bi bi-person"></i> Login</a>
                            <a href="<?= BASE_URL ?>/register" class="btn-register-top">Register</a>
                        <?php endif; ?>
                        <button id="themeToggle" class="btn btn-sm btn-outline-light ms-2" title="Toggle Theme">
                            <i class="bi bi-moon-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- NAVIGATION -->
    <!-- ============================================================ -->
    <nav class="navbar navbar-expand-lg navbar-dark main-navbar sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/">
                <svg class="brand-icon" width="42" height="42" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="gdlGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#FFD54F"/>
                            <stop offset="100%" style="stop-color:#FFA726"/>
                        </linearGradient>
                        <linearGradient id="gdlGradDark" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#1a237e"/>
                            <stop offset="100%" style="stop-color:#283593"/>
                        </linearGradient>
                    </defs>
                    <!-- Outer ring -->
                    <circle cx="60" cy="60" r="56" fill="none" stroke="url(#gdlGrad)" stroke-width="3" opacity="0.4"/>
                    <!-- Globe lines -->
                    <ellipse cx="60" cy="60" rx="36" ry="52" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="1.5"/>
                    <ellipse cx="60" cy="60" rx="52" ry="36" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="1.5"/>
                    <line x1="60" y1="8" x2="60" y2="112" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                    <line x1="8" y1="60" x2="112" y2="60" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                    <!-- Package box -->
                    <rect x="36" y="40" width="48" height="36" rx="4" fill="url(#gdlGrad)" opacity="0.95"/>
                    <line x1="36" y1="52" x2="84" y2="52" stroke="rgba(26,35,126,0.3)" stroke-width="2"/>
                    <line x1="60" y1="40" x2="60" y2="76" stroke="rgba(26,35,126,0.3)" stroke-width="2"/>
                    <!-- Box tape -->
                    <rect x="52" y="36" width="16" height="8" rx="2" fill="#fff" opacity="0.3"/>
                    <!-- Arrow / delivery swoosh -->
                    <path d="M78 32 L92 48 L82 48 L82 64 L74 64 L74 48 L64 48 Z" fill="#fff" opacity="0.9"/>
                    <!-- GDL text -->
                    <text x="60" y="100" text-anchor="middle" font-family="Inter, sans-serif" font-weight="800" font-size="16" fill="url(#gdlGrad)" letter-spacing="3">GDL</text>
                </svg>
                <span class="brand-text">Global <span class="text-warning">Delivered</span></span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link <?= currentPage('/') ? 'active' : '' ?>" href="<?= BASE_URL ?>/">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?= currentPage('/tracking') ? 'active' : '' ?>" href="<?= BASE_URL ?>/tracking">Tracking</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= currentPage('/services') ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                            Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/services/domestic">Domestic</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/services/international">International</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/services/express">Express</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/services/same_day">Same Day</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/services/freight">Freight</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/services/air_cargo">Air Cargo</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/services/sea_freight">Sea Freight</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/services/road_transport">Road Transport</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/services/warehousing">Warehousing</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/services/last_mile">Last Mile</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link <?= currentPage('/quote') ? 'active' : '' ?>" href="<?= BASE_URL ?>/quote">Get Quote</a></li>
                    <li class="nav-item"><a class="nav-link <?= currentPage('/about') ? 'active' : '' ?>" href="<?= BASE_URL ?>/about">About</a></li>
                    <li class="nav-item"><a class="nav-link <?= currentPage('/contact') ? 'active' : '' ?>" href="<?= BASE_URL ?>/contact">Contact</a></li>
                </ul>
                <div class="d-flex">
                    <a href="<?= BASE_URL ?>/quote" class="btn btn-warning nav-cta-btn">
                        <i class="bi bi-rocket-takeoff"></i> Ship Now
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================================================ -->
    <!-- MAIN CONTENT -->
    <!-- ============================================================ -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- ============================================================ -->
    <!-- FOOTER -->
    <!-- ============================================================ -->
    <footer class="main-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <svg width="42" height="42" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0">
                                <defs>
                                    <linearGradient id="gdlGradFoot" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#FFD54F"/>
                                        <stop offset="100%" style="stop-color:#FFA726"/>
                                    </linearGradient>
                                </defs>
                                <circle cx="60" cy="60" r="56" fill="none" stroke="url(#gdlGradFoot)" stroke-width="3" opacity="0.4"/>
                                <ellipse cx="60" cy="60" rx="36" ry="52" fill="none" stroke="rgba(255,255,255,0.12)" stroke-width="1.5"/>
                                <ellipse cx="60" cy="60" rx="52" ry="36" fill="none" stroke="rgba(255,255,255,0.12)" stroke-width="1.5"/>
                                <rect x="36" y="40" width="48" height="36" rx="4" fill="url(#gdlGradFoot)" opacity="0.95"/>
                                <line x1="36" y1="52" x2="84" y2="52" stroke="rgba(26,35,126,0.3)" stroke-width="2"/>
                                <line x1="60" y1="40" x2="60" y2="76" stroke="rgba(26,35,126,0.3)" stroke-width="2"/>
                                <rect x="52" y="36" width="16" height="8" rx="2" fill="#fff" opacity="0.3"/>
                                <path d="M78 32 L92 48 L82 48 L82 64 L74 64 L74 48 L64 48 Z" fill="#fff" opacity="0.9"/>
                                <text x="60" y="100" text-anchor="middle" font-family="Inter, sans-serif" font-weight="800" font-size="16" fill="url(#gdlGradFoot)" letter-spacing="3">GDL</text>
                            </svg>
                            <h4 class="mb-0">Global <span class="text-warning">Delivered</span></h4>
                        </div>
                        <p>Your trusted partner in global logistics. We deliver excellence across borders with speed, reliability, and cutting-edge technology.</p>
                    </div>
                    <div class="social-links mt-3">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/">Home</a></li>
                        <li><a href="<?= BASE_URL ?>/tracking">Track Shipment</a></li>
                        <li><a href="<?= BASE_URL ?>/quote">Get a Quote</a></li>
                        <li><a href="<?= BASE_URL ?>/services">Our Services</a></li>
                        <li><a href="<?= BASE_URL ?>/about">About Us</a></li>
                        <li><a href="<?= BASE_URL ?>/contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5>Services</h5>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/services/domestic">Domestic Shipping</a></li>
                        <li><a href="<?= BASE_URL ?>/services/international">International</a></li>
                        <li><a href="<?= BASE_URL ?>/services/express">Express Delivery</a></li>
                        <li><a href="<?= BASE_URL ?>/services/freight">Freight Services</a></li>
                        <li><a href="<?= BASE_URL ?>/services/air_cargo">Air Cargo</a></li>
                        <li><a href="<?= BASE_URL ?>/services/sea_freight">Sea Freight</a></li>
                        <li><a href="<?= BASE_URL ?>/services/warehousing">Warehousing</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5>Contact Info</h5>
                    <div class="footer-contact">
                        <p><i class="bi bi-geo-alt"></i> Westlands Business Park, Block C<br>Waiyaki Way, Nairobi, Kenya</p>
                        <p><i class="bi bi-telephone"></i> +254729373801</p>
                        <p><i class="bi bi-envelope"></i> track@globaldelivered.biz</p>
                        <p><i class="bi bi-clock"></i> 24/7 - 365 Days</p>
                    </div>
                </div>
            </div>
            
            <hr class="footer-divider">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?= date('Y') ?> Global Delivered Logistics. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="<?= BASE_URL ?>/privacy-policy" class="footer-bottom-link">Privacy Policy</a>
                    <a href="<?= BASE_URL ?>/terms-of-service" class="footer-bottom-link">Terms of Service</a>
                    <a href="<?= BASE_URL ?>/cookie-policy" class="footer-bottom-link">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button id="backToTop" class="btn btn-warning back-to-top" title="Back to Top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- ============================================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="<?= asset('js/main.js') ?>"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
        });

        // Toast notification system
        window.showToast = function(message, type = 'success') {
            const iconMap = { success: 'check-circle', error: 'x-circle', warning: 'exclamation-triangle', info: 'info-circle' };
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        };

        // Initialize any flash messages
        <?php if (has_flash('success')): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('<?= flash('success') ?>', 'success');
        });
        <?php endif; ?>
        <?php if (has_flash('error')): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('<?= flash('error') ?>', 'error');
        });
        <?php endif; ?>
    </script>
</body>
</html>
