@extends('layouts.stitch_employee_self')

@section('title', 'Leave Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Leave Management</h1>
            <p class="text-slate-500 font-medium mt-1">Track and apply for your time off</p>
        </div>
        <button onclick="openApplyModal()" class="bg-primary text-white px-5 py-2.5 rounded-lg font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Apply Leave
        </button>
    </div>

    <!-- Balance Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="balance-stats">
        <!-- JS fills this -->
    </div>

    <!-- Leave History -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800">
            <h3 class="font-bold text-lg text-slate-900 dark:text-white">Leave History</h3>
        </div>
        
        <div id="loadingLeaves" class="p-8 text-center text-slate-500">
            <span class="material-symbols-outlined animate-spin !text-3xl mb-2">autorenew</span>
            <p>Loading history...</p>
        </div>

        <div id="leavesTableWrapper" class="hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">Leave Type</th>
                            <th class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">Duration</th>
                            <th class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">Reason</th>
                            <th class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">Status</th>
                            <th class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="leavesTableBody" class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        <!-- Populated via JS -->
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <span id="pageInfo" class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Page 1 of 1</span>
                <div class="flex gap-2">
                    <button id="btnPrev" onclick="changePage(currentPage - 1)" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded disabled:opacity-50">Prev</button>
                    <button id="btnNext" onclick="changePage(currentPage + 1)" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded disabled:opacity-50">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apply Leave Modal -->
<div id="applyModal" class="fixed inset-0 bg-slate-900/50 z-50 hidden flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
            <h3 class="font-bold text-lg text-slate-900 dark:text-white">Apply for Leave</h3>
            <button onclick="closeApplyModal()" class="text-slate-400 hover:text-rose-500 transition">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form id="applyLeaveForm" class="p-6 space-y-4">
            <div id="formAlert" class="hidden p-3 rounded-lg text-sm mb-4"></div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Leave Type (Optional)</label>
                <select id="leave_type" class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-800 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    <option value="">-- Select Type --</option>
                    <option value="Annual">Annual Leave</option>
                    <option value="Sick">Sick Leave</option>
                    <option value="Casual">Casual Leave</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Start Date *</label>
                    <input type="date" id="start_date" required class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-800 rounded-lg text-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">End Date *</label>
                    <input type="date" id="end_date" required class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-800 rounded-lg text-sm focus:ring-primary focus:border-primary">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Reason</label>
                <textarea id="reason" rows="3" class="w-full border-slate-300 dark:border-slate-700 dark:bg-slate-800 rounded-lg text-sm focus:ring-primary focus:border-primary"></textarea>
            </div>
            
            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="closeApplyModal()" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg font-medium transition">Cancel</button>
                <button type="submit" id="btnSubmitApply" class="px-5 py-2 bg-primary text-white rounded-lg font-bold shadow hover:bg-primary/90 transition">Submit Application</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;

    document.addEventListener('DOMContentLoaded', () => {
        loadBalance();
        loadHistory();
    });

    async function loadBalance() {
        const statsRow = document.getElementById('balance-stats');
        statsRow.innerHTML = '<div class="col-span-full text-center text-slate-500 text-sm">Loading balances...</div>';
        
        try {
            const token = getAuthToken();
            const res = await fetch(apiBaseUrl + '/employee/leave-balance', {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const { data } = await res.json();

            statsRow.innerHTML = `
                ${createStatCard('Total Allowance', data.allowance, 'event_note', 'text-primary')}
                ${createStatCard('Used Days', data.used_days, 'event_available', 'text-green-500')}
                ${createStatCard('Pending', data.pending_days, 'pending_actions', 'text-amber-500')}
                ${createStatCard('Remaining', data.remaining_days, 'event_upcoming', 'text-indigo-500')}
            `;
        } catch (e) {
            statsRow.innerHTML = `<div class="col-span-full text-center text-rose-500 text-sm">Failed to load balance stats.</div>`;
        }
    }

    function createStatCard(title, value, icon, iconColorClass) {
        return `
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">${title}</p>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white">${value}</h3>
                </div>
                <div class="size-12 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center ${iconColorClass}">
                    <span class="material-symbols-outlined !text-2xl">${icon}</span>
                </div>
            </div>
        `;
    }

    async function loadHistory(page = 1) {
        currentPage = page;
        const loading = document.getElementById('loadingLeaves');
        const wrapper = document.getElementById('leavesTableWrapper');
        const tbody = document.getElementById('leavesTableBody');
        
        loading.classList.remove('hidden');
        wrapper.classList.add('hidden');
        tbody.innerHTML = '';

        try {
            const token = getAuthToken();
            const res = await fetch(`${apiBaseUrl}/employee/leaves?page=${page}`, {
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const responseData = await res.json();
            const data = responseData.data;
            
            if (data.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">No leave history found.</td></tr>`;
            } else {
                data.data.forEach(item => {
                    const type = item.leave_type || 'General';
                    
                    const dateOptions = { day: 'numeric', month: 'long', year: 'numeric' };
                    const fromStr = new Date(item.from_date).toLocaleDateString('en-GB', dateOptions);
                    const toStr = new Date(item.to_date).toLocaleDateString('en-GB', dateOptions);
                    const dates = fromStr === toStr ? fromStr : `${fromStr} to ${toStr}`;
                    
                    const reason = item.reason || '--';
                    
                    let statusBadge = '';
                    if(item.status === 'pending') statusBadge = `<span class="px-2 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-500 rounded text-xs font-bold">Pending</span>`;
                    else if(item.status === 'approved') statusBadge = `<span class="px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-500 rounded text-xs font-bold">Approved</span>`;
                    else if(item.status === 'rejected') statusBadge = `<span class="px-2 py-1 bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-500 rounded text-xs font-bold">Rejected</span>`;

                    let actions = '';
                    if(item.status === 'pending') {
                        actions = `<button onclick="cancelLeave(${item.id})" class="text-xs font-bold text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-900/20 px-2 py-1 rounded transition">Cancel</button>`;
                    }

                    tbody.innerHTML += `
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">${type}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-medium">${dates}</td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-500 max-w-[200px] truncate" title="${reason}">${reason}</td>
                            <td class="px-6 py-4">${statusBadge}</td>
                            <td class="px-6 py-4 text-right">${actions}</td>
                        </tr>
                    `;
                });
            }

            document.getElementById('pageInfo').innerText = `Page ${data.current_page} of ${data.last_page || 1}`;
            document.getElementById('btnPrev').disabled = (data.current_page <= 1);
            document.getElementById('btnNext').disabled = (data.current_page >= data.last_page);

        } catch (e) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-rose-500">Failed to load network</td></tr>`;
        } finally {
            loading.classList.add('hidden');
            wrapper.classList.remove('hidden');
        }
    }

    function changePage(newPage) {
        if(newPage > 0) loadHistory(newPage);
    }

    async function cancelLeave(id) {
        if(!confirm('Are you sure you want to cancel this leave application?')) return;
        
        try {
            const token = getAuthToken();
            const res = await fetch(`${apiBaseUrl}/employee/leaves/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
            });
            const data = await res.json();
            
            if(res.ok) {
                alert('Leave cancelled successfully.');
                loadHistory(currentPage);
                loadBalance();
            } else {
                alert(data.message || 'Error cancelling leave');
            }
        } catch(e) {
            alert('Network error.');
        }
    }

    // Modal Logic
    function openApplyModal() {
        document.getElementById('applyModal').classList.remove('hidden');
        document.getElementById('applyLeaveForm').reset();
        document.getElementById('formAlert').classList.add('hidden');
    }

    function closeApplyModal() {
        document.getElementById('applyModal').classList.add('hidden');
    }

    document.getElementById('applyLeaveForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = document.getElementById('btnSubmitApply');
        const alertBox = document.getElementById('formAlert');
        
        btn.disabled = true;
        btn.innerText = 'Submitting...';
        alertBox.classList.add('hidden');

        const payload = {
            from_date: document.getElementById('start_date').value,
            to_date: document.getElementById('end_date').value,
            reason: document.getElementById('reason').value,
            leave_type: document.getElementById('leave_type').value
        };

        try {
            const token = getAuthToken();
            const res = await fetch(`${apiBaseUrl}/employee/leave/apply`, {
                method: 'POST',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json' 
                },
                body: JSON.stringify(payload)
            });
            
            const data = await res.json();
            
            if(res.ok && data.status) {
                closeApplyModal();
                loadHistory(1);
                loadBalance();
                // Optionally show a global success toast...
            } else {
                alertBox.className = 'p-3 rounded-lg text-sm mb-4 bg-rose-100 text-rose-700';
                
                if(data.data && typeof data.data === 'object' && Object.keys(data.data).length > 0) {
                    let errs = [];
                    for(let k in data.data) { errs.push(data.data[k].join(', ')); }
                    alertBox.innerHTML = errs.join('<br>');
                } else {
                    alertBox.innerText = data.message || 'Failed to submit leave.';
                }
                
                alertBox.classList.remove('hidden');
            }
        } catch(err) {
            alertBox.className = 'p-3 rounded-lg text-sm mb-4 bg-rose-100 text-rose-700';
            alertBox.innerText = 'Network error during submission.';
            alertBox.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Submit Application';
        }
    });

</script>
@endpush
