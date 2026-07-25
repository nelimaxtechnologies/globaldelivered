<!-- ============================================================
     TRACKING PAGE
     ============================================================ -->
<section class="page-hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">Track Your Shipment</h1>
                <p class="mb-0 opacity-75">Enter your tracking number to get real-time updates on your shipment.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end mb-0">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Tracking</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <!-- Tracking Form -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <div class="display-6 text-primary mb-3">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <h4 class="fw-bold">Enter Tracking Number</h4>
                            <p class="text-muted">Your tracking number can be found in your shipping confirmation email.</p>
                        </div>
                        
                        <form id="trackingForm" class="tracking-form">
                            <div class="input-group input-group-lg mb-3">
                                <span class="input-group-text border-end-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" id="trackingInput" class="form-control border-start-0 ps-0" 
                                       placeholder="e.g., GDL12345678"
                                       maxlength="20" autocomplete="off" 
                                       value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                                       required>
                                <button class="btn btn-primary px-4" type="submit">
                                    <i class="bi bi-arrow-right"></i> Track
                                </button>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i> 
                                    Your tracking information is encrypted and secure
                                </small>
                            </div>
                        </form>
                        
                        <div id="trackingLoading" class="text-center py-4" style="display:none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Looking up your shipment...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tracking Result -->
        <div id="trackingResult">
            <?php if (isset($unsubscribeMessage)): ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="alert alert-success text-center py-4">
                        <div class="display-6 text-success mb-3">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h5 class="fw-bold"><?= htmlspecialchars($unsubscribeMessage) ?></h5>
                        <p class="text-muted mb-3">You will no longer receive email notifications for this shipment.</p>
                        <a href="<?= BASE_URL ?>/tracking" class="btn btn-primary mt-2">
                            <i class="bi bi-arrow-left me-1"></i> Track Another Shipment
                        </a>
                    </div>
                </div>
            </div>
            <?php elseif (isset($shipment)): ?>
            <script>
                $(document).ready(function() {
                    $.ajax({
                        url: '<?= BASE_URL ?>/tracking/lookup',
                        method: 'POST',
                        data: {
                            tracking_number: '<?= htmlspecialchars($shipment->tracking_number) ?>',
                            _csrf_token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response._csrf_token) {
                                $('meta[name="csrf-token"]').attr('content', response._csrf_token);
                            }
                            if (response.success) {
                                renderTrackingResult(response.data);
                                startPolling('<?= htmlspecialchars($shipment->tracking_number) ?>');
                            }
                        }
                    });
                });
            </script>
            <?php elseif (isset($error)): ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Tips -->
        <div class="row justify-content-center mt-5">
            <div class="col-lg-10">
                <div class="card border">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="bi bi-question-circle text-primary fs-1 mb-2"></i>
                                    <h6 class="fw-bold">Where to Find Your Tracking Number</h6>
                                    <p class="small mb-0">Check your shipping confirmation email, order receipt, or SMS notification.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="bi bi-envelope text-primary fs-1 mb-2"></i>
                                    <h6 class="fw-bold">Get Email Updates</h6>
                                    <p class="small mb-0">Sign up for automatic email notifications at every shipment milestone.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="bi bi-headset text-primary fs-1 mb-2"></i>
                                    <h6 class="fw-bold">Need Help?</h6>
                                    <p class="small mb-0">Contact our 24/7 support team at +254729373801 or <a href="<?= BASE_URL ?>/contact">email us</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
