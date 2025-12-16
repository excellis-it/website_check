@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Profile
@endsection
@push('styles')
@endpush
@section('head')
    Profile
@endsection
@section('content')
    <div class="main-content">
        <div class="inner_page">
            <div class="row">
                <!-- Profile Card -->
                <div class="col-xl-4 col-lg-5 col-md-12 mb-4">
                    <div class="card box_shadow p-4 text-center h-100">
                        <div class="profile_img_sec position-relative d-inline-block mx-auto mb-4">
                            <div class="profile_container" style="position: relative; display: inline-block;">
                                @if (Auth::user()->profile_picture)
                                    <img src="{{ Storage::url(Auth::user()->profile_picture) }}" alt="Profile Picture"
                                        id="blah" class="rounded-circle img-thumbnail"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('admin_assets/img/profile_dummy.png') }}" alt="Profile Picture"
                                        id="blah" class="rounded-circle img-thumbnail"
                                        style="width: 150px; height: 150px; object-fit: cover;" />
                                @endif
                                <div class="profile_edit_icon" style="position: absolute; bottom: 10px; right: 10px;">
                                    <label for="edit_profile" class="btn btn-sm btn-primary rounded-circle"
                                        style="width: 35px; height: 35px; line-height: 25px; cursor: pointer;">
                                        <i class="ph ph-pencil-simple text-white"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                        <p class="text-muted mb-3">{{ Auth::user()->email }}</p>

                        @if (Auth::user()->ecclesia)
                            <div class="mt-2">
                                <span class="badge bg-light text-dark p-2">
                                    <i class="ph ph-house me-1"></i> Ecclesia: {{ Auth::user()->ecclesia->name }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Edit Details Card -->
                <div class="col-xl-8 col-lg-7 col-md-12 mb-4">
                    <div class="card box_shadow p-4 h-100">
                        <h5 class="card-title mb-4 border-bottom pb-2">Profile Details</h5>

                        <form action="{{ route('admin.profile.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="edit_profile" onchange="readURL(this);" name="profile_picture"
                                class="d-none" />

                            @if ($errors->has('profile_picture'))
                                <div class="alert alert-danger">{{ $errors->first('profile_picture') }}</div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ Auth::user()->name }}" placeholder="Enter Full Name">
                                        @if ($errors->has('name'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                            value="{{ Auth::user()->phone }}" placeholder="Enter Phone Number">
                                        @if ($errors->has('phone_number'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('phone_number') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ Auth::user()->email }}" placeholder="Enter Email Address">
                                        @if ($errors->has('email'))
                                            <div class="error text-danger small mt-1">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 mt-4 text-end create-button">
                                    <button type="submit" class="btn-3 px-4">Save Changes</button>
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
