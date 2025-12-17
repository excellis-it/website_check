<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none">

<head>
    <meta content="width=device-width,  initial-scale=1,  maximum-scale=1,  shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Login</title>
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

</head>

<body class="login-body">
    <div class="login-wrapper">
        <div class="login-left"></div>
        <div class="login-right"></div>

        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('admin_assets/img/logo.png') }}" alt="Excellis">
            </div>

            <h3 class="login-title">Login</h3>

            <form action="{{ route('admin.login.check') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>User Name</label>
                    <input type="text" class="form-control" name="email" placeholder="Enter User Name" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <div class="error text-danger small mt-1">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="password-box">
                        <input type="password" class="form-control" name="password" placeholder="Enter Password" value="{{ old('password') }}">

                        <span class="toggle-eye ph ph-eye-slash"></span>
                        @if ($errors->has('password'))
                            <div class="error text-danger small mt-1">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                </div>

                <div class="forgot-link">
                    <a href="{{ route('admin.forget.password.show') }}">Forgot Your Password?</a>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>

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
    document.querySelector('.toggle-eye').addEventListener('click', function() {
        const input = this.previousElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            this.classList.remove('ph-eye-slash');
            this.classList.add('ph-eye');
        } else {
            input.type = 'password';
            this.classList.add('ph-eye-slash');
            this.classList.remove('ph-eye');
        }
    });
</script>


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
<script>
    //
</script>

</html>
