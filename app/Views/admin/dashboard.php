<!-- ============================================================
     ADMIN DASHBOARD - PREMIUM MODERN DESIGN
     ============================================================ -->

<!-- Welcome Banner -->
<div class="admin-card mb-4" style="background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%); color: #fff; border: none;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Welcome back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?> 👋</h4>
            <p class="mb-0 opacity-75">Here's what's happening with your logistics today.</p>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                <i class="bi bi-calendar3 me-1"></i> <?= date('l, M d, Y') ?>
            </span>
        </div>
    </div>
</div>

<!-- ============================================================
     STAT CARDS ROW
     ============================================================ -->
<div class="row g-4 mb-4">
    <!-- Total Shipments -->
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stat-card" style="border-left: 4px solid #5c6bc0;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Shipments</div>
                    <div class="stat-value"><?= number_format($stats->total_shipments ?? 0) ?></div>
                    <div class="stat-change text-success">
                        <i class="bi bi-arrow-up-short"></i> <?= $stats->today_shipments ?? 0 ?> today
                    </div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #5c6bc0, #3f51b5);">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stat-card" style="border-left: 4px solid #26a69a;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value"><?= format_currency($stats->total_revenue ?? 0) ?></div>
                    <div class="stat-change text-success">
                        <i class="bi bi-arrow-up-short"></i> <?= format_currency($stats->today_revenue ?? 0) ?> today
                    </div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #26a69a, #00897b);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- In Transit -->
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stat-card" style="border-left: 4px solid #42a5f5;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">In Transit</div>
                    <div class="stat-value"><?= $stats->in_transit_shipments ?? 0 ?></div>
                    <div class="stat-change text-primary">
                        <i class="bi bi-truck"></i> Active now
                    </div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #42a5f5, #1e88e5);">
                    <i class="bi bi-truck"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stat-card" style="border-left: 4px solid #ffa726;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pending Orders</div>
                    <div class="stat-value"><?= $stats->pending_shipments ?? 0 ?></div>
                    <div class="stat-change text-warning">
                        <i class="bi bi-clock-history"></i> Needs attention
                    </div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffa726, #f57c00);">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     SECONDARY STATS ROW
     ============================================================ -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card mini-stat">
            <div class="d-flex align-items-center gap-3">
                <div class="mini-stat-icon" style="background: rgba(76,175,80,0.1); color: #4caf50;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="mini-stat-value"><?= $stats->delivered_shipments ?? 0 ?></div>
                    <div class="mini-stat-label">Delivered</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card mini-stat">
            <div class="d-flex align-items-center gap-3">
                <div class="mini-stat-icon" style="background: rgba(244,67,54,0.1); color: #f44336;">
                    <i class="bi bi-arrow-return-left"></i>
                </div>
                <div>
                    <div class="mini-stat-value"><?= $stats->returned_shipments ?? 0 ?></div>
                    <div class="mini-stat-label">Returned</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card mini-stat">
            <div class="d-flex align-items-center gap-3">
                <div class="mini-stat-icon" style="background: rgba(33,150,243,0.1); color: #2196f3;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="mini-stat-value"><?= number_format($stats->total_customers ?? 0) ?></div>
                    <div class="mini-stat-label">Customers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-card mini-stat">
            <div class="d-flex align-items-center gap-3">
                <div class="mini-stat-icon" style="background: rgba(156,39,176,0.1); color: #9c27b0;">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div>
                    <div class="mini-stat-value"><?= $stats->total_branches ?? 0 ?></div>
                    <div class="mini-stat-label">Branches</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     CHARTS ROW
     ============================================================ -->
<div class="row g-4 mb-4">
    <!-- Revenue & Shipments Chart -->
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h6 class="fw-bold mb-0">Revenue & Shipments</h6>
                    <small class="text-muted">Monthly performance overview</small>
                </div>
                <div class="btn-group btn-group-sm" id="chartPeriodBtns">
                    <button class="btn btn-outline-secondary active" data-period="12">12M</button>
                    <button class="btn btn-outline-secondary" data-period="6">6M</button>
                    <button class="btn btn-outline-secondary" data-period="3">3M</button>
                </div>
            </div>
            <div style="height: 320px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="col-xl-4">
        <div class="admin-card">
            <h6 class="fw-bold mb-1">Shipment Status</h6>
            <small class="text-muted d-block mb-3">Current distribution</small>
            <div style="height: 200px;">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="status-legend mt-3" id="statusLegend"></div>
        </div>
    </div>
</div>

<!-- ============================================================
     TABLES ROW
     ============================================================ -->
<div class="row g-4 mb-4">
    <!-- Recent Shipments -->
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">Recent Shipments</h6>
                    <small class="text-muted">Latest 10 shipments</small>
                </div>
                <a href="<?= BASE_URL ?>/admin/shipments" class="btn btn-sm btn-outline-primary">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-admin align-middle">
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Recipient</th>
                            <th>Route</th>
                            <th>Status</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentShipments)): ?>
                            <?php foreach ($recentShipments as $s): ?>
                            <tr>
                                <td>
                                    <a href="<?= BASE_URL ?>/admin/shipments/<?= $s->id ?>" class="fw-semibold text-decoration-none">
                                        <?= htmlspecialchars($s->tracking_number) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($s->recipient_name) ?></td>
                                <td>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($s->sender_city ?? '') ?> → <?= htmlspecialchars($s->recipient_city ?? '') ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill" style="background: <?= $s->status_color ?? '#6c757d' ?>20; color: <?= $s->status_color ?? '#6c757d' ?>; border: 1px solid <?= $s->status_color ?? '#6c757d' ?>40;">
                                        <?= htmlspecialchars($s->status_name ?? $s->status) ?>
                                    </span>
                                </td>
                                <td class="text-end fw-semibold"><?= format_currency($s->grand_total) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>No shipments yet
                            </td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Countries -->
    <div class="col-xl-4">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">Top Countries</h6>
                    <small class="text-muted">By shipment volume</small>
                </div>
            </div>
            <?php if (!empty($countryStats)): ?>
                <?php
                $maxCountry = max(array_column($countryStats, 'total'));
                foreach (array_slice($countryStats, 0, 6) as $cs):
                    $pct = $maxCountry > 0 ? round(($cs->total / $maxCountry) * 100) : 0;
                ?>
                <div class="country-bar-item mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold small"><?= htmlspecialchars($cs->country) ?></span>
                        <span class="text-muted small"><?= $cs->total ?></span>
                    </div>
                    <div class="progress" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar" role="progressbar" style="width: <?= $pct ?>%; background: linear-gradient(90deg, #1a237e, #3949ab); border-radius: 3px;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted text-center py-3">No data yet</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ============================================================
     ACTIVITY & PERFORMANCE ROW
     ============================================================ -->
<div class="row g-4">
    <!-- Recent Activity -->
    <div class="col-xl-6">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0">Recent Activity</h6>
                    <small class="text-muted">Latest system events</small>
                </div>
                <a href="<?= BASE_URL ?>/admin/audit-logs" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="activity-list" style="max-height: 340px; overflow-y: auto;">
                <?php if (!empty($recentActivities)): ?>
                    <?php foreach (array_slice($recentActivities, 0, 8) as $a): ?>
                    <div class="activity-item d-flex align-items-start gap-3 pb-3 mb-3 border-bottom">
                        <div class="activity-dot flex-shrink-0"></div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold small">
                                <?= ucwords(str_replace('_', ' ', htmlspecialchars($a->action ?? ''))) ?>
                            </p>
                            <small class="text-muted">
                                <?= htmlspecialchars($a->user_name ?? 'System') ?> · <?= time_ago($a->created_at) ?>
                            </small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-clock-history fs-1 d-block mb-2 opacity-25"></i>
                        No recent activities
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Performance -->
    <div class="col-xl-6">
        <!-- Quick Actions -->
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3">Quick Actions</h6>
            <div class="row g-2">
                <div class="col-6">
                    <a href="<?= BASE_URL ?>/admin/shipments/create" class="quick-action-btn">
                        <i class="bi bi-plus-circle"></i>
                        <span>New Shipment</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="<?= BASE_URL ?>/admin/customers" class="quick-action-btn">
                        <i class="bi bi-person-plus"></i>
                        <span>Add Customer</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="<?= BASE_URL ?>/admin/shipments" class="quick-action-btn">
                        <i class="bi bi-search"></i>
                        <span>Track Shipment</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="<?= BASE_URL ?>/admin/reports" class="quick-action-btn">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>View Reports</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Fleet Summary -->
        <div class="admin-card">
            <h6 class="fw-bold mb-3">Fleet Summary</h6>
            <div class="row g-3">
                <div class="col-4">
                    <div class="fleet-stat text-center">
                        <div class="fleet-stat-icon mx-auto mb-2" style="background: rgba(33,150,243,0.1); color: #2196f3;">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="fw-bold fs-5"><?= $stats->total_vehicles ?? 0 ?></div>
                        <small class="text-muted">Vehicles</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="fleet-stat text-center">
                        <div class="fleet-stat-icon mx-auto mb-2" style="background: rgba(76,175,80,0.1); color: #4caf50;">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="fw-bold fs-5"><?= $stats->total_drivers ?? 0 ?></div>
                        <small class="text-muted">Drivers</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="fleet-stat text-center">
                        <div class="fleet-stat-icon mx-auto mb-2" style="background: rgba(156,39,176,0.1); color: #9c27b0;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="fw-bold fs-5"><?= $stats->total_branches ?? 0 ?></div>
                        <small class="text-muted">Branches</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     DASHBOARD STYLES & CHARTS
     ============================================================ -->
<style>
    /* Stat Cards */
    .stat-card { border-left: 4px solid; transition: all 0.3s; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .stat-card .stat-label { font-size: 0.8rem; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .stat-card .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1.2; margin-bottom: 4px; }
    .stat-card .stat-change { font-size: 0.78rem; font-weight: 600; }
    .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #fff; }

    /* Mini Stats */
    .mini-stat { transition: all 0.3s; }
    .mini-stat:hover { transform: translateY(-2px); }
    .mini-stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .mini-stat-value { font-size: 1.3rem; font-weight: 800; line-height: 1.2; }
    .mini-stat-label { font-size: 0.78rem; color: #6c757d; font-weight: 500; }

    /* Activity */
    .activity-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--admin-primary, #1a237e); margin-top: 7px; }

    /* Quick Actions */
    .quick-action-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 16px 8px; border-radius: 10px; background: #f8f9fa; border: 1px solid #e9ecef; text-decoration: none; color: #333; transition: all 0.2s; }
    .quick-action-btn:hover { background: var(--admin-primary, #1a237e); color: #fff; border-color: var(--admin-primary, #1a237e); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(26,35,126,0.3); }
    .quick-action-btn i { font-size: 1.5rem; margin-bottom: 6px; }
    .quick-action-btn span { font-size: 0.78rem; font-weight: 600; }

    /* Fleet */
    .fleet-stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }

    /* Status Legend */
    .status-legend-item { display: flex; align-items: center; gap: 8px; padding: 5px 0; font-size: 0.82rem; }
    .status-legend-dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }

    /* Table */
    .table-admin thead th { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d; border-bottom: 2px solid #e9ecef; padding: 10px 12px; }
    .table-admin tbody td { padding: 10px 12px; border-bottom: 1px solid #f1f3f5; }
    .table-admin tbody tr:hover { background: #f8f9fa; }
</style>

<script>
$(document).ready(function() {
    initRevenueChart();
    initStatusChart();
    initChartPeriodBtns();
});

function initRevenueChart() {
    const months = <?= json_encode(array_column($monthlyRevenue ?? [], 'month_name') ?: ['Jan','Feb','Mar','Apr','May','Jun']) ?>;
    const shipments = <?= json_encode(array_map('intval', array_column($monthlyRevenue ?? [], 'shipments') ?: [0,0,0,0,0,0])) ?>;
    const revenue = <?= json_encode(array_map('floatval', array_column($monthlyRevenue ?? [], 'revenue') ?: [0,0,0,0,0,0])) ?>;

    const ctx = document.getElementById('revenueChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 320);
    gradient.addColorStop(0, 'rgba(26, 35, 126, 0.15)');
    gradient.addColorStop(1, 'rgba(26, 35, 126, 0.01)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Revenue ($)',
                    data: revenue,
                    backgroundColor: 'rgba(26, 35, 126, 0.75)',
                    hoverBackgroundColor: 'rgba(26, 35, 126, 1)',
                    borderRadius: 6,
                    borderSkipped: false,
                    yAxisID: 'y',
                    barPercentage: 0.6,
                },
                {
                    label: 'Shipments',
                    data: shipments,
                    type: 'line',
                    borderColor: '#ff8f00',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ff8f00',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: true, position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 12, weight: '600' } } },
                tooltip: { backgroundColor: '#1a1a2e', titleFont: { size: 13, weight: '600' }, bodyFont: { size: 12 }, padding: 12, cornerRadius: 8 }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' } } },
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { size: 11 }, callback: v => '$' + v.toLocaleString() } },
                y1: { beginAtZero: true, position: 'right', grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });
}

function initStatusChart() {
    const delivered = <?= $stats->delivered_shipments ?? 0 ?>;
    const inTransit = <?= $stats->in_transit_shipments ?? 0 ?>;
    const pending = <?= $stats->pending_shipments ?? 0 ?>;
    const returned = <?= $stats->returned_shipments ?? 0 ?>;
    const cancelled = <?= $stats->cancelled_shipments ?? 0 ?>;

    const labels = ['Delivered', 'In Transit', 'Pending', 'Returned', 'Cancelled'];
    const data = [delivered, inTransit, pending, returned, cancelled];
    const colors = ['#4caf50', '#2196f3', '#ffa726', '#f44336', '#9e9e9e'];

    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{ data: data, backgroundColor: colors, borderWidth: 0, hoverOffset: 6 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1a1a2e', padding: 10, cornerRadius: 8 }
            }
        },
        plugins: [{
            id: 'centerText',
            afterDraw: function(chart) {
                const total = data.reduce((a, b) => a + b, 0);
                const ctx = chart.ctx;
                const centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                const centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = '800 1.6rem Inter, sans-serif';
                ctx.fillStyle = '#1a1a2e';
                ctx.fillText(total, centerX, centerY - 8);
                ctx.font = '500 0.7rem Inter, sans-serif';
                ctx.fillStyle = '#6c757d';
                ctx.fillText('Total', centerX, centerY + 14);
                ctx.restore();
            }
        }]
    });

    // Legend
    let legendHtml = '';
    labels.forEach((label, i) => {
        legendHtml += '<div class="status-legend-item"><span class="status-legend-dot" style="background:' + colors[i] + '"></span>' + label + '<span class="ms-auto fw-semibold">' + data[i] + '</span></div>';
    });
    document.getElementById('statusLegend').innerHTML = legendHtml;
}

function initChartPeriodBtns() {
    document.querySelectorAll('#chartPeriodBtns .btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#chartPeriodBtns .btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            showToast('Showing ' + this.dataset.period + ' months', 'info');
        });
    });
}
</script>
