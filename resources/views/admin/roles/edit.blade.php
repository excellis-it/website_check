@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Edit Role
@endsection
@push('styles')
    <style>
        .permission-group {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .permission-item {
            padding: 8px 0;
        }

        .permission-item label {
            cursor: pointer;
            margin-bottom: 0;
        }
    </style>
@endpush
@section('head')
    Edit Role
@endsection

@section('content')
    <div class="main-content">
        <div class="inner_page">
            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card box_shadow p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h4 class="mb-0">Role Information</h4>
                            <a href="{{ route('roles.index') }}" class="btn-3 text-decoration-none"
                                style="padding: 10px 15px; display: inline-flex; align-items-center;">
                                <i class="ph ph-arrow-left me-2"></i> Back
                            </a>
                        </div>

                        <form action="{{ route('roles.update', $role->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Role Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            oninput="this.value = this.value.toUpperCase()"
                                            value="{{ old('name', $role->name) }}" placeholder="Enter Role Name">
                                        @if ($errors->has('name'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div> --}}

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Assign Permissions</label>
                                        <div class="permission-group">
                                            <div class="row">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-md-4 permission-item">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="permissions[]" value="{{ $permission->id }}"
                                                                id="permission_{{ $permission->id }}"
                                                                {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="permission_{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @if ($errors->has('permissions'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('permissions') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn-3 px-5 py-2">Update Role</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
