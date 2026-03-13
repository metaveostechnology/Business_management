<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Company Portal') - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Company Management Portal" name="description" />

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
        @include('layouts.partials.company-header')
        @include('layouts.partials.company-sidebar')

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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Company Portal</a></li>
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

    {{-- ── Global API helper + Auth guard ─────────────────────────────── --}}
    <script>
        // ── Helpers ──────────────────────────────────────────────────────────
        const API_BASE = '/api';

        function getAuthToken() {
            return localStorage.getItem('company_token');
        }

        function getCompanyUser() {
            const raw = localStorage.getItem('company_user');
            try { return raw ? JSON.parse(raw) : null; } catch { return null; }
        }

        async function apiRequest(endpoint, method = 'GET', body = null) {
            const token = getAuthToken();
            const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const options = { method, headers };
            if (body && method !== 'GET') options.body = JSON.stringify(body);

            const res = await fetch(API_BASE + endpoint, options);
            const data = await res.json();

            if (!res.ok) {
                const err = new Error(data.message || 'API Error');
                err.data = data;
                err.status = res.status;
                throw err;
            }
            return data;
        }

        // ── Auth guard: redirect to login if no token ─────────────────────
        (function () {
            if (!getAuthToken()) {
                window.location.href = '{{ route('company.frontend.login') }}';
            }

            // Populate any elements with [data-company-name] or [data-company-user]
            document.addEventListener('DOMContentLoaded', () => {
                const user = getCompanyUser();
                const displayName = user?.name || user?.email || 'Company User';
                document.querySelectorAll('[data-company-name]').forEach(el => el.textContent = displayName);

                if (user?.logo) {
                    const logoUrl = `/storage/${user.logo}`;
                    document.querySelectorAll('[data-company-logo]').forEach(el => {
                        el.src = logoUrl;
                    });
                }
            });
        })();

        // ── Logout helper (callable from inline onclick) ──────────────────
        async function companyLogout() {
            try {
                await apiRequest('/logout', 'POST');
            } catch (_) { /* ignore API errors on logout */ }
            localStorage.removeItem('company_token');
            localStorage.removeItem('company_user');
            localStorage.removeItem('company_slug');
            window.location.href = '{{ route('company.frontend.login') }}';
        }
    </script>

    @stack('scripts')
</body>
</html>
