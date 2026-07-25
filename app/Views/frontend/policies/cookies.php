<!-- ============================================================
     COOKIE POLICY - PREMIUM MODERN DESIGN
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
    .cookie-table{width:100%;border-collapse:separate;border-spacing:0;border:1px solid #e8eaf6;border-radius:12px;overflow:hidden;}
    .cookie-table th{background:linear-gradient(135deg,#1a237e,#283593);color:#fff;padding:12px 16px;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;border:none;}
    .cookie-table td{padding:12px 16px;border-bottom:1px solid #f1f3f5;font-size:0.88rem;vertical-align:top;}
    .cookie-table tbody tr:last-child td{border-bottom:none;}
    .cookie-table tbody tr:hover{background:rgba(26,35,126,0.02);}
    .contact-strip{background:linear-gradient(135deg,#f8f9fa,#fff);border:1px solid #e8eaf6;border-radius:16px;padding:28px 32px;display:flex;align-items:center;gap:20px;}
    .contact-strip .cs-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#1a237e,#3949ab);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
    @media(max-width:991px){.section-block .section-body{padding:20px;}.toc-card{position:static;margin-bottom:24px;}}
</style>

<section class="policy-hero text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="policy-icon"><i class="bi bi-cookie"></i></div>
                    <div>
                        <h1 class="fw-bold mb-0" style="font-size:2rem;">Cookie Policy</h1>
                        <p class="mb-0 mt-1 opacity-75" style="font-size:0.88rem;">Last updated: <?= date('F d, Y') ?></p>
                    </div>
                </div>
                <p class="opacity-80 mb-0" style="max-width:550px;font-size:0.95rem;">How we use cookies and similar technologies to enhance your browsing experience.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end mb-0" style="--bs-breadcrumb-divider-color:rgba(255,255,255,0.3);">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Cookie Policy</li>
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
                        'what-are' => 'What Are Cookies',
                        'how-we-use' => 'How We Use Cookies',
                        'specific' => 'Specific Cookies We Use',
                        'third-party' => 'Third-Party Cookies',
                        'managing' => 'Managing Cookies',
                        'dnt' => 'Do Not Track',
                        'consent' => 'Cookie Consent',
                        'updates' => 'Updates to This Policy',
                        'contact' => 'Contact Us',
                    ];
                    foreach ($toc as $id => $label): ?>
                    <a href="#<?= $id ?>" class="toc-link"><?= $label ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-9">
                <!-- 1. What Are Cookies -->
                <div class="section-block" id="what-are">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">1</div>
                        <div>
                            <h4>What Are Cookies</h4>
                            <p>Cookies are small text files that are stored on your device when you visit a website. They help websites remember your preferences, understand how you interact with the site, and improve your browsing experience.</p>
                        </div>
                    </div>
                </div>

                <!-- 2. How We Use Cookies -->
                <div class="section-block" id="how-we-use">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">2</div>
                        <div>
                            <h4>How We Use Cookies</h4>
                            <p>Global Delivered Logistics uses cookies for the following purposes:</p>
                            <ul>
                                <li><strong>Essential Cookies:</strong> Required for core website functionality such as user authentication, session management, and security features</li>
                                <li><strong>Functional Cookies:</strong> Remember your preferences and settings (language, theme, region) to provide a personalized experience</li>
                                <li><strong>Analytics Cookies:</strong> Help us understand how visitors interact with our website by collecting anonymized usage data</li>
                                <li><strong>Marketing Cookies:</strong> Used to deliver relevant advertisements and track the effectiveness of marketing campaigns</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 3. Specific Cookies We Use -->
                <div class="section-block" id="specific">
                    <div class="section-body">
                        <div class="d-flex gap-3">
                            <div class="section-num">3</div>
                            <div>
                                <h4>Specific Cookies We Use</h4>
                            </div>
                        </div>
                        <div class="mt-3" style="margin-left:56px;">
                            <table class="cookie-table">
                                <thead>
                                    <tr>
                                        <th>Cookie</th>
                                        <th>Purpose</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code style="background:#e8eaf6;padding:2px 8px;border-radius:4px;font-size:0.82rem;color:#1a237e;">session_id</code></td>
                                        <td>Maintains your authenticated session</td>
                                        <td><span class="badge" style="background:#e8eaf6;color:#1a237e;border-radius:20px;">Session</span></td>
                                    </tr>
                                    <tr>
                                        <td><code style="background:#e8eaf6;padding:2px 8px;border-radius:4px;font-size:0.82rem;color:#1a237e;">csrf_token</code></td>
                                        <td>Prevents cross-site request forgery attacks</td>
                                        <td><span class="badge" style="background:#e8eaf6;color:#1a237e;border-radius:20px;">Session</span></td>
                                    </tr>
                                    <tr>
                                        <td><code style="background:#e8eaf6;padding:2px 8px;border-radius:4px;font-size:0.82rem;color:#1a237e;">remember_token</code></td>
                                        <td>Keeps you logged in when "Remember Me" is selected</td>
                                        <td><span class="badge" style="background:#e8eaf6;color:#1a237e;border-radius:20px;">30 days</span></td>
                                    </tr>
                                    <tr>
                                        <td><code style="background:#e8eaf6;padding:2px 8px;border-radius:4px;font-size:0.82rem;color:#1a237e;">theme_preference</code></td>
                                        <td>Stores your dark/light mode preference</td>
                                        <td><span class="badge" style="background:#e8eaf6;color:#1a237e;border-radius:20px;">1 year</span></td>
                                    </tr>
                                    <tr>
                                        <td><code style="background:#e8eaf6;padding:2px 8px;border-radius:4px;font-size:0.82rem;color:#1a237e;">_ga</code></td>
                                        <td>Google Analytics — distinguishes unique visitors</td>
                                        <td><span class="badge" style="background:#e8eaf6;color:#1a237e;border-radius:20px;">2 years</span></td>
                                    </tr>
                                    <tr>
                                        <td><code style="background:#e8eaf6;padding:2px 8px;border-radius:4px;font-size:0.82rem;color:#1a237e;">_gid</code></td>
                                        <td>Google Analytics — tracks page views</td>
                                        <td><span class="badge" style="background:#e8eaf6;color:#1a237e;border-radius:20px;">24 hours</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 4. Third-Party Cookies -->
                <div class="section-block" id="third-party">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">4</div>
                        <div>
                            <h4>Third-Party Cookies</h4>
                            <p>We may allow third-party services to place cookies on your device:</p>
                            <ul>
                                <li><strong>Google Analytics:</strong> Tracks website traffic and user behavior to help us improve our services</li>
                                <li><strong>Bootstrap JS:</strong> Enables interactive UI components</li>
                                <li><strong>Leaflet Maps:</strong> Provides map functionality for shipment tracking</li>
                            </ul>
                            <p>These third-party services have their own privacy policies governing the use of cookies.</p>
                        </div>
                    </div>
                </div>

                <!-- 5. Managing Cookies -->
                <div class="section-block" id="managing">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">5</div>
                        <div>
                            <h4>Managing Cookies</h4>
                            <p>You can control and manage cookies through your browser settings:</p>
                            <ul>
                                <li><strong>Chrome:</strong> Settings &gt; Privacy and Security &gt; Cookies</li>
                                <li><strong>Firefox:</strong> Settings &gt; Privacy &amp; Security &gt; Cookies and Site Data</li>
                                <li><strong>Safari:</strong> Preferences &gt; Privacy &gt; Manage Website Data</li>
                                <li><strong>Edge:</strong> Settings &gt; Privacy, Search, and Services &gt; Cookies</li>
                            </ul>
                            <p><strong>Note:</strong> Disabling essential cookies may prevent core website functionality, including login and shipment tracking.</p>
                        </div>
                    </div>
                </div>

                <!-- 6. Do Not Track -->
                <div class="section-block" id="dnt">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">6</div>
                        <div>
                            <h4>Do Not Track</h4>
                            <p>Some browsers offer a "Do Not Track" (DNT) feature. Our website respects DNT signals and will limit tracking for users who have DNT enabled in their browser settings.</p>
                        </div>
                    </div>
                </div>

                <!-- 7. Cookie Consent -->
                <div class="section-block" id="consent">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">7</div>
                        <div>
                            <h4>Cookie Consent</h4>
                            <p>When you first visit our website, you will be presented with a cookie consent banner. You can choose to accept all cookies or customize your preferences. You can change your preferences at any time by clearing your browser cookies and revisiting the site.</p>
                        </div>
                    </div>
                </div>

                <!-- 8. Updates to This Policy -->
                <div class="section-block" id="updates">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">8</div>
                        <div>
                            <h4>Updates to This Policy</h4>
                            <p>We may update this Cookie Policy to reflect changes in technology or legislation. Any updates will be posted on this page with a revised date.</p>
                        </div>
                    </div>
                </div>

                <!-- 9. Contact Us -->
                <div class="section-block" id="contact">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">9</div>
                        <div>
                            <h4>Contact Us</h4>
                            <p>If you have questions about our use of cookies:</p>
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
