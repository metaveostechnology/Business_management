@extends('layouts.app')

@section('title', 'Create Company')

@section('content')
<div class="header">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('admins.index') }}" class="btn btn-outline" style="padding: 0.5rem;">←</a>
        <h1>Register New Company</h1>
    </div>
</div>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <form action="{{ route('companies.store') }}" method="POST">
        @csrf
        
        <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; font-size: 1.125rem;">Basic Information</h3>
        
        <div class="grid">
            <div class="form-group">
                <label class="form-label">Company Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Acme Corporation" required>
            </div>
            <div class="form-group">
                <label class="form-label">Company Code (Unique)</label>
                <input type="text" name="code" value="{{ old('code') }}" class="form-control" placeholder="ACME-01" required>
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label class="form-label">Legal Name</label>
                <input type="text" name="legal_name" value="{{ old('legal_name') }}" class="form-control" placeholder="Acme International Ltd.">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="contact@acme.com">
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="1234567890">
            </div>
            <div class="form-group">
                <label class="form-label">Website URL</label>
                <input type="url" name="website" value="{{ old('website') }}" class="form-control" placeholder="https://www.acme.com">
            </div>
        </div>

        <h3 style="margin-top: 2rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; font-size: 1.125rem;">Settings & Location</h3>

        <div class="grid">
            <div class="form-group">
                <label class="form-label">Currency Code</label>
                <input type="text" name="currency_code" value="{{ old('currency_code', 'USD') }}" class="form-control" placeholder="USD" required>
            </div>
            <div class="form-group">
                <label class="form-label">Timezone</label>
                <input type="text" name="timezone" value="{{ old('timezone', 'UTC') }}" class="form-control" placeholder="UTC" required>
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 2fr 1fr;">
            <div class="form-group">
                <label class="form-label">Address Line 1</label>
                <input type="text" name="address_line1" value="{{ old('address_line1') }}" class="form-control" placeholder="123 Main St">
            </div>
            <div class="form-group">
                <label class="form-label">City</label>
                <input type="text" name="city" value="{{ old('city') }}" class="form-control" placeholder="New York">
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr 1fr;">
            <div class="form-group">
                <label class="form-label">State/Province</label>
                <input type="text" name="state" value="{{ old('state') }}" class="form-control" placeholder="NY">
            </div>
            <div class="form-group">
                <label class="form-label">Country</label>
                <input type="text" name="country" value="{{ old('country') }}" class="form-control" placeholder="USA">
            </div>
            <div class="form-group">
                <label class="form-label">Postal Code</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="form-control" placeholder="10001">
            </div>
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1rem;">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', '1') == '1' ? 'checked' : '' }} style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
            <label for="is_active" class="form-label" style="margin-bottom: 0; cursor: pointer;">Mark as Active immediately</label>
        </div>

        <div style="margin-top: 2.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
            <a href="{{ route('admins.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">Register Company</button>
        </div>
    </form>
</div>
@endsection
