@extends('layouts.employee_app')

@section('title', 'Self-Service Dashboard')
@section('page-title', 'My Attendance')

@push('styles')
<style>
    .loading-state {
        text-align: center;
        padding: 2rem;
        color: var(--text-muted);
    }
    .badge-present {
        background-color: #dcfce3;
        color: #166534;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .pagination {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .pagination button {
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--border-color);
        background: white;
        border-radius: 6px;
        cursor: pointer;
    }
    .pagination button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;">Attendance History</h3>
        <!-- Filter Date -->
        <div style="display: flex; gap: 0.5rem;">
            <input type="date" id="filterDate" class="btn btn-outline" style="padding: 0.35rem; font-size: 0.875rem;">
            <button onclick="loadAttendance()" class="btn" style="padding: 0.35rem 0.75rem; font-size: 0.875rem;">Filter</button>
        </div>
    </div>
    
    <div id="loading" class="loading-state">
        Fetching attendance records...
    </div>

    <div id="attendanceWrapper" class="hidden">
        <div style="overflow-x: auto;">
            <table class="table" id="attendanceTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Login Time</th>
                        <th>Logout Time</th>
                        <th>Duration (Mins)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- JS population -->
                </tbody>
            </table>
        </div>
        
        <div class="pagination">
            <button id="btnPrev" onclick="changePage(currentPage - 1)" disabled>&laquo; Prev</button>
            <span id="pageInfo" style="display: flex; align-items: center; font-size: 0.875rem; color: var(--text-muted);">Page 1 of 1</span>
            <button id="btnNext" onclick="changePage(currentPage + 1)" disabled>Next &raquo;</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let lastFilterDate = '';

    document.addEventListener('DOMContentLoaded', () => {
        loadAttendance();
    });

    async function loadAttendance(page = 1) {
        currentPage = page;
        const filterDate = document.getElementById('filterDate').value;
        lastFilterDate = filterDate;

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

            const res = await fetchWithAuth(endpoint);
            const data = res.data; // paginated data
            
            if (data.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: var(--text-muted);">No attendance records found.</td></tr>`;
            } else {
                data.data.forEach(record => {
                    const loginDate = new Date(record.login_time).toLocaleDateString();
                    const loginTime = new Date(record.login_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    const logoutTime = record.logout_time ? new Date(record.logout_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '--:--';
                    
                    const durationStr = record.work_duration_minutes !== null ? `${record.work_duration_minutes} mins` : 'N/A';
                    
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${loginDate}</td>
                        <td>${loginTime}</td>
                        <td>${logoutTime}</td>
                        <td>${durationStr}</td>
                        <td><span class="badge-present">Present</span></td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            // Pagination update
            document.getElementById('pageInfo').innerText = `Page ${data.current_page} of ${data.last_page || 1}`;
            document.getElementById('btnPrev').disabled = (data.current_page <= 1);
            document.getElementById('btnNext').disabled = (data.current_page >= data.last_page);

        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: red;">Error loading data. ${err.message}</td></tr>`;
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
