@extends('layouts.company_app')

@section('title', 'Role Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Role List</h4>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-primary" onclick="showAddRoleModal()">
                        <i class="ri-add-line align-middle me-1"></i> Add Role
                    </button>
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form id="searchForm">
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-6">
                            <div class="search-box">
                                <input type="text" class="form-control search" id="searchKeyword" placeholder="Search for role name, description...">
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
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="roleTableBody">
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

<!-- Add/Edit Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="roleModalLabel">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="roleForm">
                <input type="hidden" id="roleSlug">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter role name" required />
                        </div>
                        <div class="col-lg-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" placeholder="Enter role description" rows="3"></textarea>
                        </div>
                        <div class="col-lg-12">
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
                        <button type="submit" class="btn btn-success" id="btnSave">Save Role</button>
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
                    <p class="text-muted fs-15 mb-4">Are you sure you want to remove this role?</p>
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
const roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
let currentDeleteSlug = null;

document.addEventListener('DOMContentLoaded', () => {
    loadRoles();
    
    document.getElementById('searchForm').addEventListener('submit', (e) => {
        e.preventDefault();
        currentPage = 1;
        loadRoles();
    });

    document.getElementById('roleForm').addEventListener('submit', handleRoleSubmit);
    document.getElementById('btnConfirmDelete').addEventListener('click', deleteRole);
});

async function loadRoles(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchKeyword').value;
    const status = document.getElementById('statusFilter').value;
    const tableBody = document.getElementById('roleTableBody');
    const noResult = document.getElementById('noResult');

    try {
        const query = new URLSearchParams({
            page: page,
            search: search,
            is_active: status,
            per_page: 10
        });

        const response = await apiRequest(`/company/roles?${query.toString()}`);
        
        tableBody.innerHTML = '';
        
        if (response.data && response.data.length > 0) {
            noResult.classList.add('d-none');
            response.data.forEach(role => {
                tableBody.innerHTML += `
                    <tr>
                        <td class="fw-medium">${role.name}</td>
                        <td>${role.slug}</td>
                        <td class="text-truncate" style="max-width: 250px;">${role.description || 'N/A'}</td>
                        <td>
                            <span class="badge ${role.is_active ? 'bg-success' : 'bg-danger'}">
                                ${role.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <div class="hstack gap-3 flex-wrap">
                                <a href="javascript:void(0);" onclick="editRole('${role.slug}')" class="link-success fs-15"><i class="ri-edit-2-line"></i></a>
                                <a href="javascript:void(0);" onclick="showDeleteModal('${role.slug}')" class="link-danger fs-15"><i class="ri-delete-bin-line"></i></a>
                            </div>
                        </td>
                    </tr>
                `;
            });
            renderPagination(response.meta);
        } else {
            noResult.classList.remove('d-none');
            document.getElementById('paginationContainer').innerHTML = '';
        }
    } catch (error) {
        console.error('Error loading roles:', error);
        alert('Failed to load roles.');
    }
}

function renderPagination(meta) {
    const container = document.getElementById('paginationContainer');
    if (!meta || meta.last_page <= 1) {
        container.innerHTML = '';
        return;
    }

    let html = '<ul class="pagination pagination-separated mb-0">';
    
    // Prev
    html += `<li class="page-item ${meta.current_page === 1 ? 'disabled' : ''}">
                <a href="javascript:void(0);" class="page-link" onclick="loadRoles(${meta.current_page - 1})">Previous</a>
             </li>`;
    
    // Pages
    for (let i = 1; i <= meta.last_page; i++) {
        html += `<li class="page-item ${meta.current_page === i ? 'active' : ''}">
                    <a href="javascript:void(0);" class="page-link" onclick="loadRoles(${i})">${i}</a>
                 </li>`;
    }
    
    // Next
    html += `<li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
                <a href="javascript:void(0);" class="page-link" onclick="loadRoles(${meta.current_page + 1})">Next</a>
             </li>`;
    
    html += '</ul>';
    container.innerHTML = html;
}

function showAddRoleModal() {
    document.getElementById('roleModalLabel').innerText = 'Add Role';
    document.getElementById('roleForm').reset();
    document.getElementById('roleSlug').value = '';
    document.getElementById('is_active').checked = true;
    roleModal.show();
}

async function editRole(slug) {
    try {
        const response = await apiRequest(`/company/roles/${slug}`);
        const role = response.data;
        
        document.getElementById('roleModalLabel').innerText = 'Edit Role';
        document.getElementById('roleSlug').value = role.slug;
        document.getElementById('name').value = role.name;
        document.getElementById('description').value = role.description || '';
        document.getElementById('is_active').checked = role.is_active;
        
        roleModal.show();
    } catch (error) {
        console.error('Error fetching role:', error);
        alert('Failed to fetch role details.');
    }
}

async function handleRoleSubmit(e) {
    e.preventDefault();
    const slug = document.getElementById('roleSlug').value;
    const btnSave = document.getElementById('btnSave');
    
    const payload = {
        name: document.getElementById('name').value,
        description: document.getElementById('description').value || null,
        is_active: document.getElementById('is_active').checked
    };

    btnSave.disabled = true;
    btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';

    try {
        if (slug) {
            await apiRequest(`/company/roles/${slug}`, 'PUT', payload);
        } else {
            await apiRequest('/company/roles', 'POST', payload);
        }
        roleModal.hide();
        loadRoles(slug ? currentPage : 1);
    } catch (error) {
        console.error('Error saving role:', error);
        alert(error.data?.message || 'Failed to save role.');
    } finally {
        btnSave.disabled = false;
        btnSave.innerText = 'Save Role';
    }
}

function showDeleteModal(slug) {
    currentDeleteSlug = slug;
    deleteModal.show();
}

async function deleteRole() {
    if (!currentDeleteSlug) return;
    
    const btn = document.getElementById('btnConfirmDelete');
    btn.disabled = true;
    btn.innerText = 'Deleting...';

    try {
        await apiRequest(`/company/roles/${currentDeleteSlug}`, 'DELETE');
        deleteModal.hide();
        loadRoles(currentPage);
    } catch (error) {
        console.error('Error deleting role:', error);
        alert('Failed to delete role.');
    } finally {
        btn.disabled = false;
        btn.innerText = 'Yes, Delete It';
        currentDeleteSlug = null;
    }
}
</script>
@endpush
