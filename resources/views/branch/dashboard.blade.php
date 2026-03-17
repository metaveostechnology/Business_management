@extends('layouts.branch_app')

@section('title', 'Branch Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h2 class="header-title">Welcome to Branch Dashboard</h2>
                
                <div id="loading" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                    Loading branch details...
                </div>

                <div id="dashboard-content" class="hidden">
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading" id="branchGreeting">Hello!</h4>
                        <p>You are logged in as a Branch Admin. Manage your branch operations here.</p>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card shadow-none border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                                <i class="ri-user-star-line"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-medium text-muted mb-1">Role</p>
                                            <h4 class="mb-0">Branch Admin</h4>
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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const loading = document.getElementById('loading');
    const content = document.getElementById('dashboard-content');
    const greeting = document.getElementById('branchGreeting');
    
    const user = getBranchUser();
    if (user) {
        greeting.innerText = `Hello, ${user.name || user.email}!`;
    }

    // Simulate slight loading
    setTimeout(() => {
        loading.classList.add('hidden');
        content.classList.remove('hidden');
    }, 500);
});
</script>
@endpush
