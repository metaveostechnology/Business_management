@extends('layouts.company_app2', ['isAuthPage' => true])

@section('title', 'Company Registration')

@push('styles')
<style>
    .auth-container {
        width: 100%;
        max-width: 480px;
        padding: 2.5rem;
        background: rgba(255, 255, 255, 0.98);
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
    }
    .auth-logo { text-align: center; margin-bottom: 0.5rem; color: var(--primary); font-size: 2rem; font-weight: 800; }
    .auth-sub { text-align: center; margin-bottom: 2rem; color: var(--text-muted); font-size: 0.875rem; }
    .btn-auth { width: 100%; padding: 0.875rem; font-size: 1rem; margin-top: 1rem; }
    .auth-links { text-align: center; margin-top: 1.5rem; font-size: 0.875rem; }
    .auth-links a { color: var(--primary); text-decoration: none; font-weight: 600; }
    .auth-links a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-logo">Join Company Portal</div>
    <div class="auth-sub">Create an account for your business</div>

    <div id="error-alert" class="alert alert-error hidden"></div>
    <div id="success-alert" class="alert alert-success hidden"></div>

    <form id="registerForm">
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" id="email" class="form-control" required placeholder="business@example.com">
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" id="password" class="form-control" required placeholder="Min 8 characters">
        </div>

        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" id="password_confirmation" class="form-control" required placeholder="Re-type password">
        </div>

        <button type="submit" class="btn btn-auth" id="btnSubmit">Create Account</button>
    </form>
    
    <div class="auth-links">
        Already have an account? <a href="{{ route('company.frontend.login') }}">Log in here</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('registerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('password_confirmation').value;
    
    const errorAlert = document.getElementById('error-alert');
    const successAlert = document.getElementById('success-alert');
    const btnSubmit = document.getElementById('btnSubmit');
    
    errorAlert.classList.add('hidden');
    successAlert.classList.add('hidden');
    
    if (password !== password_confirmation) {
        errorAlert.innerText = 'Passwords do not match.';
        errorAlert.classList.remove('hidden');
        return;
    }

    btnSubmit.disabled = true;
    btnSubmit.innerText = 'Creating Account...';

    try {
        const response = await fetch(apiBaseUrl + '/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password, password_confirmation })
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            successAlert.innerText = 'Registration successful! You can now log in.';
            successAlert.classList.remove('hidden');
            setTimeout(() => {
                window.location.href = "{{ route('company.frontend.login') }}";
            }, 2000);
        } else {
            errorAlert.innerText = data.message || 'Validation failed. Check your data.';
            if (data.errors) {
                const msgs = Object.values(data.errors).flat().join(' ');
                errorAlert.innerText += ' ' + msgs;
            }
            errorAlert.classList.remove('hidden');
        }
    } catch (error) {
        errorAlert.innerText = 'Network error or server unavailable.';
        errorAlert.classList.remove('hidden');
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerText = 'Create Account';
    }
});
</script>
@endpush
