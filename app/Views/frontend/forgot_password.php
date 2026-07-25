<section class="py-5" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <div class="display-6 text-primary mb-2"><i class="bi bi-key"></i></div>
                            <h4 class="fw-bold">Forgot Password</h4>
                            <p class="text-muted">Enter your email and we'll send you a reset link.</p>
                        </div>
                        
                        <form method="POST" action="<?= BASE_URL ?>/forgot-password">
                            <?= csrf_field() ?>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="you@example.com" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-send me-2"></i>Send Reset Link
                            </button>
                        </form>
                        
                        <hr class="my-4">
                        <p class="text-center mb-0">
                            <a href="<?= BASE_URL ?>/login" class="fw-bold">Back to Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
