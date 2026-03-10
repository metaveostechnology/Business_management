@extends('layouts.appadmin')

@section('title', 'Companies')
@section('page-title', 'Companies')
@section('breadcrumb', 'Companies')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">All Companies</h5>
                    <a href="{{ route('companies.create') }}" class="btn btn-primary"><i class="ri-add-line align-bottom me-1"></i> Register Company</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="companies-datatable" class="table table-bordered nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Legal Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Registered Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td>{{ $company->name }}</td>
                                <td><span class="badge bg-light text-dark">{{ $company->code }}</span></td>
                                <td>{{ $company->legal_name ?? '-' }}</td>
                                <td>{{ $company->email ?? '-' }}</td>
                                <td>
                                    @if($company->is_active)
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $company->created_at->format('d M, Y') }}</td>
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="{{ route('companies.show', $company->slug) }}" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                            <li><a href="{{ route('companies.edit', $company->slug) }}" class="dropdown-item"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li>
                                               <form action="{{ route('companies.destroy',  $company->slug) }}" method="POST" onsubmit="return confirm('Delete this company?')">
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#companies-datatable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            order: [[0, 'desc']],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search companies...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ companies",
                paginate: {
                    first: '<i class="ri-arrow-left-double-line"></i>',
                    previous: '<i class="ri-arrow-left-s-line"></i>',
                    next: '<i class="ri-arrow-right-s-line"></i>',
                    last: '<i class="ri-arrow-right-double-line"></i>'
                }
            },
            columnDefs: [
                { orderable: false, targets: 7 }
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