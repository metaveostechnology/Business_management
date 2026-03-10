@extends('layouts.appadmin')

@section('title', 'Edit Company - ' . $company->name)
@section('page-title', 'Edit Company')
@section('breadcrumb', 'Edit Company')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <a href="{{ route('companies.index') }}" class="btn btn-soft-secondary btn-sm me-2">
                        <i class="ri-arrow-left-line"></i>
                    </a>
                    <h5 class="card-title mb-0">Edit Company: {{ $company->name }}</h5>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('companies.update', $company->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information Section -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3 text-primary">
                                <i class="ri-information-line me-1"></i> Basic Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $company->name) }}" 
                                placeholder="Acme Corporation" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Company Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                id="code" name="code" value="{{ old('code', $company->code) }}" 
                                placeholder="ACME-01" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Unique identifier used for subdomains or references.</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="legal_name" class="form-label">Legal Name</label>
                            <input type="text" class="form-control @error('legal_name') is-invalid @enderror" 
                                id="legal_name" name="legal_name" value="{{ old('legal_name', $company->legal_name) }}" 
                                placeholder="Acme International Ltd.">
                            @error('legal_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $company->email) }}" 
                                placeholder="contact@acme.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" name="phone" value="{{ old('phone', $company->phone) }}" 
                                placeholder="1234567890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="website" class="form-label">Website URL</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                id="website" name="website" value="{{ old('website', $company->website) }}" 
                                placeholder="https://www.acme.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Settings & Location Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3 text-primary">
                                <i class="ri-settings-line me-1"></i> Settings & Location
                            </h6>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="currency_code" class="form-label">Currency Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('currency_code') is-invalid @enderror" 
                                id="currency_code" name="currency_code" value="{{ old('currency_code', $company->currency_code) }}" 
                                placeholder="USD" required>
                            @error('currency_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="timezone" class="form-label">Timezone <span class="text-danger">*</span></label>
                            <select class="form-control @error('timezone') is-invalid @enderror" 
                                id="timezone" name="timezone" required>
                                <option value="UTC" {{ old('timezone', $company->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="Asia/Kolkata" {{ old('timezone', $company->timezone) == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                                <option value="America/New_York" {{ old('timezone', $company->timezone) == 'America/New_York' ? 'selected' : '' }}>New York (EST)</option>
                                <option value="Europe/London" {{ old('timezone', $company->timezone) == 'Europe/London' ? 'selected' : '' }}>London (GMT)</option>
                                <option value="Asia/Dubai" {{ old('timezone', $company->timezone) == 'Asia/Dubai' ? 'selected' : '' }}>Dubai (GST)</option>
                                <option value="Asia/Singapore" {{ old('timezone', $company->timezone) == 'Asia/Singapore' ? 'selected' : '' }}>Singapore (SGT)</option>
                                <option value="Australia/Sydney" {{ old('timezone', $company->timezone) == 'Australia/Sydney' ? 'selected' : '' }}>Sydney (AEST)</option>
                            </select>
                            @error('timezone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tax_number" class="form-label">Tax Number</label>
                            <input type="text" class="form-control @error('tax_number') is-invalid @enderror" 
                                id="tax_number" name="tax_number" value="{{ old('tax_number', $company->tax_number) }}" 
                                placeholder="123-456-789">
                            @error('tax_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="registration_number" class="form-label">Registration Number</label>
                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                id="registration_number" name="registration_number" value="{{ old('registration_number', $company->registration_number) }}" 
                                placeholder="REG-12345">
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-8 mb-3">
                            <label for="address_line1" class="form-label">Address Line 1</label>
                            <input type="text" class="form-control @error('address_line1') is-invalid @enderror" 
                                id="address_line1" name="address_line1" value="{{ old('address_line1', $company->address_line1) }}" 
                                placeholder="123 Main St">
                            @error('address_line1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="address_line2" class="form-label">Address Line 2</label>
                            <input type="text" class="form-control @error('address_line2') is-invalid @enderror" 
                                id="address_line2" name="address_line2" value="{{ old('address_line2', $company->address_line2) }}" 
                                placeholder="Suite 100">
                            @error('address_line2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                id="city" name="city" value="{{ old('city', $company->city) }}" 
                                placeholder="New York">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="state" class="form-label">State/Province</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                id="state" name="state" value="{{ old('state', $company->state) }}" 
                                placeholder="NY">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                id="country" name="country" value="{{ old('country', $company->country) }}" 
                                placeholder="USA">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                id="postal_code" name="postal_code" value="{{ old('postal_code', $company->postal_code) }}" 
                                placeholder="10001">
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                    id="is_active" {{ old('is_active', $company->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Company is Active
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Logo Upload Section (Optional) -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3 text-primary">
                                <i class="ri-image-line me-1"></i> Company Logo
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            @if($company->logo)
                                <div class="mb-3">
                                    <label class="form-label">Current Logo</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" 
                                            class="avatar-xl rounded-circle">
                                    </div>
                                </div>
                            @endif
                            
                            <label for="logo" class="form-label">Upload New Logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Recommended size: 200x200px. Max size: 2MB</small>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Update Company
                        </button>
                        <a href="{{ route('companies.index') }}" class="btn btn-soft-secondary ms-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.5em;
        margin-top: 0.15rem;
        cursor: pointer;
    }
    .form-switch .form-check-label {
        padding-left: 0.5rem;
        cursor: pointer;
    }
    .avatar-xl {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 3px solid #f3f6f9;
    }
</style>
@endpush