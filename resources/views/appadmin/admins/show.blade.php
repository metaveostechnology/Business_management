@extends('layouts.appadmin')

@section('title', 'Admin Details: ' . $admin->name)
@section('page-title', 'Administrator Details')
@section('breadcrumb', 'View Admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admins.index') }}" class="btn btn-soft-secondary btn-sm me-2">
                            <i class="ri-arrow-left-line"></i>
                        </a>
                        <h5 class="card-title mb-0">Administrator Profile: {{ $admin->name }}</h5>
                    </div>
                    <div>
                        <a href="{{ route('admins.edit', $admin->slug) }}" class="btn btn-primary">
                            <i class="ri-edit-line me-1"></i> Edit Details
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Personal Information Card -->
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="ri-user-line me-1"></i> Personal Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th style="width: 40%;" class="text-muted fw-normal">Full Name</th>
                                        <td class="fw-medium">{{ $admin->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Email Address</th>
                                        <td>
                                            <span class="badge bg-light text-dark p-2">{{ $admin->email }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Username</th>
                                        <td>{{ $admin->username ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Phone Number</th>
                                        <td>{{ $admin->phone ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Account Status Card -->
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="ri-shield-user-line me-1"></i> Account Status
                                </h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th style="width: 40%;" class="text-muted fw-normal">Current Status</th>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'inactive' => 'warning',
                                                    'blocked' => 'danger'
                                                ];
                                                $color = $statusColors[$admin->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}-subtle text-{{ $color }} p-2">
                                                {{ ucfirst($admin->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Joined Date</th>
                                        <td>
                                            {{ $admin->created_at->format('F d, Y') }}
                                            <small class="text-muted d-block">{{ $admin->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted fw-normal">Last Updated</th>
                                        <td>{{ $admin->updated_at->format('F d, Y H:i') }}</td>
                                    </tr>
                                </table>
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
                                        <h6 class="text-danger">Delete this administrator account</h6>
                                        <p class="text-muted mb-0 small">
                                            Deleting this administrator account is a permanent action. 
                                            All associated data will be soft-deleted and may be recoverable by a super-admin.
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <form action="{{ route('admins.destroy', $admin->slug) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete this administrator? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="ri-delete-bin-line me-1"></i> Delete Account
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Timeline (Optional Enhancement) -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admin->activities ?? [] as $activity)
                            <tr>
                                <td>
                                    <span class="badge bg-info-subtle text-info">{{ $activity->action }}</span>
                                </td>
                                <td>{{ $activity->description }}</td>
                                <td><code>{{ $activity->ip_address }}</code></td>
                                <td>{{ $activity->created_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No recent activity found.
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
@endsection

@push('styles')
<style>
    .bg-success-subtle {
        background-color: #d1e7dd;
    }
    .text-success {
        color: #0f5132 !important;
    }
    .bg-warning-subtle {
        background-color: #fff3cd;
    }
    .text-warning {
        color: #856404 !important;
    }
    .bg-danger-subtle {
        background-color: #f8d7da;
    }
    .text-danger {
        color: #842029 !important;
    }
    .border-opacity-25 {
        border-opacity: 0.25;
    }
    .table-borderless td, .table-borderless th {
        border: none;
        padding: 0.75rem;
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
        // Initialize any tooltips if needed
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush