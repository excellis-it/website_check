<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="width=device-width,  initial-scale=1,  maximum-scale=1,  shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Forgot Password - {{ env('APP_NAME') }} Admin Panel</title>
    <link rel="stylesheet" href="{{ asset('admin_assets/bootstrap-5.3/css/bootstrap.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/phosphor-icons"></script>
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css" /> -->
    <link rel="stylesheet" href="{{ asset('admin_assets/css/app.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin_assets/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin_assets/css/style.css') }}" />

    <link rel="stylesheet" href="{{ asset('admin_assets/css/morris.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .form-gap {
            padding-top: 70px;
        }
    </style>
</head>

<body class="login-body">

    <div class="login-wrapper">
        <!-- Split background -->
        <div class="login-left"></div>
        <div class="login-right"></div>

        <!-- Forgot Password Card -->
        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('admin_assets/img/logo.png') }}" alt="Excellis">
            </div>

            <h3 class="login-title">Forgot Password</h3>
            <p class="login-subtitle">You can reset your password here</p>

            <form action="{{ route('admin.forget.password') }}" method="POST" autocomplete="off">
                @csrf

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email"
                           class="form-control"
                           name="email"
                           placeholder="Enter your email"
                           value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    Reset Password
                </button>

                <div class="back-login">
                    <a href="{{ route('admin.login') }}">← Back to Login</a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="copyright">
            © 2025 Excellis IT Private Limited
        </div>
    </div>

</body>

<script src="{{ asset('admin_assets/js/jquery-3.4.1.min.js') }}"></script>
<!-- <script src="js/jquery.min.js"></script> -->
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> -->
<!-- <script src="js/bootstrap.min.js" async=""></script> -->
<script src="{{ asset('admin_assets/bootstrap-5.3/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/raphael-min.js') }}"></script>
<script src="{{ asset('admin_assets/js/morris.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/Chart.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/custom.js') }}" async=""></script>
<script src="{{ asset('admin_assets/js/app.min.js') }}"></script>
<script src="{{ asset('admin_assets/js/scripts.js') }}" async=""></script>
<script src="{{ asset('admin_assets/js/jquery-ui.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<script>
    @if (Session::has('message'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.success("{{ session('message') }}");
    @endif

    @if (Session::has('error'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.error("{{ session('error') }}");
    @endif

    @if (Session::has('info'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.info("{{ session('info') }}");
    @endif

    @if (Session::has('warning'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.warning("{{ session('warning') }}");
    @endif
</script>

</html>
