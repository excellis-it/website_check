@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Create Permission
@endsection
@section('head')
    Create Permission
@endsection

@section('content')
    <div class="main-content">
        <div class="container-fluid px-4 pt-4">

            <!-- Header & Actions -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">Create Permission</h4>
                    <p class="text-muted mb-0 small">Add a new system permission.</p>
                </div>
                <div>
                    <a href="{{ route('permissions.index') }}" class="btn btn-light border me-2 fw-medium">
                        <i class="ph ph-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" form="permissionForm" class="btn btn-primary fw-medium px-4">
                        <i class="ph ph-check me-1"></i> Create Permission
                    </button>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <form action="{{ route('permissions.store') }}" method="post" id="permissionForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="name"
                                        class="form-label fw-bold small text-uppercase text-muted">Permission Name <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ph ph-key fs-5"></i></span>
                                        <input type="text" class="form-control form-control-lg" id="name"
                                            name="name" value="{{ old('name') }}" placeholder="e.g., create-user"
                                            required autofocus>
                                    </div>
                                    <div class="form-text mt-2"><i class="ph ph-info me-1"></i> Use lowercase with hyphens
                                        (e.g., <code>view-dashboard</code>, <code>manage-settings</code>).</div>
                                    @if ($errors->has('name'))
                                        <div class="text-danger small mt-1"><i class="ph ph-warning me-1"></i>
                                            {{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
