<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Session Expired | GDL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; display: flex; align-items: center; min-height: 100vh; }
        .error-page { text-align: center; }
        .error-code { font-size: 10rem; font-weight: 900; color: #ff6f00; line-height: 1; opacity: 0.8; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="error-page">
        <div class="error-code">419</div>
        <h2 class="fw-bold mb-2">Session Expired</h2>
        <p class="text-muted mb-4">Your session has expired. Please refresh the page and try again.</p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary btn-lg px-5 rounded-pill">Go to Home</a>
    </div>
</body>
</html>
