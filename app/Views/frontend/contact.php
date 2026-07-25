<!-- ============================================================
     CONTACT PAGE
     ============================================================ -->
<section class="page-hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">Contact Us</h1>
                <p class="mb-0 opacity-75">We're here to help 24/7. Reach out to our team for any inquiries.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end mb-0">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Contact</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <!-- Contact Cards -->
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-card text-center">
                    <div class="contact-icon mx-auto"><i class="bi bi-telephone-fill"></i></div>
                    <h5 class="fw-bold">Phone</h5>
                    <p class="mb-1">+254 729 373 801</p>
                    <a href="tel:+254729373801" class="btn btn-sm btn-outline-primary">Call Now</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-card text-center">
                    <div class="contact-icon mx-auto"><i class="bi bi-envelope-fill"></i></div>
                    <h5 class="fw-bold">Email</h5>
                    <p class="mb-1">track@globaldelivered.biz</p>
                    <p class="small text-muted">We reply within 1 hour</p>
                    <a href="mailto:track@globaldelivered.biz" class="btn btn-sm btn-outline-primary">Send Email</a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="contact-card text-center">
                    <div class="contact-icon mx-auto"><i class="bi bi-geo-alt-fill"></i></div>
                    <h5 class="fw-bold">Head Office</h5>
                    <p class="mb-1">Westlands Business Park</p>
                    <p class="small text-muted">Nairobi, Kenya</p>
                    <a href="https://maps.google.com/?q=Westlands+Business+Park+Nairobi+Kenya" class="btn btn-sm btn-outline-primary" target="_blank">View Map</a>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-7" data-aos="fade-up">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <h4 class="fw-bold mb-1">Send Us a Message</h4>
                        <p class="text-muted mb-4">Fill out the form below and we'll get back to you shortly.</p>

                        <div id="contactSuccess" class="alert alert-success d-none" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <span id="contactSuccessMsg">Thank you! Your message has been sent.</span>
                        </div>
                        <div id="contactError" class="alert alert-danger d-none" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span id="contactErrorMsg"></span>
                        </div>

                        <form id="contactForm" onsubmit="return submitContactForm(event)">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-lg"
                                           placeholder="John Doe" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg"
                                           placeholder="john@example.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg"
                                           placeholder="+254 729 373 801">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                    <select name="subject" class="form-select form-select-lg" required>
                                        <option value="">Select a subject...</option>
                                        <option value="General Inquiry">General Inquiry</option>
                                        <option value="Shipping Quote">Shipping Quote</option>
                                        <option value="Shipment Support">Shipment Support</option>
                                        <option value="Complaint">Complaint</option>
                                        <option value="Partnership">Partnership</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control form-control-lg" rows="5"
                                              placeholder="Tell us how we can help you..." required></textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" id="contactSubmitBtn" class="btn btn-primary btn-lg px-5">
                                    <i class="bi bi-send-fill me-2"></i>Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Map & Branches -->
            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="200">
                <!-- Map -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-0">
                        <div style="height: 300px; border-radius: var(--radius-md, 8px); overflow: hidden;">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.26!2d36.8065!3d-1.2641!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f173c01d7d383%3A0xe4a1ea92dd46e84c!2sWestlands%2C+Nairobi!5e0!3m2!1sen!2ske!4v1"
                                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</section>

<script>
function submitContactForm(e) {
    e.preventDefault();
    var form = document.getElementById('contactForm');
    var btn = document.getElementById('contactSubmitBtn');
    var successEl = document.getElementById('contactSuccess');
    var errorEl = document.getElementById('contactError');

    successEl.classList.add('d-none');
    errorEl.classList.add('d-none');

    var formData = new FormData(form);
    formData.append('_csrf_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

    fetch('<?= BASE_URL ?>/contact/send', {
        method: 'POST',
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(response) {
        if (response._csrf_token) {
            document.querySelector('meta[name="csrf-token"]').setAttribute('content', response._csrf_token);
        }
        if (response.success) {
            successEl.classList.remove('d-none');
            document.getElementById('contactSuccessMsg').textContent = response.message || 'Thank you! Your message has been sent.';
            form.reset();
        } else {
            errorEl.classList.remove('d-none');
            var msg = response.message || 'Something went wrong.';
            if (response.data && typeof response.data === 'object') {
                var fieldErrors = [];
                Object.keys(response.data).forEach(function(k) {
                    if (Array.isArray(response.data[k])) {
                        fieldErrors = fieldErrors.concat(response.data[k]);
                    }
                });
                if (fieldErrors.length) msg = fieldErrors.join(' ');
            }
            document.getElementById('contactErrorMsg').textContent = msg;
        }
    })
    .catch(function() {
        errorEl.classList.remove('d-none');
        document.getElementById('contactErrorMsg').textContent = 'Network error. Please try again.';
    })
    .finally(function() {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send-fill me-2"></i>Send Message';
    });

    return false;
}
</script>
