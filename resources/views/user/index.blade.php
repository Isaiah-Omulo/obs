@extends('layouts.default')

@section('title', 'All Users')


@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">


@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <div>
            <h1 class="h3 mb-0 text-dark">All Users</h1>
            <small class="text-muted">View and manage all registered users in the system.</small>
        </div>
        <a href="{{ route('user.create') }}" class="btn btn-success">
            <i class="fa fa-user-plus me-1"></i> New User
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle me-2"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th class="text-center" style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    @php
                                        $roleClass = match($user->role) {
                                            'administrator' => 'bg-danger',
                                            'director' => 'bg-warning text-dark',
                                            'manager' => 'bg-info',
                                            'zonal_officer' => 'bg-primary',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $roleClass }}">
                                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-center">
                                     <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                <button class="btn btn-danger btn-sm btn-delete-user" 
                                        data-url="{{ route('user.destroy', $user->id) }}">
                                    <i class="fa fa-trash"></i> Delete
                                </button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fa fa-users-slash fa-2x mb-2"></i><br>
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete-user');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const deleteUrl = this.getAttribute('data-url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This user will be permanently deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#usersTable').DataTable(
            {
            dom: 'Bfrtip',
            buttons: [
        {
            extend: 'copy',
            exportOptions: { columns: ':not(:last-child)' }
        },
        {
            extend: 'csv',
            exportOptions: { columns: ':not(:last-child)' }
        },
        {
            extend: 'excel',
            exportOptions: { columns: ':not(:last-child)' }
        },
        {
            extend: 'pdf',
            exportOptions: { columns: ':not(:last-child)' },
            orientation: 'landscape',
            pageSize: 'A4'
        },
        {
            extend: 'print',
            exportOptions: { columns: ':not(:last-child)' }
        }
    ]
        }
        );
         // Custom search box
        $('#customSearchBox').on('keyup', function () {
            table.search(this.value).draw();
        });
    });
</script>



@endpush
