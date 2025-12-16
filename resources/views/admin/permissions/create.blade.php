@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Create Permission
@endsection
@section('head')
    Create Permission
@endsection

@section('content')
    <div class="main-content">
        <div class="inner_page">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10 col-md-12">
                    <div class="card box_shadow p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h4 class="mb-0">Permission Information</h4>
                            <a href="{{ route('permissions.index') }}" class="btn-3 text-decoration-none"
                                style="padding: 10px 15px; display: inline-flex; align-items-center;">
                                <i class="ph ph-arrow-left me-2"></i> Back
                            </a>
                        </div>

                        <form action="{{ route('permissions.store') }}" method="post">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Permission Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" placeholder="e.g., create-user, edit-post">
                                        <small class="text-muted">Use lowercase with hyphens (e.g., create-user)</small>
                                        @if ($errors->has('name'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn-3 px-5 py-2">Create Permission</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
