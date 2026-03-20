<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Employee Portal') | Nexus Enterprise</title>
    
    <!-- TailWind & Fonts -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="{{ asset('appadmin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    
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
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-size: 20px; font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        [v-cloak] { display: none; }
        .hidden { display: none !important; }
        
        /* Sidebar transition */
        #sidebar { transition: transform 0.3s ease-in-out; }
        @media (max-width: 1024px) {
            #sidebar.closed { transform: translateX(-100%); }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
    <div class="relative flex h-screen w-full overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col transform lg:translate-x-0 lg:static closed">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3 text-primary">
                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined font-bold">rocket_launch</span>
                    </div>
                    <h2 class="text-slate-900 dark:text-white text-lg font-bold tracking-tight">Nexus Portal</h2>
                </div>
                <button onclick="toggleSidebar()" class="lg:hidden text-slate-500 hover:text-primary">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Menu</p>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white shadow-lg shadow-primary/20 transition-all font-semibold">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-sm">Dashboard</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium">
                    <span class="material-symbols-outlined">task_alt</span>
                    <span class="text-sm">Tasks</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium">
                    <span class="material-symbols-outlined">description</span>
                    <span class="text-sm">Documents</span>
                </a>
                
                <p class="px-4 mt-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Account</p>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium">
                    <span class="material-symbols-outlined">person</span>
                    <span class="text-sm">My Profile</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="text-sm">Settings</span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                <button onclick="logout()" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors font-bold text-sm">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Sign Out</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-background-light dark:bg-background-dark">
            
            <!-- Top Navigation Bar -->
            <header class="h-16 flex items-center justify-between border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md px-6 lg:px-10 sticky top-0 z-40">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                
                <div class="hidden lg:flex items-center gap-4">
                    <h2 class="text-slate-800 dark:text-slate-200 text-sm font-semibold">@yield('title', 'Welcome back!')</h2>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="hidden md:flex items-center gap-2">
                        <button class="size-10 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                            <span class="material-symbols-outlined">notifications</span>
                        </button>
                        <button class="size-10 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                            <span class="material-symbols-outlined">mode_night</span>
                        </button>
                    </div>
                    
                    <div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-700 mx-1"></div>
                    
                    <!-- Profile Button / Dropdown -->
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profile-btn" onclick="toggleProfileDropdown()" class="flex items-center gap-2 hover:bg-slate-50 dark:hover:bg-slate-800 p-1 rounded-lg transition-colors focus:outline-none">
                            <div class="text-right ml-2 pr-1">
                                <p id="user-name" class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Employee</p>
                                <p id="user-role" class="text-[9px] text-slate-500 font-bold uppercase tracking-wider leading-tight">Department</p>
                            </div>
                            <div id="user-avatar" class="size-8 bg-center bg-no-repeat bg-cover rounded-full ring-2 ring-primary/10 shadow-sm" style='background-image: url("https://ui-avatars.com/api/?name=User&background=1152d4&color=fff");'></div>
                            <span class="material-symbols-outlined text-slate-400 !text-sm">expand_more</span>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown" class="absolute right-0 mt-3 w-48 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 py-2 hidden z-50">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800 md:hidden">
                                <p id="user-name-mobile" class="text-sm font-bold text-slate-900 dark:text-white">Employee</p>
                                <p id="user-role-mobile" class="text-xs text-slate-500">Department</p>
                            </div>
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined !text-lg">person</span>
                                <span>My Profile</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined !text-lg">settings</span>
                                <span>Settings</span>
                            </a>
                            <div class="my-1 border-t border-slate-100 dark:border-slate-800"></div>
                            <button onclick="logout()" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors font-bold text-left">
                                <span class="material-symbols-outlined !text-lg">logout</span>
                                <span>Sign Out</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>


            <main class="flex-1 overflow-y-auto p-4 lg:p-10">
                <div class="max-w-[1600px] mx-auto w-full">
                    @yield('content')
                </div>
            </main>

            <footer class="border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-6 lg:px-10 py-4 text-center text-slate-500 text-[10px] sm:text-xs">
                <div class="max-w-[1600px] mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
                    <p>© {{ date('Y') }} Nexus Enterprise. Modern Systems for Scalable Success.</p>
                    <div class="flex gap-4">
                        <a class="hover:text-primary transition-colors" href="#">Support</a>
                        <a class="hover:text-primary transition-colors" href="#">Security</a>
                    </div>
                </div>
            </footer>
        </div>
        
        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden"></div>
    </div>

    <script>
        const apiBaseUrl = '/api';
        
        function getAuthToken() { return localStorage.getItem('auth_token'); }
        function removeAuthToken() { localStorage.removeItem('auth_token'); localStorage.removeItem('user'); }

        async function logout() {
            try {
                await fetch(apiBaseUrl + '/dept-employee/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + getAuthToken(),
                        'Accept': 'application/json'
                    }
                });
            } catch (error) { console.error('Logout failed:', error); }
            removeAuthToken();
            window.location.href = "{{ route('employee.login') }}";
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('closed');
            overlay.classList.toggle('hidden');
        }
        
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('hidden');
        }
        
        // Close dropdown when clicking outside
        window.addEventListener('click', (e) => {
            const dropdown = document.getElementById('profile-dropdown');
            const profileBtn = document.getElementById('profile-btn');
            if (profileBtn && !profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });


        // Initialize user info
        document.addEventListener('DOMContentLoaded', () => {
            const userRaw = localStorage.getItem('user');
            if (userRaw) {
                try {
                    const user = JSON.parse(userRaw);
                    const name = user.name || 'Employee';
                    const role = user.department?.name || 'Department staff';
                    
                    document.querySelectorAll('[id^="user-name"]').forEach(el => el.textContent = name);
                    document.querySelectorAll('[id^="user-role"]').forEach(el => el.textContent = role);
                    
                    const avatarUrl = user.profile_image 
                        ? `/storage/${user.profile_image}` 
                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=1152d4&color=fff`;
                        
                    document.querySelectorAll('[id^="user-avatar"]').forEach(el => {
                        el.style.backgroundImage = `url("${avatarUrl}")`;
                    });
                } catch (e) { console.error('Error parsing user data:', e); }
            }
        });

    </script>
    @stack('scripts')
</body>
</html>
