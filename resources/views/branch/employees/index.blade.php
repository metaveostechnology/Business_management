@extends('layouts.branch_app')

@section('title', 'Manage Employees')
@section('page-title', 'Manage Employees')
@section('breadcrumb', 'Employees')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<style>
    .employee-avatar { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; }
    .hidden { display: none !important; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Branch Employees</h5>
                <div class="flex-shrink-0">
                    <button class="btn btn-primary" onclick="openCreateModal()">
                        <i class="ri-add-line align-bottom me-1"></i> Add Employee
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="employeesTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Emp ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="employeesList">
                        <!-- Loaded via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="employeeForm">
                <div class="modal-body">
                    <input type="hidden" id="employeeSlug" name="slug">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="employeeName" name="name" required placeholder="Enter full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="employeeEmail" name="email" required placeholder="Enter email address">
                        </div>
                        <div class="col-md-6" id="passwordField">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="employeePassword" name="password" required placeholder="••••••••">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" id="employeePhone" name="phone" placeholder="Enter phone number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select" id="employeeDept" name="dept_id" required>
                                <option value="">Select Department</option>
                                <!-- Loaded via JS -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="employeeImage" name="profile_image" accept="image/*">
                        </div>
                        <div class="col-md-6" id="statusField">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="employeeStatus" name="is_active" checked>
                                <label class="form-check-label">Is Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Save Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let departments = [];
    let table = null;
    const modal = new bootstrap.Modal(document.getElementById('employeeModal'));
    const form = document.getElementById('employeeForm');

    document.addEventListener('DOMContentLoaded', async () => {
        await loadDepartments();
        loadEmployees();
    });

    async function loadDepartments() {
        try {
            const data = await apiRequest('/departments');
            departments = data.data;
            const select = document.getElementById('employeeDept');
            departments.forEach(dept => {
                const opt = document.createElement('option');
                opt.value = dept.id;
                opt.textContent = dept.name;
                select.appendChild(opt);
            });
        } catch (error) {
            console.error('Failed to load departments', error);
        }
    }

    async function loadEmployees() {
        if (table) {
            table.destroy();
        }

        try {
            const data = await apiRequest('/branch/employees');
            const employees = data.data;
            const tbody = document.getElementById('employeesList');
            tbody.innerHTML = '';

            employees.forEach(emp => {
                const tr = document.createElement('tr');
                const avatar = emp.profile_image 
                    ? `<img src="/storage/${emp.profile_image}" class="employee-avatar" />`
                    : `<div class="avatar-sm"><span class="avatar-title rounded-circle bg-soft-primary text-primary">${emp.name.charAt(0)}</span></div>`;
                
                tr.innerHTML = `
                    <td>${avatar}</td>
                    <td>${emp.emp_id}</td>
                    <td>${emp.name}</td>
                    <td>${emp.email}</td>
                    <td>${emp.phone || '-'}</td>
                    <td>${emp.department ? emp.department.name : (departments.find(d => d.id == emp.dept_id)?.name || 'N/A')}</td>
                    <td>
                        <span class="badge ${emp.is_active ? 'bg-success' : 'bg-danger'}">
                            ${emp.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-fill align-middle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item edit-item-btn" href="javascript:void(0);" onclick="openEditModal('${emp.slug}')"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                <li>
                                    <a class="dropdown-item remove-item-btn" href="javascript:void(0);" onclick="deleteEmployee('${emp.slug}')">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            table = $('#employeesTable').DataTable({
                responsive: true
            });
        } catch (error) {
            console.error('Failed to load employees', error);
            Swal.fire('Error', 'Failed to load employees list', 'error');
        }
    }

    function openCreateModal() {
        document.getElementById('employeeModalLabel').textContent = 'Add Employee';
        document.getElementById('employeeSlug').value = '';
        document.getElementById('employeeEmail').disabled = false;
        document.getElementById('passwordField').classList.remove('hidden');
        document.getElementById('employeePassword').required = true;
        document.getElementById('statusField').classList.add('hidden');
        form.reset();
        modal.show();
    }

    async function openEditModal(slug) {
        try {
            const data = await apiRequest(`/branch/employees/${slug}`);
            const emp = data.data;
            
            document.getElementById('employeeModalLabel').textContent = 'Edit Employee';
            document.getElementById('employeeSlug').value = emp.slug;
            document.getElementById('employeeName').value = emp.name;
            document.getElementById('employeeEmail').value = emp.email;
            document.getElementById('employeeEmail').disabled = true;
            document.getElementById('employeePhone').value = emp.phone || '';
            document.getElementById('employeeDept').value = emp.dept_id;
            document.getElementById('employeeStatus').checked = emp.is_active;
            
            document.getElementById('passwordField').classList.add('hidden');
            document.getElementById('employeePassword').required = false;
            document.getElementById('statusField').classList.remove('hidden');
            
            modal.show();
        } catch (error) {
            Swal.fire('Error', 'Failed to fetch employee details', 'error');
        }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btnSave = document.getElementById('btnSave');
        const slug = document.getElementById('employeeSlug').value;
        const isEdit = !!slug;

        btnSave.disabled = true;
        btnSave.textContent = 'Saving...';

        const formData = new FormData(form);
        if (isEdit) {
            formData.append('_method', 'PUT');
            formData.set('is_active', document.getElementById('employeeStatus').checked ? 1 : 0);
        }

        const endpoint = isEdit ? `/api/branch/employees/${slug}` : '/api/branch/employees';
        const method = 'POST'; // Always POST because of FormData and _method=PUT

        try {
            const token = getAuthToken();
            const headers = { 'Accept': 'application/json' };
            if (token) headers['Authorization'] = `Bearer ${token}`;

            const response = await fetch(endpoint, {
                method: method,
                headers: headers,
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                if (data.data) {
                    let errors = Object.values(data.data).flat().join('<br>');
                    throw new Error(errors || data.message || 'Validation error');
                }
                throw new Error(data.message || 'Saving failed');
            }

            Swal.fire('Success', data.message, 'success');
            modal.hide();
            loadEmployees();
        } catch (error) {
            Swal.fire('Error', error.message || 'Failed to save employee', 'error');
        } finally {
            btnSave.disabled = false;
            btnSave.textContent = 'Save Employee';
        }
    });

    async function deleteEmployee(slug) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will soft-delete the employee!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            try {
                await apiRequest(`/branch/employees/${slug}`, 'DELETE');
                Swal.fire('Deleted!', 'Employee has been deleted.', 'success');
                loadEmployees();
            } catch (error) {
                Swal.fire('Error', error.message || 'Failed to delete employee', 'error');
            }
        }
    }
</script>
@endpush
