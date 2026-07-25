<!-- ============================================================
     PRIVACY POLICY - PREMIUM MODERN DESIGN
     ============================================================ -->

<style>
    .policy-hero{background:linear-gradient(135deg,#0d1b3e 0%,#1a237e 50%,#3949ab 100%);padding:60px 0 50px;position:relative;overflow:hidden;}
    .policy-hero::before{content:'';position:absolute;top:-40%;right:-10%;width:500px;height:500px;background:radial-gradient(circle,rgba(255,255,255,0.06) 0%,transparent 70%);border-radius:50%;}
    .policy-hero::after{content:'';position:absolute;bottom:-30%;left:-5%;width:400px;height:400px;background:radial-gradient(circle,rgba(255,255,255,0.04) 0%,transparent 70%);border-radius:50%;}
    .policy-hero .container{position:relative;z-index:2;}
    .policy-icon{width:72px;height:72px;background:rgba(255,255,255,0.12);border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:#fff;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.15);}
    .toc-card{border-radius:14px;border:1px solid #e8eaf6;background:#fff;position:sticky;top:100px;}
    .toc-card .toc-title{font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#1a237e;padding:16px 20px 10px;border-bottom:1px solid #f1f3f5;}
    .toc-card .toc-link{display:block;padding:8px 20px;font-size:0.85rem;color:#555;text-decoration:none;border-left:3px solid transparent;transition:all 0.2s;}
    .toc-card .toc-link:hover{color:#1a237e;background:#f8f9fa;border-left-color:#1a237e;}
    .section-block{background:#fff;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.04);border:1px solid #f1f3f5;margin-bottom:20px;overflow:hidden;}
    .section-block .section-num{width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#1a237e,#3949ab);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0;}
    .section-block .section-body{padding:28px 32px;}
    .section-block h4{font-size:1.05rem;font-weight:700;color:#1a237e;margin:0 0 12px;}
    .section-block p,.section-block li{font-size:0.92rem;line-height:1.75;color:#555;}
    .section-block ul{padding-left:20px;margin:8px 0 0;}
    .section-block li{margin-bottom:6px;}
    .section-block strong{color:#333;}
    .contact-strip{background:linear-gradient(135deg,#f8f9fa,#fff);border:1px solid #e8eaf6;border-radius:16px;padding:28px 32px;display:flex;align-items:center;gap:20px;}
    .contact-strip .cs-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#1a237e,#3949ab);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
    @media(max-width:991px){.section-block .section-body{padding:20px;}.toc-card{position:static;margin-bottom:24px;}}
</style>

<section class="policy-hero text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="policy-icon"><i class="bi bi-shield-lock"></i></div>
                    <div>
                        <h1 class="fw-bold mb-0" style="font-size:2rem;">Privacy Policy</h1>
                        <p class="mb-0 mt-1 opacity-75" style="font-size:0.88rem;">Last updated: <?= date('F d, Y') ?></p>
                    </div>
                </div>
                <p class="opacity-80 mb-0" style="max-width:550px;font-size:0.95rem;">How Global Delivered Logistics collects, uses, and protects your personal information.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end mb-0" style="--bs-breadcrumb-divider-color:rgba(255,255,255,0.3);">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Privacy Policy</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section-padding" style="background:#f8f9fa;">
    <div class="container">
        <div class="row g-4">
            <!-- Table of Contents -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="toc-card">
                    <div class="toc-title"><i class="bi bi-list-ul me-1"></i> Contents</div>
                    <?php
                    $toc = [
                        'introduction' => 'Introduction',
                        'info-collect' => 'Information We Collect',
                        'info-use' => 'How We Use Your Information',
                        'info-share' => 'Information Sharing',
                        'data-security' => 'Data Security',
                        'data-retention' => 'Data Retention',
                        'rights' => 'Your Rights',
                        'cookies' => 'Cookies and Tracking',
                        'children' => "Children's Privacy",
                        'changes' => 'Changes to This Policy',
                        'contact' => 'Contact Us',
                    ];
                    foreach ($toc as $id => $label): ?>
                    <a href="#<?= $id ?>" class="toc-link"><?= $label ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-9">
                <!-- 1. Introduction -->
                <div class="section-block" id="introduction">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">1</div>
                        <div>
                            <h4>Introduction</h4>
                            <p>Global Delivered Logistics ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and use our logistics services.</p>
                            <p>By accessing or using our services, you agree to the collection and use of information in accordance with this policy. If you do not agree, please discontinue use of our services.</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Information We Collect -->
                <div class="section-block" id="info-collect">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">2</div>
                        <div>
                            <h4>Information We Collect</h4>
                            <p><strong>Personal Information:</strong> When you register an account, create a shipment, or contact us, we may collect:</p>
                            <ul>
                                <li>Full name, email address, phone number</li>
                                <li>Physical addresses (sender and recipient)</li>
                                <li>Payment and billing information</li>
                                <li>Company name and registration details (if applicable)</li>
                            </ul>
                            <p><strong>Shipment Information:</strong> Package details including weight, dimensions, contents description, and delivery instructions.</p>
                            <p><strong>Automatically Collected Information:</strong> IP address, browser type, operating system, device identifiers, pages visited, and referring URLs.</p>
                            <p><strong>Location Data:</strong> GPS coordinates and tracking data for shipment monitoring and delivery optimization.</p>
                        </div>
                    </div>
                </div>

                <!-- 3. How We Use Your Information -->
                <div class="section-block" id="info-use">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">3</div>
                        <div>
                            <h4>How We Use Your Information</h4>
                            <ul>
                                <li>Process and fulfill shipment orders</li>
                                <li>Provide real-time tracking and delivery updates</li>
                                <li>Communicate shipment status via email, SMS, or push notifications</li>
                                <li>Process payments and generate invoices</li>
                                <li>Improve our website, services, and customer experience</li>
                                <li>Comply with legal obligations and resolve disputes</li>
                                <li>Detect and prevent fraud or unauthorized access</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 4. Information Sharing -->
                <div class="section-block" id="info-share">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">4</div>
                        <div>
                            <h4>Information Sharing</h4>
                            <p>We may share your information with:</p>
                            <ul>
                                <li><strong>Delivery Partners:</strong> To facilitate shipment pickup and delivery</li>
                                <li><strong>Customs Authorities:</strong> For international shipments requiring customs clearance</li>
                                <li><strong>Payment Processors:</strong> To process transactions securely</li>
                                <li><strong>Service Providers:</strong> Third parties that assist in operations (IT support, analytics, marketing)</li>
                                <li><strong>Legal Authorities:</strong> When required by law or to protect our rights</li>
                            </ul>
                            <p>We do not sell your personal information to third parties.</p>
                        </div>
                    </div>
                </div>

                <!-- 5. Data Security -->
                <div class="section-block" id="data-security">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">5</div>
                        <div>
                            <h4>Data Security</h4>
                            <p>We implement industry-standard security measures including SSL encryption, secure servers, access controls, and regular security audits. However, no method of electronic transmission is 100% secure, and we cannot guarantee absolute security.</p>
                        </div>
                    </div>
                </div>

                <!-- 6. Data Retention -->
                <div class="section-block" id="data-retention">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">6</div>
                        <div>
                            <h4>Data Retention</h4>
                            <p>We retain your personal information for as long as your account is active or as needed to provide services. Shipment records are retained for a minimum of 5 years for regulatory compliance. You may request deletion of your account data subject to legal retention requirements.</p>
                        </div>
                    </div>
                </div>

                <!-- 7. Your Rights -->
                <div class="section-block" id="rights">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">7</div>
                        <div>
                            <h4>Your Rights</h4>
                            <p>You have the right to:</p>
                            <ul>
                                <li>Access and review your personal data</li>
                                <li>Correct inaccurate or incomplete data</li>
                                <li>Request deletion of your data</li>
                                <li>Opt out of marketing communications</li>
                                <li>Export your data in a portable format</li>
                                <li>Lodge a complaint with a supervisory authority</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 8. Cookies and Tracking -->
                <div class="section-block" id="cookies">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">8</div>
                        <div>
                            <h4>Cookies and Tracking</h4>
                            <p>We use cookies and similar technologies to enhance your browsing experience. See our <a href="<?= BASE_URL ?>/cookie-policy" class="fw-semibold" style="color:#1a237e;">Cookie Policy</a> for detailed information.</p>
                        </div>
                    </div>
                </div>

                <!-- 9. Children's Privacy -->
                <div class="section-block" id="children">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">9</div>
                        <div>
                            <h4>Children's Privacy</h4>
                            <p>Our services are not directed to individuals under 18 years of age. We do not knowingly collect personal information from children.</p>
                        </div>
                    </div>
                </div>

                <!-- 10. Changes to This Policy -->
                <div class="section-block" id="changes">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">10</div>
                        <div>
                            <h4>Changes to This Policy</h4>
                            <p>We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated revision date. Continued use of our services after changes constitutes acceptance of the updated policy.</p>
                        </div>
                    </div>
                </div>

                <!-- 11. Contact Us -->
                <div class="section-block" id="contact">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">11</div>
                        <div>
                            <h4>Contact Us</h4>
                            <p>If you have questions about this Privacy Policy, please contact us:</p>
                            <div class="contact-strip mt-3">
                                <div class="cs-icon"><i class="bi bi-envelope"></i></div>
                                <div>
                                    <strong>Global Delivered Logistics</strong><br>
                                    <span class="text-muted" style="font-size:0.88rem;">
                                        <a href="mailto:track@globaldelivered.biz" class="text-decoration-none" style="color:#1a237e;">track@globaldelivered.biz</a> ·
                                        <a href="tel:+254729373801" class="text-decoration-none" style="color:#1a237e;">+254 729 373 801</a>
                                    </span><br>
                                    <small class="text-muted">Westlands Business Park, Block C, Waiyaki Way, Nairobi, Kenya</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
