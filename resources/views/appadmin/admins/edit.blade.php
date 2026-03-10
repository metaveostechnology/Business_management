@extends('layouts.appadmin')

@section('title', 'Edit Admin: ' . $admin->name)
@section('page-title', 'Edit Administrator')
@section('breadcrumb', 'Edit Admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <a href="{{ route('admins.index') }}" class="btn btn-soft-secondary btn-sm me-2">
                        <i class="ri-arrow-left-line"></i>
                    </a>
                    <h5 class="card-title mb-0">Edit Administrator: {{ $admin->name }}</h5>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admins.update', $admin->slug) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $admin->name) }}" 
                                placeholder="John Doe" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $admin->email) }}" 
                                placeholder="john@example.com" readonly 
                                style="background-color: #f3f6f9; cursor: not-allowed;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Email cannot be changed.</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                id="username" name="username" value="{{ old('username', $admin->username) }}" 
                                placeholder="johndoe">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" name="phone" value="{{ old('phone', $admin->phone) }}" 
                                placeholder="1234567890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border border-warning border-dashed">
                                <div class="card-header bg-warning-subtle">
                                    <h6 class="card-title mb-0 text-warning">
                                        <i class="ri-lock-line me-1"></i> Change Password (Optional)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="password" class="form-label">New Password</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                id="password" name="password" 
                                                placeholder="Leave blank to keep current">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" 
                                                id="password_confirmation" name="password_confirmation" 
                                                placeholder="Confirm new password">
                                        </div>
                                    </div>
                                    <small class="text-muted">Leave both fields empty if you don't want to change the password.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                                <option value="active" {{ old('status', $admin->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $admin->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="blocked" {{ old('status', $admin->status) == 'blocked' ? 'selected' : '' }}>Blocked</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Update Administrator
                        </button>
                        <a href="{{ route('admins.index') }}" class="btn btn-soft-secondary ms-2">
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
    .border-dashed {
        border-style: dashed !important;
    }
    .bg-warning-subtle {
        background-color: #fff3cd;
    }
    .text-warning {
        color: #856404 !important;
    }
</style>
@endpush