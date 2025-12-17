@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Create Role
@endsection
@push('styles')
    <style>
        .permission-card {
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
            height: 100%;
            background: #fff;
        }

        .permission-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
            border-color: #e2e8f0;
        }

        .permission-header {
            background-color: #f8fafc;
            padding: 15px 20px;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .permission-body {
            padding: 20px;
        }

        .group-title {
            font-weight: 700;
            color: #475569;
            margin: 0;
            font-size: 1rem;
            text-transform: capitalize;
            letter-spacing: 0.5px;
        }

        /* Custom Toggle Switch */
        .custom-switch {
            padding-left: 0;
            display: flex;
            align-items: center;
        }

        .custom-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
            margin-right: 0.75em;
            margin-left: 0;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%28255, 255, 255, 0.25%29'/%3e%3c/svg%3e");
            background-position: left center;
            border-radius: 2em;
            transition: background-position .15s ease-in-out;
            cursor: pointer;
        }

        .custom-switch .form-check-input:checked {
            background-position: right center;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }

        .perm-label {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
            cursor: pointer;
            text-transform: capitalize;
        }

        .perm-item {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .input-group-text {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #64748b;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }
    </style>
@endpush
@section('head')
    Create Role
@endsection

@section('content')
    <div class="main-content">
        <div class="container-fluid px-4 pt-4">
            <form action="{{ route('roles.store') }}" method="post" id="roleForm">
                @csrf

                <!-- Header & Actions -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1 fw-bold text-dark">Create New Role</h4>
                        <p class="text-muted mb-0 small">Define role details and assign permissions.</p>
                    </div>
                    <div>
                        <a href="{{ route('roles.index') }}" class="btn btn-light border me-2 fw-medium">
                            <i class="ph ph-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary fw-medium px-4">
                            <i class="ph ph-check me-1"></i> Create Role
                        </button>
                    </div>
                </div>

                <!-- Role Name Input -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold small text-uppercase text-muted">Role Name
                                    <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ph ph-shield-check fs-5"></i></span>
                                    <input type="text" class="form-control form-control-lg" id="name" name="name"
                                        oninput="this.value = this.value.toUpperCase()" value="{{ old('name') }}"
                                        placeholder="E.g., MANAGER" required autofocus>
                                </div>
                                <div class="form-text mt-2">The role name will be automatically converted to uppercase.
                                </div>
                                @if ($errors->has('name'))
                                    <div class="text-danger small mt-1"><i class="ph ph-warning me-1"></i>
                                        {{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Section -->
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <h5 class="fw-bold text-dark mb-0">Assign Permissions</h5>
                    <div class="form-check form-switch custom-switch">
                        <input class="form-check-input" type="checkbox" id="selectAllGlobal">
                        <label class="form-check-label fw-bold text-primary" for="selectAllGlobal"
                            style="cursor:pointer">Select All Permissions</label>
                    </div>
                </div>

                <div class="row g-4 pb-5">
                    @foreach ($groupedPermissions as $group => $permissions)
                        <div class="col-xl-4 col-md-6">
                            <div class="permission-card">
                                <div class="permission-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ph ph-folder-notch text-primary"></i>
                                        <span class="group-title">{{ $group }}</span>
                                    </div>
                                    <div class="form-check form-switch custom-switch mb-0">
                                        <input class="form-check-input group-select-all" type="checkbox"
                                            data-group="{{ Str::slug($group) }}" id="group_{{ Str::slug($group) }}">
                                    </div>
                                </div>
                                <div class="permission-body">
                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            @php
                                                // Extract cleaner name: "view-users" -> "View"
                                                $parts = explode('-', $permission->name);
                                                $displayName = ucfirst($parts[0]);
                                                if (count($parts) > 2) {
                                                    // Handle multi-word actions if needed, though usually standard
                                                    $displayName = ucfirst(
                                                        implode(' ', array_slice($parts, 0, count($parts) - 1)),
                                                    );
                                                }
                                            @endphp
                                            <div class="col-6 perm-item">
                                                <div class="form-check form-switch custom-switch w-100">
                                                    <input
                                                        class="form-check-input perm-checkbox group-{{ Str::slug($group) }}"
                                                        type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                        id="permission_{{ $permission->id }}"
                                                        {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label perm-label text-truncate w-100"
                                                        for="permission_{{ $permission->id }}"
                                                        title="{{ $permission->name }}">
                                                        {{ $displayName }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Global Select All
            $('#selectAllGlobal').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.perm-checkbox').prop('checked', isChecked);
                $('.group-select-all').prop('checked', isChecked);
            });

            // Group Select All
            $('.group-select-all').on('change', function() {
                var group = $(this).data('group');
                var isChecked = $(this).is(':checked');
                $('.group-' + group).prop('checked', isChecked);
                updateGlobalCheckbox();
            });

            // Individual Checkbox Change
            $('.perm-checkbox').on('change', function() {
                var groupClass = $(this).attr('class').split(' ').find(cls => cls.startsWith('group-'));
                var group = groupClass.replace('group-', '');

                // Update Group Checkbox
                var allGroupChecked = true;
                $('.group-' + group).each(function() {
                    if (!$(this).is(':checked')) {
                        allGroupChecked = false;
                        return false;
                    }
                });
                $('#group_' + group).prop('checked', allGroupChecked);

                updateGlobalCheckbox();
            });

            // Initial Check (on load/validation error)
            function updateState() {
                $('.group-select-all').each(function() {
                    var group = $(this).data('group');
                    var allGroupChecked = true;
                    var hasCheckbox = false;
                    $('.group-' + group).each(function() {
                        hasCheckbox = true;
                        if (!$(this).is(':checked')) allGroupChecked = false;
                    });
                    if (hasCheckbox) $(this).prop('checked', allGroupChecked);
                });
                updateGlobalCheckbox();
            }

            function updateGlobalCheckbox() {
                var allChecked = true;
                $('.perm-checkbox').each(function() {
                    if (!$(this).is(':checked')) {
                        allChecked = false;
                        return false;
                    }
                });
                $('#selectAllGlobal').prop('checked', allChecked);
            }

            updateState();
        });
    </script>
@endpush
