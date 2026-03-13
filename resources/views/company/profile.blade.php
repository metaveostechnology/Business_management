@extends('layouts.company_app')

@section('title', 'Company Profile')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-0">
                <h4 class="card-title mb-0">My Company Profile</h4>
            </div>
            <div class="card-body">
                <div id="loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div id="profile-content" class="d-none">
                    <div id="alert-container"></div>

                    <form id="profileForm">
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fs-15 mb-3">Basic Information</h5>
                            </div>

                            <div class="col-md-12 mb-4 d-flex align-items-center gap-3">
                                <div>
                                    <img id="logoPreview"
                                         src="{{ asset('appadmin/assets/images/users/avatar-1.jpg') }}"
                                         alt="Company Logo"
                                         class="rounded-circle avatar-xl shadow"
                                         style="object-fit: cover; border: 2px solid #e2e8f0;">
                                </div>
                                <div class="flex-grow-1">
                                    <label for="logo" class="form-label">Company Logo</label>
                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                    <div class="form-text">Optional. Max size: 5MB. Formats: jpeg, png, jpg, gif, webp.</div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Company Code</label>
                                <input type="text" class="form-control" id="code" name="code">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="legal_name" class="form-label">Legal Name</label>
                                <input type="text" class="form-control" id="legal_name" name="legal_name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control bg-light" id="email" name="email" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website" placeholder="https://example.com">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fs-15 mb-3">Legal & Financial</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tax_number" class="form-label">Tax Number</label>
                                <input type="text" class="form-control" id="tax_number" name="tax_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="registration_number" class="form-label">Registration Number</label>
                                <input type="text" class="form-control" id="registration_number" name="registration_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="currency_code" class="form-label">Currency Code</label>
                                <input type="text" class="form-control" id="currency_code" name="currency_code" placeholder="e.g., INR">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <input type="text" class="form-control" id="timezone" name="timezone" placeholder="e.g., Asia/Kolkata">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fs-15 mb-3">Address Details</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address_line1" class="form-label">Address Line 1</label>
                                <input type="text" class="form-control" id="address_line1" name="address_line1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address_line2" class="form-label">Address Line 2</label>
                                <input type="text" class="form-control" id="address_line2" name="address_line2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">State / Province</label>
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code">
                            </div>
                        </div>

                      <div class="text-end d-flex justify-content-end gap-2">
    <button type="button" class="btn btn-light" id="btnCancel">
        <i class="ri-arrow-left-line align-middle me-1"></i> Cancel
    </button>
    <button type="submit" class="btn btn-primary" id="btnSubmit">
        <i class="ri-save-line align-middle me-1"></i> Save Profile
    </button>
</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
let currentSlug = localStorage.getItem('company_slug') || null;

document.getElementById('btnCancel')?.addEventListener('click', function () {
    window.location.href = "{{ route('company.frontend.dashboard') }}";
});
document.addEventListener('DOMContentLoaded', async () => {
    const loading = document.getElementById('loading');
    const content = document.getElementById('profile-content');

    try {
        if (!getAuthToken()) {
            showAlert('danger', 'Authentication token not found. Please login again.');
            return;
        }

        const response = await apiRequest('/company/profile', 'GET');

        console.log('Profile API response:', response);

        // Your backend returns: { success, message, data: {...profile fields...} }
        const profileData = response?.data || null;

        if (!profileData) {
            throw new Error('No profile data found in API response.');
        }

        if (profileData.slug) {
            currentSlug = profileData.slug;
            localStorage.setItem('company_slug', currentSlug);
        }

        // Update company_user for global layout use
        localStorage.setItem('company_user', JSON.stringify(profileData));

        populateForm(profileData);

    } catch (error) {
        console.error('Failed to load profile', error);
        showAlert('danger', 'Failed to load company profile.');
    } finally {
        loading.classList.add('d-none');
        content.classList.remove('d-none');
    }
});

// Logo preview
document.getElementById('logo')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('logoPreview').src = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
});

function populateForm(data) {
    console.log('Populate form data:', data);
    originalCode = data.code || '';

    const fields = [
        'name', 'code', 'legal_name', 'email', 'phone', 'website',
        'tax_number', 'registration_number', 'currency_code', 'timezone',
        'address_line1', 'address_line2', 'city', 'state', 'country', 'postal_code'
    ];

    fields.forEach(field => {
        const el = document.getElementById(field);
        if (el) {
            el.value = data[field] ?? '';
        }
    });

    const logoValue = data.logo || data.logo_path || '';

    if (logoValue) {
        const preview = document.getElementById('logoPreview');
        let logoUrl = logoValue;

        if (logoUrl.startsWith('http://') || logoUrl.startsWith('https://')) {
            preview.src = logoUrl;
        } else if (logoUrl.startsWith('/storage/')) {
            preview.src = logoUrl;
        } else if (logoUrl.startsWith('storage/')) {
            preview.src = '/' + logoUrl;
        } else {
            preview.src = '/storage/' + logoUrl.replace(/^\/+/, '');
        }
    }

    const nameEls = document.querySelectorAll('[data-company-name]');
    nameEls.forEach(el => {
        el.innerText = data.name || 'Company User';
    });
}
function showAlert(type, message) {
    const container = document.getElementById('alert-container');
    if (!container) return;

    container.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.getElementById('profileForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const btnSubmit = document.getElementById('btnSubmit');
    const originalBtnText = btnSubmit.innerHTML;

    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';

    document.getElementById('alert-container').innerHTML = '';

    const formData = new FormData();
    const fields = [
        'name', 'code', 'legal_name', 'phone', 'website',
        'tax_number', 'registration_number', 'currency_code', 'timezone',
        'address_line1', 'address_line2', 'city', 'state', 'country', 'postal_code'
    ];

    fields.forEach(field => {
    const el = document.getElementById(field);
    if (!el) return;

    const value = el.value.trim();

    if (field === 'code') {
        if (value !== originalCode) {
            formData.append('code', value);
        }
    } else {
        formData.append(field, value);
    }
});

    const logoInput = document.getElementById('logo');
    if (logoInput && logoInput.files.length > 0) {
        formData.append('logo', logoInput.files[0]);
    }

    try {
        if (!currentSlug) {
            throw new Error('Company slug not found. Please login again.');
        }

        formData.append('_method', 'PUT');

        const response = await fetch(API_BASE + `/companies/${currentSlug}`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();
        console.log('Update response:', result);

        if (!response.ok) {
            throw { data: result };
        }

        showAlert('success', result.message || 'Profile updated successfully!');

        // backend returns { success, message, data: {...updated company...} }
        if (result.data) {
            if (result.data.slug) {
                currentSlug = result.data.slug;
                localStorage.setItem('company_slug', currentSlug);
            }

            populateForm(result.data);
        }

    } catch (error) {
        console.error('Update error:', error);

        let msg = 'Failed to save profile.';

        if (error.data?.message) {
            msg += ' ' + error.data.message;
        }

        if (error.data?.errors) {
            msg += '<br>' + Object.values(error.data.errors).flat().join('<br>');
        }

        if (error.message) {
            msg += ' ' + error.message;
        }

        showAlert('danger', msg);
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalBtnText;
    }
});
</script>
@endpush