<section class="py-5" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <div class="display-6 text-primary mb-2">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <h4 class="fw-bold">Welcome Back</h4>
                            <p class="text-muted">Sign in to your GDL account</p>
                        </div>
                        
                        <form method="POST" action="<?= BASE_URL ?>/login">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control form-control-lg" 
                                           placeholder="you@example.com" required value="<?= htmlspecialchars(old('email')) ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control form-control-lg" 
                                           placeholder="Enter your password" required>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <a href="<?= BASE_URL ?>/forgot-password" class="small">Forgot password?</a>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </button>
                        </form>
                        
                        <hr class="my-4">
                        
                        <p class="text-center mb-0">
                            Don't have an account? 
                            <a href="<?= BASE_URL ?>/register" class="fw-bold">Create Account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
