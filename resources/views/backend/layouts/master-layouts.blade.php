<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Manage your hospital and clinic operations efficiently with Doctorly, a powerful Laravel-based system for healthcare institutions. Streamline patient records, appointments, billing, and more.">
    <meta name="keywords" content="Doctorly, Hospital Management, Clinic Management, Laravel System, Healthcare Software">
    <meta name="author" content="Themesbrand">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/') . '/' . AppSetting('favicon') }}">
    @include('backend.layouts.head')
</head>

<body data-sidebar="dark" data-topbar="light" data-layout="vertical">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>
    <!-- Begin page -->

    <div id="layout-wrapper">
        @include('backend.layouts.top-hor')
        @include('backend.layouts.sidebar')

        {{-- @include('layouts.hor-menu') --}}
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <!-- Start content -->
                <div class="container-fluid">
                    @yield('content')
                </div> <!-- content -->
            </div>
            @include('backend.layouts.footer')
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    @include('backend.layouts.right-sidebar')
    <!-- END Right Sidebar -->

    @include('backend.layouts.footer-script')
</body>

</html>
