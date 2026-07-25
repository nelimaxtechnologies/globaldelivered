<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden | GDL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; display: flex; align-items: center; min-height: 100vh; }
        .error-page { text-align: center; }
        .error-code { font-size: 12rem; font-weight: 900; color: #e74c3c; line-height: 1; opacity: 0.8; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="error-page">
        <div class="error-code">403</div>
        <h2 class="fw-bold mb-2">Access Denied</h2>
        <p class="text-muted mb-4">You don't have permission to access this resource.</p>
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-primary btn-lg px-5 rounded-pill">Go to Dashboard</a>
    </div>
</body>
</html>
