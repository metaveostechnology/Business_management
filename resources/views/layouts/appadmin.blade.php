<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'App Admin Dashboard') - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('appadmin/assets/images/favicon.ico') }}">

    <!-- jsvectormap css -->
    <link href="{{ asset('appadmin/assets/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />

    <!--Swiper slider css-->
    <link href="{{ asset('appadmin/assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('appadmin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('appadmin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ asset('appadmin/assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('appadmin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('appadmin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('appadmin/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('appadmin/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    
    @stack('styles')
</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.partials.appadmin-header')
        @include('layouts.partials.appadmin-sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">@yield('page-title', 'Dashboard')</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">App Admin</a></li>
                                        <li class="breadcrumb-item active">@yield('breadcrumb', 'Dashboard')</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © {{ config('app.name') }}.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Metaveos Consultancy Pvt Ltd
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('appadmin/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('appadmin/assets/js/plugins.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('appadmin/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Vector map-->
    <script src="{{ asset('appadmin/assets/libs/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

    <!--Swiper slider js-->
    <script src="{{ asset('appadmin/assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('appadmin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('appadmin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('appadmin/assets/js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>