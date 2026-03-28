<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employee Self-Service') | Business Management</title>
    <style>
        :root {
            --primary: #4F46E5; /* Indigo */
            --primary-hover: #4338CA;
            --bg-color: #f3f4f6;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --navbar-bg: #ffffff;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: var(--navbar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .hidden { display: none !important; }

        .btn {
            background-color: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            background-color: #f9fafb;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .table th, .table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.875rem;
            text-transform: uppercase;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
        }
    </style>
    @stack('styles')
</head>
<body>
    <header class="navbar">
        <a href="#" class="navbar-brand">Employee Portal</a>
        <div class="navbar-user">
            <span id="user-name" class="font-medium text-sm">Loading...</span>
            <button onclick="employeeLogout()" class="btn btn-outline" id="btnLogout">Logout</button>
        </div>
    </header>

    <main class="main-content">
        <div class="flex justify-between items-center mb-6">
            <h1 style="font-size: 1.5rem; font-weight: 600; margin: 0 0 1rem 0;">@yield('page-title', 'Dashboard')</h1>
        </div>
        
        @yield('content')
    </main>

    <script>
        const apiBaseUrl = '/api';
        
        function getAuthToken() {
            return localStorage.getItem('branch_user_token');
        }

        function getEmployeeUser() {
            const raw = localStorage.getItem('branch_user');
            try { return raw ? JSON.parse(raw) : null; } catch { return null; }
        }

        // ── Auth guard ──────────────────────────────────────────────────────────
        if (!getAuthToken()) {
            window.location.href = "{{ route('employee_self.login') }}";
        }

        document.addEventListener('DOMContentLoaded', () => {
            const user = getEmployeeUser();
            if(user) {
                document.getElementById('user-name').innerText = user.name || user.email || 'Employee';
            }
        });

        async function fetchWithAuth(endpoint, method = 'GET', body = null) {
            const token = getAuthToken();
            const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const options = { method, headers };
            if (body && method !== 'GET') options.body = JSON.stringify(body);

            const res = await fetch(apiBaseUrl + endpoint, options);
            const data = await res.json();

            if (!res.ok) {
                if (res.status === 401) {
                    employeeLogout(false);
                }
                throw new Error(data.message || 'API Error');
            }
            return data;
        }

        async function employeeLogout(callApi = true) {
            const btn = document.getElementById('btnLogout');
            if(btn) btn.innerText = 'Logging out...';

            if(callApi) {
                try {
                    await fetchWithAuth('/employee/logout', 'POST');
                } catch (e) {
                    console.error('Logout error UI');
                }
            }
            
            localStorage.removeItem('branch_user_token');
            localStorage.removeItem('branch_user');
            window.location.href = "{{ route('employee_self.login') }}";
        }
    </script>
    @stack('scripts')
</body>
</html>
