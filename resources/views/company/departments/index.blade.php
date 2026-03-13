@extends('layouts.company_app')

@section('title', 'Department Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Department List</h4>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-primary" onclick="showAddDepartmentModal()">
                        <i class="ri-add-line align-middle me-1"></i> Add Department
                    </button>
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form id="searchForm">
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-6">
                            <div class="search-box">
                                <input type="text" class="form-control search" id="searchKeyword" placeholder="Search for name, code...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <select class="form-control" id="statusFilter">
                                    <option value="">Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-1 col-sm-4">
                            <div>
                                <button type="submit" class="btn btn-primary w-100"> <i class="ri-equalizer-fill me-1 align-bottom"></i> Filters </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive table-card mb-4">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Level</th>
                                <th>Approval Mode</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="departmentTableBody">
                            <!-- Data will be loaded via JS -->
                        </tbody>
                    </table>
                    <div id="noResult" class="text-center py-4 d-none">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                        <h5 class="mt-2">Sorry! No Result Found</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-end" id="paginationContainer">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Department Modal -->
<div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="departmentModalLabel">Add Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="departmentForm">
                <input type="hidden" id="departmentSlug">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label for="code" class="form-label">Department Code <span class="text-danger">*</span></label>
                            <input type="text" id="code" name="code" class="form-control" placeholder="HR-001" required />
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Human Resource Department" required />
                        </div>
                        <div class="col-lg-6">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select id="branch_id" name="branch_id" class="form-control">
                                <option value="">Select Branch</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="level_no" class="form-label">Level No</label>
                            <input type="number" id="level_no" name="level_no" class="form-control" value="1" min="1" />
                        </div>
                        <div class="col-lg-6">
                            <label for="parent_department_id" class="form-label">Parent Department</label>
                            <select id="parent_department_id" name="parent_department_id" class="form-control">
                                <option value="">None</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="reports_to_department_id" class="form-label">Reports To Department</label>
                            <select id="reports_to_department_id" name="reports_to_department_id" class="form-control">
                                <option value="">None</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="approval_mode" class="form-label">Approval Mode</label>
                            <select id="approval_mode" name="approval_mode" class="form-control">
                                <option value="single">Single</option>
                                <option value="multi">Multi</option>
                                <option value="hierarchical" selected>Hierarchical</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="escalation_mode" class="form-label">Escalation Mode</label>
                            <select id="escalation_mode" name="escalation_mode" class="form-control">
                                <option value="none">None</option>
                                <option value="manager_to_ceo">Manager to CEO</option>
                                <option value="full_chain" selected>Full Chain</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="can_create_tasks" checked>
                                <label class="form-check-label" for="can_create_tasks">Can Create Tasks</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="can_receive_tasks" checked>
                                <label class="form-check-label" for="can_receive_tasks">Can Receive Tasks</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Active Status</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="btnSave">Save Department</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade flip" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-5 text-center">
                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                <div class="mt-4 text-center">
                    <h4>Are you sure?</h4>
                    <p class="text-muted fs-15 mb-4">Are you sure you want to remove this department? All related data will be affected.</p>
                    <div class="hstack gap-2 justify-content-center remove">
                        <button class="btn btn-link link-success fw-medium text-decoration-none" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</button>
                        <button class="btn btn-danger" id="btnConfirmDelete">Yes, Delete It</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
const departmentModal = new bootstrap.Modal(document.getElementById('departmentModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
let currentDeleteSlug = null;
let branches = [];
let allDepartments = [];

document.addEventListener('DOMContentLoaded', () => {
    loadInitialData();
    loadDepartments();
    
    document.getElementById('searchForm').addEventListener('submit', (e) => {
        e.preventDefault();
        currentPage = 1;
        loadDepartments();
    });

    document.getElementById('departmentForm').addEventListener('submit', handleDepartmentSubmit);
    document.getElementById('btnConfirmDelete').addEventListener('click', deleteDepartment);
});

async function loadInitialData() {
    try {
        const branchRes = await apiRequest('/company/branches');
        branches = branchRes.data || [];
        
        const deptRes = await apiRequest('/company/departments');
        allDepartments = deptRes.data || [];
        
        populateDropdowns();
    } catch (error) {
        console.error('Error loading initial data:', error);
    }
}

function populateDropdowns() {
    const branchSelect = document.getElementById('branch_id');
    const parentSelect = document.getElementById('parent_department_id');
    const reportSelect = document.getElementById('reports_to_department_id');
    
    branchSelect.innerHTML = '<option value="">Select Branch</option>';
    branches.forEach(branch => {
        branchSelect.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
    });

    const populateDeptDropdown = (select, currentId = null) => {
        select.innerHTML = '<option value="">None</option>';
        allDepartments.forEach(dept => {
            if (dept.id !== currentId) {
                select.innerHTML += `<option value="${dept.id}">${dept.name}</option>`;
            }
        });
    };

    populateDeptDropdown(parentSelect);
    populateDeptDropdown(reportSelect);
}

async function loadDepartments(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchKeyword').value;
    const status = document.getElementById('statusFilter').value;
    const tableBody = document.getElementById('departmentTableBody');
    const noResult = document.getElementById('noResult');

    try {
        const query = new URLSearchParams({
            page: page,
            search: search,
            is_active: status,
            per_page: 10
        });

        const response = await apiRequest(`/company/departments?${query.toString()}`);
        
        tableBody.innerHTML = '';
        
        if (response.data && response.data.length > 0) {
            noResult.classList.add('d-none');
            response.data.forEach(dept => {
                tableBody.innerHTML += `
                    <tr>
                        <td class="fw-medium">${dept.code}</td>
                        <td>${dept.name}</td>
                        <td>Level ${dept.level_no}</td>
                        <td class="text-capitalize">${dept.approval_mode}</td>
                        <td>
                            <span class="badge ${dept.is_active ? 'bg-success' : 'bg-danger'}">
                                ${dept.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <div class="hstack gap-3 flex-wrap">
                                <a href="javascript:void(0);" onclick="editDepartment('${dept.slug}')" class="link-success fs-15"><i class="ri-edit-2-line"></i></a>
                                <a href="javascript:void(0);" onclick="showDeleteModal('${dept.slug}', ${dept.is_system_default})" class="link-danger fs-15"><i class="ri-delete-bin-line"></i></a>
                            </div>
                        </td>
                    </tr>
                `;
            });
            if (response.meta) {
                renderPagination(response.meta);
            }
        } else {
            noResult.classList.remove('d-none');
            document.getElementById('paginationContainer').innerHTML = '';
        }
    } catch (error) {
        console.error('Error loading departments:', error);
    }
}

function renderPagination(meta) {
    const container = document.getElementById('paginationContainer');
    let html = '<ul class="pagination pagination-separated mb-0">';
    
    html += `<li class="page-item ${meta.current_page === 1 ? 'disabled' : ''}">
                <a href="javascript:void(0);" class="page-link" onclick="loadDepartments(${meta.current_page - 1})">Previous</a>
             </li>`;
    
    for (let i = 1; i <= meta.last_page; i++) {
        html += `<li class="page-item ${meta.current_page === i ? 'active' : ''}">
                    <a href="javascript:void(0);" class="page-link" onclick="loadDepartments(${i})">${i}</a>
                 </li>`;
    }
    
    html += `<li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
                <a href="javascript:void(0);" class="page-link" onclick="loadDepartments(${meta.current_page + 1})">Next</a>
             </li>`;
    
    html += '</ul>';
    container.innerHTML = html;
}

function showAddDepartmentModal() {
    document.getElementById('departmentModalLabel').innerText = 'Add Department';
    document.getElementById('departmentForm').reset();
    document.getElementById('departmentSlug').value = '';
    document.getElementById('is_active').checked = true;
    document.getElementById('can_create_tasks').checked = true;
    document.getElementById('can_receive_tasks').checked = true;
    
    // Refresh dropdowns with current departments
    populateDropdowns();
    departmentModal.show();
}

async function editDepartment(slug) {
    try {
        const response = await apiRequest(`/company/departments/${slug}`);
        const dept = response.data;
        
        document.getElementById('departmentModalLabel').innerText = 'Edit Department';
        document.getElementById('departmentSlug').value = dept.slug;
        document.getElementById('code').value = dept.code;
        document.getElementById('name').value = dept.name;
        document.getElementById('branch_id').value = dept.branch_id || '';
        document.getElementById('level_no').value = dept.level_no;
        
        // Refresh dropdowns excluding current dept
        const parentSelect = document.getElementById('parent_department_id');
        const reportSelect = document.getElementById('reports_to_department_id');
        
        const populateDeptDropdown = (select, currentId = null) => {
            select.innerHTML = '<option value="">None</option>';
            allDepartments.forEach(d => {
                if (d.id !== currentId) {
                    select.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                }
            });
        };
        populateDeptDropdown(parentSelect, dept.id);
        populateDeptDropdown(reportSelect, dept.id);

        document.getElementById('parent_department_id').value = dept.parent_department_id || '';
        document.getElementById('reports_to_department_id').value = dept.reports_to_department_id || '';
        document.getElementById('approval_mode').value = dept.approval_mode;
        document.getElementById('escalation_mode').value = dept.escalation_mode;
        document.getElementById('can_create_tasks').checked = dept.can_create_tasks;
        document.getElementById('can_receive_tasks').checked = dept.can_receive_tasks;
        document.getElementById('is_active').checked = dept.is_active;
        
        departmentModal.show();
    } catch (error) {
        console.error('Error fetching department:', error);
        alert('Failed to fetch department details.');
    }
}

async function handleDepartmentSubmit(e) {
    e.preventDefault();
    const slug = document.getElementById('departmentSlug').value;
    const btnSave = document.getElementById('btnSave');
    
    const payload = {
        code: document.getElementById('code').value,
        name: document.getElementById('name').value,
        branch_id: document.getElementById('branch_id').value || null,
        level_no: document.getElementById('level_no').value || 1,
        parent_department_id: document.getElementById('parent_department_id').value || null,
        reports_to_department_id: document.getElementById('reports_to_department_id').value || null,
        approval_mode: document.getElementById('approval_mode').value,
        escalation_mode: document.getElementById('escalation_mode').value,
        can_create_tasks: document.getElementById('can_create_tasks').checked,
        can_receive_tasks: document.getElementById('can_receive_tasks').checked,
        is_active: document.getElementById('is_active').checked
    };

    btnSave.disabled = true;
    btnSave.innerText = 'Saving...';

    try {
        if (slug) {
            await apiRequest(`/company/departments/${slug}`, 'PUT', payload);
        } else {
            await apiRequest('/company/departments', 'POST', payload);
        }
        departmentModal.hide();
        // Refresh allDepartments list and table
        await loadInitialData();
        loadDepartments(slug ? currentPage : 1);
    } catch (error) {
        console.error('Error saving department:', error);
        alert(error.data?.message || 'Failed to save department.');
    } finally {
        btnSave.disabled = false;
        btnSave.innerText = 'Save Department';
    }
}

function showDeleteModal(slug, isSystemDefault) {
    if (isSystemDefault) {
        alert('System default departments cannot be deleted.');
        return;
    }
    currentDeleteSlug = slug;
    deleteModal.show();
}

async function deleteDepartment() {
    if (!currentDeleteSlug) return;
    
    const btn = document.getElementById('btnConfirmDelete');
    btn.disabled = true;
    btn.innerText = 'Deleting...';

    try {
        await apiRequest(`/company/departments/${currentDeleteSlug}`, 'DELETE');
        deleteModal.hide();
        await loadInitialData();
        loadDepartments(currentPage);
    } catch (error) {
        console.error('Error deleting department:', error);
        alert(error.data?.message || 'Failed to delete department.');
    } finally {
        btn.disabled = false;
        btn.innerText = 'Yes, Delete It';
        currentDeleteSlug = null;
    }
}
</script>
@endpush
