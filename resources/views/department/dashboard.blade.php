@extends('layouts.dept_app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Employees</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="0" id="total-employees">0</span></h4>
                        <a href="{{ route('department.employees') }}" class="text-decoration-underline">View all</a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-info rounded fs-3">
                            <i class="ri-user-follow-line text-info"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Welcome to Department Portal</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">You are logged in as a Department Administrator. From here, you can manage employees within your department.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const data = await apiRequest('/dept/employees');
            document.getElementById('total-employees').textContent = data.data.length;
        } catch (error) {
            console.error('Failed to load dashboard stats', error);
        }
    });
</script>
@endpush
