@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<style>
    /* Override main content margin for login page */
    .main-content { margin-left: 0 !important; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 0 !important; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
    .sidebar { display: none; }
    .login-container { width: 100%; max-width: 420px; padding: 2.5rem; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.2); }
    .login-logo { font-size: 2.5rem; text-align: center; margin-bottom: 2rem; color: #4f46e5; font-family: 'Outfit', sans-serif; font-weight: 800; }
    .btn-login { width: 100%; padding: 0.875rem; font-size: 1rem; margin-top: 1rem; }
    .remember-me { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #64748b; margin-top: 1rem; }
</style>

<div class="login-container">
    <div class="login-logo">BizAdmin</div>
    
    <h3 style="text-align: center; margin-bottom: 1.5rem; color: #1e293b;">Welcome Back</h3>

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Email or Username</label>
            <input type="text" name="login" value="{{ old('login') }}" class="form-control" placeholder="admin@example.com" required autofocus>
        </div>

        <div class="form-group" style="margin-bottom: 0.5rem;">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="remember-me">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember" style="cursor: pointer;">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary btn-login">Sign In</button>
    </form>
</div>
@endsection
