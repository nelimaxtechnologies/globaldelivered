<section class="py-5" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <div class="display-6 text-primary mb-2">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <h4 class="fw-bold">Create Your Account</h4>
                            <p class="text-muted">Join Global Delivered Logistics today</p>
                        </div>
                        
                        <form method="POST" action="<?= BASE_URL ?>/register">
                            <?= csrf_field() ?>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control form-control-lg" 
                                           placeholder="John" required value="<?= htmlspecialchars(old('first_name')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control form-control-lg" 
                                           placeholder="Doe" required value="<?= htmlspecialchars(old('last_name')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg" 
                                           placeholder="you@example.com" required value="<?= htmlspecialchars(old('email')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg" 
                                           placeholder="+254729373801" value="<?= htmlspecialchars(old('phone')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control form-control-lg" 
                                           placeholder="Min. 8 characters" required minlength="8">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirm" class="form-control form-control-lg" 
                                           placeholder="Repeat password" required>
                                </div>
                            </div>
                            
                            <div class="form-check mt-3">
                                <input type="checkbox" name="terms" id="terms" class="form-check-input" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="<?= BASE_URL ?>/terms-of-service" target="_blank">Terms of Service</a> and <a href="<?= BASE_URL ?>/privacy-policy" target="_blank">Privacy Policy</a>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">
                                <i class="bi bi-person-check me-2"></i>Create Account
                            </button>
                        </form>
                        
                        <hr class="my-4">
                        
                        <p class="text-center mb-0">
                            Already have an account? 
                            <a href="<?= BASE_URL ?>/login" class="fw-bold">Sign In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
