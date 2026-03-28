@extends('layouts.stitch_employee_self')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-primary to-[#312E81]"></div>
        <div class="px-6 sm:px-10 pb-8 relative">
            <div class="-mt-16 flex flex-col sm:flex-row items-center sm:items-end gap-6 mb-6">
                <!-- Avatar -->
                <div id="profile-avatar" class="size-32 rounded-full border-4 border-white dark:border-slate-900 bg-slate-100 shadow-lg bg-cover bg-center bg-no-repeat" style='background-image: url("https://ui-avatars.com/api/?name=User&background=1152d4&color=fff");'></div>
                
                <div class="text-center sm:text-left flex-1">
                    <h1 id="profile-name" class="text-2xl font-black text-slate-900 dark:text-white">Loading...</h1>
                    <p id="profile-role" class="text-slate-500 font-medium">Loading...</p>
                </div>
                
                <div class="flex gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                        <span class="size-2 rounded-full bg-green-500"></span> Active
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Personal Info -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span>
                Personal Information
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Email Address</label>
                    <p id="profile-email" class="font-medium text-slate-900 dark:text-slate-200">--</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Phone Number</label>
                    <p id="profile-phone" class="font-medium text-slate-900 dark:text-slate-200">--</p>
                </div>
            </div>
        </div>

        <!-- Organization Info -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">corporate_fare</span>
                Organization Details
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Company</label>
                    <p id="profile-company" class="font-medium text-slate-900 dark:text-slate-200">--</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Branch</label>
                    <p id="profile-branch" class="font-medium text-slate-900 dark:text-slate-200">--</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Department</label>
                    <p id="profile-department" class="font-medium text-slate-900 dark:text-slate-200">--</p>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadProfile();
    });

    async function loadProfile() {
        try {
            const token = getAuthToken();
            const res = await fetch(apiBaseUrl + '/employee/profile', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });
            const responseData = await res.json();
            
            if(!res.ok) {
                if(res.status === 401) return logout();
                throw new Error("Failed to load profile");
            }

            const user = responseData.data;
            
            // Populating UI
            document.getElementById('profile-name').innerText = user.name || 'N/A';
            document.getElementById('profile-role').innerText = user.department?.name || 'Employee';
            
            document.getElementById('profile-email').innerText = user.email || 'N/A';
            document.getElementById('profile-phone').innerText = user.phone || 'N/A';
            
            document.getElementById('profile-company').innerText = user.company?.name || 'N/A';
            document.getElementById('profile-branch').innerText = user.branch?.name || 'N/A';
            document.getElementById('profile-department').innerText = user.department?.name || 'N/A';

            // User Avatar
            const avatarUrl = user.profile_image 
                ? `/storage/${user.profile_image}` 
                : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name || 'User')}&background=1152d4&color=fff&size=200`;
            document.getElementById('profile-avatar').style.backgroundImage = `url("${avatarUrl}")`;
            
        } catch (err) {
            console.error('Error fetching profile:', err);
        }
    }
</script>
@endpush
