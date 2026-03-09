@extends('layouts.app')

@section('title', 'Administrators')

@section('content')
<div class="header">
    <h1>Administrators</h1>
    <a href="{{ route('admins.create') }}" class="btn btn-primary">+ New Admin</a>
</div>

<div class="card">
    <div style="margin-bottom: 1.5rem;">
        <form action="{{ route('admins.index') }}" method="GET" style="display: flex; gap: 1rem;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="form-control" style="max-width: 300px;">
            <select name="status" class="form-control" style="max-width: 150px;">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
            </select>
            <button type="submit" class="btn btn-outline">Filter</button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admins.index') }}" class="btn btn-outline" style="color: var(--danger);">Clear</a>
            @endif
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Joined</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($admins as $admin)
                <tr>
                    <td style="font-weight: 500;">{{ $admin->name }}</td>
                    <td style="color: var(--text-muted);">{{ $admin->email }}</td>
                    <td>
                        <span class="badge badge-{{ $admin->status }}">
                            {{ $admin->status }}
                        </span>
                    </td>
                    <td style="font-size: 0.875rem; color: var(--text-muted);">
                        {{ $admin->created_at->format('M d, Y') }}
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="{{ route('admins.show', $admin->slug) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem;">View</a>
                            <a href="{{ route('admins.edit', $admin->slug) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem;">Edit</a>
                            <form action="{{ route('admins.destroy', $admin->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.8rem; color: var(--danger);">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        No administrators found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $admins->links() }}
    </div>
</div>
@endsection
