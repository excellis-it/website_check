<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="width=device-width,  initial-scale=1,  maximum-scale=1,  shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @stack('styles')
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body class="light light-sidebar theme-white ">
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <!--header-->
            @include('admin.includes.header')
            <section class="section_breadcrumb bred-des by-me">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="page-title m-b-0">@yield('head')</h4>
                    </div>
                    <div class="col-md-6 create-button">
                        @yield('create_button')
                    </div>
                </div>
            </section>
            <!--end header-->
            <!--sidebar-wrapper-->
            @include('admin.includes.sidebar')
            <!--end sidebar-wrapper-->
            <!--page-wrapper-->
            @yield('content')

            <!--end page-wrapper-->
            <!--footer -->
            @include('admin.includes.footer')

            <!-- end footer -->
        </div>
    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    {{-- trippy cdn link --}}
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://unpkg.com/tippy.js@5"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- trippy --}}
    <script>
        tippy('[data-tippy-content]', {
            allowHTML: true,
            placement: 'bottom',
            theme: 'light-theme',
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

    @stack('scripts')
</body>

</html>
