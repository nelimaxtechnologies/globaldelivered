<style>
    .create-shipment-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .create-shipment-wrapper .cs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .create-shipment-wrapper .cs-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .create-shipment-wrapper .cs-header-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.4rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .create-shipment-wrapper .cs-header h1 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }

    .create-shipment-wrapper .cs-header p {
        font-size: 0.85rem;
        color: #7c7c8a;
        margin: 0.2rem 0 0 0;
    }

    .create-shipment-wrapper .cs-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.2rem;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #4a5568;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .create-shipment-wrapper .cs-back-btn:hover {
        border-color: #667eea;
        color: #667eea;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
    }

    /* Tracking Number Card */
    .create-shipment-wrapper .cs-tracking-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f0f0f5;
    }

    .create-shipment-wrapper .cs-tracking-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.25rem;
        align-items: end;
    }

    @media (max-width: 768px) {
        .create-shipment-wrapper .cs-tracking-row {
            grid-template-columns: 1fr;
        }
    }

    .create-shipment-wrapper .cs-tracking-number-wrap {
        position: relative;
    }

    .create-shipment-wrapper .cs-tracking-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.4rem;
        display: block;
    }

    .create-shipment-wrapper .cs-tracking-input-group {
        display: flex;
        align-items: stretch;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #e5e7eb;
        background: #f9fafb;
        transition: border-color 0.2s;
    }

    .create-shipment-wrapper .cs-tracking-input-group:focus-within {
        border-color: #667eea;
        background: #fff;
    }

    .create-shipment-wrapper .cs-tracking-input-group .cs-tracking-prefix {
        display: flex;
        align-items: center;
        padding: 0 0.8rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-weight: 700;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .create-shipment-wrapper .cs-tracking-input-group input {
        border: none;
        outline: none;
        background: transparent;
        padding: 0.7rem 1rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a2e;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        flex: 1;
        min-width: 0;
    }

    .create-shipment-wrapper .cs-tracking-input-group .cs-regen-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        background: transparent;
        border: none;
        border-left: 1px solid #e5e7eb;
        color: #667eea;
        cursor: pointer;
        font-size: 1.1rem;
        transition: all 0.2s;
    }

    .create-shipment-wrapper .cs-tracking-input-group .cs-regen-btn:hover {
        background: #f0f0ff;
        color: #764ba2;
    }

    .create-shipment-wrapper .cs-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.4rem;
        display: block;
    }

    .create-shipment-wrapper .cs-label .required {
        color: #ef4444;
        margin-left: 2px;
    }

    /* Section Card */
    .create-shipment-wrapper .cs-section {
        background: #fff;
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f0f0f5;
        transition: box-shadow 0.3s ease;
    }

    .create-shipment-wrapper .cs-section:hover {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06), 0 8px 30px rgba(0, 0, 0, 0.05);
    }

    .create-shipment-wrapper .cs-section-header {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.2rem;
        border-bottom: 1px solid #f0f0f5;
    }

    .create-shipment-wrapper .cs-section-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .create-shipment-wrapper .cs-section-icon.blue {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
    }

    .create-shipment-wrapper .cs-section-icon.green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.35);
    }

    .create-shipment-wrapper .cs-section-icon.amber {
        background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
        box-shadow: 0 4px 12px rgba(242, 153, 74, 0.35);
    }

    .create-shipment-wrapper .cs-section-icon.gray {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .create-shipment-wrapper .cs-section-icon.purple {
        background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);
        box-shadow: 0 4px 12px rgba(168, 85, 247, 0.35);
    }

    .create-shipment-wrapper .cs-section-title-group h6 {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }

    .create-shipment-wrapper .cs-section-title-group p {
        font-size: 0.78rem;
        color: #9ca3af;
        margin: 0.15rem 0 0 0;
    }

    /* Form Fields */
    .create-shipment-wrapper .cs-field-group {
        display: grid;
        gap: 1.15rem;
    }

    .create-shipment-wrapper .cs-row {
        display: grid;
        gap: 1.15rem;
    }

    .create-shipment-wrapper .cs-row.cols-2 { grid-template-columns: 1fr 1fr; }
    .create-shipment-wrapper .cs-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
    .create-shipment-wrapper .cs-row.cols-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
    .create-shipment-wrapper .cs-row.cols-1 { grid-template-columns: 1fr; }

    @media (max-width: 768px) {
        .create-shipment-wrapper .cs-row.cols-2,
        .create-shipment-wrapper .cs-row.cols-3,
        .create-shipment-wrapper .cs-row.cols-4 {
            grid-template-columns: 1fr;
        }
    }

    .create-shipment-wrapper .cs-input-icon-group {
        position: relative;
    }

    .create-shipment-wrapper .cs-input-icon-group .cs-input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 0.9rem;
        pointer-events: none;
        z-index: 1;
    }

    .create-shipment-wrapper .cs-input-icon-group input,
    .create-shipment-wrapper .cs-input-icon-group select {
        padding-left: 2.6rem;
    }

    .create-shipment-wrapper input[type="text"],
    .create-shipment-wrapper input[type="email"],
    .create-shipment-wrapper input[type="tel"],
    .create-shipment-wrapper input[type="number"],
    .create-shipment-wrapper input[type="date"],
    .create-shipment-wrapper select,
    .create-shipment-wrapper textarea {
        width: 100%;
        padding: 0.65rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.88rem;
        color: #1a1a2e;
        background: #fafbfc;
        transition: all 0.2s ease;
        outline: none;
        font-family: inherit;
    }

    .create-shipment-wrapper input:focus,
    .create-shipment-wrapper select:focus,
    .create-shipment-wrapper textarea:focus {
        border-color: #667eea;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
    }

    .create-shipment-wrapper select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 2.5rem;
    }

    .create-shipment-wrapper textarea {
        resize: vertical;
        min-height: 70px;
    }

    .create-shipment-wrapper .cs-placeholder {
        color: #b0b5c0;
    }

    /* Checkbox Cards */
    .create-shipment-wrapper .cs-checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .create-shipment-wrapper .cs-checkbox-card {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.75rem 1rem;
        border: 1.5px solid #f0f0f5;
        border-radius: 10px;
        background: #fafbfc;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .create-shipment-wrapper .cs-checkbox-card:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }

    .create-shipment-wrapper .cs-checkbox-card input[type="checkbox"] {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 2px solid #d1d5db;
        accent-color: #667eea;
        cursor: pointer;
        flex-shrink: 0;
    }

    .create-shipment-wrapper .cs-checkbox-card label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        cursor: pointer;
        margin: 0;
        user-select: none;
    }

    /* Conditional Fields */
    .create-shipment-wrapper .cs-conditional-field {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height 0.3s ease, opacity 0.3s ease, margin 0.3s ease;
        margin-top: 0;
    }

    .create-shipment-wrapper .cs-conditional-field.visible {
        max-height: 120px;
        opacity: 1;
        margin-top: 1.15rem;
    }

    /* Submit Area */
    .create-shipment-wrapper .cs-submit-area {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.75rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f0f0f5;
        margin-top: 0.5rem;
    }

    .create-shipment-wrapper .cs-btn-cancel {
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        background: #fff;
        color: #6b7280;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .create-shipment-wrapper .cs-btn-cancel:hover {
        border-color: #d1d5db;
        background: #f9fafb;
        color: #374151;
    }

    .create-shipment-wrapper .cs-btn-submit {
        padding: 0.65rem 2rem;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        letter-spacing: 0.3px;
    }

    .create-shipment-wrapper .cs-btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }

    .create-shipment-wrapper .cs-btn-submit:active {
        transform: translateY(0);
    }

    .create-shipment-wrapper .cs-btn-submit i {
        font-size: 1rem;
    }
</style>

<div class="create-shipment-wrapper">
    <form method="POST" action="<?= BASE_URL ?>/admin/shipments">
        <?= csrf_field() ?>

        <!-- Header -->
        <div class="cs-header">
            <div class="cs-header-left">
                <div class="cs-header-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <div>
                    <h1>Create New Shipment</h1>
                    <p>Fill in the details below to create a new shipment</p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/admin/shipments" class="cs-back-btn">
                <i class="bi bi-arrow-left"></i> Back to Shipments
            </a>
        </div>

        <!-- Tracking Number Card -->
        <div class="cs-tracking-card">
            <div class="cs-tracking-row">
                <div class="cs-tracking-number-wrap">
                    <span class="cs-tracking-label">Tracking Number</span>
                    <div class="cs-tracking-input-group">
                        <span class="cs-tracking-prefix">GDL</span>
                        <input type="text" name="tracking_number" id="trackingNumberInput" value="<?= htmlspecialchars(substr($trackingNumber, 3)) ?>" readonly>
                        <button type="button" class="cs-regen-btn" onclick="regenerateTracking()" title="Regenerate tracking number">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <span class="cs-tracking-label">Customer (Optional)</span>
                    <div class="cs-input-icon-group">
                        <i class="bi bi-person cs-input-icon"></i>
                        <select name="customer_id">
                            <option value="">Walk-in Customer</option>
                            <?php foreach ($customers as $c): ?>
                            <option value="<?= $c->id ?>"><?= htmlspecialchars($c->first_name . ' ' . $c->last_name) ?> (<?= htmlspecialchars($c->email) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <span class="cs-tracking-label">Reference Number</span>
                    <div class="cs-input-icon-group">
                        <i class="bi bi-hash cs-input-icon"></i>
                        <input type="text" name="reference_number" placeholder="Customer reference">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sender Information -->
        <div class="cs-section">
            <div class="cs-section-header">
                <div class="cs-section-icon blue">
                    <i class="bi bi-send-fill"></i>
                </div>
                <div class="cs-section-title-group">
                    <h6>Sender Information</h6>
                    <p>Details of the person or entity sending the package</p>
                </div>
            </div>
            <div class="cs-field-group">
                <div class="cs-row cols-3">
                    <div>
                        <label class="cs-label">Full Name <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-person cs-input-icon"></i>
                            <input type="text" name="sender_name" required placeholder="John Doe">
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Email <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-envelope cs-input-icon"></i>
                            <input type="email" name="sender_email" required placeholder="john@example.com">
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Phone <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-telephone cs-input-icon"></i>
                            <input type="tel" name="sender_phone" required placeholder="+254 7XX XXX XXX">
                        </div>
                    </div>
                </div>
                <div class="cs-row cols-1">
                    <div>
                        <label class="cs-label">Address <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-geo-alt cs-input-icon"></i>
                            <input type="text" name="sender_address" required placeholder="Street address">
                        </div>
                    </div>
                </div>
                <div class="cs-row cols-4">
                    <div>
                        <label class="cs-label">Country <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-globe cs-input-icon"></i>
                            <select name="sender_country" required class="sender-country-select" data-target="sender">
                                <option value="">Select Country</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">City <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-building cs-input-icon"></i>
                            <select name="sender_city" required class="sender-city-select" data-target="sender" disabled>
                                <option value="">Select country first</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">State</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-map cs-input-icon"></i>
                            <input type="text" name="sender_state" placeholder="State / Province">
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Postal Code</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-mailbox cs-input-icon"></i>
                            <input type="text" name="sender_postal_code" placeholder="00100" class="postal-code-input" data-target="sender">
                        </div>
                        <small class="text-muted" style="font-size:0.7rem;margin-top:2px;display:block;">Auto-detect address from zip</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recipient Information -->
        <div class="cs-section">
            <div class="cs-section-header">
                <div class="cs-section-icon green">
                    <i class="bi bi-box-arrow-in-down"></i>
                </div>
                <div class="cs-section-title-group">
                    <h6>Recipient Information</h6>
                    <p>Details of the person or entity receiving the package</p>
                </div>
            </div>
            <div class="cs-field-group">
                <div class="cs-row cols-3">
                    <div>
                        <label class="cs-label">Full Name <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-person cs-input-icon"></i>
                            <input type="text" name="recipient_name" required placeholder="Jane Doe">
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Email</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-envelope cs-input-icon"></i>
                            <input type="email" name="recipient_email" placeholder="jane@example.com">
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Phone <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-telephone cs-input-icon"></i>
                            <input type="tel" name="recipient_phone" required placeholder="+1 555 0124">
                        </div>
                    </div>
                </div>
                <div class="cs-row cols-1">
                    <div>
                        <label class="cs-label">Address <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-geo-alt cs-input-icon"></i>
                            <input type="text" name="recipient_address" required placeholder="Street address">
                        </div>
                    </div>
                </div>
                <div class="cs-row cols-4">
                    <div>
                        <label class="cs-label">Country <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-globe cs-input-icon"></i>
                            <select name="recipient_country" required class="recipient-country-select" data-target="recipient">
                                <option value="">Select Country</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">City <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-building cs-input-icon"></i>
                            <select name="recipient_city" required class="recipient-city-select" data-target="recipient" disabled>
                                <option value="">Select country first</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">State</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-map cs-input-icon"></i>
                            <input type="text" name="recipient_state" placeholder="State / Province">
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Postal Code</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-mailbox cs-input-icon"></i>
                            <input type="text" name="recipient_postal_code" placeholder="00100" class="postal-code-input" data-target="recipient">
                        </div>
                        <small class="text-muted" style="font-size:0.7rem;margin-top:2px;display:block;">Auto-detect address from zip</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Details -->
        <div class="cs-section">
            <div class="cs-section-header">
                <div class="cs-section-icon amber">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div class="cs-section-title-group">
                    <h6>Package Details</h6>
                    <p>Dimensions, weight, and service specifications</p>
                </div>
            </div>
            <div class="cs-field-group">
                <div class="cs-row cols-3">
                    <div>
                        <label class="cs-label">Service Type <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-truck cs-input-icon"></i>
                            <select name="service_type" required>
                                <option value="domestic">Domestic</option>
                                <option value="international">International</option>
                                <option value="express" selected>Express</option>
                                <option value="same_day">Same Day</option>
                                <option value="freight">Freight</option>
                                <option value="air_cargo">Air Cargo</option>
                                <option value="sea_freight">Sea Freight</option>
                                <option value="road_transport">Road Transport</option>
                                <option value="warehousing">Warehousing</option>
                                <option value="last_mile">Last Mile</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Package Type</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-archive cs-input-icon"></i>
                            <select name="package_type">
                                <option value="parcel">Parcel</option>
                                <option value="document">Document</option>
                                <option value="box">Box</option>
                                <option value="pallet">Pallet</option>
                                <option value="container">Container</option>
                                <option value="envelope">Envelope</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Currency</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-currency-dollar cs-input-icon"></i>
                            <select name="currency">
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="GBP">GBP (£)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="cs-row cols-4">
                    <div>
                        <label class="cs-label">Weight (kg) <span class="required">*</span></label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-speedometer cs-input-icon"></i>
                            <input type="number" name="weight" required step="0.1" min="0.1" placeholder="0.0">
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Length (cm)</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-arrows-expand cs-input-icon"></i>
                            <input type="number" name="length" step="0.1" min="0" placeholder="0.0">
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Width (cm)</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-arrows-angle-expand cs-input-icon"></i>
                            <input type="number" name="width" step="0.1" min="0" placeholder="0.0">
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Height (cm)</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-arrows-vertical cs-input-icon"></i>
                            <input type="number" name="height" step="0.1" min="0" placeholder="0.0">
                        </div>
                    </div>
                </div>
                <div class="cs-row cols-1">
                    <div>
                        <label class="cs-label">Description</label>
                        <textarea name="description" rows="2" placeholder="Describe the package contents..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Options & Schedule -->
        <div class="cs-section">
            <div class="cs-section-header">
                <div class="cs-section-icon gray">
                    <i class="bi bi-gear-fill"></i>
                </div>
                <div class="cs-section-title-group">
                    <h6>Options & Schedule</h6>
                    <p>Special handling, delivery preferences, and scheduling</p>
                </div>
            </div>
            <div class="cs-field-group">
                <div class="cs-checkbox-grid">
                    <div class="cs-checkbox-card">
                        <input type="checkbox" name="is_fragile" id="isFragile">
                        <label for="isFragile"><i class="bi bi-exclamation-triangle me-1 text-warning"></i> Fragile</label>
                    </div>
                    <div class="cs-checkbox-card">
                        <input type="checkbox" name="is_insured" id="isInsured">
                        <label for="isInsured"><i class="bi bi-shield-check me-1 text-primary"></i> Insured</label>
                    </div>
                    <div class="cs-checkbox-card">
                        <input type="checkbox" name="signature_required" id="sigRequired">
                        <label for="sigRequired"><i class="bi bi-pen me-1 text-success"></i> Signature Required</label>
                    </div>
                    <div class="cs-checkbox-card">
                        <input type="checkbox" name="is_cod" id="isCod">
                        <label for="isCod"><i class="bi bi-cash-stack me-1 text-info"></i> Cash on Delivery</label>
                    </div>
                </div>

                <div class="cs-conditional-field" id="codAmountField">
                    <div class="cs-row cols-2">
                        <div>
                            <label class="cs-label">COD Amount</label>
                            <div class="cs-input-icon-group">
                                <i class="bi bi-currency-dollar cs-input-icon"></i>
                                <input type="number" name="cod_amount" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cs-conditional-field" id="declaredValueField">
                    <div class="cs-row cols-2">
                        <div>
                            <label class="cs-label">Declared Value ($)</label>
                            <div class="cs-input-icon-group">
                                <i class="bi bi-tag cs-input-icon"></i>
                                <input type="number" name="declared_value" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cs-row cols-3">
                    <div>
                        <label class="cs-label">Status</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-flag cs-input-icon"></i>
                            <select name="current_status_id">
                                <?php foreach ($statuses as $st): ?>
                                <option value="<?= $st->id ?>" <?= $st->sort_order == 1 ? 'selected' : '' ?>><?= htmlspecialchars($st->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Pickup Date</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-calendar-event cs-input-icon"></i>
                            <input type="date" name="pickup_date">
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Expected Delivery</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-calendar-check cs-input-icon"></i>
                            <input type="date" name="expected_delivery_date">
                        </div>
                    </div>
                </div>
                <div class="cs-row cols-1">
                    <div>
                        <label class="cs-label">Notes</label>
                        <textarea name="notes" rows="2" placeholder="Internal notes or special instructions..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment -->
        <div class="cs-section">
            <div class="cs-section-header">
                <div class="cs-section-icon purple">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div class="cs-section-title-group">
                    <h6>Assignment</h6>
                    <p>Assign branches, drivers, and vehicles for this shipment</p>
                </div>
            </div>
            <div class="cs-field-group">
                <div class="cs-row cols-2">
                    <div>
                        <label class="cs-label">Origin Branch</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-geo-alt-fill cs-input-icon"></i>
                            <select name="origin_branch_id">
                                <option value="">Select branch...</option>
                                <?php foreach ($branches as $b): ?>
                                <option value="<?= $b->id ?>"><?= htmlspecialchars($b->name) ?> - <?= htmlspecialchars($b->city) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Destination Branch</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-geo-alt cs-input-icon"></i>
                            <select name="destination_branch_id">
                                <option value="">Select branch...</option>
                                <?php foreach ($branches as $b): ?>
                                <option value="<?= $b->id ?>"><?= htmlspecialchars($b->name) ?> - <?= htmlspecialchars($b->city) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="cs-row cols-2">
                    <div>
                        <label class="cs-label">Assign Driver</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-person-circle cs-input-icon"></i>
                            <select name="assigned_driver_id">
                                <option value="">Select driver...</option>
                                <?php foreach ($drivers as $d): ?>
                                <option value="<?= $d->id ?>"><?= htmlspecialchars($d->first_name . ' ' . $d->last_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="cs-label">Assign Vehicle</label>
                        <div class="cs-input-icon-group">
                            <i class="bi bi-truck cs-input-icon"></i>
                            <select name="assigned_vehicle_id">
                                <option value="">Select vehicle...</option>
                                <?php foreach ($vehicles as $v): ?>
                                <option value="<?= $v->id ?>"><?= htmlspecialchars($v->name . ' (' . $v->registration_number . ')') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="cs-submit-area">
            <a href="<?= BASE_URL ?>/admin/shipments" class="cs-btn-cancel">
                <i class="bi bi-x-lg"></i> Cancel
            </a>
            <button type="submit" class="cs-btn-submit">
                <i class="bi bi-check-circle-fill"></i> Create Shipment
            </button>
        </div>
    </form>
</div>

<script>
function regenerateTracking() {
    var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    var suffix = '';
    for (var i = 0; i < 10; i++) {
        suffix += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('trackingNumberInput').value = suffix;
}

$(document).ready(function() {
    $('#isInsured').on('change', function() {
        if (this.checked) {
            $('#declaredValueField').addClass('visible');
        } else {
            $('#declaredValueField').removeClass('visible');
            $('#declaredValueField input').val('');
        }
    });

    $('#isCod').on('change', function() {
        if (this.checked) {
            $('#codAmountField').addClass('visible');
        } else {
            $('#codAmountField').removeClass('visible');
            $('#codAmountField input').val('');
        }
    });

    // --- Country/City Cascading Dropdowns ---
    const BASE = '<?= BASE_URL ?>';

    // Load countries into both dropdowns
    $.getJSON(BASE + '/admin/api/countries', function(resp) {
        if (resp.success && resp.data) {
            var opts = '<option value="">Select Country</option>';
            resp.data.forEach(function(c) {
                opts += '<option value="' + c.name + '">' + c.name + '</option>';
            });
            $('.sender-country-select, .recipient-country-select').html(opts).prop('disabled', false);
        }
    });

    // On country change → load cities
    $(document).on('change', '.sender-country-select, .recipient-country-select', function() {
        var target = $(this).data('target');
        var country = $(this).val();
        var citySelect = $('.' + target + '-city-select');

        if (!country) {
            citySelect.html('<option value="">Select country first</option>').prop('disabled', true);
            return;
        }

        citySelect.html('<option value="">Loading cities...</option>').prop('disabled', true);

        $.getJSON(BASE + '/admin/api/cities?country=' + encodeURIComponent(country), function(resp) {
            if (resp.success && resp.cities.length > 0) {
                var opts = '<option value="">Select City</option>';
                resp.cities.forEach(function(city) {
                    opts += '<option value="' + city + '">' + city + '</option>';
                });
                // Add "Other" option for cities not in the list
                opts += '<option value="__other__">Other (type manually)</option>';
                citySelect.html(opts).prop('disabled', false);
            } else {
                var opts = '<option value="__other__">Type city manually</option>';
                citySelect.html(opts).prop('disabled', false);
            }
        }).fail(function() {
            citySelect.html('<option value="__other__">Type city manually</option>').prop('disabled', false);
        });
    });

    // If "Other" selected → convert to text input
    $(document).on('change', '.sender-city-select, .recipient-city-select', function() {
        var target = $(this).data('target');
        if ($(this).val() === '__other__') {
            var name = $(this).attr('name');
            var input = $('<input type="text" class="form-control" name="' + name + '" required placeholder="Enter city name">');
            $(this).replaceWith(input);
        }
    });

    // --- Zip Code Auto-Detection ---
    var geocodeTimers = {};
    $(document).on('input', '.postal-code-input', function() {
        var $input = $(this);
        var target = $input.data('target');
        var postalCode = $input.val().trim();

        clearTimeout(geocodeTimers[target]);

        if (postalCode.length < 3) return;

        geocodeTimers[target] = setTimeout(function() {
            var country = $('.' + target + '-country-select').val();
            if (!country) return;

            // Show loading indicator
            var $hint = $input.closest('.cs-row').find('small.text-muted');
            var origText = $hint.text();
            $hint.text('Looking up address...').css('color', '#1a237e');

            $.getJSON(BASE + '/admin/api/geocode?postal_code=' + encodeURIComponent(postalCode) + '&country=' + encodeURIComponent(country), function(resp) {
                if (resp.success && resp.data) {
                    var d = resp.data;
                    // Auto-fill city
                    var citySelect = $('.' + target + '-city-select');
                    if (d.city) {
                        var found = false;
                        citySelect.find('option').each(function() {
                            if ($(this).val().toLowerCase() === d.city.toLowerCase()) {
                                $(this).prop('selected', true);
                                found = true;
                                return false;
                            }
                        });
                        if (!found && !citySelect.prop('disabled')) {
                            // City not in dropdown → set as text by selecting "Other" then replacing
                            citySelect.val('__other__').trigger('change');
                            var name = citySelect.attr('name');
                            if (!$('input[name="' + name + '"]').length) {
                                var input = $('<input type="text" class="form-control" name="' + name + '" required placeholder="Enter city name">');
                                citySelect.replaceWith(input);
                            }
                            $('input[name="' + name + '"]').val(d.city);
                        }
                    }
                    // Auto-fill state
                    if (d.state) {
                        $('input[name="' + target + '_state"]').val(d.state);
                    }
                    $hint.text('Address detected successfully').css('color', '#2e7d32');
                    setTimeout(function() { $hint.text(origText).css('color', ''); }, 3000);
                } else {
                    $hint.text('No address found for this zip code').css('color', '#d32f2f');
                    setTimeout(function() { $hint.text(origText).css('color', ''); }, 3000);
                }
            }).fail(function() {
                $hint.text(origText).css('color', '');
            });
        }, 800);
    });
});
</script>
