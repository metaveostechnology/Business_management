@extends('layouts.app')

@section('title', 'Create Admin')

@section('content')
<div class="header">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('admins.index') }}" class="btn btn-outline" style="padding: 0.5rem;">←</a>
        <h1>Create Administrator</h1>
    </div>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('admins.store') }}" method="POST">
        @csrf
        
        <div class="grid">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="John Doe" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="john@example.com" required>
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="johndoe">
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number (10 digits)</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="1234567890">
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <div class="form-group" style="max-width: 385px;">
            <label class="form-label">Initial Status</label>
            <select name="status" class="form-control" required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
            </select>
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
            <a href="{{ route('admins.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Administrator</button>
        </div>
    </form>
</div>
@endsection
