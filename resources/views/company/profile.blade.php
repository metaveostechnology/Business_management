@extends('layouts.company_app')

@section('title', 'Company Profile')

@section('content')
<div class="card">
    <h2 class="header-title">My Company Profile</h2>
    
    <div id="loading" style="padding: 2rem; text-align: center; color: var(--text-muted);">
        Initializing...
    </div>

    <div id="profile-content" class="hidden">
        <div id="error-alert" class="alert alert-error hidden"></div>
        <div id="success-alert" class="alert alert-success hidden"></div>

        <form id="profileForm">
            <!-- Basic Info -->
            <h3 style="border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; color: #334155;">Basic Information</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Company Name *</label>
                    <input type="text" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Company Code *</label>
                    <input type="text" id="code" class="form-control" required placeholder="e.g. COMP-001">
                </div>
                <div class="form-group">
                    <label class="form-label">Legal Name *</label>
                    <input type="text" id="legal_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" id="phone" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Website</label>
                    <input type="url" id="website" class="form-control" placeholder="https://example.com">
                </div>
            </div>

            <!-- Tax & Legal -->
            <h3 style="border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; margin: 2rem 0 1.5rem; color: #334155;">Legal & Financial</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Tax Number</label>
                    <input type="text" id="tax_number" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Registration Number</label>
                    <input type="text" id="registration_number" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Currency Code</label>
                    <input type="text" id="currency_code" class="form-control" placeholder="USD">
                </div>
                <div class="form-group">
                    <label class="form-label">Timezone</label>
                    <input type="text" id="timezone" class="form-control" placeholder="UTC">
                </div>
            </div>

            <!-- Address -->
            <h3 style="border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; margin: 2rem 0 1.5rem; color: #334155;">Address Details</h3>
            <div class="form-group">
                <label class="form-label">Address Line 1</label>
                <input type="text" id="address_line1" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Address Line 2</label>
                <input type="text" id="address_line2" class="form-control">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" id="city" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">State / Province</label>
                    <input type="text" id="state" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="text" id="country" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Postal Code</label>
                    <input type="text" id="postal_code" class="form-control">
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn" id="btnSubmit">Save Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentSlug = localStorage.getItem('company_slug') || null;

document.addEventListener('DOMContentLoaded', async () => {
    const loading = document.getElementById('loading');
    const content = document.getElementById('profile-content');
    
    // Automatically try to resolve slug
    if (getAuthToken()) {
        try {
            // If we don't know the slug, we could attempt to fetch an index of companies or the user's company relation.
            // Based on constraints, assume first hit to `companies` index returns user's accessible company logic:
            const response = await apiRequest('/companies', 'GET');
            if (response.data && response.data.length > 0) {
                const company = response.data[0];
                currentSlug = company.slug;
                localStorage.setItem('company_slug', currentSlug);
                populateForm(company);
            }
        } catch (error) {
            console.error(error);
        }
    }
    
    loading.classList.add('hidden');
    content.classList.remove('hidden');
});

function populateForm(data) {
    const fields = ['name', 'code', 'legal_name', 'email', 'phone', 'website', 'tax_number', 'registration_number', 'currency_code', 'timezone', 'address_line1', 'address_line2', 'city', 'state', 'country', 'postal_code'];
    fields.forEach(f => {
        if(document.getElementById(f)) {
            document.getElementById(f).value = data[f] || '';
        }
    });
}

document.getElementById('profileForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const btnSubmit = document.getElementById('btnSubmit');
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    
    btnSubmit.disabled = true;
    btnSubmit.innerText = 'Saving...';
    successAlert.classList.add('hidden');
    errorAlert.classList.add('hidden');
    
    const payload = {};
    const fields = ['name', 'code', 'legal_name', 'email', 'phone', 'website', 'tax_number', 'registration_number', 'currency_code', 'timezone', 'address_line1', 'address_line2', 'city', 'state', 'country', 'postal_code'];
    fields.forEach(f => {
        if(document.getElementById(f)) {
            payload[f] = document.getElementById(f).value;
        }
    });

    try {
        let endpoint = '/companies';
        let method = 'POST';
        
        if (currentSlug) {
            endpoint = `/companies/${currentSlug}`;
            method = 'PUT';
        }

        const data = await apiRequest(endpoint, method, payload);
        
        successAlert.innerText = 'Profile saved successfully!';
        successAlert.classList.remove('hidden');
        
        if (data.data && data.data.slug) {
            currentSlug = data.data.slug;
            localStorage.setItem('company_slug', currentSlug);
        }
    } catch (error) {
        errorAlert.innerText = 'Failed to save profile. ' + (error.data?.message || '');
        if (error.data?.errors) {
            errorAlert.innerText += ' ' + Object.values(error.data.errors).flat().join(' ');
        }
        errorAlert.classList.remove('hidden');
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerText = 'Save Profile';
        window.scrollTo(0, 0);
    }
});
</script>
@endpush
