@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Role Details
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
    </style>
@endpush
@section('head')
    Role Details
@endsection

@section('content')
    <div class="main-content">
        <div class="inner_page">
            <div class="mb-3">
                <a href="{{ route('roles.index') }}" class="btn-3 text-decoration-none"
                    style="padding: 10px 15px; display: inline-flex; align-items-center;">
                    <i class="ph ph-arrow-left me-2"></i> Back to List
                </a>
            </div>

            <div class="card box_shadow p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h4 class="mb-0">{{ $role->name }}</h4>
                    @can('edit-roles')
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm">
                            <i class="ph ph-pencil-simple"></i> Edit
                        </a>
                    @endcan
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="info-card">
                            <div class="info-label">Role Name</div>
                            <div class="info-value">{{ $role->name }}</div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">Permissions ({{ $role->permissions->count() }})</div>
                            <div class="info-value">
                                @if ($role->permissions->count() > 0)
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        @foreach ($role->permissions as $permission)
                                            <span class="badge bg-secondary" style="padding: 8px 12px; font-size: 13px;">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No permissions assigned</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-label">Users with this Role ({{ $users->total() }})</div>
                            <div class="info-value">
                                @if ($users->count() > 0)
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>
                                                            @if ($user->status)
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-danger">Inactive</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if ($users->hasPages())
                                        <div class="d-flex justify-content-center mt-3">
                                            {!! $users->links() !!}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted">No users have this role</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
