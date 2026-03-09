@extends('layouts.app')

@section('title', 'Admin Details: ' . $admin->name)

@section('content')
<div class="header">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('admins.index') }}" class="btn btn-outline" style="padding: 0.5rem;">←</a>
        <h1>Administrator Details</h1>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('admins.edit', $admin->slug) }}" class="btn btn-primary">Edit Details</a>
    </div>
</div>

<div class="grid">
    <div class="card">
        <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Personal Information</h3>
        
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Full Name</label>
                <p style="font-size: 1.125rem; font-weight: 500;">{{ $admin->name }}</p>
            </div>
            
            <div>
                <label style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Email Address</label>
                <p style="font-size: 1rem;">{{ $admin->email }}</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Username</label>
                    <p>{{ $admin->username ?? 'N/A' }}</p>
                </div>
                <div>
                    <label style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Phone</label>
                    <p>{{ $admin->phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Account Status</h3>
        
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Current Status</label>
                <div style="margin-top: 0.25rem;">
                    <span class="badge badge-{{ $admin->status }}" style="font-size: 1rem; padding: 0.4rem 1rem;">
                        {{ $admin->status }}
                    </span>
                </div>
            </div>

            <div>
                <label style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Joined Date</label>
                <p>{{ $admin->created_at->format('F d, Y') }} ({{ $admin->created_at->diffForHumans() }})</p>
            </div>

            <div>
                <label style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 600;">Last Updated</label>
                <p>{{ $admin->updated_at->format('F d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <h3 style="margin-bottom: 1rem; color: var(--danger);">Danger Zone</h3>
    <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.875rem;">Deleting this administrator account is a permanent action. All associated data will be soft-deleted and may be recoverable by a super-admin.</p>
    
    <form action="{{ route('admins.destroy', $admin->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this administrator?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline" style="border-color: var(--danger); color: var(--danger);">Delete Account</button>
    </form>
</div>
@endsection
