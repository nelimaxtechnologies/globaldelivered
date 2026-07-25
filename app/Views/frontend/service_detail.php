<section class="page-hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2"><i class="bi <?= htmlspecialchars($service['icon']) ?> me-2"></i><?= htmlspecialchars($service['title']) ?></h1>
                <p class="mb-0 opacity-75"><?= htmlspecialchars($service['description']) ?></p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= BASE_URL ?>/quote" class="btn btn-warning btn-lg">Get a Quote</a>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <h3 class="fw-bold mb-4">Why Choose Our <?= htmlspecialchars($service['title']) ?> Service?</h3>
                <p>Our <?= strtolower(htmlspecialchars($service['title'])) ?> service is designed to meet the highest standards of reliability and efficiency. Whether you're shipping documents or heavy cargo, we have the expertise and infrastructure to handle your logistics needs.</p>
                
                <div class="row g-3 mt-4">
                    <?php foreach ($service['features'] as $feature): ?>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-3 border rounded-3">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
                            <span class="fw-semibold"><?= htmlspecialchars($feature) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mt-5">
                    <h4 class="fw-bold mb-3">How It Works</h4>
                    <div class="d-flex gap-3 mb-3">
                        <div class="fw-bold text-primary fs-4">1</div>
                        <div><strong>Book your shipment</strong> — Fill in the details online or contact our team.</div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <div class="fw-bold text-primary fs-4">2</div>
                        <div><strong>We pick up</strong> — Our team collects your package at the scheduled time.</div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <div class="fw-bold text-primary fs-4">3</div>
                        <div><strong>Real-time tracking</strong> — Monitor your shipment every step of the way.</div>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="fw-bold text-primary fs-4">4</div>
                        <div><strong>Safe delivery</strong> — Your package arrives safely with proof of delivery.</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="card p-4 shadow-sm border-0 sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-3">Need a Quote?</h5>
                    <p class="text-muted small">Get an instant quote for this service.</p>
                    <div id="quickQuoteError" class="alert alert-danger d-none" role="alert"></div>
                    <div id="quickQuoteResult" class="d-none"></div>
                    <form id="quickQuoteForm">
                        <div class="mb-3">
                            <input type="text" name="origin" class="form-control" placeholder="Origin" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="destination" class="form-control" placeholder="Destination" required>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="weight" class="form-control" placeholder="Weight (kg)" step="0.1" min="0.1" required>
                        </div>
                        <input type="hidden" name="service_type" value="<?= htmlspecialchars($service['type'] ?? 'express') ?>">
                        <input type="hidden" name="package_type" value="parcel">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-calculator me-2"></i>Calculate Quote
                        </button>
                    </form>
                    <div id="quickQuoteLoading" class="text-center py-3 d-none">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <p class="text-muted small mb-0 mt-1">Calculating...</p>
                    </div>
                    <hr>
                    <div class="text-center">
                        <p class="mb-1"><i class="bi bi-telephone text-primary"></i> +254729373801</p>
                        <p class="mb-0"><i class="bi bi-envelope text-primary"></i> track@globaldelivered.biz</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#quickQuoteForm').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $loading = $('#quickQuoteLoading');
        var $result = $('#quickQuoteResult');
        var $error = $('#quickQuoteError');

        $loading.removeClass('d-none');
        $result.addClass('d-none').empty();
        $error.addClass('d-none');
        $form.find('button').prop('disabled', true);

        $.ajax({
            url: GDL.BASE_URL + '/quote/calculate',
            method: 'POST',
            data: $form.serialize() + '&_csrf_token=' + (GDL.CSRF_TOKEN || ''),
            dataType: 'json',
            success: function(response) {
                if (response._csrf_token) {
                    GDL.CSRF_TOKEN = response._csrf_token;
                }
                if (response.success) {
                    var d = response.data.breakdown;
                    $result.html(
                        '<div class="alert alert-success">' +
                        '<h5 class="alert-heading fw-bold">$' + d.total.toFixed(2) + '</h5>' +
                        '<p class="mb-1 small">Transit: <strong>' + response.data.transit_time + '</strong></p>' +
                        '<hr class="my-2">' +
                        '<p class="mb-1 small">Base: $' + d.base_rate.toFixed(2) + ' | Weight: $' + d.weight_charge.toFixed(2) + '</p>' +
                        '<p class="mb-1 small">Tax (' + d.tax_percentage + '%): $' + d.tax_amount.toFixed(2) + '</p>' +
                        (d.insurance > 0 ? '<p class="mb-0 small">Insurance: $' + d.insurance.toFixed(2) + '</p>' : '') +
                        '</div>'
                    ).removeClass('d-none');
                } else {
                    $error.text(response.message || 'Unable to calculate quote.').removeClass('d-none');
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON?.message || 'Error calculating quote.';
                $error.text(msg).removeClass('d-none');
            },
            complete: function() {
                $loading.addClass('d-none');
                $form.find('button').prop('disabled', false);
            }
        });
    });
});
</script>
