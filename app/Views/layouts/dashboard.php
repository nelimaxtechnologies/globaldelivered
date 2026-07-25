<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="base-url" content="<?= BASE_URL ?>">
    <title><?= $pageTitle ?? 'Dashboard' ?> | GDL</title>
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/favicon.svg">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Loading -->
    <div class="admin-loading" id="dashboardLoading" style="display:none; position:fixed; inset:0; background:rgba(255,255,255,0.8); z-index:9999;">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-2 text-muted">Loading...</p>
        </div>
    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="d-lg-none" id="sidebarOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999;" onclick="toggleDashboardSidebar()"></div>

    <div class="dashboard-layout">
        <!-- ============================================================ -->
        <!-- SIDEBAR -->
        <!-- ============================================================ -->
        <aside class="dashboard-sidebar" id="dashboardSidebar">
            <div class="sidebar-header d-flex align-items-center gap-2">
                <svg width="32" height="32" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="gdlGradPortal" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#FFD54F"/>
                            <stop offset="100%" style="stop-color:#FFA726"/>
                        </linearGradient>
                    </defs>
                    <circle cx="60" cy="60" r="56" fill="none" stroke="url(#gdlGradPortal)" stroke-width="3" opacity="0.4"/>
                    <rect x="36" y="40" width="48" height="36" rx="4" fill="url(#gdlGradPortal)" opacity="0.95"/>
                    <line x1="36" y1="52" x2="84" y2="52" stroke="rgba(0,0,0,0.2)" stroke-width="2"/>
                    <line x1="60" y1="40" x2="60" y2="76" stroke="rgba(0,0,0,0.2)" stroke-width="2"/>
                    <rect x="52" y="36" width="16" height="8" rx="2" fill="#fff" opacity="0.3"/>
                    <path d="M78 32 L92 48 L82 48 L82 64 L74 64 L74 48 L64 48 Z" fill="#fff" opacity="0.9"/>
                    <text x="60" y="100" text-anchor="middle" font-family="Inter, sans-serif" font-weight="800" font-size="16" fill="url(#gdlGradPortal)" letter-spacing="3">GDL</text>
                </svg>
                <div>
                    <h4 class="mb-0">GDL <span style="color: #ffc107;">Portal</span></h4>
                    <small>Customer Dashboard</small>
                </div>
            </div>
            
            <div class="sidebar-menu">
                <div class="menu-label">Main</div>
                
                <a href="<?= BASE_URL ?>/dashboard" class="nav-link <?= currentPage('/dashboard') || currentPage('/dashboard/') ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
                
                <a href="<?= BASE_URL ?>/dashboard/shipments" class="nav-link <?= currentPage('/dashboard/shipments') ? 'active' : '' ?>">
                    <i class="bi bi-box-seam"></i> My Shipments
                </a>
                
                <a href="<?= BASE_URL ?>/tracking" class="nav-link <?= currentPage('/tracking') ? 'active' : '' ?>">
                    <i class="bi bi-search"></i> Track Shipment
                </a>
                
                <div class="menu-label">Account</div>
                
                <a href="<?= BASE_URL ?>/dashboard/profile" class="nav-link <?= currentPage('/dashboard/profile') ? 'active' : '' ?>">
                    <i class="bi bi-person"></i> My Profile
                </a>
                
                <a href="<?= BASE_URL ?>/dashboard/addresses" class="nav-link <?= currentPage('/dashboard/addresses') ? 'active' : '' ?>">
                    <i class="bi bi-geo-alt"></i> Addresses
                </a>
                
                <a href="<?= BASE_URL ?>/dashboard/invoices" class="nav-link <?= currentPage('/dashboard/invoices') ? 'active' : '' ?>">
                    <i class="bi bi-receipt"></i> Invoices
                </a>
                
                <a href="<?= BASE_URL ?>/dashboard/notifications" class="nav-link <?= currentPage('/dashboard/notifications') ? 'active' : '' ?>">
                    <i class="bi bi-bell"></i> Notifications
                </a>
                
                <div class="menu-label">Quick Links</div>
                
                <a href="<?= BASE_URL ?>/quote" class="nav-link">
                    <i class="bi bi-calculator"></i> Get Quote
                </a>
                
                <a href="<?= BASE_URL ?>/services" class="nav-link">
                    <i class="bi bi-grid-3x3-gap"></i> Services
                </a>
                
                <hr style="border-color: rgba(255,255,255,0.1); margin: 10px 20px;">
                
                <a href="<?= BASE_URL ?>/" class="nav-link">
                    <i class="bi bi-globe"></i> View Website
                </a>
                
                <a href="<?= BASE_URL ?>/logout" class="nav-link">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </div>
        </aside>

        <!-- ============================================================ -->
        <!-- MAIN CONTENT -->
        <!-- ============================================================ -->
        <div class="dashboard-main">
            <!-- Top Bar -->
            <div class="dashboard-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm d-lg-none" onclick="toggleDashboardSidebar()">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <h5 class="fw-bold mb-0" style="font-size: 1.1rem;"><?= $pageTitle ?? 'Dashboard' ?></h5>
                </div>
                
                <div class="topbar-right">
                    <a href="<?= BASE_URL ?>/dashboard/notifications" class="btn-icon position-relative" title="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                              id="notifBadge" style="font-size: 0.55rem; display: none;">0</span>
                    </a>
                    
                    <div class="dropdown">
                        <button class="btn btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown" 
                                style="background: var(--bg-light); border-radius: 10px; padding: 5px 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); 
                                        display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 0.85rem;">
                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <span class="d-none d-md-inline fw-semibold small"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/dashboard/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/dashboard/addresses"><i class="bi bi-geo-alt me-2"></i>Addresses</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/dashboard/invoices"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Page Content -->
            <div class="dashboard-content">
                <!-- Flash Messages -->
                <?php if (has_flash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i><?= flash('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                <?php if (has_flash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= flash('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?= $content ?? '' ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Toggle Sidebar
        function toggleDashboardSidebar() {
            const sidebar = document.getElementById('dashboardSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
        }
        
        // Auto-dismiss alerts
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert-dismissible').alert('close');
            }, 5000);
            
            // Global AJAX setup with CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            // Load notification count
            $.ajax({
                url: '<?= BASE_URL ?>/dashboard/notifications',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data?.unread > 0) {
                        $('#notifBadge').text(response.data.unread).show();
                    }
                }
            });
        });
        
        // Toast helper
        window.showToast = function(message, type = 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        };
        
        // Confirm dialog helper
        window.confirmAction = function(title, text, callback) {
            Swal.fire({
                title: title || 'Are you sure?',
                text: text || 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        };
    </script>
</body>
</html>
