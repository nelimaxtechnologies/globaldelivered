<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section class="hero-section">
    <div class="hero-bg-animation">
        <div class="orb"></div>
        <div class="orb"></div>
        <div class="orb"></div>
    </div>
    
    <!-- Delivery Person Image -->
    <div class="hero-delivery-image">
        <img src="<?= asset('images/delivery-person.png') ?>" alt="Delivery Person">
    </div>
    
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="bi bi-award-fill me-1"></i> Trusted by 10,000+ Businesses Worldwide
                    </div>
                    <h1>Global Logistics <br>Delivered With <span class="text-warning">Excellence</span></h1>
                    <p>Enterprise-grade courier and logistics solutions connecting businesses across 200+ countries. Real-time tracking, competitive rates, and 24/7 support.</p>
                    
                    <div class="hero-buttons">
                        <a href="<?= BASE_URL ?>/quote" class="btn btn-warning btn-lg">
                            <i class="bi bi-rocket-takeoff me-2"></i>Get Started
                        </a>
                        <a href="<?= BASE_URL ?>/services" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-grid-3x3-gap me-2"></i>Our Services
                        </a>
                    </div>
                    
                    <!-- Tracking Widget -->
                    <div class="tracking-widget mt-4">
                        <h4><i class="bi bi-box-seam me-2"></i>Track Your Shipment</h4>
                        <p class="text-white-50 small mb-3">Enter your tracking number to get real-time updates</p>
                        <form id="trackingForm" class="tracking-form">
                            <div class="input-group mb-2">
                                <input type="text" id="trackingInput" class="form-control" 
                                       placeholder="Enter tracking number (e.g., GDL73435656)"
                                       maxlength="20" autocomplete="off" required>
                                <button class="btn btn-warning" type="submit">
                                    <i class="bi bi-search"></i> Track
                                </button>
                            </div>
                            <small class="text-white-50">
                                <i class="bi bi-shield-check me-1"></i>Secure & Encrypted
                            </small>
                        </form>
                        <div id="trackingLoading" class="text-center py-3" style="display:none;">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-white-50 small mt-2">Looking up your shipment...</p>
                        </div>
                        <div id="trackingResult"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-5 d-none d-lg-block">
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
                        <h2 class="counter"><?= number_format($stats->shipments ?? 50000) ?>+</h2>
                        <span>Shipments Delivered</span>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-icon"><i class="bi bi-globe2"></i></div>
                        <h2 class="counter"><?= $stats->countries ?? 200 ?>+</h2>
                        <span>Countries Covered</span>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-icon"><i class="bi bi-people"></i></div>
                        <h2 class="counter"><?= number_format($stats->customers ?? 10000) ?>+</h2>
                        <span>Happy Customers</span>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-icon"><i class="bi bi-truck"></i></div>
                        <h2 class="counter"><?= number_format($stats->drivers ?? 500) ?>+</h2>
                        <span>Fleet Drivers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     WHY CHOOSE US SECTION
     ============================================================ -->
<section class="section-padding" id="why-us">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Why Choose Us</span>
            <h2>Built for <span class="text-primary">Enterprise-Grade</span> Logistics</h2>
            <p>We combine cutting-edge technology with global infrastructure to deliver exceptional logistics solutions.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="icon-box"><i class="bi bi-globe2"></i></div>
                    <h4>Global Coverage</h4>
                    <p>Operations across 200+ countries with strategic partnerships ensuring seamless worldwide delivery.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="icon-box"><i class="bi bi-clock"></i></div>
                    <h4>Real-Time Tracking</h4>
                    <p>GPS-enabled tracking with AJAX real-time updates. Know exactly where your shipment is at all times.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="icon-box"><i class="bi bi-shield-check"></i></div>
                    <h4>Secure & Insured</h4>
                    <p>Comprehensive insurance coverage and enterprise-grade security for all shipments worldwide.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="icon-box"><i class="bi bi-speedometer2"></i></div>
                    <h4>Express Delivery</h4>
                    <p>Time-critical delivery services with guaranteed windows. Same-day and next-day options available.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-card">
                    <div class="icon-box"><i class="bi bi-headset"></i></div>
                    <h4>24/7 Support</h4>
                    <p>Round-the-clock customer support team ready to assist you with any shipping needs or inquiries.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="feature-card">
                    <div class="icon-box"><i class="bi bi-robot"></i></div>
                    <h4>Smart Automation</h4>
                    <p>AI-powered route optimization, automated notifications, and intelligent shipment management.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     SERVICES SECTION
     ============================================================ -->
<section class="section-padding" style="background: var(--bg-card);" id="services">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Our Services</span>
            <h2>Comprehensive <span class="text-primary">Logistics Solutions</span></h2>
            <p>From documents to heavy freight, we handle it all with precision and care.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="service-card">
                    <div class="service-icon"><i class="bi bi-building"></i></div>
                    <h4>Domestic Shipping</h4>
                    <p>Fast and reliable domestic shipping with next-day delivery across all major cities.</p>
                    <ul>
                        <li>Next-day delivery</li>
                        <li>Real-time tracking</li>
                        <li>Insurance options</li>
                    </ul>
                    <a href="<?= BASE_URL ?>/services/domestic" class="btn btn-outline-primary btn-sm">Learn More →</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="service-card">
                    <div class="service-icon"><i class="bi bi-globe2"></i></div>
                    <h4>International Shipping</h4>
                    <p>Global shipping solutions to 200+ countries with comprehensive customs clearance.</p>
                    <ul>
                        <li>200+ countries</li>
                        <li>Customs clearance</li>
                        <li>Door-to-door</li>
                    </ul>
                    <a href="<?= BASE_URL ?>/services/international" class="btn btn-outline-primary btn-sm">Learn More →</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="service-card">
                    <div class="service-icon"><i class="bi bi-lightning"></i></div>
                    <h4>Express Delivery</h4>
                    <p>Time-critical express services with guaranteed delivery windows and priority handling.</p>
                    <ul>
                        <li>Guaranteed delivery</li>
                        <li>Priority handling</li>
                        <li>Dedicated support</li>
                    </ul>
                    <a href="<?= BASE_URL ?>/services/express" class="btn btn-outline-primary btn-sm">Learn More →</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="service-card">
                    <div class="service-icon"><i class="bi bi-truck"></i></div>
                    <h4>Freight Services</h4>
                    <p>Comprehensive LTL and FTL freight solutions for palletized and bulk cargo shipping.</p>
                    <ul>
                        <li>LTL & FTL options</li>
                        <li>Palletized shipping</li>
                        <li>Bulk cargo</li>
                    </ul>
                    <a href="<?= BASE_URL ?>/services/freight" class="btn btn-outline-primary btn-sm">Learn More →</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="service-card">
                    <div class="service-icon"><i class="bi bi-airplane"></i></div>
                    <h4>Air Cargo</h4>
                    <p>Premium air freight services with priority boarding and expedited customs processing.</p>
                    <ul>
                        <li>Priority boarding</li>
                        <li>Fast customs</li>
                        <li>Global network</li>
                    </ul>
                    <a href="<?= BASE_URL ?>/services/air_cargo" class="btn btn-outline-primary btn-sm">Learn More →</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="service-card">
                    <div class="service-icon"><i class="bi bi-boxes"></i></div>
                    <h4>Warehousing</h4>
                    <p>Strategic warehousing with inventory management, order fulfillment, and distribution.</p>
                    <ul>
                        <li>Secure storage</li>
                        <li>Inventory management</li>
                        <li>Order fulfillment</li>
                    </ul>
                    <a href="<?= BASE_URL ?>/services/warehousing" class="btn btn-outline-primary btn-sm">Learn More →</a>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?= BASE_URL ?>/services" class="btn btn-primary btn-lg">
                <i class="bi bi-grid-3x3-gap me-2"></i>View All Services
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     STATS SECTION
     ============================================================ -->
<section class="stats-section" id="stats">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-item">
                    <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
                    <div class="stat-number"><?= number_format(max($stats->shipments, 50000)) ?>+</div>
                    <div class="stat-label">Shipments Delivered</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-item">
                    <div class="stat-icon"><i class="bi bi-globe2"></i></div>
                    <div class="stat-number"><?= max($stats->countries, 200) ?>+</div>
                    <div class="stat-label">Countries Covered</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-item">
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                    <div class="stat-number"><?= number_format(max($stats->customers, 10000)) ?>+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-item">
                    <div class="stat-icon"><i class="bi bi-truck"></i></div>
                    <div class="stat-number"><?= number_format(max($stats->drivers, 500)) ?>+</div>
                    <div class="stat-label">Fleet Drivers</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     DELIVERY PROCESS
     ============================================================ -->
<section class="section-padding" id="process">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">How It Works</span>
            <h2>Simple <span class="text-primary">Delivery Process</span></h2>
            <p>Get your shipments moving in just a few simple steps.</p>
        </div>
        
        <div class="process-timeline">
            <div class="process-item" data-aos="fade-right">
                <div class="process-number">1</div>
                <div class="process-content">
                    <h4>Book a Shipment</h4>
                    <p>Fill out our simple booking form with pickup and delivery details, package dimensions, and preferred service type.</p>
                </div>
            </div>
            <div class="process-item" data-aos="fade-left">
                <div class="process-number">2</div>
                <div class="process-content">
                    <h4>We Pick It Up</h4>
                    <p>Our professional driver collects your package at the scheduled time from your preferred location.</p>
                </div>
            </div>
            <div class="process-item" data-aos="fade-right">
                <div class="process-number">3</div>
                <div class="process-content">
                    <h4>Real-Time Tracking</h4>
                    <p>Follow your shipment every step of the way with live GPS tracking and automatic status updates.</p>
                </div>
            </div>
            <div class="process-item" data-aos="fade-left">
                <div class="process-number">4</div>
                <div class="process-content">
                    <h4>Safe Delivery</h4>
                    <p>Your package arrives safely at its destination with proof of delivery and optional signature confirmation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     TESTIMONIALS
     ============================================================ -->
<section class="section-padding" style="background: var(--bg-card);" id="testimonials">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Testimonials</span>
            <h2>What Our <span class="text-primary">Clients Say</span></h2>
            <p>Trusted by thousands of businesses worldwide for our reliable logistics services.</p>
        </div>
    </div>
    
    <div class="testimonial-track-wrapper">
        <div class="testimonial-track" id="testimonialTrack">
            <?php foreach ($testimonials as $i => $t): ?>
            <div class="testimonial-card">
                <div class="stars">
                    <?php for ($s = 0; $s < 5; $s++): ?>
                        <i class="bi <?= $s < $t->rating ? 'bi-star-fill' : 'bi-star' ?>"></i>
                    <?php endfor; ?>
                </div>
                <p class="testimonial-text">"<?= htmlspecialchars($t->text) ?>"</p>
                <div class="testimonial-author">
                    <div class="author-avatar"><?= strtoupper(substr($t->name, 0, 1)) ?></div>
                    <div>
                        <h6 class="mb-0"><?= htmlspecialchars($t->name) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($t->company) ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <!-- Duplicates for seamless loop -->
            <?php foreach ($testimonials as $i => $t): ?>
            <div class="testimonial-card">
                <div class="stars">
                    <?php for ($s = 0; $s < 5; $s++): ?>
                        <i class="bi <?= $s < $t->rating ? 'bi-star-fill' : 'bi-star' ?>"></i>
                    <?php endfor; ?>
                </div>
                <p class="testimonial-text">"<?= htmlspecialchars($t->text) ?>"</p>
                <div class="testimonial-author">
                    <div class="author-avatar"><?= strtoupper(substr($t->name, 0, 1)) ?></div>
                    <div>
                        <h6 class="mb-0"><?= htmlspecialchars($t->name) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($t->company) ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.testimonial-track-wrapper {
    overflow: hidden;
    width: 100%;
    padding: 20px 0;
}
.testimonial-track {
    display: flex;
    gap: 24px;
    animation: scrollTestimonials 40s linear infinite;
    width: max-content;
}
.testimonial-track:hover {
    animation-play-state: paused;
}
@keyframes scrollTestimonials {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.testimonial-card {
    min-width: 380px;
    max-width: 380px;
    padding: 32px;
    border-radius: 16px;
    background: var(--bg-secondary, #1a1e23);
    border: 1px solid rgba(255, 255, 255, 0.06);
    transition: all 0.3s ease;
}
.testimonial-card:hover {
    border-color: rgba(74, 108, 247, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    transform: translateY(-4px);
}
.testimonial-card .stars {
    margin-bottom: 16px;
}
.testimonial-card .stars i {
    color: #f5a623;
    font-size: 1rem;
}
.testimonial-card .testimonial-text {
    font-size: 0.95rem;
    line-height: 1.7;
    color: #b0b8c4;
    font-style: italic;
    margin-bottom: 24px;
}
.testimonial-card .testimonial-author {
    display: flex;
    align-items: center;
    gap: 12px;
}
.testimonial-card .author-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f5a623, #f7c948);
    color: #1a1e23;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
}
.testimonial-card .testimonial-author h6 {
    font-weight: 600;
    color: #fff;
    font-size: 0.9rem;
}
.testimonial-card .testimonial-author small {
    font-size: 0.8rem;
}
</style>

<!-- ============================================================
     FAQ SECTION
     ============================================================ -->
<section class="section-padding faq-section" id="faq">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">FAQ</span>
            <h2>Frequently Asked <span class="text-primary">Questions</span></h2>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item" data-aos="fade-up">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How do I track my shipment?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Simply enter your unique tracking number in the tracking widget on our homepage or visit the <a href="<?= BASE_URL ?>/tracking">Tracking</a> page. You'll get real-time updates on your shipment's location, status, and estimated delivery time.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item" data-aos="fade-up" data-aos-delay="100">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                What is the estimated delivery time?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Delivery times vary from 1 to 45 days depending on the shipping method selected and destination. Use our <a href="<?= BASE_URL ?>/quote">Quote Calculator</a> for accurate estimates based on your specific shipment.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Do you offer insurance for shipments?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, we offer comprehensive shipping insurance for all packages. You can add insurance when booking your shipment. Our rates are competitive at just 1% of the declared value.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item" data-aos="fade-up" data-aos-delay="300">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                How do I get a shipping quote?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Visit our <a href="<?= BASE_URL ?>/quote">Quote Calculator</a> page, enter your shipment details including origin, destination, weight, and dimensions. You'll receive an instant quote with a full price breakdown.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item" data-aos="fade-up" data-aos-delay="400">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                What documents do I need for international shipping?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                For international shipments, you typically need a commercial invoice, packing list, and any relevant customs documentation. Our team handles the customs clearance process and will guide you through the required paperwork.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CTA SECTION
     ============================================================ -->
<section class="cta-section" data-aos="fade-up">
    <div class="container">
        <h2>Ready to Ship With Us?</h2>
        <p>Join thousands of satisfied customers who trust Global Delivered Logistics for their shipping needs. Get started today!</p>
        <a href="<?= BASE_URL ?>/quote" class="btn btn-warning btn-lg">
            <i class="bi bi-rocket-takeoff me-2"></i>Get Your Free Quote
        </a>
    </div>
</section>
