<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="base-url" content="<?= BASE_URL ?>">
    <title><?= $pageTitle ?? 'Admin Dashboard' ?> | GDL</title>
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/favicon.svg">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.css" rel="stylesheet">
    
    <!-- Admin Styles -->
    <style>
        :root {
            --sidebar-width: 260px;
            --admin-primary: #1a237e;
            --admin-sidebar-bg: #0d1452;
            --admin-header-height: 60px;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0d1452 0%, #1a237e 100%);
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }
        
        .admin-sidebar .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .admin-sidebar .sidebar-header h4 {
            font-weight: 800;
            font-size: 1.2rem;
        }
        
        .admin-sidebar .sidebar-header small {
            opacity: 0.7;
        }
        
        .sidebar-menu {
            padding: 15px 0;
        }
        
        .sidebar-menu .menu-label {
            padding: 10px 20px 5px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            opacity: 0.5;
        }
        
        .sidebar-menu .nav-item {
            position: relative;
        }
        
        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: rgba(255,255,255,0.7);
            transition: all 0.3s;
            gap: 12px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar-menu .nav-link.active {
            border-right: 3px solid #ffc107;
        }
        
        .sidebar-menu .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        
        .sidebar-menu .nav-link .badge {
            margin-left: auto;
        }
        
        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        /* Top Navbar */
        .admin-topbar {
            background: #fff;
            padding: 0 30px;
            height: var(--admin-header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .admin-topbar .page-title {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .admin-topbar .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-topbar .topbar-right .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: #f4f6f9;
            color: #555;
            transition: all 0.3s;
        }
        
        .admin-topbar .topbar-right .btn-icon:hover {
            background: #e4e6ef;
        }
        
        .admin-content {
            padding: 25px 30px;
        }
        
        /* Dashboard Cards */
        .admin-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s;
            height: 100%;
        }
        
        .admin-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        
        .admin-card .card-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
        }
        
        .admin-card .card-value {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.2;
        }
        
        .admin-card .card-label {
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        /* Tables */
        .table-admin {
            margin-bottom: 0;
        }
        
        .table-admin th {
            border-top: none;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
        }
        
        .table-admin td {
            vertical-align: middle;
            font-size: 0.9rem;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* Buttons */
        .admin-btn {
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s;
        }
        
        .admin-btn-sm {
            padding: 5px 12px;
            font-size: 0.78rem;
        }
        
        /* Scrollbar */
        .admin-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .admin-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
            .admin-content {
                padding: 20px 15px;
            }
        }
        
        /* Loading */
        .admin-loading {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        .admin-loading.show {
            display: flex;
        }

        /* Dashboard premium styles */
        .stat-card { border-left: 4px solid; transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .stat-card .stat-label { font-size: 0.78rem; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1.2; margin-bottom: 4px; }
        .stat-card .stat-change { font-size: 0.78rem; font-weight: 600; }
        .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #fff; }
        .mini-stat { transition: all 0.3s; }
        .mini-stat:hover { transform: translateY(-2px); }
        .mini-stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .mini-stat-value { font-size: 1.3rem; font-weight: 800; line-height: 1.2; }
        .mini-stat-label { font-size: 0.78rem; color: #6c757d; font-weight: 500; }
        .activity-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--admin-primary, #1a237e); margin-top: 7px; }
        .quick-action-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 16px 8px; border-radius: 10px; background: #f8f9fa; border: 1px solid #e9ecef; text-decoration: none; color: #333; transition: all 0.2s; }
        .quick-action-btn:hover { background: var(--admin-primary, #1a237e); color: #fff; border-color: var(--admin-primary, #1a237e); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(26,35,126,0.3); }
        .quick-action-btn i { font-size: 1.5rem; margin-bottom: 6px; }
        .quick-action-btn span { font-size: 0.78rem; font-weight: 600; }
        .fleet-stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .status-legend-item { display: flex; align-items: center; gap: 8px; padding: 5px 0; font-size: 0.82rem; }
        .status-legend-dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
        .table-admin thead th { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d; border-bottom: 2px solid #e9ecef; padding: 10px 12px; }
        .table-admin tbody td { padding: 10px 12px; border-bottom: 1px solid #f1f3f5; }
        .table-admin tbody tr:hover { background: #f8f9fa; }
    </style>
</head>
<body>
    <!-- Loading -->
    <div class="admin-loading" id="adminLoading">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-2 text-muted">Loading...</p>
        </div>
    </div>
    
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay d-lg-none" id="sidebarOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999;" onclick="toggleSidebar()"></div>

    <!-- ============================================================ -->
    <!-- SIDEBAR -->
    <!-- ============================================================ -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header d-flex align-items-center gap-2">
            <svg width="32" height="32" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="gdlGradAdmin" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#FFD54F"/>
                        <stop offset="100%" style="stop-color:#FFA726"/>
                    </linearGradient>
                </defs>
                <circle cx="60" cy="60" r="56" fill="none" stroke="url(#gdlGradAdmin)" stroke-width="3" opacity="0.4"/>
                <rect x="36" y="40" width="48" height="36" rx="4" fill="url(#gdlGradAdmin)" opacity="0.95"/>
                <line x1="36" y1="52" x2="84" y2="52" stroke="rgba(0,0,0,0.2)" stroke-width="2"/>
                <line x1="60" y1="40" x2="60" y2="76" stroke="rgba(0,0,0,0.2)" stroke-width="2"/>
                <rect x="52" y="36" width="16" height="8" rx="2" fill="#fff" opacity="0.3"/>
                <path d="M78 32 L92 48 L82 48 L82 64 L74 64 L74 48 L64 48 Z" fill="#fff" opacity="0.9"/>
                <text x="60" y="100" text-anchor="middle" font-family="Inter, sans-serif" font-weight="800" font-size="16" fill="url(#gdlGradAdmin)" letter-spacing="3">GDL</text>
            </svg>
            <div>
                <h4 class="mb-0">GDL <span style="color: #ffc107;">Admin</span></h4>
                <small>v2.0 Enterprise</small>
            </div>
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-label">Main</div>
            
            <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-link <?= currentPage('/admin/dashboard') || currentPage('/admin') ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            
            <a href="<?= BASE_URL ?>/admin/shipments" class="nav-link <?= currentPage('/admin/shipments') ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i> Shipments
                <span class="badge bg-warning text-dark" id="shipmentBadge">0</span>
            </a>
            
            <a href="<?= BASE_URL ?>/admin/customers" class="nav-link <?= currentPage('/admin/customers') ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Customers
            </a>
            
            <div class="menu-label">Operations</div>
            
            <a href="<?= BASE_URL ?>/admin/drivers" class="nav-link <?= currentPage('/admin/drivers') ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i> Drivers
            </a>
            
            <a href="<?= BASE_URL ?>/admin/vehicles" class="nav-link <?= currentPage('/admin/vehicles') ? 'active' : '' ?>">
                <i class="bi bi-truck"></i> Vehicles
            </a>
            
            <a href="<?= BASE_URL ?>/admin/branches" class="nav-link <?= currentPage('/admin/branches') ? 'active' : '' ?>">
                <i class="bi bi-building"></i> Branches
            </a>
            
            <a href="<?= BASE_URL ?>/admin/warehouses" class="nav-link <?= currentPage('/admin/warehouses') ? 'active' : '' ?>">
                <i class="bi bi-boxes"></i> Warehouses
            </a>
            
            <div class="menu-label">Finance</div>
            
            <a href="<?= BASE_URL ?>/admin/invoices" class="nav-link <?= currentPage('/admin/invoices') ? 'active' : '' ?>">
                <i class="bi bi-receipt"></i> Invoices
            </a>
            
            <a href="<?= BASE_URL ?>/admin/payments" class="nav-link <?= currentPage('/admin/payments') ? 'active' : '' ?>">
                <i class="bi bi-credit-card"></i> Payments
            </a>
            
            <a href="<?= BASE_URL ?>/admin/reports" class="nav-link <?= currentPage('/admin/reports') ? 'active' : '' ?>">
                <i class="bi bi-graph-up-arrow"></i> Reports
            </a>

            <div class="menu-label">Communication</div>

            <a href="<?= BASE_URL ?>/admin/whatsapp" class="nav-link <?= currentPage('/admin/whatsapp') ? 'active' : '' ?>">
                <i class="bi bi-whatsapp"></i> WhatsApp
            </a>

            <div class="menu-label">System</div>
            
            <a href="<?= BASE_URL ?>/admin/notifications" class="nav-link <?= currentPage('/admin/notifications') ? 'active' : '' ?>">
                <i class="bi bi-bell"></i> Notifications
            </a>
            
            <a href="<?= BASE_URL ?>/admin/users" class="nav-link <?= currentPage('/admin/users') ? 'active' : '' ?>">
                <i class="bi bi-shield"></i> Users & Roles
            </a>
            
            <a href="<?= BASE_URL ?>/admin/documents" class="nav-link <?= currentPage('/admin/documents') ? 'active' : '' ?>">
                <i class="bi bi-files"></i> Documents
            </a>
            
            <a href="<?= BASE_URL ?>/admin/audit-logs" class="nav-link <?= currentPage('/admin/audit-logs') ? 'active' : '' ?>">
                <i class="bi bi-list-check"></i> Audit Logs
            </a>
            
            <a href="<?= BASE_URL ?>/admin/settings" class="nav-link <?= currentPage('/admin/settings') ? 'active' : '' ?>">
                <i class="bi bi-gear"></i> Settings
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
    <div class="admin-main">
        <!-- Top Bar -->
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5 class="page-title mb-0"><?= $pageTitle ?? 'Dashboard' ?></h5>
            </div>
            
            <div class="topbar-right">
                <button class="btn-icon" onclick="location.reload()" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                
                <div class="dropdown">
                    <button class="btn-icon position-relative" data-bs-toggle="dropdown" title="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                              id="notifBadge" style="font-size: 0.55rem;">0</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 300px;">
                        <h6 class="fw-bold mb-3">Notifications</h6>
                        <div id="notifList"><p class="text-muted small mb-0">No new notifications</p></div>
                    </div>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown" 
                            style="background: #f4f6f9; border-radius: 10px; padding: 5px 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--admin-primary); 
                                    display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 0.85rem;">
                            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <span class="d-none d-md-inline fw-semibold small"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/settings"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="admin-content">
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
        }
        
        // Initialize DataTables
        $(document).ready(function() {
            $('.data-table').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                },
                dom: '<"d-flex justify-content-between align-items-center mb-3"lf>t<"d-flex justify-content-between align-items-center mt-3"ip>',
            });
            
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
            });
            
            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert-dismissible').alert('close');
            }, 5000);
        });
        
        // Global AJAX setup with CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
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
        
        // Load dashboard stats
        $(document).ready(function() {
            $.ajax({
                url: '<?= BASE_URL ?>/admin/dashboard/stats',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#shipmentBadge').text(response.data.pending || 0);
                    }
                }
            });
        });
    </script>
</body>
</html>
