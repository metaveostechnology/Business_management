@extends('layouts.app')

@section('title', 'Edit Admin: ' . $admin->name)

@section('content')
<div class="header">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('admins.index') }}" class="btn btn-outline" style="padding: 0.5rem;">←</a>
        <h1>Edit Administrator</h1>
    </div>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('admins.update', $admin->slug) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control" placeholder="John Doe" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control" placeholder="john@example.com" readonly style="background: #f1f5f9; cursor: not-allowed;">
                <small style="color: var(--text-muted);">Email cannot be changed.</small>
            </div>
        </div>

        <div class="grid">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="{{ old('username', $admin->username) }}" class="form-control" placeholder="johndoe">
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number (10 digits)</label>
                <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" class="form-control" placeholder="1234567890">
            </div>
        </div>

        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px dashed var(--border);">
            <h4 style="margin-bottom: 1rem; font-size: 0.875rem;">Change Password (Optional)</h4>
            <div class="grid" style="margin-bottom: 0;">
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                </div>
            </div>
        </div>

        <div class="form-group" style="max-width: 385px;">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="active" {{ old('status', $admin->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $admin->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="blocked" {{ old('status', $admin->status) == 'blocked' ? 'selected' : '' }}>Blocked</option>
            </select>
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
            <a href="{{ route('admins.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Administrator</button>
        </div>
    </form>
</div>
@endsection
