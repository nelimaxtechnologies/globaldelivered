<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | GDL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; display: flex; align-items: center; min-height: 100vh; }
        .error-page { text-align: center; }
        .error-code { font-size: 12rem; font-weight: 900; color: #dc3545; line-height: 1; opacity: 0.8; }
        .error-text { font-size: 1.5rem; color: #6c757d; margin-bottom: 30px; }
        .btn-home { padding: 14px 36px; border-radius: 50px; font-weight: 600; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="error-page">
        <div class="error-code">500</div>
        <h2 class="fw-bold mb-2">Server Error</h2>
        <p class="error-text">Something went wrong. Our team has been notified.</p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary btn-home">
            <i class="bi bi-house-door me-2"></i>Back to Home
        </a>
        <a href="<?= BASE_URL ?>/contact" class="btn btn-outline-primary btn-home ms-2">
            <i class="bi bi-headset me-2"></i>Contact Support
        </a>
    </div>
</body>
</html>
