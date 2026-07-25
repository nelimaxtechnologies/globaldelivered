/**
 * Global Delivered Logistics - Main JavaScript
 * Enterprise-grade frontend with real-time tracking, animations, and interactive features
 */

(function($) {
    'use strict';

    // ============================================================
    // CONSTANTS
    // ============================================================
    const GDL = {
        BASE_URL: $('meta[name="base-url"]').attr('content') || '',
        POLLING_INTERVAL: 5000, // ms
        ANIMATION_DELAY: 100,
        CSRF_TOKEN: $('meta[name="csrf-token"]').attr('content') || '',
    };

    // ============================================================
    // DOM READY
    // ============================================================
    $(document).ready(function() {
        initNavbar();
        initBackToTop();
        initThemeToggle();
        initCounterAnimation();
        initTracking();
        initQuoteCalculator();
        initContactForm();
        initSearchFilters();
        initToastNotifications();
        initDashboardSidebar();
    });

    // ============================================================
    // NAVBAR
    // ============================================================
    function initNavbar() {
        const $nav = $('#mainNav');
        
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 100) {
                $nav.addClass('scrolled');
            } else {
                $nav.removeClass('scrolled');
            }
        });

        // Close mobile menu on click
        $('.nav-link').on('click', function() {
            const $collapse = $('#mainNavbar');
            if ($collapse.hasClass('show')) {
                $collapse.collapse('hide');
            }
        });
    }

    // ============================================================
    // BACK TO TOP
    // ============================================================
    function initBackToTop() {
        const $btn = $('#backToTop');
        
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 400) {
                $btn.addClass('show');
            } else {
                $btn.removeClass('show');
            }
        });
        
        $btn.on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 600);
        });
    }

    // ============================================================
    // THEME TOGGLE (Dark/Light)
    // ============================================================
    function initThemeToggle() {
        const $toggle = $('#themeToggle');
        const html = document.documentElement;
        
        // Load saved theme
        const savedTheme = localStorage.getItem('gdl_theme') || 'dark';
        html.setAttribute('data-bs-theme', savedTheme);
        updateThemeIcon(savedTheme);
        
        $toggle.on('click', function() {
            const current = html.getAttribute('data-bs-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('gdl_theme', next);
            updateThemeIcon(next);
        });
        
        function updateThemeIcon(theme) {
            const icon = theme === 'dark' ? 'bi-sun-fill' : 'bi-moon-fill';
            $toggle.find('i').attr('class', 'bi ' + icon);
        }
    }

    // ============================================================
    // COUNTER ANIMATIONS
    // ============================================================
    function initCounterAnimation() {
        $('.stat-number').each(function() {
            const $this = $(this);
            const target = parseInt($this.text().replace(/[^0-9]/g, ''));
            const suffix = $this.text().replace(/[0-9]/g, '');
            
            if (isNaN(target)) return;
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCount($this, 0, target, suffix, 2000);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe($this[0]);
        });
    }

    function animateCount($el, start, end, suffix, duration) {
        const startTime = performance.now();
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Ease out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(start + (end - start) * eased);
            
            $el.text(current.toLocaleString() + suffix);
            
            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        
        requestAnimationFrame(update);
    }

    // ============================================================
    // SHIPMENT TRACKING (AJAX with Polling)
    // ============================================================
    function initTracking() {
        const $form = $('#trackingForm');
        const $input = $('#trackingInput');
        const $result = $('#trackingResult');
        const $loading = $('#trackingLoading');
        let pollingInterval = null;
        
        if (!$form.length) return;

        // Handle browser back/forward
        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.tracking) {
                $input.val(e.state.tracking);
                lookupShipment(e.state.tracking);
            } else {
                $result.empty();
                $input.val('');
            }
        });

        // Auto-load from URL (e.g. /tracking/GDL45558223)
        const pathMatch = window.location.pathname.match(/\/tracking\/([A-Za-z0-9]+)/);
        if (pathMatch) {
            const num = pathMatch[1];
            $input.val(num);
            lookupShipment(num);
        }

        // Real-time input validation
        $input.on('input', function() {
            const val = $(this).val().trim().toUpperCase();
            $(this).val(val);
            
            if (val.length >= 8) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid is-invalid');
            }
        });

        // Form submit
        $form.on('submit', function(e) {
            e.preventDefault();
            const trackingNumber = $input.val().trim();
            
            if (trackingNumber.length < 8) {
                $input.addClass('is-invalid');
                return;
            }
            
            lookupShipment(trackingNumber);
        });

        // Auto-submit on Enter
        $input.on('keypress', function(e) {
            if (e.which === 13) {
                $form.submit();
            }
        });

        function getCsrfToken() {
            return $('meta[name="csrf-token"]').attr('content') || '';
        }

        function lookupShipment(trackingNumber) {
            $loading.show();
            $result.hide().empty();
            $input.prop('disabled', true);

            // Update URL to /tracking/NUMBER
            const newUrl = GDL.BASE_URL + '/tracking/' + encodeURIComponent(trackingNumber);
            window.history.pushState({ tracking: trackingNumber }, '', newUrl);
            
            // Clear existing polling
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }

            $.ajax({
                url: GDL.BASE_URL + '/tracking/lookup',
                method: 'POST',
                data: {
                    tracking_number: trackingNumber,
                    _csrf_token: GDL.CSRF_TOKEN
                },
                dataType: 'json',
                success: function(response) {
                    // Update CSRF token from response header if present
                    const newToken = response._csrf_token;
                    if (newToken) {
                        $('meta[name="csrf-token"]').attr('content', newToken);
                    }
                    
                    if (response.success) {
                        renderTrackingResult(response.data);
                        // Start polling for updates
                        startPolling(trackingNumber);
                    } else {
                        showTrackingError(response.message || 'Shipment not found.');
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                    showTrackingError(msg);
                },
                complete: function() {
                    $loading.hide();
                    $input.prop('disabled', false);
                }
            });
        }

        function renderTrackingResult(data) {
            const shipment = data.shipment;
            const history = data.history || [];
            const timeline = data.timeline || [];
            
            let html = '<div class="tracking-result animate__animated animate__fadeInUp">';
            
            // Header
            html += `
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <small class="text-muted">Tracking Number</small>
                                <h4 class="fw-bold mb-0">${shipment.tracking_number}</h4>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Status</small>
                                <h5 class="mb-0">
                                    <span class="badge" style="background: ${shipment.status_color}">
                                        ${shipment.status_name || 'Unknown'}
                                    </span>
                                </h5>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Estimated Delivery</small>
                                <h5 class="mb-0">${shipment.expected_delivery_date ? new Date(shipment.expected_delivery_date).toLocaleDateString() : 'Pending'}</h5>
                            </div>
                            <div class="col-md-2 text-end">
                                <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                                    <i class="bi bi-printer"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Progress Timeline
            html += '<div class="card mb-4"><div class="card-body"><h6 class="fw-bold mb-3">Shipment Progress</h6>';
            html += '<div class="tracking-progress">';
            
            const currentStatusId = shipment.current_status_id;
            
            const statusIcons = {
                'order_received': 'bi-bag-check',
                'picked_up': 'bi-box-seam',
                'at_warehouse': 'bi-building',
                'in_transit': 'bi-truck',
                'customs_clearance': 'bi-shield-check',
                'fees_payment_required': 'bi-credit-card',
                'awaiting_forwarding_to_final_destination': 'bi-arrow-left-right',
                'out_for_delivery': 'bi-pin-map',
                'delivered': 'bi-check-circle',
                'delayed': 'bi-exclamation-triangle',
                'returned': 'bi-arrow-return-left',
                'cancelled': 'bi-x-circle',
                'on_hold': 'bi-pause-circle',
            };
            
            // Build set of status IDs that actually have history entries
            const reachedStatusIds = new Set(history.map(h => h.status_id));
            // Always include current status
            reachedStatusIds.add(currentStatusId);

            timeline.forEach((status, index) => {
                // Skip statuses that don't apply to this shipment
                if (!reachedStatusIds.has(status.id)) return;
                
                const isCompleted = history.some(h => h.status_id == status.id);
                const isCurrent = status.id == currentStatusId;
                const stepClass = isCompleted ? 'completed' : (isCurrent ? 'current' : '');
                const slug = (status.slug || status.name || '').toLowerCase().replace(/\s+/g, '_');
                const icon = isCompleted ? 'bi-check-lg' : (statusIcons[slug] || status.icon || 'bi-circle');
                
                html += `
                    <div class="tracking-step ${stepClass}">
                        <div class="step-dot">
                            <i class="bi ${icon}"></i>
                        </div>
                        <div class="step-label">${status.name}</div>
                    </div>
                `;
            });
            
            html += '</div></div></div>';
            
            // Package Details
            html += `
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Package Details</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <small class="text-muted">Weight</small>
                                <p class="fw-bold mb-0">${shipment.weight} kg</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Dimensions</small>
                                <p class="fw-bold mb-0">${shipment.length || '-'} × ${shipment.width || '-'} × ${shipment.height || '-'} cm</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Service Type</small>
                                <p class="fw-bold mb-0 text-capitalize">${shipment.service_type.replace(/_/g, ' ')}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">From</small>
                                <p class="fw-bold mb-0">${shipment.sender_city}, ${shipment.sender_country}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">To</small>
                                <p class="fw-bold mb-0">${shipment.recipient_city}, ${shipment.recipient_country}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Reference Number</small>
                                <p class="fw-bold mb-0">${shipment.reference_number || '-'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Sender & Recipient
            html += '<div class="row g-4 mb-4">';
            html += `
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="bi bi-box-arrow-up-right text-primary"></i> Sender</h6>
                            <p class="mb-1 fw-bold">${shipment.sender_name}</p>
                            <p class="mb-1 text-muted">${shipment.sender_email}</p>
                            <p class="mb-1 text-muted">${shipment.sender_phone}</p>
                            <p class="mb-0 text-muted small">${shipment.sender_address}, ${shipment.sender_city}, ${shipment.sender_state}, ${shipment.sender_country}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="bi bi-box-arrow-in-down-right text-success"></i> Recipient</h6>
                            <p class="mb-1 fw-bold">${shipment.recipient_name}</p>
                            <p class="mb-1 text-muted">${shipment.recipient_email || '-'}</p>
                            <p class="mb-1 text-muted">${shipment.recipient_phone}</p>
                            <p class="mb-0 text-muted small">${shipment.recipient_address}, ${shipment.recipient_city}, ${shipment.recipient_state}, ${shipment.recipient_country}</p>
                        </div>
                    </div>
                </div>
            `;
            html += '</div>';
            
            // Tracking History Timeline
            if (history.length > 0) {
                html += '<div class="card"><div class="card-body"><h6 class="fw-bold mb-3">Tracking History</h6>';
                html += '<div class="timeline-list">';
                
                history.forEach(function(event) {
                    const time = new Date(event.created_at);
                    html += `
                        <div class="timeline-item">
                            <div class="timeline-time">${time.toLocaleString()}</div>
                            <div class="timeline-status" style="color: ${event.status_color}">
                                <i class="bi ${event.status_icon || 'bi-circle'}"></i>
                                ${event.status_name}
                            </div>
                            <div class="timeline-desc">${event.description || event.remarks || ''}</div>
                            ${event.location ? `<div class="timeline-location text-muted"><i class="bi bi-geo-alt"></i> ${event.location}</div>` : ''}
                        </div>
                    `;
                });
                
                html += '</div></div></div>';
            }
            
            // Notification Opt-In Card
            html += `
                <div class="card mb-4 border-primary" id="notifyCard">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                    <i class="bi bi-bell text-primary fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Get Notified of Updates</h6>
                                <p class="text-muted small mb-3 mb-lg-0">Receive an email whenever the status of this shipment changes.</p>
                            </div>
                        </div>
                        <div id="notifyFormWrap" class="mt-3">
                            <form id="notifyForm" class="row g-2 align-items-end">
                                <input type="hidden" name="tracking_number" value="${shipment.tracking_number}">
                                <div class="col-sm-5">
                                    <label class="form-label small">Your Name</label>
                                    <input type="text" name="name" class="form-control form-control-sm" placeholder="John Doe">
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label small">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-sm" placeholder="you@example.com" required>
                                </div>
                                <div class="col-sm-2 d-grid">
                                    <button type="submit" class="btn btn-primary btn-sm" id="notifyBtn">
                                        <i class="bi bi-bell me-1"></i> Subscribe
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="notifySuccess" class="d-none mt-3">
                            <div class="alert alert-success py-2 mb-0">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                <span id="notifyMsg">You will receive email notifications for future updates.</span>
                                <a href="#" id="notifyUnsubLink" class="ms-2 small text-decoration-underline" style="display:none;">Unsubscribe</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            html += '</div>';
            
            // Add Leaflet Live Map if coordinates are available
            if (shipment.current_latitude && shipment.current_longitude) {
                const lat = parseFloat(shipment.current_latitude);
                const lng = parseFloat(shipment.current_longitude);
                const mapId = 'trackingMap_' + shipment.tracking_number.replace(/[^a-zA-Z0-9]/g, '_');
                
                html += `
                    <div class="card mb-4" id="${mapId}-card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Current Location</h6>
                            <div id="${mapId}" style="height: 280px; border-radius: 8px;" 
                                 data-lat="${lat}" data-lng="${lng}"></div>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-clock"></i> Last updated: ${shipment.last_scan_at ? new Date(shipment.last_scan_at).toLocaleString() : 'N/A'}
                            </small>
                        </div>
                    </div>
                `;
                
                // Initialize map after DOM insertion
                setTimeout(() => initLiveTrackingMap(mapId, lat, lng, shipment.tracking_number), 100);
            }
            
            $result.html(html).fadeIn();
        }
        
        // Leaflet Live Tracking Map
        function initLiveTrackingMap(mapId, lat, lng, trackingNumber) {
            if (typeof L === 'undefined') {
                // Load Leaflet dynamically if not present
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(link);
                
                var script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                script.onload = function() { renderMap(mapId, lat, lng, trackingNumber); };
                document.head.appendChild(script);
            } else {
                renderMap(mapId, lat, lng, trackingNumber);
            }
        }
        
        function renderMap(mapId, lat, lng, trackingNumber) {
            const el = document.getElementById(mapId);
            if (!el) return;
            
            const map = L.map(mapId, {
                center: [lat, lng],
                zoom: 14,
                zoomControl: true,
            });
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            
            // Pulse animation marker
            const pulseIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div style="width:24px;height:24px;background:#1a237e;border:3px solid #fff;border-radius:50%;box-shadow:0 0 0 4px rgba(26,35,126,0.4),0 0 20px rgba(26,35,126,0.2);"></div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12],
            });
            
            const marker = L.marker([lat, lng], { icon: pulseIcon }).addTo(map);
            marker.bindPopup(`<b>Shipment: ${trackingNumber}</b><br>Current Location`);
            
            // Fix map rendering after card is shown
            setTimeout(() => map.invalidateSize(), 200);
        }
        
        function showTrackingError(message) {
            $result.html(`
                <div class="alert alert-danger animate__animated animate__shakeX">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    ${message}
                    <p class="mt-2 mb-0 small">Please check your tracking number and try again. Tracking numbers are typically 12 characters long (e.g., GDL-XXXXXXXXXX).</p>
                </div>
            `).fadeIn();
        }

        function startPolling(trackingNumber) {
            pollingInterval = setInterval(function() {
                $.ajax({
                    url: GDL.BASE_URL + '/tracking/lookup',
                    method: 'POST',
                    data: {
                        tracking_number: trackingNumber,
                        _csrf_token: getCsrfToken()
                    },
                    dataType: 'json',
                    success: function(response) {
                        const newToken = response._csrf_token;
                        if (newToken) {
                            $('meta[name="csrf-token"]').attr('content', newToken);
                        }
                        if (response.success) {
                            renderTrackingResult(response.data);
                        }
                    }
                });
            }, GDL.POLLING_INTERVAL);
        }

        // Expose to global scope for inline scripts (tracking page direct URL)
        window.renderTrackingResult = renderTrackingResult;
        window.startPolling = startPolling;
        window.showTrackingError = showTrackingError;

        // Notification subscription form
        $(document).on('submit', '#notifyForm', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $btn = $('#notifyBtn');
            const $wrap = $('#notifyFormWrap');
            const $success = $('#notifySuccess');
            const $msg = $('#notifyMsg');
            const $unsubLink = $('#notifyUnsubLink');
            const origHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: GDL.BASE_URL + '/tracking/subscribe',
                method: 'POST',
                data: $form.serialize() + '&_csrf_token=' + GDL.CSRF_TOKEN,
                dataType: 'json',
                success: function(response) {
                    if (response._csrf_token) {
                        GDL.CSRF_TOKEN = response._csrf_token;
                        $('meta[name="csrf-token"]').attr('content', response._csrf_token);
                    }
                    if (response.success) {
                        $wrap.addClass('d-none');
                        $msg.text(response.message || 'You will receive email notifications for future updates.');
                        $unsubLink.attr('href', GDL.BASE_URL + '/tracking/unsubscribe?tracking_number=' + encodeURIComponent($form.find('[name=tracking_number]').val()) + '&email=' + encodeURIComponent($form.find('[name=email]').val()));
                        $unsubLink.show();
                        $success.removeClass('d-none');
                    } else {
                        showToast(response.message || 'Subscription failed.', 'error');
                        $btn.prop('disabled', false).html(origHtml);
                    }
                },
                error: function() {
                    showToast('Network error. Please try again.', 'error');
                    $btn.prop('disabled', false).html(origHtml);
                }
            });
        });

        // Check existing subscription when tracking result loads
        $(document).on('click', '#notifyCard input[name=email]', function() {
            const email = $(this).val().trim();
            const trackingNumber = $('#notifyForm input[name=tracking_number]').val();
            if (email && trackingNumber && email.indexOf('@') > 0) {
                $.ajax({
                    url: GDL.BASE_URL + '/tracking/check-subscription',
                    method: 'POST',
                    data: { tracking_number: trackingNumber, email: email, _csrf_token: GDL.CSRF_TOKEN },
                    dataType: 'json',
                    success: function(response) {
                        if (response._csrf_token) {
                            GDL.CSRF_TOKEN = response._csrf_token;
                            $('meta[name="csrf-token"]').attr('content', response._csrf_token);
                        }
                        if (response.success && response.data.subscribed) {
                            const $wrap = $('#notifyFormWrap');
                            const $success = $('#notifySuccess');
                            const $msg = $('#notifyMsg');
                            const $unsubLink = $('#notifyUnsubLink');
                            $wrap.addClass('d-none');
                            $msg.text('You are already subscribed to notifications for this shipment.');
                            $unsubLink.attr('href', GDL.BASE_URL + '/tracking/unsubscribe?tracking_number=' + encodeURIComponent(trackingNumber) + '&email=' + encodeURIComponent(email));
                            $unsubLink.show();
                            $success.removeClass('d-none');
                        }
                    }
                });
            }
        });

        // Unsubscribe link inside success alert
        $(document).on('click', '#notifyUnsubLink', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const $wrap = $('#notifyFormWrap');
            const $success = $('#notifySuccess');

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $success.addClass('d-none');
                        $wrap.removeClass('d-none');
                        $('#notifyBtn').prop('disabled', false).html('<i class="bi bi-bell me-1"></i> Subscribe');
                        showToast(response.message || 'Unsubscribed successfully.', 'success');
                    }
                },
                error: function() {
                    window.location.href = url;
                }
            });
        });

        // Clean up polling on page unload
        $(window).on('beforeunload', function() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        });
    }

    // ============================================================
    // QUOTE CALCULATOR
    // ============================================================
    function initQuoteCalculator() {
        const $form = $('#quoteForm');
        const $result = $('#quoteResult');
        const $loading = $('#quoteLoading');
        
        if (!$form.length) return;
        
        // Auto-calculate dimensional weight
        $('#length, #width, #height').on('input', function() {
            const l = parseFloat($('#length').val()) || 0;
            const w = parseFloat($('#width').val()) || 0;
            const h = parseFloat($('#height').val()) || 0;
            const dimWeight = (l * w * h) / 5000;
            
            if (dimWeight > 0) {
                $('#dimWeight').val(dimWeight.toFixed(2) + ' kg');
                $('#dimWeightHelp').text(`Dimensional weight: ${dimWeight.toFixed(2)} kg`);
            } else {
                $('#dimWeight').val('');
                $('#dimWeightHelp').text('');
            }
        });

        // Service type selection
        $('[name="service_type"]').on('change', function() {
            $('.service-type-card').removeClass('selected');
            $(this).closest('.service-type-card').addClass('selected');
        });

        // Form submit
        $form.on('submit', function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            $loading.show();
            $result.hide();

            $.ajax({
                url: GDL.BASE_URL + '/quote/calculate',
                method: 'POST',
                data: formData + '&_csrf_token=' + ($('meta[name="csrf-token"]').attr('content') || ''),
                dataType: 'json',
                success: function(response) {
                    if (response._csrf_token) {
                        $('meta[name="csrf-token"]').attr('content', response._csrf_token);
                    }
                    if (response.success) {
                        renderQuoteResult(response.data);
                    } else {
                        showQuoteError(response.message || 'Unable to calculate quote.');
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Calculation error. Please try again.';
                    showQuoteError(msg);
                },
                complete: function() {
                    $loading.hide();
                }
            });
        });

        function renderQuoteResult(data) {
            const breakdown = data.breakdown;
            
            let html = `
                <div class="quote-result animate__animated animate__fadeInUp">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">Shipping Quote</h4>
                        <p class="text-muted">Estimated transit time: <strong>${data.transit_time}</strong></p>
                    </div>
                    
                    <div class="price-display text-center mb-4">
                        <h2 class="display-4 fw-bold text-primary">$${breakdown.total.toFixed(2)}</h2>
                        <p class="text-muted">Total estimated cost</p>
                    </div>
                    
                    <div class="price-breakdown">
                        <div class="d-flex justify-content-between py-2">
                            <span>Base Rate</span>
                            <span class="fw-bold">$${breakdown.base_rate.toFixed(2)}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span>Weight Charge (${breakdown.chargeable_weight} kg)</span>
                            <span class="fw-bold">$${breakdown.weight_charge.toFixed(2)}</span>
                        </div>
            `;
            
            if (breakdown.volume_surcharge) {
                html += `<div class="d-flex justify-content-between py-2 text-warning">
                    <span>Volume/Oversize Surcharge <i class="bi bi-info-circle" title="Dimensional weight exceeds actual weight"></i></span>
                    <span class="fw-bold">+10%</span>
                </div>`;
            }
            
            if (breakdown.priority_surcharge) {
                html += `<div class="d-flex justify-content-between py-2 text-warning">
                    <span>Priority Surcharge</span>
                    <span class="fw-bold">+25%</span>
                </div>`;
            }
            
            if (breakdown.insurance > 0) {
                html += `<div class="d-flex justify-content-between py-2">
                    <span>Insurance</span>
                    <span class="fw-bold">$${breakdown.insurance.toFixed(2)}</span>
                </div>`;
            }
            
            html += `
                        <hr>
                        <div class="d-flex justify-content-between py-2">
                            <span>Subtotal</span>
                            <span class="fw-bold">$${breakdown.subtotal.toFixed(2)}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span>Tax (${breakdown.tax_percentage}%)</span>
                            <span class="fw-bold">$${breakdown.tax_amount.toFixed(2)}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between py-2 h5 mb-0">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary">$${breakdown.total.toFixed(2)}</span>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="${GDL.BASE_URL}/contact" class="btn btn-primary">Book This Shipment</a>
                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="$('#quoteForm')[0].reset(); $('#quoteResult').hide();">Start Over</button>
                    </div>
                </div>
            `;
            
            $result.html(html).fadeIn();
            $result[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function showQuoteError(message) {
            $result.html(`
                <div class="alert alert-danger">${message}</div>
            `).fadeIn();
        }
    }

    // ============================================================
    // CONTACT FORM
    // ============================================================
    function initContactForm() {
        const $form = $('#contactForm');
        if (!$form.length) return;
        
        $form.on('submit', function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            const $btn = $(this).find('button[type="submit"]');
            const originalText = $btn.html();
            
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Sending...');
            
            $.ajax({
                url: GDL.BASE_URL + '/contact/send',
                method: 'POST',
                data: formData + '&_csrf_token=' + ($('meta[name="csrf-token"]').attr('content') || ''),
                dataType: 'json',
                success: function(response) {
                    if (response._csrf_token) {
                        $('meta[name="csrf-token"]').attr('content', response._csrf_token);
                    }
                    if (response.success) {
                        $form[0].reset();
                        showToast(response.message || 'Message sent successfully!', 'success');
                    } else {
                        showToast(response.message || 'Failed to send message.', 'error');
                    }
                },
                error: function() {
                    showToast('Network error. Please try again.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        });
    }

    // ============================================================
    // SEARCH FILTERS (for data tables)
    // ============================================================
    function initSearchFilters() {
        $('.search-filter').on('input', function() {
            const $table = $(this).closest('.table-responsive').find('table');
            const value = $(this).val().toLowerCase();
            
            $table.find('tbody tr').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(value) > -1);
            });
        });
    }

    // ============================================================
    // TOAST NOTIFICATIONS
    // ============================================================
    function initToastNotifications() {
        // Check for new notifications every 30 seconds
        if ($('#notificationBadge').length) {
            setInterval(function() {
                $.ajax({
                    url: GDL.BASE_URL + '/dashboard/notifications',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data?.unread > 0) {
                            $('#notificationBadge').text(response.data.unread).show();
                        }
                    }
                });
            }, 30000);
        }
    }

    // ============================================================
    // TOAST HELPER
    // ============================================================
    window.showToast = function(message, type = 'success') {
        if (typeof Swal !== 'undefined') {
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
        } else {
            alert(message);
        }
    };

    // ============================================================
    // DASHBOARD SIDEBAR TOGGLE
    // ============================================================
    function initDashboardSidebar() {
        const $toggle = $('[onclick="toggleDashboardSidebar()"]');
        if (!$toggle.length) return;

        $(document).on('click', function(e) {
            const sidebar = document.getElementById('dashboardSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (!sidebar || !overlay) return;

            if (sidebar.classList.contains('show') &&
                !sidebar.contains(e.target) &&
                !$toggle.is(e.target) && !$toggle.has(e.target).length) {
                sidebar.classList.remove('show');
                overlay.style.display = 'none';
            }
        });
    }

    // ============================================================
    // PRINT LABEL
    // ============================================================
    window.printLabel = function(shipmentId) {
        window.open('/dashboard/shipments/' + shipmentId + '/label', '_blank');
    };

    // ============================================================
    // DOWNLOAD INVOICE
    // ============================================================
    window.downloadInvoice = function(invoiceId) {
        window.open('/dashboard/invoices/' + invoiceId + '/download', '_blank');
    };

})(jQuery);
