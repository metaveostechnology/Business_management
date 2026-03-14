@extends('layouts.company_app')

@section('title', 'Branch Users Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Branch Users List</h4>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-primary" onclick="showAddUserModal()">
                        <i class="ri-add-line align-middle me-1"></i> Add Branch User
                    </button>
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form id="searchForm">
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-6">
                            <div class="search-box">
                                <input type="text" class="form-control search" id="searchKeyword" placeholder="Search for name, email, phone...">
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
                                <th>Contact</th>
                                <th>Branch</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
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

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="userModalLabel">Add Branch User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm">
                <input type="hidden" id="userSlug">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required />
                        </div>
                        <div class="col-lg-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="user@example.com" required autocomplete="username" />
                        </div>
                        <div class="col-lg-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number" />
                        </div>
                        <div class="col-lg-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Active Status</label>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                            <select class="form-control" id="branch_id" name="branch_id" required>
                                <option value="">Select Branch</option>
                                <!-- Populated dynamically -->
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-control" id="role_id" name="role_id" required>
                                <option value="">Select Role</option>
                                <!-- Populated dynamically -->
                            </select>
                        </div>
                        
                        <!-- Password fields (required for Add, optional for Edit if we handle it differently, but API requires confirmed password for store) -->
                        <div class="col-lg-6" id="passwordGroup">
                            <label for="password" class="form-label">Password <span class="text-danger" id="passwordRequiredAsterisk">*</span></label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" autocomplete="new-password" />
                        </div>
                        <div class="col-lg-6" id="passwordConfirmGroup">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger" id="passwordConfirmRequiredAsterisk">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm password" autocomplete="new-password" />
                        </div>
                        
                        <div class="col-lg-12 d-none" id="editPasswordHint">
                            <div class="alert alert-info mb-0">
                                Leave password fields blank if you do not wish to change the user's password.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="btnSave">Save User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="viewUserModalLabel">Branch User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless table-sm mb-0">
                    <tbody>
                        <tr><th class="ps-0" scope="row">Name:</th><td class="text-muted" id="view_name"></td></tr>
                        <tr><th class="ps-0" scope="row">Email:</th><td class="text-muted" id="view_email"></td></tr>
                        <tr><th class="ps-0" scope="row">Phone:</th><td class="text-muted" id="view_phone"></td></tr>
                        <tr><th class="ps-0" scope="row">Branch:</th><td class="text-muted" id="view_branch"></td></tr>
                        <tr><th class="ps-0" scope="row">Role:</th><td class="text-muted" id="view_role"></td></tr>
                        <tr><th class="ps-0" scope="row">Status:</th><td class="text-muted" id="view_status"></td></tr>
                        <tr><th class="ps-0" scope="row">Password:</th><td class="text-muted"><span id="view_password" class="text-secondary fst-italic">Hidden</span></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
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
                    <p class="text-muted fs-15 mb-4">Are you sure you want to remove this user? They will no longer be able to log in.</p>
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
const userModal = new bootstrap.Modal(document.getElementById('userModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
const viewUserModal = new bootstrap.Modal(document.getElementById('viewUserModal'));
let currentDeleteSlug = null;

let branchesList = [];
let rolesList = [];

document.addEventListener('DOMContentLoaded', async () => {
    // Load dropdown options first
    await Promise.all([loadBranchesDropdown(), loadRolesDropdown()]);
    
    loadUsers();
    
    document.getElementById('searchForm').addEventListener('submit', (e) => {
        e.preventDefault();
        currentPage = 1;
        loadUsers();
    });

    document.getElementById('userForm').addEventListener('submit', handleUserSubmit);

    document.getElementById('btnConfirmDelete').addEventListener('click', deleteUser);
});

async function loadBranchesDropdown() {
    try {
        const response = await apiRequest('/company/branches?per_page=100&is_active=1');
        branchesList = response.data || [];
        const select = document.getElementById('branch_id');
        select.innerHTML = '<option value="">Select Branch</option>';
        branchesList.forEach(branch => {
            select.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
        });
    } catch (error) {
        console.error('Failed to load branches for dropdown', error);
    }
}

async function loadRolesDropdown() {
    try {
        const response = await apiRequest('/company/roles?per_page=100&is_active=1');
        rolesList = response.data || [];
        const select = document.getElementById('role_id');
        select.innerHTML = '<option value="">Select Role</option>';
        rolesList.forEach(role => {
            select.innerHTML += `<option value="${role.id}">${role.name}</option>`;
        });
    } catch (error) {
        console.error('Failed to load roles for dropdown', error);
    }
}

async function loadUsers(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchKeyword').value;
    const status = document.getElementById('statusFilter').value;
    const tableBody = document.getElementById('userTableBody');
    const noResult = document.getElementById('noResult');

    try {
        const query = new URLSearchParams({
            page: page,
            search: search,
            is_active: status,
            per_page: 10
        });

        const response = await apiRequest(`/company/branch-users?${query.toString()}`);
        
        tableBody.innerHTML = '';
        
        if (response.data && response.data.length > 0) {
            noResult.classList.add('d-none');
            response.data.forEach(user => {
                tableBody.innerHTML += `
                    <tr>
                        <td class="fw-medium">${user.name}</td>
                        <td>
                            <div><i class="ri-mail-line align-bottom me-1 text-muted"></i> ${user.email}</div>
                            ${user.phone ? `<div><i class="ri-phone-line align-bottom me-1 text-muted"></i> ${user.phone}</div>` : ''}
                        </td>
                        <td>${user.branch ? user.branch.name : '<span class="text-danger">None</span>'}</td>
                        <td>${user.role ? user.role.name : '<span class="text-danger">None</span>'}</td>
                        <td>
                            <span class="badge ${user.is_active ? 'bg-success' : 'bg-danger'}">
                                ${user.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <div class="hstack gap-3 flex-wrap">
                                <a href="javascript:void(0);" onclick="editUser('${user.slug}')" class="link-success fs-15 text-decoration-none" data-bs-toggle="tooltip" title="Edit User">
                                    <i class="ri-edit-2-line"></i>
                                </a>
                                <a href="javascript:void(0);" onclick="viewUser('${user.slug}')" class="link-info fs-15 text-decoration-none" data-bs-toggle="tooltip" title="View Details">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="javascript:void(0);" onclick="showDeleteModal('${user.slug}')" class="link-danger fs-15 text-decoration-none" data-bs-toggle="tooltip" title="Delete User">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
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
        console.error('Error loading branch users:', error);
        alert('Failed to load branch users.');
    }
}

function renderPagination(meta) {
    const container = document.getElementById('paginationContainer');
    let html = '<ul class="pagination pagination-separated mb-0">';
    
    // Prev
    html += `<li class="page-item ${meta.current_page === 1 ? 'disabled' : ''}">
                <a href="javascript:void(0);" class="page-link" onclick="loadUsers(${meta.current_page - 1})">Previous</a>
             </li>`;
    
    // Pages
    for (let i = 1; i <= meta.last_page; i++) {
        html += `<li class="page-item ${meta.current_page === i ? 'active' : ''}">
                    <a href="javascript:void(0);" class="page-link" onclick="loadUsers(${i})">${i}</a>
                 </li>`;
    }
    
    // Next
    html += `<li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
                <a href="javascript:void(0);" class="page-link" onclick="loadUsers(${meta.current_page + 1})">Next</a>
             </li>`;
    
    html += '</ul>';
    container.innerHTML = html;
}

function showAddUserModal() {
    document.getElementById('userModalLabel').innerText = 'Add Branch User';
    document.getElementById('userForm').reset();
    document.getElementById('userSlug').value = '';
    document.getElementById('is_active').checked = true;
    
    // Make password required
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
    document.getElementById('passwordGroup').classList.remove('d-none');
    document.getElementById('passwordConfirmGroup').classList.remove('d-none');
    document.getElementById('editPasswordHint').classList.add('d-none');
    
    userModal.show();
}

async function editUser(slug) {
    try {
        const response = await apiRequest(`/company/branch-users/${slug}`);
        const user = response.data;
        
        document.getElementById('userModalLabel').innerText = 'Edit Branch User';
        document.getElementById('userSlug').value = user.slug;
        document.getElementById('name').value = user.name;
        document.getElementById('email').value = user.email;
        document.getElementById('phone').value = user.phone || '';
        document.getElementById('is_active').checked = user.is_active;
        
        if (user.branch && user.branch.id) {
            document.getElementById('branch_id').value = user.branch.id;
        }
        
        if (user.role && user.role.id) {
            document.getElementById('role_id').value = user.role.id;
        }
        
        // Hide password fields (they should use the change password flow)
        document.getElementById('password').required = false;
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').required = false;
        document.getElementById('password_confirmation').value = '';
        
        document.getElementById('passwordGroup').classList.add('d-none');
        document.getElementById('passwordConfirmGroup').classList.add('d-none');
        document.getElementById('editPasswordHint').classList.add('d-none'); // Hide hint too if we just totally hide fields
        
        userModal.show();
    } catch (error) {
        console.error('Error fetching user:', error);
        alert('Failed to fetch user details.');
    }
}

async function handleUserSubmit(e) {
    e.preventDefault();
    const slug = document.getElementById('userSlug').value;
    const btnSave = document.getElementById('btnSave');
    
    const payload = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value || null,
        branch_id: document.getElementById('branch_id').value,
        role_id: document.getElementById('role_id').value,
        is_active: document.getElementById('is_active').checked
    };

    if (!slug) {
        // Only valid for creating
        payload.password = document.getElementById('password').value;
        payload.password_confirmation = document.getElementById('password_confirmation').value;
    }

    btnSave.disabled = true;
    btnSave.innerText = 'Saving...';

    try {
        if (slug) {
            // Edit user (no password in this request, handled separately)
            await apiRequest(`/company/branch-users/${slug}`, 'PUT', payload);
            alertSuccess('User updated successfully.');
        } else {
            // Create user
            await apiRequest('/company/branch-users', 'POST', payload);
            alertSuccess('User created successfully.');
        }
        userModal.hide();
        loadUsers(slug ? currentPage : 1);
    } catch (error) {
        console.error('Error saving user:', error);
        let msg = error.data?.message || 'Failed to save user.';
        if (error.data?.errors) {
            const firstError = Object.values(error.data.errors)[0][0];
            msg += '\n' + firstError;
        }
        alert(msg);
    } finally {
        btnSave.disabled = false;
        btnSave.innerText = 'Save User';
    }
}

async function viewUser(slug) {
    try {
        const response = await apiRequest(`/company/branch-users/${slug}`);
        const user = response.data;
        
        document.getElementById('view_name').innerText = user.name;
        document.getElementById('view_email').innerText = user.email;
        document.getElementById('view_phone').innerText = user.phone || 'N/A';
        document.getElementById('view_branch').innerText = user.branch ? user.branch.name : 'N/A';
        document.getElementById('view_role').innerText = user.role ? user.role.name : 'N/A';
        document.getElementById('view_status').innerHTML = user.is_active 
            ? '<span class="badge bg-success">Active</span>' 
            : '<span class="badge bg-danger">Inactive</span>';
        
        // Passwords are not returned via the API for security reasons.
        document.getElementById('view_password').innerText = user.show_password || '********';

        viewUserModal.show();
    } catch (error) {
        console.error('Error fetching user:', error);
        alert('Failed to fetch user details.');
    }
}

function showDeleteModal(slug) {
    currentDeleteSlug = slug;
    deleteModal.show();
}

async function deleteUser() {
    if (!currentDeleteSlug) return;
    
    const btn = document.getElementById('btnConfirmDelete');
    btn.disabled = true;
    btn.innerText = 'Deleting...';

    try {
        await apiRequest(`/company/branch-users/${currentDeleteSlug}`, 'DELETE');
        deleteModal.hide();
        alertSuccess('User deleted successfully.');
        loadUsers(currentPage);
    } catch (error) {
        console.error('Error deleting user:', error);
        alert('Failed to delete user.');
    } finally {
        btn.disabled = false;
        btn.innerText = 'Yes, Delete It';
        currentDeleteSlug = null;
    }
}

function alertSuccess(msg) {
    // Custom logic if Toastify is available, fallback to simple alert
    if (typeof Toastify !== 'undefined') {
        Toastify({
            text: msg,
            duration: 3000,
            close: true,
            gravity: "top", 
            position: "right", 
            backgroundColor: "#4f46e5",
        }).showToast();
    }
}
</script>
@endpush
