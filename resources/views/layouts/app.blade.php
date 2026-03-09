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
        <aside class="sidebar">
            <div class="sidebar-logo">BizAdmin</div>
            <nav>
                <a href="{{ route('admins.index') }}" class="nav-link {{ request()->routeIs('admins.*') ? 'active' : '' }}">Administrators</a>
                <a href="{{ route('companies.create') }}" class="nav-link {{ request()->routeIs('companies.create') ? 'active' : '' }}">Create Company</a>
            </nav>
        </aside>

        <main class="main-content">
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
