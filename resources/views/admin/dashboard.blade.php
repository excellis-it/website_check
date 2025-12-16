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
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #2af598 0%, #009efd 100%);
            --warning-gradient: linear-gradient(135deg, #fce38a 0%, #f38181 100%);
            --danger-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
            --info-gradient: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
        }

        .main-content {
            background-color: #f8f9fa;
            /* Softer background */
        }

        .welcome-card {
            background: white;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.03);
        }

        .stat-card {
            border: none;
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }

        .stat-icon.primary {
            background: rgba(118, 75, 162, 0.1);
            color: #764ba2;
        }

        .stat-icon.success {
            background: rgba(0, 158, 253, 0.1);
            color: #009efd;
        }

        .stat-icon.warning {
            background: rgba(243, 129, 129, 0.1);
            color: #f38181;
        }

        .stat-icon.info {
            background: rgba(102, 166, 255, 0.1);
            color: #66a6ff;
        }

        .stat-icon.danger {
            background: rgba(255, 154, 158, 0.1);
            color: #ff9a9e;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            line-height: 1.2;
        }

        .stat-label {
            color: #718096;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0;
        }

        .trend-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .trend-up {
            background-color: #e6fffa;
            color: #047481;
        }

        .trend-down {
            background-color: #fff5f5;
            color: #c53030;
        }

        .dashboard-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
            height: 100%;
        }

        .table-custom thead th {
            background-color: #f7fafc;
            color: #4a5568;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem;
        }

        .table-custom tbody td {
            padding: 1rem;
            vertical-align: middle;
            color: #4a5568;
            font-weight: 500;
            border-bottom: 1px solid #edf2f7;
        }

        .agent-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            background-color: #e2e8f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #4a5568;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="main-content">
        <div class="container-fluid px-4 pt-4">
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-card p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-dark fw-bold">Good Afternoon, {{ Auth::user()->name ?? 'Admin' }}! 👋</h4>
                            <p class="text-muted mb-0">Here's what's happening with your business today.</p>
                        </div>
                        <div class="d-none d-md-block text-end">
                            <h6 class="text-muted mb-0">{{ now()->format('l, d F Y') }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Primary Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Total Booking</p>
                                <h2 class="stat-value my-2">1,063</h2>
                                <span class="trend-badge trend-up">
                                    <i class="ph ph-arrow-up-right"></i> 12% vs last month
                                </span>
                            </div>
                            <div class="stat-icon info">
                                <i class="ph ph-airplane-tilt"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 70%"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Agent Balance</p>
                                <h2 class="stat-value my-2">₹ 6.97L</h2>
                                <span class="trend-badge trend-up">
                                    <i class="ph ph-arrow-up-right"></i> 8.5% vs last month
                                </span>
                            </div>
                            <div class="stat-icon success">
                                <i class="ph ph-currency-inr"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Total Agents</p>
                                <h2 class="stat-value my-2">76</h2>
                                <span class="trend-badge trend-up">
                                    <i class="ph ph-plus"></i> 3 new today
                                </span>
                            </div>
                            <div class="stat-icon primary">
                                <i class="ph ph-users-three"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 45%"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="stat-label">Pending Actions</p>
                                <h2 class="stat-value my-2">9</h2>
                                <span class="trend-badge trend-down" style="background-color: #fff5f5; color: #c53030;">
                                    <i class="ph ph-warning"></i> Action Required
                                </span>
                            </div>
                            <div class="stat-icon warning">
                                <i class="ph ph-bell-ringing"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 15%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Stats Grid -->
            <h6 class="text-uppercase text-muted fw-bold mb-3 small date-header px-2">Today's Overview</h6>
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-sm-6">
                    <div
                        class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-0 fw-bold text-uppercase">Confirmed</p>
                            <h4 class="mb-0 fw-bold text-dark">0</h4>
                        </div>
                        <div class="bg-light-success text-success rounded-circle p-2 d-flex align-items-center justify-content-center"
                            style="width:40px; height:40px;">
                            <i class="ph ph-check-circle fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div
                        class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-0 fw-bold text-uppercase">Month to Date</p>
                            <h4 class="mb-0 fw-bold text-dark">49</h4>
                        </div>
                        <div class="bg-light-primary text-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                            style="width:40px; height:40px;">
                            <i class="ph ph-calendar fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div
                        class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-0 fw-bold text-uppercase">Pending</p>
                            <h4 class="mb-0 fw-bold text-dark">0</h4>
                        </div>
                        <div class="bg-light-warning text-warning rounded-circle p-2 d-flex align-items-center justify-content-center"
                            style="width:40px; height:40px;">
                            <i class="ph ph-clock fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div
                        class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small mb-0 fw-bold text-uppercase">Requests</p>
                            <h4 class="mb-0 fw-bold text-dark">0</h4>
                        </div>
                        <div class="bg-light-danger text-danger rounded-circle p-2 d-flex align-items-center justify-content-center"
                            style="width:40px; height:40px;">
                            <i class="ph ph-chart-line-down fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Lists -->
            <div class="row g-4">
                <!-- Source Chart -->
                <div class="col-lg-5 col-xl-4">
                    <div class="dashboard-card bg-white p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold text-dark mb-0">Bookings by Source</h5>
                            <button class="btn btn-sm btn-light rounded-circle"><i class="ph ph-dots-three"></i></button>
                        </div>

                        <div style="position: relative; height: 250px;"
                            class="d-flex align-items-center justify-content-center">
                            <div id="donut-example" class="morris-donut-inverse"></div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded bg-light">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-circle p-1 me-2">&nbsp;</span>
                                    <span class="fw-bold text-dark small">Agency</span>
                                </div>
                                <span class="fw-bold">2,500</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded bg-light">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info rounded-circle p-1 me-2">&nbsp;</span>
                                    <span class="fw-bold text-dark small">Corporates</span>
                                </div>
                                <span class="fw-bold">3,630</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning rounded-circle p-1 me-2">&nbsp;</span>
                                    <span class="fw-bold text-dark small">Others</span>
                                </div>
                                <span class="fw-bold">4,870</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Agents List -->
                <div class="col-lg-7 col-xl-8">
                    <div class="dashboard-card bg-white p-0 overflow-hidden">
                        <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                            <h5 class="fw-bold text-dark mb-0">Top Performing Agents</h5>
                            <a href="#" class="text-primary fw-bold text-decoration-none small">View All Agents <i
                                    class="ph ph-arrow-right"></i></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Agent</th>
                                        <th>Status</th>
                                        <th>Total Bookings</th>
                                        <th>Wallet Balance</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="agent-avatar me-3 bg-light-primary text-primary">FM</div>
                                                <div>
                                                    <div class="fw-bold text-dark">Flight Mantra</div>
                                                    <div class="small text-muted">ID: #AG-001</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light-success text-success rounded-pill">Active</span>
                                        </td>
                                        <td><span class="fw-bold">452</span> <small class="text-muted">orders</small></td>
                                        <td class="fw-bold text-dark">₹ 50,452</td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light"><i class="ph ph-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="agent-avatar me-3 bg-light-info text-info">SG</div>
                                                <div>
                                                    <div class="fw-bold text-dark">SG Travels</div>
                                                    <div class="small text-muted">ID: #AG-002</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light-success text-success rounded-pill">Active</span>
                                        </td>
                                        <td><span class="fw-bold">156</span> <small class="text-muted">orders</small></td>
                                        <td class="fw-bold text-dark">₹ 50,452</td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light"><i class="ph ph-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="agent-avatar me-3 bg-light-warning text-warning">KC</div>
                                                <div>
                                                    <div class="fw-bold text-dark">KOGENT CONNECT</div>
                                                    <div class="small text-muted">ID: #AG-005</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light-warning text-warning rounded-pill">Pending</span>
                                        </td>
                                        <td><span class="fw-bold">85</span> <small class="text-muted">orders</small></td>
                                        <td class="fw-bold text-dark">₹ 21,300</td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light"><i class="ph ph-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="agent-avatar me-3 bg-light-danger text-danger">GO</div>
                                                <div>
                                                    <div class="fw-bold text-dark">Goibibo</div>
                                                    <div class="small text-muted">ID: #AG-012</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light-success text-success rounded-pill">Active</span>
                                        </td>
                                        <td><span class="fw-bold">456</span> <small class="text-muted">orders</small></td>
                                        <td class="fw-bold text-dark">₹ 1,50,452</td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light"><i class="ph ph-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="agent-avatar me-3 bg-light-primary text-primary">MM</div>
                                                <div>
                                                    <div class="fw-bold text-dark">Make My Trip</div>
                                                    <div class="small text-muted">ID: #AG-015</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span
                                                class="badge bg-light-secondary text-secondary rounded-pill">Inactive</span>
                                        </td>
                                        <td><span class="fw-bold">21</span> <small class="text-muted">orders</small></td>
                                        <td class="fw-bold text-dark">₹ 452</td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light"><i class="ph ph-eye"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
