<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Employee Login | Nexus Enterprise</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1152d4",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .hidden { display: none !important; }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 antialiased">
<div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-slate-200 dark:border-slate-800 px-6 lg:px-40 py-4 bg-white dark:bg-slate-900/50 backdrop-blur-sm sticky top-0 z-50">
            <div class="flex items-center gap-3">
                <div class="size-8 bg-primary rounded flex items-center justify-center text-white">
                    <span class="material-symbols-outlined !text-2xl">cloud_done</span>
                </div>
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">Nexus Employee Portal</h2>
            </div>
            <button class="flex min-w-[84px] cursor-pointer items-center justify-center rounded-lg h-10 px-4 bg-primary/10 text-primary hover:bg-primary/20 transition-colors text-sm font-semibold">
                <span>Help Center</span>
            </button>
        </header>

        <main class="flex-1 flex items-center justify-center px-4 py-12 relative overflow-hidden">
            <!-- Abstract Background Pattern -->
            <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
                <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary rounded-full blur-[120px]"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-primary/40 rounded-full blur-[120px]"></div>
            </div>

            <div class="layout-content-container flex flex-col max-w-[480px] w-full bg-white dark:bg-slate-900 p-8 lg:p-10 rounded-xl shadow-xl border border-slate-200 dark:border-slate-800 z-10">
                <div class="mb-8 text-center">
                    <h1 class="text-slate-900 dark:text-white text-3xl font-extrabold leading-tight tracking-tight mb-2">Welcome Back</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-base">Sign in to your dynamic workspace</p>
                </div>

                <div id="error-alert" class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 hidden"></div>
                <div id="success-alert" class="bg-green-50 text-green-600 p-3 rounded-lg text-sm mb-6 hidden"></div>

                <form id="loginForm" class="space-y-5">
                    <div class="flex flex-col gap-2">
                        <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold" for="email">Email address</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 !text-xl">mail</span>
                            <input autocomplete="email" class="w-full pl-11 pr-4 py-3.5 bg-background-light dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-slate-900 dark:text-white placeholder:text-slate-400 transition-all font-medium" id="email" placeholder="name@company.com" required="" type="email"/>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold" for="password">Password</label>
                            <a class="text-primary hover:underline text-xs font-semibold" href="#">Forgot password?</a>
                        </div>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-4 text-slate-400 !text-xl">lock</span>
                            <input autocomplete="current-password" class="w-full pl-11 pr-12 py-3.5 bg-background-light dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-slate-900 dark:text-white placeholder:text-slate-400 transition-all font-medium" id="password" placeholder="••••••••" required="" type="password"/>
                            <button class="absolute right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" type="button" onclick="togglePassword()">
                                <span class="material-symbols-outlined !text-xl" id="eyeIcon">visibility</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 py-1">
                        <input class="w-4 h-4 rounded text-primary focus:ring-primary border-slate-300 dark:border-slate-700 dark:bg-slate-800" id="remember" type="checkbox"/>
                        <label class="text-slate-600 dark:text-slate-400 text-sm" for="remember">Remember me on this device</label>
                    </div>
                    <button id="btnSubmit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-lg shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2" type="submit">
                        <span>Sign In</span>
                        <span class="material-symbols-outlined !text-lg">arrow_forward</span>
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                        Don't have access? <a class="text-primary font-bold hover:underline" href="#">Contact HR</a>
                    </p>
                </div>
            </div>
        </main>

        <footer class="px-6 lg:px-40 py-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-400 font-medium">
            <div class="flex gap-6">
                <a class="hover:text-primary transition-colors" href="#">Privacy Policy</a>
                <a class="hover:text-primary transition-colors" href="#">Terms of Service</a>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span>All systems operational</span>
            </div>
        </footer>
    </div>
</div>

<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.innerText = 'visibility_off';
    } else {
        pwd.type = 'password';
        icon.innerText = 'visibility';
    }
}

document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorAlert = document.getElementById('error-alert');
    const successAlert = document.getElementById('success-alert');
    const btnSubmit = document.getElementById('btnSubmit');
    
    errorAlert.classList.add('hidden');
    successAlert.classList.add('hidden');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span>Signing In...</span>';

    try {
        const response = await fetch('/api/dept-employee/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (response.ok && data.status) {
            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));

            successAlert.innerText = 'Login successful! Redirecting...';
            successAlert.classList.remove('hidden');

            const deptSlug = data.user.department.slug;
            setTimeout(() => {
                window.location.href = `/employee/dashboard/${deptSlug}`;
            }, 1000);
        } else {
            errorAlert.innerText = data.message || 'Invalid credentials';
            errorAlert.classList.remove('hidden');
        }
    } catch (error) {
        errorAlert.innerText = 'Network error or server unavailable.';
        errorAlert.classList.remove('hidden');
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<span>Sign In</span><span class="material-symbols-outlined !text-lg">arrow_forward</span>';
    }
});
</script>
</body>
</html>
