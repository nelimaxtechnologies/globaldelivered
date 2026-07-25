<!-- ============================================================
     SERVICES PAGE
     ============================================================ -->
<section class="page-hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">Our Services</h1>
                <p class="mb-0 opacity-75">Comprehensive logistics solutions tailored to your needs.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end mb-0">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Services</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <!-- Service Categories -->
        <div class="row g-4">
            <?php 
            $delays = [100, 200, 300, 100, 200, 300, 100, 200, 100, 200];
            $i = 0;
            foreach ($services as $type => $service): 
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $delays[$i] ?? 100 ?>">
                <div class="service-card h-100">
                    <div class="service-icon"><i class="bi <?= $service['icon'] ?>"></i></div>
                    <h4><?= htmlspecialchars($service['title']) ?></h4>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                    <ul>
                        <?php foreach ($service['features'] as $feature): ?>
                        <li><?= htmlspecialchars($feature) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?= BASE_URL ?>/services/<?= $type ?>" class="btn btn-outline-primary mt-auto">
                        Learn More <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <?php $i++; endforeach; ?>
        </div>
        
        <!-- CTA -->
        <div class="text-center mt-5" data-aos="fade-up">
            <div class="card bg-primary text-white p-5 border-0 rounded-4">
                <h3 class="fw-bold mb-3">Not Sure Which Service You Need?</h3>
                <p class="mb-4 opacity-75">Our logistics experts are here to help you choose the right solution.</p>
                <div>
                    <a href="<?= BASE_URL ?>/contact" class="btn btn-warning btn-lg me-3">
                        <i class="bi bi-headset me-2"></i>Contact Us
                    </a>
                    <a href="<?= BASE_URL ?>/quote" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-calculator me-2"></i>Get a Quote
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
