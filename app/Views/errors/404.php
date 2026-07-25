<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | GDL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; display: flex; align-items: center; min-height: 100vh; }
        .error-page { text-align: center; }
        .error-code { font-size: 12rem; font-weight: 900; background: linear-gradient(135deg, #1a237e, #3949ab); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; }
        .error-text { font-size: 1.5rem; color: #6c757d; margin-bottom: 30px; }
        .btn-home { padding: 14px 36px; border-radius: 50px; font-weight: 600; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="error-page">
        <div class="error-code">404</div>
        <h2 class="fw-bold mb-2">Page Not Found</h2>
        <p class="error-text">The page you're looking for doesn't exist or has been moved.</p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary btn-home">
            <i class="bi bi-house-door me-2"></i>Back to Home
        </a>
        <a href="<?= BASE_URL ?>/tracking" class="btn btn-outline-primary btn-home ms-2">
            <i class="bi bi-box-seam me-2"></i>Track Shipment
        </a>
    </div>
</body>
</html>
