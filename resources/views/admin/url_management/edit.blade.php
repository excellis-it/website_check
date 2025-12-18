@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Edit URL
@endsection
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple {
            border-color: #ced4da;
            min-height: 38px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
@endpush
@section('head')
    Edit URL
@endsection

@section('content')
    <div class="main-content">
        <div class="inner_page">
            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card box_shadow p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h4 class="mb-0">URL Information</h4>
                            <a href="{{ route('url-management.index') }}" class="btn-3 text-decoration-none"
                                style="padding: 10px 15px; display: inline-flex; align-items-center;">
                                <i class="ph ph-arrow-left me-2"></i> Back
                            </a>
                        </div>

                        <form action="{{ route('url-management.update', $url->encrypted_id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            oninput="this.value = this.value.toUpperCase()"
                                            value="{{ old('name', $url->name) }}" placeholder="Enter URL Name">
                                        @if ($errors->has('name'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="url" class="form-label">URL <span
                                                class="text-danger">*</span></label>
                                        <input type="url" class="form-control" id="url" name="url"
                                            value="{{ old('url', $url->url) }}" placeholder="https://example.com">
                                        @if ($errors->has('url'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('url') }}</div>
                                        @endif
                                    </div>
                                </div>
                                @if (auth()->user()->hasRole('ADMIN'))
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="assigned_users" class="form-label">Assign to Users</label>
                                            <select name="assigned_users[]" id="assigned_users" class="form-control select2"
                                                multiple>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ in_array($user->id, old('assigned_users', $assignedUserIds)) ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Select multiple users who can view this
                                                URL</small>
                                            @if ($errors->has('assigned_users'))
                                                <div class="error text-danger small mt-1">
                                                    {{ $errors->first('assigned_users') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn-3 px-5 py-2">Update URL</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select users",
                allowClear: true
            });
        });
    </script>
@endpush
