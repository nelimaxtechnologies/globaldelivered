<!-- ============================================================
     TERMS OF SERVICE - PREMIUM MODERN DESIGN
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
                    <div class="policy-icon"><i class="bi bi-file-earmark-text"></i></div>
                    <div>
                        <h1 class="fw-bold mb-0" style="font-size:2rem;">Terms of Service</h1>
                        <p class="mb-0 mt-1 opacity-75" style="font-size:0.88rem;">Last updated: <?= date('F d, Y') ?></p>
                    </div>
                </div>
                <p class="opacity-80 mb-0" style="max-width:550px;font-size:0.95rem;">Please read these terms carefully before using our logistics services.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end mb-0" style="--bs-breadcrumb-divider-color:rgba(255,255,255,0.3);">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Terms of Service</li>
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
                        'acceptance' => 'Acceptance of Terms',
                        'services' => 'Services',
                        'account' => 'Account Registration',
                        'shipments' => 'Shipments & Bookings',
                        'payment' => 'Payment & Fees',
                        'insurance' => 'Insurance & Liability',
                        'customs' => 'Customs & International',
                        'returns' => 'Returns & Refunds',
                        'prohibited' => 'Prohibited Items',
                        'ip' => 'Intellectual Property',
                        'liability' => 'Limitation of Liability',
                        'indemnification' => 'Indemnification',
                        'governing' => 'Governing Law',
                        'changes' => 'Changes to Terms',
                        'contact' => 'Contact Us',
                    ];
                    foreach ($toc as $id => $label): ?>
                    <a href="#<?= $id ?>" class="toc-link"><?= $label ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-9">
                <!-- 1. Acceptance of Terms -->
                <div class="section-block" id="acceptance">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">1</div>
                        <div>
                            <h4>Acceptance of Terms</h4>
                            <p>By accessing or using the Global Delivered Logistics ("GDL") website and services, you agree to be bound by these Terms of Service. If you do not agree, please do not use our services.</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Services -->
                <div class="section-block" id="services">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">2</div>
                        <div>
                            <h4>Services</h4>
                            <p>GDL provides logistics and shipping services including domestic shipping, international shipping, express delivery, freight services, air cargo, sea freight, warehousing, and related services. Service availability may vary by location.</p>
                        </div>
                    </div>
                </div>

                <!-- 3. Account Registration -->
                <div class="section-block" id="account">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">3</div>
                        <div>
                            <h4>Account Registration</h4>
                            <ul>
                                <li>You must provide accurate and complete information during registration</li>
                                <li>You are responsible for maintaining the confidentiality of your account credentials</li>
                                <li>You must notify us immediately of any unauthorized use of your account</li>
                                <li>You must be at least 18 years old to create an account</li>
                                <li>One person or entity may not maintain more than one account</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 4. Shipments and Bookings -->
                <div class="section-block" id="shipments">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">4</div>
                        <div>
                            <h4>Shipments and Bookings</h4>
                            <ul>
                                <li>You must provide accurate sender and recipient information</li>
                                <li>Prohibited items include hazardous materials, illegal goods, weapons, and counterfeit items</li>
                                <li>GDL reserves the right to inspect, refuse, or return shipments</li>
                                <li>Shipping rates are subject to change without prior notice</li>
                                <li>Estimated delivery times are not guaranteed unless a guaranteed service is purchased</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 5. Payment and Fees -->
                <div class="section-block" id="payment">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">5</div>
                        <div>
                            <h4>Payment and Fees</h4>
                            <ul>
                                <li>All fees must be paid in full before or at the time of shipment</li>
                                <li>Additional charges may apply for customs duties, taxes, remote area delivery, or special handling</li>
                                <li>Overdue payments will incur interest at 1.5% per month</li>
                                <li>GDL reserves the right to suspend services for unpaid invoices</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 6. Insurance and Liability -->
                <div class="section-block" id="insurance">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">6</div>
                        <div>
                            <h4>Insurance and Liability</h4>
                            <p>GDL offers optional shipment insurance. Without insurance, GDL's liability is limited to:</p>
                            <ul>
                                <li>Domestic shipments: $2.00 per kg or the actual value, whichever is lower</li>
                                <li>International shipments: $20.00 per kg or the actual value, whichever is lower</li>
                            </ul>
                            <p>GDL is not liable for delays caused by customs, natural disasters, strikes, or events beyond our control.</p>
                        </div>
                    </div>
                </div>

                <!-- 7. Customs and International Shipping -->
                <div class="section-block" id="customs">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">7</div>
                        <div>
                            <h4>Customs and International Shipping</h4>
                            <ul>
                                <li>You are the importer of record for all international shipments</li>
                                <li>You are responsible for customs duties, taxes, and compliance with local laws</li>
                                <li>GDL may act as a customs broker but is not liable for customs decisions</li>
                                <li>Incorrect customs documentation may result in delays or seizure of goods</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 8. Returns and Refunds -->
                <div class="section-block" id="returns">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">8</div>
                        <div>
                            <h4>Returns and Refunds</h4>
                            <ul>
                                <li>Refund requests must be made within 30 days of shipment delivery or attempted delivery</li>
                                <li>Refunds will be processed to the original payment method within 5-10 business days</li>
                                <li>Shipping fees are non-refundable once the shipment has been dispatched</li>
                                <li>Customs duties and taxes are non-refundable</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 9. Prohibited Items -->
                <div class="section-block" id="prohibited">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">9</div>
                        <div>
                            <h4>Prohibited Items</h4>
                            <p>You may not ship the following items through GDL:</p>
                            <ul>
                                <li>Hazardous or flammable materials</li>
                                <li>Illegal narcotics or controlled substances</li>
                                <li>Weapons, ammunition, or explosives</li>
                                <li>Counterfeit goods or stolen property</li>
                                <li>Live animals or perishable goods without prior arrangement</li>
                                <li>Items that violate export or import laws</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 10. Intellectual Property -->
                <div class="section-block" id="ip">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">10</div>
                        <div>
                            <h4>Intellectual Property</h4>
                            <p>All content on the GDL website, including logos, text, graphics, and software, is the property of Global Delivered Logistics and protected by intellectual property laws. You may not reproduce, distribute, or create derivative works without our written consent.</p>
                        </div>
                    </div>
                </div>

                <!-- 11. Limitation of Liability -->
                <div class="section-block" id="liability">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">11</div>
                        <div>
                            <h4>Limitation of Liability</h4>
                            <p>To the maximum extent permitted by law, GDL shall not be liable for indirect, incidental, special, or consequential damages, including loss of profits, data, or business opportunities, arising from the use of our services.</p>
                        </div>
                    </div>
                </div>

                <!-- 12. Indemnification -->
                <div class="section-block" id="indemnification">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">12</div>
                        <div>
                            <h4>Indemnification</h4>
                            <p>You agree to indemnify and hold GDL harmless from any claims, losses, or damages arising from your use of our services, violation of these terms, or infringement of any third-party rights.</p>
                        </div>
                    </div>
                </div>

                <!-- 13. Governing Law -->
                <div class="section-block" id="governing">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">13</div>
                        <div>
                            <h4>Governing Law</h4>
                            <p>These Terms are governed by the laws of Kenya. Any disputes shall be resolved in the courts of Nairobi, Kenya.</p>
                        </div>
                    </div>
                </div>

                <!-- 14. Changes to Terms -->
                <div class="section-block" id="changes">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">14</div>
                        <div>
                            <h4>Changes to Terms</h4>
                            <p>GDL reserves the right to modify these Terms at any time. Changes will be effective upon posting to the website. Your continued use of our services constitutes acceptance of the modified terms.</p>
                        </div>
                    </div>
                </div>

                <!-- 15. Contact Us -->
                <div class="section-block" id="contact">
                    <div class="section-body d-flex gap-3">
                        <div class="section-num">15</div>
                        <div>
                            <h4>Contact Us</h4>
                            <p>For questions regarding these Terms of Service:</p>
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
