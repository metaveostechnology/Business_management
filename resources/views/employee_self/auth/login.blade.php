@extends('layouts.employee_auth')

@section('title', 'Employee Self-Service Login')

@push('styles')
<style>
    .auth-logo { text-align: center; margin-bottom: 0.5rem; color: var(--primary); font-size: 2rem; font-weight: 800; }
    .auth-sub { text-align: center; margin-bottom: 2rem; color: var(--text-muted); font-size: 0.875rem; }
    .btn-auth { width: 100%; padding: 0.875rem; font-size: 1rem; margin-top: 1rem; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="auth-logo">Employee Portal</div>
    <div class="auth-sub">Sign in to your Self-Service Dashboard</div>

    <div id="error-alert" class="alert alert-error hidden"></div>
    <div id="success-alert" class="alert alert-success hidden"></div>

    <form id="loginForm">
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" id="email" class="form-control" required placeholder="employee@company.com">
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" id="password" class="form-control" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-auth" id="btnSubmit">Sign In</button>
    </form>
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
        const response = await fetch(apiBaseUrl + '/employee/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (response.ok && data.status) {
            setAuthToken(data.token);
            localStorage.setItem('branch_user', JSON.stringify(data.user));

            successAlert.innerText = 'Login successful! Redirecting...';
            successAlert.classList.remove('hidden');

            setTimeout(() => {
                window.location.href = "{{ route('employee_self.dashboard') }}";
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
