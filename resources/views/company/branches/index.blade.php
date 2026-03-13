@extends('layouts.company_app')

@section('title', 'Branch Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Branch List</h4>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-primary" onclick="showAddBranchModal()">
                        <i class="ri-add-line align-middle me-1"></i> Add Branch
                    </button>
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form id="searchForm">
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-6">
                            <div class="search-box">
                                <input type="text" class="form-control search" id="searchKeyword" placeholder="Search for branch name, code, email, city...">
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
                                <th>Location</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="branchTableBody">
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

<!-- Add/Edit Branch Modal -->
<div class="modal fade" id="branchModal" tabindex="-1" aria-labelledby="branchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="branchModalLabel">Add Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="branchForm">
                <input type="hidden" id="branchSlug">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label for="code" class="form-label">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" id="code" name="code" class="form-control" placeholder="BHB-001" required />
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter branch name" required />
                        </div>
                        <div class="col-lg-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="branch@example.com" />
                        </div>
                        <div class="col-lg-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number" />
                        </div>
                        <div class="col-lg-12">
                            <label for="address_line1" class="form-label">Address Line 1</label>
                            <input type="text" id="address_line1" name="address_line1" class="form-control" placeholder="Plot 12, Saheed Nagar" />
                        </div>
                        <div class="col-lg-4">
                            <label for="city" class="form-label">City</label>
                            <input type="text" id="city" name="city" class="form-control" placeholder="City" />
                        </div>
                        <div class="col-lg-4">
                            <label for="state" class="form-label">State</label>
                            <input type="text" id="state" name="state" class="form-control" placeholder="State" />
                        </div>
                        <div class="col-lg-4">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" class="form-control" placeholder="Postal Code" />
                        </div>
                        <div class="col-lg-12">
                            <label for="google_map_link" class="form-label">Google Map Link</label>
                            <input type="url" id="google_map_link" name="google_map_link" class="form-control" placeholder="https://maps.google.com/?q=..." />
                        </div>
                        <div class="col-lg-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="is_head_office">
                                <label class="form-check-label" for="is_head_office">Is Head Office?</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Active Status</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="btnSave">Save Branch</button>
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
                    <p class="text-muted fs-15 mb-4">Are you sure you want to remove this branch? All related data will be affected.</p>
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
const branchModal = new bootstrap.Modal(document.getElementById('branchModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
let currentDeleteSlug = null;

document.addEventListener('DOMContentLoaded', () => {
    loadBranches();
    
    document.getElementById('searchForm').addEventListener('submit', (e) => {
        e.preventDefault();
        currentPage = 1;
        loadBranches();
    });

    document.getElementById('branchForm').addEventListener('submit', handleBranchSubmit);
    document.getElementById('btnConfirmDelete').addEventListener('click', deleteBranch);
});

async function loadBranches(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchKeyword').value;
    const status = document.getElementById('statusFilter').value;
    const tableBody = document.getElementById('branchTableBody');
    const noResult = document.getElementById('noResult');

    try {
        const query = new URLSearchParams({
            page: page,
            search: search,
            is_active: status,
            per_page: 10
        });

        const response = await apiRequest(`/company/branches?${query.toString()}`);
        
        tableBody.innerHTML = '';
        
        if (response.data && response.data.length > 0) {
            noResult.classList.add('d-none');
            response.data.forEach(branch => {
                tableBody.innerHTML += `
                    <tr>
                        <td class="fw-medium">${branch.code}</td>
                        <td>${branch.name}</td>
                        <td>${branch.city || ''}, ${branch.state || ''}</td>
                        <td>
                            <div><i class="ri-mail-line align-bottom me-1"></i> ${branch.email || 'N/A'}</div>
                            <div><i class="ri-phone-line align-bottom me-1"></i> ${branch.phone || 'N/A'}</div>
                        </td>
                        <td>
                            <span class="badge ${branch.is_head_office ? 'bg-primary' : 'bg-info'}">
                                ${branch.is_head_office ? 'Head Office' : 'Branch'}
                            </span>
                        </td>
                        <td>
                            <span class="badge ${branch.is_active ? 'bg-success' : 'bg-danger'}">
                                ${branch.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <div class="hstack gap-3 flex-wrap">
                                <a href="javascript:void(0);" onclick="editBranch('${branch.slug}')" class="link-success fs-15"><i class="ri-edit-2-line"></i></a>
                                <a href="javascript:void(0);" onclick="showDeleteModal('${branch.slug}')" class="link-danger fs-15"><i class="ri-delete-bin-line"></i></a>
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
        console.error('Error loading branches:', error);
        alert('Failed to load branches.');
    }
}

function renderPagination(meta) {
    const container = document.getElementById('paginationContainer');
    let html = '<ul class="pagination pagination-separated mb-0">';
    
    // Prev
    html += `<li class="page-item ${meta.current_page === 1 ? 'disabled' : ''}">
                <a href="javascript:void(0);" class="page-link" onclick="loadBranches(${meta.current_page - 1})">Previous</a>
             </li>`;
    
    // Pages
    for (let i = 1; i <= meta.last_page; i++) {
        html += `<li class="page-item ${meta.current_page === i ? 'active' : ''}">
                    <a href="javascript:void(0);" class="page-link" onclick="loadBranches(${i})">${i}</a>
                 </li>`;
    }
    
    // Next
    html += `<li class="page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}">
                <a href="javascript:void(0);" class="page-link" onclick="loadBranches(${meta.current_page + 1})">Next</a>
             </li>`;
    
    html += '</ul>';
    container.innerHTML = html;
}

function showAddBranchModal() {
    document.getElementById('branchModalLabel').innerText = 'Add Branch';
    document.getElementById('branchForm').reset();
    document.getElementById('branchSlug').value = '';
    document.getElementById('is_active').checked = true;
    document.getElementById('is_head_office').checked = false;
    branchModal.show();
}

async function editBranch(slug) {
    try {
        const response = await apiRequest(`/company/branches/${slug}`);
        const branch = response.data;
        
        document.getElementById('branchModalLabel').innerText = 'Edit Branch';
        document.getElementById('branchSlug').value = branch.slug;
        document.getElementById('code').value = branch.code;
        document.getElementById('name').value = branch.name;
        document.getElementById('email').value = branch.email || '';
        document.getElementById('phone').value = branch.phone || '';
        document.getElementById('address_line1').value = branch.address_line1 || '';
        document.getElementById('city').value = branch.city || '';
        document.getElementById('state').value = branch.state || '';
        document.getElementById('postal_code').value = branch.postal_code || '';
        document.getElementById('google_map_link').value = branch.google_map_link || '';
        document.getElementById('is_head_office').checked = branch.is_head_office;
        document.getElementById('is_active').checked = branch.is_active;
        
        branchModal.show();
    } catch (error) {
        console.error('Error fetching branch:', error);
        alert('Failed to fetch branch details.');
    }
}

async function handleBranchSubmit(e) {
    e.preventDefault();
    const slug = document.getElementById('branchSlug').value;
    const btnSave = document.getElementById('btnSave');
    
    const payload = {
        code: document.getElementById('code').value,
        name: document.getElementById('name').value,
        email: document.getElementById('email').value || null,
        phone: document.getElementById('phone').value || null,
        address_line1: document.getElementById('address_line1').value || null,
        city: document.getElementById('city').value || null,
        state: document.getElementById('state').value || null,
        postal_code: document.getElementById('postal_code').value || null,
        google_map_link: document.getElementById('google_map_link').value || null,
        is_head_office: document.getElementById('is_head_office').checked,
        is_active: document.getElementById('is_active').checked
    };

    btnSave.disabled = true;
    btnSave.innerText = 'Saving...';

    try {
        if (slug) {
            await apiRequest(`/company/branches/${slug}`, 'PUT', payload);
        } else {
            await apiRequest('/company/branches', 'POST', payload);
        }
        branchModal.hide();
        loadBranches(slug ? currentPage : 1);
    } catch (error) {
        console.error('Error saving branch:', error);
        alert(error.data?.message || 'Failed to save branch.');
    } finally {
        btnSave.disabled = false;
        btnSave.innerText = 'Save Branch';
    }
}

function showDeleteModal(slug) {
    currentDeleteSlug = slug;
    deleteModal.show();
}

async function deleteBranch() {
    if (!currentDeleteSlug) return;
    
    const btn = document.getElementById('btnConfirmDelete');
    btn.disabled = true;
    btn.innerText = 'Deleting...';

    try {
        await apiRequest(`/company/branches/${currentDeleteSlug}`, 'DELETE');
        deleteModal.hide();
        loadBranches(currentPage);
    } catch (error) {
        console.error('Error deleting branch:', error);
        alert('Failed to delete branch.');
    } finally {
        btn.disabled = false;
        btn.innerText = 'Yes, Delete It';
        currentDeleteSlug = null;
    }
}
</script>
@endpush
