@extends('admin.layouts.master')
@section('title')
    Dashboard - {{ env('APP_NAME') }} admin
@endsection
@push('styles')
@endpush
@section('head')
    Dashboard
@endsection
@section('content')
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --secondary-gradient: linear-gradient(135deg, #3b82f6 0%, #2dd4bf 100%);
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --dark-color: #1e293b;
            --light-bg: #f3f4f6;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .main-content {
            background-color: var(--light-bg);
            min-height: 100vh;
            padding-bottom: 2rem;
        }

        .welcome-card {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }

        .welcome-card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-2px);
        }

        .stat-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid rgba(0, 0, 0, 0.02);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .stat-icon.primary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .stat-icon.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .stat-icon.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .stat-icon.info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }

        .stat-icon.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .stat-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--dark-color);
            line-height: 1;
            margin: 0.5rem 0;
            letter-spacing: -1px;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.825rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0;
        }

        .trend-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            margin-top: auto;
        }

        .trend-up {
            background-color: #dafbf0;
            color: #059669;
        }

        .trend-down {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .dashboard-card {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            height: 100%;
        }

        .dashboard-card:hover {
            box-shadow: var(--hover-shadow);
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1.25rem 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-custom tbody td {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
            color: #475569;
            font-weight: 500;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s;
        }

        .table-custom tbody tr:hover td {
            background-color: #f8fafc;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .agent-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background-size: cover;
            background-position: center;
            background-color: #e2e8f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            color: #475569;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .progress {
            height: 6px;
            border-radius: 10px;
            background-color: #f1f5f9;
            overflow: hidden;
            margin-top: 1rem;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 1s ease-in-out;
        }

        /* Overview Cards */
        .overview-card {
            border: 1px solid rgba(0, 0, 0, 0.03);
            background: white;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .overview-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 767.98px) {
            .stat-value {
                font-size: 1.75rem;
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }

            .welcome-card {
                text-align: center;
            }

            .row.g-4 {
                --bs-gutter-y: 1rem;
            }
        }
    </style>

    <div class="main-content">
        <div class="container-fluid px-4 pt-4">
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-card p-3 p-md-4 d-block d-md-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-dark fw-bold">Good Afternoon, {{ Auth::user()->name ?? 'Admin' }}! 👋</h4>
                            <p class="text-muted mb-0">Here's the current status of your monitored websites.</p>
                        </div>
                        <div class="mt-3 mt-md-0 text-md-end">
                            <h6 class="text-muted mb-0">{{ now()->format('l, d F Y') }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Primary Stats Row -->
            <div class="row g-4 mb-4">
                @can('viewAny', App\Models\UrlManagement::class)
                    <div class="col-xl-3 col-md-6 mb-2">
                        <div class="stat-card p-3 p-md-4 h-100">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="stat-label">Total URLs</p>
                                    <h2 class="stat-value my-2">{{ $data['total_urls'] ?? 0 }}</h2>
                                    <span class="trend-badge trend-up">
                                        <i class="ph ph-check-circle"></i> Monitored
                                    </span>
                                </div>
                                <div class="stat-icon info">
                                    <i class="ph ph-globe"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 4px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-2">
                        <div class="stat-card p-3 p-md-4 h-100">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="stat-label">Total Checks</p>
                                    <h2 class="stat-value my-2">{{ number_format($data['total_checks'] ?? 0) }}</h2>
                                    <span class="trend-badge trend-up">
                                        <i class="ph ph-activity"></i> Lifetime
                                    </span>
                                </div>
                                <div class="stat-icon success">
                                    <i class="ph ph-activity"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 4px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('viewAny', App\Models\User::class)
                    <div class="col-xl-3 col-md-6 mb-2">
                        <div class="stat-card p-3 p-md-4 h-100">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="stat-label">Total Users</p>
                                    <h2 class="stat-value my-2">{{ $data['total_users'] ?? 0 }}</h2>
                                    <span class="trend-badge trend-up">
                                        <i class="ph ph-users"></i> Registered
                                    </span>
                                </div>
                                <div class="stat-icon primary">
                                    <i class="ph ph-users-three"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 4px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('viewAny', App\Models\UrlManagement::class)
                    <div class="col-xl-3 col-md-6 mb-2">
                        <div class="stat-card p-3 p-md-4 h-100">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="stat-label">Down URLs</p>
                                    <h2 class="stat-value my-2">{{ $data['down_urls'] ?? 0 }}</h2>
                                    @if (($data['down_urls'] ?? 0) > 0)
                                        <span class="trend-badge trend-down">
                                            <i class="ph ph-warning"></i> Action Required
                                        </span>
                                    @else
                                        <span class="trend-badge trend-up">
                                            <i class="ph ph-check"></i> All Good
                                        </span>
                                    @endif
                                </div>
                                <div class="stat-icon warning">
                                    <i class="ph ph-warning-circle"></i>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 4px;">
                                <div class="progress-bar bg-warning" role="progressbar"
                                    style="width: {{ $data['total_urls'] > 0 ? ($data['down_urls'] / $data['total_urls']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>

            <!-- Secondary Stats Grid -->
            @can('viewAny', App\Models\UrlManagement::class)
                <h6 class="text-uppercase text-muted fw-bold mb-4 small date-header px-1">Today's Overview</h6>
                <div class="row g-4 mb-4">
                    <div class="col-lg-3 col-sm-6 mb-2">
                        <div
                            class="overview-card rounded-4 p-3 p-md-4 d-flex flex-row align-items-center justify-content-between h-100">
                            <div>
                                <p class="text-muted small mb-1 fw-bold text-uppercase">Check Runs</p>
                                <h3 class="mb-0 fw-bold text-dark">{{ $data['today_stats']['checks'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-light-primary text-primary rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="width:50px; height:50px;">
                                <i class="ph ph-arrow-clockwise fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-2">
                        <div
                            class="overview-card rounded-4 p-3 p-md-4 d-flex flex-row align-items-center justify-content-between h-100">
                            <div>
                                <p class="text-muted small mb-1 fw-bold text-uppercase">Successful</p>
                                <h3 class="mb-0 fw-bold text-dark">{{ $data['today_stats']['up'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-light-success text-success rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="width:50px; height:50px;">
                                <i class="ph ph-check-circle fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-2">
                        <div
                            class="overview-card rounded-4 p-3 p-md-4 d-flex flex-row align-items-center justify-content-between h-100">
                            <div>
                                <p class="text-muted small mb-1 fw-bold text-uppercase">Failures</p>
                                <h3 class="mb-0 fw-bold text-dark">{{ $data['today_stats']['down'] ?? 0 }}</h3>
                            </div>
                            <div class="bg-light-danger text-danger rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="width:50px; height:50px;">
                                <i class="ph ph-warning-octagon fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-2  ">
                        <div
                            class="overview-card rounded-4 p-3 p-md-4 d-flex flex-row align-items-center justify-content-between h-100">
                            <div>
                                <p class="text-muted small mb-1 fw-bold text-uppercase">Avg Response</p>
                                <h3 class="mb-0 fw-bold text-dark">{{ $data['today_stats']['avg_response'] ?? 0 }} <small
                                        class="fs-6 text-muted">ms</small></h3>
                            </div>
                            <div class="bg-light-info text-info rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="width:50px; height:50px;">
                                <i class="ph ph-timer fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            <!-- Charts and Lists -->
            @can('viewAny', App\Models\UrlManagement::class)
                <div class="row g-4 mb-2">
                    <!-- Source Chart -->
                    <div class="col-lg-5 col-xl-4">
                        <div class="dashboard-card bg-white p-3 p-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold text-dark mb-0">Status Overview</h5>
                                <button class="btn btn-sm btn-light rounded-circle"><i class="ph ph-dots-three"></i></button>
                            </div>

                            <div style="position: relative; height: 250px;"
                                class="d-flex align-items-center justify-content-center">
                                <div id="status-donut-chart" class="morris-donut-inverse"></div>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded bg-light">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success rounded-circle p-1 me-2">&nbsp;</span>
                                        <span class="fw-bold text-dark small">Up</span>
                                    </div>
                                    <span class="fw-bold">{{ $data['url_status_counts']['up'] ?? 0 }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded bg-light">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-danger rounded-circle p-1 me-2">&nbsp;</span>
                                        <span class="fw-bold text-dark small">Down</span>
                                    </div>
                                    <span class="fw-bold">{{ $data['url_status_counts']['down'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity List -->
                    <div class="col-lg-7 col-xl-8 mb-2">
                        <div class="dashboard-card bg-white p-0 overflow-hidden mt-2">
                            <div class="p-3 p-md-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                                <h5 class="fw-bold text-dark mb-0">Recent Activity</h5>
                                <a href="{{ route('url-management.index') }}"
                                    class="text-primary fw-bold text-decoration-none small">View All <i
                                        class="ph ph-arrow-right"></i></a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-custom mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">URL</th>
                                            <th>Status</th>
                                            <th>Response Time</th>
                                            <th>Checked At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data['recent_logs'] ?? [] as $log)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="agent-avatar me-3 bg-light-primary text-primary">
                                                            <i class="ph ph-link-simple"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $log->url->name ?? 'Unknown' }}
                                                            </div>
                                                            <div class="small text-muted text-truncate"
                                                                style="max-width: 200px;">
                                                                <a href="{{ $log->url->url ?? '#' }}" target="_blank"
                                                                    class="text-muted text-decoration-none hover-primary">
                                                                    {{ $log->url->url ?? '' }} <i
                                                                        class="ph ph-arrow-square-out fs-6 ms-1"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($log->status == 'up')
                                                        <span
                                                            class="badge bg-light-success text-success rounded-pill px-3 py-2 border border-success-subtle">
                                                            <i class="ph ph-check-circle me-1"></i> UP
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-light-danger text-danger rounded-pill px-3 py-2 border border-danger-subtle">
                                                            <i class="ph ph-x-circle me-1"></i> DOWN
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-bold {{ $log->response_time > 1000 ? 'text-warning' : 'text-dark' }}">
                                                        {{ $log->response_time }} ms
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text-muted small">
                                                        <i class="ph ph-clock me-1"></i>
                                                        {{ $log->checked_at ? $log->checked_at->diffForHumans() : '-' }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <div
                                                        class="d-flex flex-column align-items-center justify-content-center text-muted">
                                                        <i class="ph ph-clipboard-text fs-1 mb-2 opacity-50"></i>
                                                        <p class="mb-0">No recent activity found.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @if (isset($data['url_status_counts']) && array_sum($data['url_status_counts']) > 0)
                Morris.Donut({
                    element: 'status-donut-chart',
                    data: [{
                            label: "Up",
                            value: {{ $data['url_status_counts']['up'] }}
                        },
                        {
                            label: "Down",
                            value: {{ $data['url_status_counts']['down'] }}
                        }
                    ],
                    colors: ['#10b981', '#ef4444'], // Using CSS variable colors
                    resize: true,
                    formatter: function(y, data) {
                        return y
                    }
                });
            @endif
        });
    </script>
@endpush
