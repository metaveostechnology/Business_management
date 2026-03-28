<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employee Portal') | Business Management</title>
    <style>
        :root {
            --primary: #4F46E5; /* Indigo color for employee portal */
            --primary-hover: #4338CA;
            --bg-color: #f3f4f6;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --sidebar-bg: #111827;
            --sidebar-text: #f9fafb;
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
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .alert-error { background-color: #fee2e2; color: #991b1b; }
        .alert-success { background-color: #e0e7ff; color: #3730a3; }
        
        .main-content {
            flex: 1;
            padding: 2.5rem;
            box-sizing: border-box;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #4F46E5 0%, #312E81 100%);
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
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
            return localStorage.getItem('branch_user_token');
        }

        function setAuthToken(token) {
            localStorage.setItem('branch_user_token', token);
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (getAuthToken()) {
                window.location.href = "{{ route('employee_self.dashboard') }}";
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
