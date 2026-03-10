@extends('layouts.company_app')

@section('title', 'Company Dashboard')

@section('content')
<div class="card">
    <h2 class="header-title">Welcome to Your Dashboard</h2>
    
    <div id="loading" style="padding: 2rem; text-align: center; color: var(--text-muted);">
        Loading your company details...
    </div>

    <div id="dashboard-content" class="hidden">
        <div style="background: #f1f5f9; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; border-left: 4px solid var(--primary);">
            <h3 style="margin-top: 0; color: #334155;" id="companyGreeting">Hello!</h3>
            <p style="margin-bottom: 0; color: #475569;">Manage everything about your company from this portal.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
            <!-- Profile Card -->
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onclick="window.location.href='{{ route('company.frontend.profile') }}'">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <h3 style="margin: 0; font-size: 1.25rem;">Company Profile</h3>
                    <div style="height: 48px; width: 48px; background: #e0e7ff; color: #4338ca; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        🏢
                    </div>
                </div>
                <p style="color: var(--text-muted); margin-bottom: 0;">Update your legal name, contact information, and registration details.</p>
                <div style="margin-top: 1.5rem; font-weight: 600; color: var(--primary);">Update Profile →</div>
            </div>

            <!-- Stats Placeholder -->
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <h3 style="margin: 0; font-size: 1.25rem;">Activity</h3>
                    <div style="height: 48px; width: 48px; background: #dcfce3; color: #166534; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        📊
                    </div>
                </div>
                <div style="font-size: 2rem; font-weight: 800; color: #1e293b;">Active</div>
                <p style="color: var(--text-muted); margin-bottom: 0; margin-top: 0.5rem;">Your account status</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    // Attempt to load some user context or state
    // In our case, the API companies setup is slug-based, so this is just a generic welcome
    const loading = document.getElementById('loading');
    const content = document.getElementById('dashboard-content');
    
    // Simulate slight loading of setup data
    setTimeout(() => {
        loading.classList.add('hidden');
        content.classList.remove('hidden');
    }, 500);
});
</script>
@endpush
