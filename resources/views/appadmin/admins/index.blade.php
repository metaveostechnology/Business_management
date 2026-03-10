@extends('layouts.appadmin')

@section('title', 'Administrators')
@section('page-title', 'Administrators')
@section('breadcrumb', 'Admins')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">All Administrators</h5>
                    <a href="{{ route('admins.create') }}" class="btn btn-primary"><i class="ri-add-line align-bottom me-1"></i> Add New Admin</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="admins-datatable" class="table table-bordered nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->phone ?? 'N/A' }}</td>
                                <td>
                                    @if($admin->status == 'active')
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    @elseif($admin->status == 'inactive')
                                        <span class="badge bg-warning-subtle text-warning">Inactive</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Blocked</span>
                                    @endif
                                </td>
                                <td>{{ $admin->created_at->format('d M, Y') }}</td>
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="{{ route('admins.show', $admin->slug) }}" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                            <li><a href="{{ route('admins.edit', $admin->slug) }}" class="dropdown-item"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li>
 
                                                <form action="{{ route('admins.destroy', $admin->slug) }}" method="POST" onsubmit="return confirm('Delete this admin?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-soft-secondary {
        padding: 0.25rem 0.5rem;
    }
    .dropdown-item {
        cursor: pointer;
    }
    .remove-item-btn {
        color: #f06548 !important;
    }
    .remove-item-btn:hover {
        background-color: #f06548 !important;
        color: #fff !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#admins-datatable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[0, 'desc']],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search admins...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ admins",
                paginate: {
                    first: '<i class="ri-arrow-left-double-line"></i>',
                    previous: '<i class="ri-arrow-left-s-line"></i>',
                    next: '<i class="ri-arrow-right-s-line"></i>',
                    last: '<i class="ri-arrow-right-double-line"></i>'
                }
            },
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="ri-file-copy-line"></i> Copy',
                    className: 'btn btn-sm btn-soft-primary'
                },
                {
                    extend: 'csv',
                    text: '<i class="ri-file-excel-line"></i> CSV',
                    className: 'btn btn-sm btn-soft-success'
                },
                {
                    extend: 'excel',
                    text: '<i class="ri-file-excel-2-line"></i> Excel',
                    className: 'btn btn-sm btn-soft-success'
                },
                {
                    extend: 'pdf',
                    text: '<i class="ri-file-pdf-line"></i> PDF',
                    className: 'btn btn-sm btn-soft-danger'
                },
                {
                    extend: 'print',
                    text: '<i class="ri-printer-line"></i> Print',
                    className: 'btn btn-sm btn-soft-info'
                }
            ],
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
            initComplete: function() {
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                $('.dataTables_length select').addClass('form-select form-select-sm');
                $('.dt-buttons').addClass('mb-3').find('button').removeClass('btn-secondary').addClass('me-1');
            }
        });
    });
</script>
@endpush