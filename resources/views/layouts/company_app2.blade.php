<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Company Portal') | Business Management</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --sidebar-bg: #1e293b;
            --sidebar-text: #f8fafc;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* Utility */
        .hidden { display: none !important; }
        .btn {
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn:hover { background-color: var(--primary-hover); }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.875rem; }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229, 0.1); }
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .alert-error { background-color: #fee2e2; color: #991b1b; }
        .alert-success { background-color: #dcfce3; color: #166534; }
        
        /* Layouts */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0; top: 0;
            box-sizing: border-box;
        }
        .sidebar-logo { font-size: 1.5rem; font-weight: 800; margin-bottom: 3rem; color: #818cf8; }
        .nav-link {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }
        .nav-link:hover, .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; }
        
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 2.5rem;
            box-sizing: border-box;
            width: calc(100% - 260px);
        }

        /* Card */
        .card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .header-title { font-size: 1.75rem; font-weight: 700; margin-top: 0; margin-bottom: 2rem; }
    </style>
    @stack('styles')
</head>
<body>
    @if(!isset($isAuthPage))
    <aside class="sidebar">
        <div class="sidebar-logo">Company Portal</div>
        <nav>
            <a href="{{ route('company.frontend.dashboard') }}" class="nav-link {{ request()->routeIs('company.frontend.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('company.frontend.profile') }}" class="nav-link {{ request()->routeIs('company.frontend.profile') ? 'active' : '' }}">My Profile</a>
            
            <div style="margin-top: 2rem; border-top: 1px solid #334155; padding-top: 1rem;">
                <button type="button" class="nav-link" id="btnLogout" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; color: #ef4444; font-size: 1rem;">Sign Out</button>
            </div>
        </nav>
    </aside>
    @endif

    <main class="main-content" style="{{ isset($isAuthPage) ? 'margin-left: 0; width: 100%; display: flex; align-items: center; justify-content: center; min-height: 100vh; background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);' : '' }}">
        @yield('content')
    </main>

    <script>
        // Common API fetching logic
        const apiBaseUrl = '/api';
        
        function getAuthToken() {
            return localStorage.getItem('company_token');
        }

        function setAuthToken(token) {
            localStorage.setItem('company_token', token);
        }

        function clearAuthToken() {
            localStorage.removeItem('company_token');
            localStorage.removeItem('company_slug');
        }

        // Middleware equivalent for frontend
        @if(!isset($isAuthPage))
            document.addEventListener('DOMContentLoaded', () => {
                if (!getAuthToken()) {
                    window.location.href = "{{ route('company.frontend.login') }}";
                }
            });
            
            // Logout logic
            document.getElementById('btnLogout')?.addEventListener('click', async () => {
                try {
                    await fetch(apiBaseUrl + '/company/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + getAuthToken(),
                            'Accept': 'application/json'
                        }
                    });
                } finally {
                    clearAuthToken();
                    window.location.href = "{{ route('company.frontend.login') }}";
                }
            });
        @else
            document.addEventListener('DOMContentLoaded', () => {
                if (getAuthToken()) {
                    window.location.href = "{{ route('company.frontend.dashboard') }}";
                }
            });
        @endif
        
        async function apiRequest(endpoint, method = 'GET', body = null) {
            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            };
            
            const token = getAuthToken();
            if (token) {
                headers['Authorization'] = 'Bearer ' + token;
            }

            const options = { method, headers };
            if (body) {
                options.body = JSON.stringify(body);
            }

            const response = await fetch(apiBaseUrl + endpoint, options);
            
            if (response.status === 401) {
                clearAuthToken();
                window.location.href = "{{ route('company.frontend.login') }}";
                throw new Error('Unauthorized');
            }
            
            const data = await response.json();
            
            if (!response.ok) {
                throw { status: response.status, data };
            }
            
            return data;
        }
    </script>
    @stack('scripts')
</body>
</html>
