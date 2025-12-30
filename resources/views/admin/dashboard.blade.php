<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>EcomPanel - Dashboard</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('admin/assets/favicon.ico') }}" type="image/x-icon" />

    <!-- CSS Local -->
    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet" />

    <!-- FontAwesome -->
    <link href="{{ asset('admin/assets/icons/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/icons/fontawesome/css/brands.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/icons/fontawesome/css/solid.min.css') }}" rel="stylesheet" />

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    <!-- Stack for page-specific styles -->
    @stack('styles')
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <!-- Main Wrapper -->
    <div id="main-wrapper" class="d-flex">

        <!-- Sidebar -->
        @include('layouts.admin.sidebar')

        <!-- Header -->
        @include('layouts.admin.header')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')

            <!-- Footer -->
            @include('layouts.admin.footer')
        </div>
    </div>

    <!-- JS Local -->
    <script src="{{ asset('admin/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/chart.js') }}"></script>
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>

    <!-- Stack for page-specific scripts -->
    @stack('scripts')
</body>
</html>
