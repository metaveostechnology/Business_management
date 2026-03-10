@extends('layouts.appadmin')

@section('title', 'Company Details - ' . $company->name)
@section('page-title', 'Company Details')
@section('breadcrumb', 'View Company')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('companies.index') }}" class="btn btn-soft-secondary btn-sm me-2">
                            <i class="ri-arrow-left-line"></i>
                        </a>
                        <div>
                            <h5 class="card-title mb-0">{{ $company->name }}</h5>
                            <p class="text-muted mb-0 small">
                                Code: <span class="badge bg-light text-dark">{{ $company->code }}</span> | 
                                Registered: {{ $company->created_at->format('F d, Y') }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('companies.edit', $company->slug) }}" class="btn btn-primary">
                            <i class="ri-edit-line me-1"></i> Edit Company
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Status Banner -->
                <div class="alert {{ $company->is_active ? 'alert-success' : 'alert-warning' }} alert-border-left alert-dismissible fade show mb-4">
                    <div class="d-flex align-items-center">
                        @if($company->is_active)
                            <i class="ri-checkbox-circle-line me-2 fs-16"></i>
                            <strong>Active Company</strong> - This company is currently active and can access all features.
                        @else
                            <i class="ri-error-warning-line me-2 fs-16"></i>
                            <strong>Inactive Company</strong> - This company is currently inactive and has limited access.
                        @endif
                    </div>
                </div>

                <div class="row">
                    <!-- Company Logo Section -->
                    <div class="col-md-3">
                        <div class="card border bg-light">
                            <div class="card-body text-center">
                                @if($company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" 
                                        alt="{{ $company->name }}" 
                                        class="img-fluid rounded-circle avatar-xl mb-3">
                                @else
                                    <div class="avatar-xl bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                        <i class="ri-building-line fs-40 text-primary"></i>
                                    </div>
                                @endif
                                <h5 class="mb-1">{{ $company->name }}</h5>
                                <p class="text-muted mb-2">{{ $company->code }}</p>
                                <span class="badge bg-{{ $company->is_active ? 'success' : 'warning' }}-subtle text-{{ $company->is_active ? 'success' : 'warning' }} p-2">
                                    {{ $company->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Company Details -->
                    <div class="col-md-9">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">
                                            <i class="ri-information-line me-1"></i> Basic Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <th class="text-muted fw-normal" style="width: 40%;">Legal Name</th>
                                                <td class="fw-medium">{{ $company->legal_name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Email Address</th>
                                                <td>
                                                    @if($company->email)
                                                        <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Phone Number</th>
                                                <td>
                                                    @if($company->phone)
                                                        <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Website</th>
                                                <td>
                                                    @if($company->website)
                                                        <a href="{{ $company->website }}" target="_blank">
                                                            {{ Str::limit($company->website, 30) }}
                                                        </a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings -->
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">
                                            <i class="ri-settings-line me-1"></i> Settings
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <th class="text-muted fw-normal" style="width: 40%;">Currency</th>
                                                <td class="fw-medium">{{ $company->currency_code }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Timezone</th>
                                                <td>{{ $company->timezone }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Tax Number</th>
                                                <td>{{ $company->tax_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Registration Number</th>
                                                <td>{{ $company->registration_number ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">
                                            <i class="ri-map-pin-line me-1"></i> Address
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <address class="mb-0" style="font-style: normal;">
                                            <p class="mb-1">{{ $company->address_line1 }}</p>
                                            @if($company->address_line2)
                                                <p class="mb-1">{{ $company->address_line2 }}</p>
                                            @endif
                                            <p class="mb-1">
                                                {{ $company->city }}, {{ $company->state }} {{ $company->postal_code }}
                                            </p>
                                            <p class="mb-0">{{ $company->country }}</p>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border border-danger border-opacity-25">
                            <div class="card-header bg-danger-subtle">
                                <h6 class="card-title mb-0 text-danger">
                                    <i class="ri-alert-line me-1"></i> Danger Zone
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="text-danger">Delete this company</h6>
                                        <p class="text-muted mb-0 small">
                                            Deleting a company is permanent. This will remove all associated data including 
                                            users, settings, and configurations. This action cannot be undone.
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <form action="{{ route('companies.destroy', $company->slug) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete this company? All associated data will be permanently removed.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="ri-delete-bin-line me-1"></i> Delete Company Permanently
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Users List -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Company Users</h5>
                            </div>
                            <div class="card-body">
                                <table id="company-users-table" class="table table-bordered nowrap table-striped align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Joined</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($company->users ?? [] as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">{{ $user->role ?? 'User' }}</span>
                                            </td>
                                            <td>
                                                @if($user->is_active)
                                                    <span class="badge bg-success-subtle text-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d M, Y') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-soft-primary" onclick="viewUser({{ $user->id }})">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                No users found for this company.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-success-subtle {
        background-color: #d1e7dd !important;
    }
    .bg-warning-subtle {
        background-color: #fff3cd !important;
    }
    .bg-danger-subtle {
        background-color: #f8d7da !important;
    }
    .bg-primary-subtle {
        background-color: #cfe2ff !important;
    }
    .text-success {
        color: #0f5132 !important;
    }
    .text-warning {
        color: #856404 !important;
    }
    .text-danger {
        color: #842029 !important;
    }
    .text-primary {
        color: #0d6efd !important;
    }
    .border-opacity-25 {
        border-opacity: 0.25;
    }
    .avatar-xl {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
    .fs-40 {
        font-size: 40px;
    }
    .alert-border-left {
        border-left: 4px solid;
    }
    .alert-success {
        border-left-color: #0f5132;
    }
    .alert-warning {
        border-left-color: #856404;
    }
    .table-borderless td, .table-borderless th {
        border: none;
        padding: 0.5rem 0.75rem;
    }
    .card-header.bg-light {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#company-users-table').DataTable({
            responsive: true,
            pageLength: 5,
            lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search users...",
                lengthMenu: "Show _MENU_ users",
                info: "Showing _START_ to _END_ of _TOTAL_ users",
                paginate: {
                    first: '<i class="ri-arrow-left-double-line"></i>',
                    previous: '<i class="ri-arrow-left-s-line"></i>',
                    next: '<i class="ri-arrow-right-s-line"></i>',
                    last: '<i class="ri-arrow-right-double-line"></i>'
                }
            }
        });
    });

    function viewUser(userId) {
        // Implement view user functionality
        console.log('View user:', userId);
    }
</script>
@endpush