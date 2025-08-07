@extends('layouts.default')
@section('title', 'Escalations')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<div class="container mt-5">
    <h4 class="mb-4">Escalated Issues</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0" id="escalationTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Occurrence ID</th>
                            <th>Recipient</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Sent By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($escalations as $escalation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($escalation->created_at)->format('M d, Y h:i A') }}</td>
                                <td>{{ $escalation->occurrence_id }}</td>
                                <td>{{ $escalation->recipient_email }}</td>
                                <td>{{ $escalation->subject }}</td>
                                <td>{{ Str::limit($escalation->message, 50) }}</td>
                                <td>{{ $escalation->user->name ?? 'System' }}</td>
                            </tr>
                        @endforeach
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
        $('#escalationTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
        });
    });
</script>
@endpush
