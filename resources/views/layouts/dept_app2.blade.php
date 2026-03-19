<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Department Portal') | Business Management</title>
    <style>
        :root {
            --primary: #6366f1; /* Distinct indigo color for department */
            --primary-hover: #4f46e5;
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
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241, 0.1); }
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .alert-error { background-color: #fee2e2; color: #991b1b; }
        .alert-success { background-color: #dcfce3; color: #166534; }
        
        .main-content {
            flex: 1;
            padding: 2.5rem;
            box-sizing: border-box;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #6366f1 0%, #312e81 100%);
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            width: 100%;
            max-width: 420px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <main class="main-content">
        @yield('content')
    </main>

    <script>
        const apiBaseUrl = '/api';
        
        function getAuthToken() {
            return localStorage.getItem('dept_admin_token');
        }

        function setAuthToken(token) {
            localStorage.setItem('dept_admin_token', token);
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (getAuthToken()) {
                window.location.href = "{{ route('department.dashboard') }}";
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
