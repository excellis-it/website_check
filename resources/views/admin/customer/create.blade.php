@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Create Customer
@endsection
@push('styles')
@endpush
@section('head')
    Create Customer
@endsection

@section('content')
    <div class="main-content">
        <div class="inner_page">
            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card box_shadow p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h4 class="mb-0">Login Information</h4>
                            <a href="{{ route('customers.index') }}" class="btn-3 text-decoration-none"
                                style="padding: 10px 15px; display: inline-flex; align-items: center;">
                                <i class="ph ph-arrow-left me-2"></i> Back
                            </a>
                        </div>

                        <form action="{{ route('customers.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" placeholder="Enter Full Name">
                                        @if ($errors->has('name'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control form-select">
                                            <option value="">Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('status') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email') }}" placeholder="Enter Email Address">
                                        @if ($errors->has('email'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Mobile <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="{{ old('phone') }}" placeholder="Enter Mobile Number">
                                        @if ($errors->has('phone'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Enter Password">
                                            <button class="btn btn-outline-secondary" type="button" id="eye-button-1"
                                                style="border-color: #ced4da;">
                                                <i class="ph ph-eye-slash"></i>
                                            </button>
                                        </div>
                                        @if ($errors->has('password'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="confirm_password" class="form-label">Confirm Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_password"
                                                name="confirm_password" placeholder="Confirm Password">
                                            <button class="btn btn-outline-secondary" type="button" id="eye-button-2"
                                                style="border-color: #ced4da;">
                                                <i class="ph ph-eye-slash"></i>
                                            </button>
                                        </div>
                                        @if ($errors->has('confirm_password'))
                                            <div class="error text-danger small mt-1">
                                                {{ $errors->first('confirm_password') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn-3 px-5 py-2">Create Customer</button>
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
    <script>
        $(document).ready(function() {
            $('#eye-button-1').click(function() {
                $('#password').attr('type', $('#password').is(':password') ? 'text' : 'password');
                $(this).find('i').toggleClass('ph-eye-slash ph-eye');
            });
            $('#eye-button-2').click(function() {
                $('#confirm_password').attr('type', $('#confirm_password').is(':password') ? 'text' :
                    'password');
                $(this).find('i').toggleClass('ph-eye-slash ph-eye');
            });
        });
    </script>
@endpush
