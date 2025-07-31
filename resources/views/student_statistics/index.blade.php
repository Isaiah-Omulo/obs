@extends('layouts.default')
@section('title', 'All Student Statistics')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">Student Attendance Statistics</h4>
        <a href="{{ route('student_statistics.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Record
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered mb-0" id="statisticsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Reported By</th>
                            <th>Hostel</th>
                            <th>Zone</th>
                            <th>Date</th>
                            <th>Shift</th>
                            <th>Students Present</th>
                            <th>Comments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($studentStatistics as $stat)
                            <tr>
                                <td>{{ $stat->user->name ?? 'N/A' }}</td>
                                <td>{{ $stat->hostel->name ?? 'N/A' }}</td>
                                <td>{{ $stat->hostel->zone->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($stat->record_date)->format('M d, Y') }}</td>
                                <td>{{ ucfirst($stat->shift) }}</td>
                                <td>{{ $stat->students_present }}</td>
                                <td>{{ $stat->comments ?? '-' }}</td>
                                <td>
                                    <form id="delete-form-{{ $stat->id }}" action="{{ route('student_statistics.destroy', $stat->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $stat->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No statistics recorded yet.</td>
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<script>
    $(document).ready(function () {
        $('#statisticsTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy', exportOptions: { columns: ':visible' }},
                { extend: 'csv', exportOptions: { columns: ':visible' }},
                { extend: 'excel', exportOptions: { columns: ':visible' }},
                { extend: 'pdf', exportOptions: { columns: ':visible' }, orientation: 'landscape', pageSize: 'A4' },
                { extend: 'print', exportOptions: { columns: ':visible' }},
                'colvis'
            ]
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');

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
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            });
        });
    });
</script>

@endpush
