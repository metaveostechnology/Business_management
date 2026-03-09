<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Business Management</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* Fallback for vite asset loading if not built */
        @import url('{{ asset('resources/css/app.css') }}');
    </style>
   
</head>
<body>
    <div class="layout">
        @if(Auth::guard('admin')->check())
        <aside class="sidebar">
            <div class="sidebar-logo">BizAdmin</div>
            <nav>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admins.index') }}" class="nav-link {{ request()->routeIs('admins.*') ? 'active' : '' }}">Administrators</a>
                <a href="{{ route('companies.create') }}" class="nav-link {{ request()->routeIs('companies.create') ? 'active' : '' }}">Create Company</a>
                
                <div style="margin-top: 2rem; border-top: 1px solid #334155; padding-top: 1rem;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; color: #ef4444;">Sign Out</button>
                    </form>
                </div>
            </nav>
        </aside>
        @endif

        <main class="main-content" style="{{ !Auth::guard('admin')->check() ? 'margin-left: 0;' : '' }}">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
