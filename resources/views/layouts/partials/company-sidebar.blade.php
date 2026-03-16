<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('company.frontend.dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('appadmin/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('appadmin/assets/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('company.frontend.dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('appadmin/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('appadmin/assets/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="sidebar-user-dropdown"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user"
                    src="{{ asset('appadmin/assets/images/users/avatar-1.jpg') }}"
                    alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text" data-company-name>Company User</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text">
                        <i class="ri ri-circle-fill fs-10 text-success align-baseline"></i>
                        <span class="align-middle">Online</span>
                    </span>
                </span>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <h6 class="dropdown-header">Welcome <span data-company-name>User</span>!</h6>
            <a class="dropdown-item" href="{{ route('company.frontend.profile') }}">
                <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
                <span class="align-middle">My Profile</span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="javascript:void(0);" onclick="companyLogout()">
                <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                <span class="align-middle" data-key="t-logout">Logout</span>
            </a>
        </div>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('company.frontend.dashboard') ? 'active' : '' }}"
                        href="{{ route('company.frontend.dashboard') }}">
                        <i class="ri-dashboard-2-line"></i>
                        <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('company.frontend.branches') ? 'active' : '' }}"
                        href="{{ route('company.frontend.branches') }}">
                        <i class="ri-git-branch-line"></i>
                        <span data-key="t-branches">Branch Management</span>
                    </a>
                </li>





                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('company.frontend.branch-users') ? 'active' : '' }}"
                        href="{{ route('company.frontend.branch-users') }}">
                        <i class="ri-team-line"></i>
                        <span data-key="t-branch-users">Employees</span>
                    </a>
                </li>

                {{-- Add more company nav items here as the portal grows --}}

            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
