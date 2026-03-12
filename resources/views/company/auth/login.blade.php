@extends('layouts.company_app2', ['isAuthPage' => true])

@section('title', 'Company Login')

@push('styles')
<style>
    .auth-container {
        width: 100%;
        max-width: 420px;
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
    <div class="auth-logo">Company Portal</div>
    <div class="auth-sub">Sign in to manage your company</div>

    <div id="error-alert" class="alert alert-error hidden"></div>
    <div id="success-alert" class="alert alert-success hidden"></div>

    <form id="loginForm">
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" id="email" class="form-control" required placeholder="company@example.com">
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" id="password" class="form-control" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-auth" id="btnSubmit">Sign In</button>
    </form>
    
    <div class="auth-links">
        Don't have an account? <a href="{{ route('company.frontend.register') }}">Register here</a>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorAlert = document.getElementById('error-alert');
    const successAlert = document.getElementById('success-alert');
    const btnSubmit = document.getElementById('btnSubmit');
    
    errorAlert.classList.add('hidden');
    successAlert.classList.add('hidden');
    btnSubmit.disabled = true;
    btnSubmit.innerText = 'Signing In...';

    try {
        const response = await fetch(apiBaseUrl + '/company/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            const token = data.data?.token || null;
            const profile = data.data?.profile || null;

            if (token) {
                setAuthToken(token);
            }

            if (profile?.slug) {
                localStorage.setItem('company_slug', profile.slug);
            }

            successAlert.innerText = 'Login successful! Redirecting...';
            successAlert.classList.remove('hidden');

            setTimeout(() => {
                window.location.href = "{{ route('company.frontend.dashboard') }}";
            }, 1000);
        } else {
            errorAlert.innerText = data.message || 'Invalid credentials';
            errorAlert.classList.remove('hidden');
        }
    } catch (error) {
        errorAlert.innerText = 'Network error or server unavailable.';
        errorAlert.classList.remove('hidden');
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerText = 'Sign In';
    }
});
</script>
@endpush