@extends('layouts.stitch_employee_self')

@section('title', 'Self-Service Dashboard')

@section('content')
<div class="grid grid-cols-1 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
        <p class="text-slate-500 dark:text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Welcome Back</p>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">Attendance Dashboard</h1>
        <p class="text-xs text-primary font-bold mt-2">Manage your daily logs easily</p>
    </div>
</div>

<div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h3 class="font-bold text-lg text-slate-900 dark:text-white">Attendance History</h3>
        
        <div class="flex items-center gap-2">
            <input type="date" id="filterDate" class="text-sm border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-lg focus:ring-primary focus:border-primary px-3 py-2">
            <button onclick="loadAttendance()" class="bg-primary text-white text-sm font-semibold rounded-lg px-4 py-2 shadow-md hover:bg-primary/90 transition">
                Filter
            </button>
        </div>
    </div>
    
    <div id="loading" class="p-8 text-center text-slate-500 dark:text-slate-400">
        <span class="material-symbols-outlined animate-spin !text-3xl mb-2">autorenew</span>
        <p>Fetching attendance records...</p>
    </div>

    <div id="attendanceWrapper" class="hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="attendanceTable">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">Date</th>
                        <th class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">Log In</th>
                        <th class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">Log Out</th>
                        <th class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">Duration</th>
                        <th class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    <!-- JS population -->
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <span id="pageInfo" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Page 1 of 1</span>
            
            <div class="flex gap-2">
                <button id="btnPrev" onclick="changePage(currentPage - 1)" class="px-3 py-1 rounded border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition text-sm font-medium">
                    &laquo; Prev
                </button>
                <button id="btnNext" onclick="changePage(currentPage + 1)" class="px-3 py-1 rounded border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition text-sm font-medium">
                    Next &raquo;
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;

    document.addEventListener('DOMContentLoaded', () => {
        loadAttendance();
    });

    async function loadAttendance(page = 1) {
        currentPage = page;
        const filterDate = document.getElementById('filterDate').value;
        const loading = document.getElementById('loading');
        const wrapper = document.getElementById('attendanceWrapper');
        const tbody = document.querySelector('#attendanceTable tbody');
        
        loading.classList.remove('hidden');
        wrapper.classList.add('hidden');
        tbody.innerHTML = '';

        try {
            let endpoint = `/employee/attendance?page=${page}`;
            if(filterDate) {
                endpoint += `&from_date=${filterDate}&to_date=${filterDate}`;
            }

            const token = getAuthToken();
            const res = await fetch(apiBaseUrl + endpoint, {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });
            const responseData = await res.json();
            
            if(!res.ok) {
                if(res.status === 401) return logout();
                throw new Error(responseData.message || "Failed to load data");
            }
            
            const data = responseData.data;
            
            if (data.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">No attendance records found.</td></tr>`;
            } else {
                data.data.forEach(record => {
                    const loginDate = new Date(record.login_time).toLocaleDateString();
                    const loginTime = new Date(record.login_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    const logoutTime = record.logout_time ? new Date(record.logout_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '--:--';
                    const durationStr = record.work_duration_minutes !== null ? `<span class="font-semibold">${record.work_duration_minutes}</span> mins` : '<span class="text-slate-400">N/A</span>';
                    
                    const tr = document.createElement('tr');
                    tr.className = "hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors";
                    tr.innerHTML = `
                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-slate-100">${loginDate}</td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">${loginTime}</td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">${logoutTime}</td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">${durationStr}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-inset ring-green-600/20">
                                <span class="size-1.5 rounded-full bg-green-500"></span> Present
                            </span>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            // Pagination update
            document.getElementById('pageInfo').innerText = `Page ${data.current_page} of ${data.last_page || 1}`;
            document.getElementById('btnPrev').disabled = (data.current_page <= 1);
            document.getElementById('btnNext').disabled = (data.current_page >= data.last_page);

        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-rose-500 font-medium">Error loading data. ${err.message}</td></tr>`;
        } finally {
            loading.classList.add('hidden');
            wrapper.classList.remove('hidden');
        }
    }

    function changePage(newPage) {
        if(newPage > 0) {
            loadAttendance(newPage);
        }
    }
</script>
@endpush
