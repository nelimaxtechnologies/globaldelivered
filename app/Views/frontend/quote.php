<!-- ============================================================
     QUOTE CALCULATOR PAGE
     ============================================================ -->
<section class="page-hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">Get a Shipping Quote</h1>
                <p class="mb-0 opacity-75">Calculate shipping costs instantly. Transparent pricing with no hidden fees.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end mb-0">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Get Quote</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <!-- Quote Form -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="fw-bold mb-4">Shipment Details</h4>
                        
                        <form id="quoteForm">
                            <!-- Service Type -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Service Type</label>
                                <div class="row g-2">
                                    <?php 
                                    $serviceTypes = [
                                        'domestic' => ['Domestic', 'bi-building'],
                                        'international' => ['International', 'bi-globe2'],
                                        'express' => ['Express', 'bi-lightning'],
                                        'freight' => ['Freight', 'bi-truck'],
                                        'air_cargo' => ['Air Cargo', 'bi-airplane'],
                                        'sea_freight' => ['Sea Freight', 'bi-ship'],
                                    ];
                                    foreach ($serviceTypes as $val => $info): 
                                    ?>
                                    <div class="col-6 col-md-4">
                                        <div class="service-type-card rounded p-3 text-center <?= $val === 'express' ? 'selected' : '' ?>" 
                                             style="cursor:pointer;">
                                            <input type="radio" name="service_type" value="<?= $val ?>" 
                                                   <?= $val === 'express' ? 'checked' : '' ?> 
                                                   class="d-none">
                                            <i class="bi <?= $info[1] ?> fs-3 d-block mb-2 text-muted"></i>
                                            <small class="fw-semibold"><?= $info[0] ?></small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Origin & Destination -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Origin Country</label>
                                    <select name="origin" class="form-select" required>
                                        <option value="">Select origin...</option>
                                        <?php if (!empty($countries)): ?>
                                            <?php foreach ($countries as $c): ?>
                                                <option value="<?= htmlspecialchars($c->name) ?>"><?= htmlspecialchars($c->name) ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="United States">United States</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Germany">Germany</option>
                                            <option value="China">China</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Destination Country</label>
                                    <select name="destination" class="form-select" required>
                                        <option value="">Select destination...</option>
                                        <?php if (!empty($countries)): ?>
                                            <?php foreach ($countries as $c): ?>
                                                <option value="<?= htmlspecialchars($c->name) ?>"><?= htmlspecialchars($c->name) ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="United States">United States</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="South Africa">South Africa</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <!-- Package Dimensions -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Package Weight & Dimensions</label>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Weight</label>
                                    <div class="input-group">
                                        <input type="number" name="weight" id="weight" class="form-control" 
                                               placeholder="0.0" step="0.1" min="0.1" required>
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Length</label>
                                    <div class="input-group">
                                        <input type="number" name="length" id="length" class="form-control" 
                                               placeholder="0.0" step="0.1" min="0">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Width</label>
                                    <div class="input-group">
                                        <input type="number" name="width" id="width" class="form-control" 
                                               placeholder="0.0" step="0.1" min="0">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Height</label>
                                    <div class="input-group">
                                        <input type="number" name="height" id="height" class="form-control" 
                                               placeholder="0.0" step="0.1" min="0">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                
                                <!-- Dimensional Weight -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Dimensional Weight</label>
                                    <input type="text" id="dimWeight" class="form-control" 
                                           placeholder="Auto-calculated" readonly style="opacity: 0.85;">
                                    <small id="dimWeightHelp" style="opacity: 0.7;"></small>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Package Type</label>
                                    <select name="package_type" class="form-select" required>
                                        <option value="parcel">Parcel</option>
                                        <option value="document">Document</option>
                                        <option value="box">Box</option>
                                        <option value="pallet">Pallet</option>
                                        <option value="container">Container</option>
                                        <option value="envelope">Envelope</option>
                                    </select>
                                </div>
                                
                                <!-- Additional Options -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Additional Options</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="insurance" id="insurance" class="form-check-input" value="1">
                                        <label class="form-check-label" for="insurance">Add Insurance</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="priority" id="priority" class="form-check-input" value="1">
                                        <label class="form-check-label" for="priority">Priority Handling</label>
                                    </div>
                                </div>
                                
                                <!-- Declared Value -->
                                <div class="col-md-6" id="declaredValueField" style="display:none;">
                                    <label class="form-label">Declared Value ($)</label>
                                    <input type="number" name="declared_value" class="form-control" 
                                           placeholder="Enter package value" step="0.01" min="0">
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="bi bi-calculator me-2"></i>Calculate Quote
                                </button>
                            </div>
                        </form>
                        
                        <div id="quoteLoading" class="text-center py-4" style="display:none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Calculating...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Calculating your shipping quote...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quote Result -->
            <div class="col-lg-5">
                <div id="quoteResult">
                    <div class="card border">
                        <div class="card-body p-4 p-lg-5 text-center">
                            <div class="display-3 opacity-50 mb-3">
                                <i class="bi bi-calculator"></i>
                            </div>
                            <h5 class="fw-bold">Your Quote Will Appear Here</h5>
                            <p>Fill in the shipment details and click "Calculate Quote" to see your estimated shipping cost.</p>
                            <div class="text-start mt-4">
                                <h6 class="fw-semibold">Included in your quote:</h6>
                                <ul class="list-unstyled small">
                                    <li class="mb-1"><i class="bi bi-check-circle text-success me-2"></i>Base shipping rate</li>
                                    <li class="mb-1"><i class="bi bi-check-circle text-success me-2"></i>Weight & dimension charges</li>
                                    <li class="mb-1"><i class="bi bi-check-circle text-success me-2"></i>Taxes & fees</li>
                                    <li class="mb-1"><i class="bi bi-check-circle text-success me-2"></i>Insurance (if selected)</li>
                                    <li class="mb-1"><i class="bi bi-check-circle text-success me-2"></i>Estimated transit time</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Show declared value field when insurance is checked
    $('#insurance').on('change', function() {
        $('#declaredValueField').toggle(this.checked);
    });

    // Service type card selection
    $('.service-type-card').on('click', function() {
        $('.service-type-card').removeClass('selected');
        $(this).addClass('selected');
        $(this).find('input[type="radio"]').prop('checked', true);
    });
});
</script>

<style>
.service-type-card {
    border: 2px solid #3a3f47 !important;
    background: #1a1e23;
    transition: all 0.3s ease;
}
.service-type-card:hover {
    border-color: #4a6cf7 !important;
    background: rgba(74, 108, 247, 0.08);
}
.service-type-card:hover i {
    color: #4a6cf7 !important;
}
.service-type-card.selected {
    border-color: #4a6cf7 !important;
    background: rgba(74, 108, 247, 0.12);
    box-shadow: 0 0 12px rgba(74, 108, 247, 0.25);
}
.service-type-card.selected i {
    color: #4a6cf7 !important;
}
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
    appearance: textfield;
}
#dimWeight {
    background-color: var(--bs-body-bg) !important;
    color: var(--bs-body-color) !important;
    opacity: 1 !important;
}
</style>
