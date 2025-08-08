@extends('layouts.default') {{-- Or your main layout --}}
@section('title', 'Handover/Takeover Log')
@section('content')

@push('styles')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
{{-- Make sure CSRF token is available for AJAX --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Handover/Takeover Log</h2>
        <a href="{{ route('takeover.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Record
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle" id="changeoverTable">
                    <thead class="table-dark">
                        <tr>
                           
                            <th>Type</th>
                            <th>Shift</th>
                            <th>Hostel</th>
                            <th>Performed By</th>
                            <th>Involved User</th>
                            <th>Comments</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($changeovers as $changeover)
                            <tr id="changeover-row-{{ $changeover->id }}">
                               
                                <td>
                                    <span class="badge {{ $changeover->changeover_type == 'take-over' ? 'bg-success' : 'bg-info' }}">
                                        {{ ucwords(str_replace('-', ' ', $changeover->changeover_type)) }}
                                    </span>
                                </td>
                                <td>{{ $changeover->shift }}</td>
                                <td>{{ $changeover->hostel->name ?? 'N/A' }}</td>
                                <td>{{ $changeover->actingUser->name ?? 'N/A' }}</td>
                                <td>{{ $changeover->involvedUser->name ?? 'N/A' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($changeover->comments, 50) }}</td>
                                <td>{{ $changeover->created_at->format('M d, Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                               <td></td>
                              
                               <td></td>
                               <td></td>
                               <td></td>
                               <td></td>
                               <td></td>
                               <td></td>
                              
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- DataTables Core JS --}}
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Column Visibility button (This is the one you're missing) -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

{{-- SweetAlert2 for beautiful alerts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#changeoverTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy', exportOptions: { columns: ':not(:first-child)' } },
                { extend: 'csv', exportOptions: { columns: ':not(:first-child)' } },
                { extend: 'excel', exportOptions: { columns: ':not(:first-child)' } },
                { 
                    extend: 'pdf', 
                    exportOptions: { columns: ':not(:first-child)' },
                    orientation: 'landscape',
                    pageSize: 'A4'
                },
                { extend: 'print', exportOptions: { columns: ':not(:first-child)' } },
                'colvis'
            ]
        });
    });

    // Handle Delete Button Click
    $(document).on('click', '.btn-delete-changeover', function () {
        const changeoverId = $(this).data('id');
        const url = $(this).data('url');

        Swal.fire({
            title: 'Are you sure?',
            text: "This record will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The record has been deleted.',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $(`#changeover-row-${changeoverId}`).fadeOut('slow', function () {
                            $(this).remove();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete the record.'
                        });
                    }
                });
            }
        });
    });
</script>
@endpush