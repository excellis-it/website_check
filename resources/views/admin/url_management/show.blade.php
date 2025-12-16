@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | URL Details
@endsection
@push('styles')
    <style>
        .info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #212529;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-down {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-inactive {
            background-color: #fff3cd;
            color: #856404;
        }

        .uptime-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
        }

        .uptime-percentage {
            font-size: 48px;
            font-weight: 700;
            margin: 10px 0;
        }

        .log-status-up {
            color: #28a745;
        }

        .log-status-down {
            color: #dc3545;
        }
    </style>
@endpush
@section('head')
    URL Details
@endsection

@section('content')
    <div class="main-content">
        <div class="inner_page">
            <div class="mb-3">
                <a href="{{ route('url-management.index') }}" class="btn-3 text-decoration-none"
                    style="padding: 10px 15px; display: inline-flex; align-items: center;">
                    <i class="ph ph-arrow-left me-2"></i> Back to List
                </a>
            </div>

            <!-- URL Information Card -->
            <div class="card box_shadow p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h4 class="mb-0">{{ $url->name }}</h4>
                    <div>
                        @can('update', $url)
                            <a href="{{ route('url-management.edit', $url->encrypted_id) }}"
                                class="btn btn-primary btn-sm me-2">
                                <i class="ph ph-pencil-simple"></i> Edit
                            </a>
                        @endcan
                        <button class="btn btn-info btn-sm check-url-btn" data-id="{{ $url->encrypted_id }}">
                            <i class="ph ph-arrow-clockwise"></i> Check Now
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="info-card">
                            <div class="info-label">URL</div>
                            <div class="info-value">
                                <a href="{{ $url->url }}" target="_blank" class="text-primary text-decoration-none">
                                    {{ $url->url }} <i class="ph ph-arrow-square-out"></i>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-label">Status</div>
                                    <div class="info-value">
                                        <span class="status-badge status-{{ $url->status }}">
                                            {{ ucfirst($url->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-label">Response Time</div>
                                    <div class="info-value">
                                        @if ($url->response_time)
                                            <span class="badge bg-info">{{ $url->response_time }}ms</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-label">Last Checked</div>
                                    <div class="info-value">
                                        @if ($url->last_checked_at)
                                            {{ $url->last_checked_at->format('M d, Y h:i A') }}
                                            <small
                                                class="text-muted d-block">{{ $url->last_checked_at->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">Assigned Users</div>
                            <div class="info-value">
                                @if ($url->assignedUsers->count() > 0)
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        @foreach ($url->assignedUsers as $user)
                                            <span class="badge bg-secondary" style="padding: 8px 12px; font-size: 13px;">
                                                {{ $user->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No users assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="uptime-card">
                            <div>Uptime (Last 30 Days)</div>
                            <div class="uptime-percentage">{{ $uptimePercentage }}%</div>
                            <small>Based on monitoring data</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Summary -->
            <div class="card box_shadow p-4 mb-4">
                <h5 class="mb-4">Daily Summary (Last 30 Days)</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total Checks</th>
                                <th>Up</th>
                                <th>Down</th>
                                <th>Avg Response Time</th>
                                <th>Uptime %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailySummary as $summary)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($summary->log_date)->format('M d, Y') }}</td>
                                    <td>{{ $summary->total_checks }}</td>
                                    <td><span class="badge bg-success">{{ $summary->up_count }}</span></td>
                                    <td><span class="badge bg-danger">{{ $summary->down_count }}</span></td>
                                    <td>
                                        @if ($summary->avg_response_time)
                                            {{ round($summary->avg_response_time) }}ms
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $summary->total_checks > 0 ? round(($summary->up_count / $summary->total_checks) * 100, 2) : 0 }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Logs -->
            <div class="card box_shadow p-4">
                <h5 class="mb-4">Activity Logs</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Checked At</th>
                                <th>Status</th>
                                <th>Response Time</th>
                                <th>Status Code</th>
                                <th>Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activityLogs as $log)
                                <tr>
                                    <td>{{ $log->checked_at->format('M d, Y h:i:s A') }}</td>
                                    <td>
                                        @if ($log->status === 'up')
                                            <i class="ph ph-check-circle log-status-up"></i>
                                            <span class="log-status-up">Up</span>
                                        @else
                                            <i class="ph ph-x-circle log-status-down"></i>
                                            <span class="log-status-down">Down</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($log->response_time)
                                            {{ $log->response_time }}ms
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($log->status_code)
                                            <span
                                                class="badge {{ $log->status_code >= 200 && $log->status_code < 400 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $log->status_code }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($log->error_message)
                                            <small class="text-danger">{{ Str::limit($log->error_message, 50) }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No activity logs available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($activityLogs->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {!! $activityLogs->links() !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Manual URL check
        $(document).on('click', '.check-url-btn', function(e) {
            e.preventDefault();
            var btn = $(this);
            var encryptedId = btn.data('id');
            var icon = btn.find('i');

            // Show loading state
            icon.removeClass('ph-arrow-clockwise').addClass('ph-spinner ph-spin');
            btn.prop('disabled', true);

            $.ajax({
                type: "POST",
                url: '/admin/url-management/' + encryptedId + '/check',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    icon.removeClass('ph-spinner ph-spin').addClass('ph-arrow-clockwise');
                    btn.prop('disabled', false);

                    swal('Success', 'URL checked successfully! Refreshing page...', 'success').then(
                    () => {
                            location.reload();
                        });
                },
                error: function() {
                    icon.removeClass('ph-spinner ph-spin').addClass('ph-arrow-clockwise');
                    btn.prop('disabled', false);
                    swal('Error', 'Failed to check URL', 'error');
                }
            });
        });
    </script>
@endpush
