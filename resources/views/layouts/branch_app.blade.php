<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Branch Portal') - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Branch Management Portal" name="description" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('appadmin/assets/images/favicon.ico') }}">

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
        @include('layouts.partials.branch-header')
        @include('layouts.partials.branch-sidebar')

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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Branch Portal</a></li>
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

    <!-- App js -->
    <script src="{{ asset('appadmin/assets/js/app.js') }}"></script>

    {{-- ── Global API helper + Auth guard ─────────────────────────────── --}}
    <script>
        const API_BASE = '/api';

        function getAuthToken() {
            return localStorage.getItem('branch_admin_token');
        }

        function getBranchUser() {
            const raw = localStorage.getItem('branch_user');
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
                if (res.status === 401) {
                    branchLogout();
                }
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
                window.location.href = '{{ route('branch.login') }}';
            }

            document.addEventListener('DOMContentLoaded', () => {
                const user = getBranchUser();
                const displayName = user?.name || user?.email || 'Branch Admin';
                document.querySelectorAll('[data-branch-name]').forEach(el => el.textContent = displayName);
            });
        })();

        async function branchLogout() {
            try {
                await apiRequest('/branch-admin/logout', 'POST');
            } catch (_) { }
            localStorage.removeItem('branch_admin_token');
            localStorage.removeItem('branch_user');
            window.location.href = '{{ route('branch.login') }}';
        }
    </script>

    @stack('scripts')
</body>
</html>
