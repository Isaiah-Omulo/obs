@extends('layouts.default')
@section('title', 'All reports')



@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

@endpush


@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="mb-0">Zonal Reports</h4>
        <a href="{{ route('daily_reports.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Report 
        </a>
    </div>
  


    {{-- session alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered mb-0" id="zonalTable">
                    <thead class="table-dark">
                        <tr>




                            @if (in_array(auth()->user()->role, ['director', 'manager']))
                            <th style="width: 160px;">Actions</th>
                            @endif
                            <th>Date & Time</th>
                            <th>Report</th>
                            <th>Shift</th>
                            <th>Zone</th>
                            <th>Reporter</th>
                            <th>Manager</th>
                            <th>Director</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dailyReports as $report)
                            <tr id="report-row-{{ $report->id }}">
                                @if (in_array(auth()->user()->role, ['director', 'manager']))
                                <td>

                                <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('daily_reports.edit', $report->id) }}" 
                                           class="btn btn-sm btn-warning m-1" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                       <form action="{{ route('daily_reports.destroy', $report->id) }}"
                                          method="POST"
                                          class="delete-form m-1"
                                          data-report-id="{{ $report->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                    <button class="btn btn-sm btn-info text-white m-1 btn-add-report-input" 
                                            data-id="{{ $report->id }}"
                                            data-role="{{ auth()->user()->role }}"
                                            data-input-url="{{ route('daily_reports.input', $report->id) }}"
                                            title="Add your Input"
                                            data-bs-toggle="modal"
                                            data-bs-target="#reportInputModal">
                                        <i class="fas fa-comment-medical"></i>
                                    </button>

                                    </div>
                                
                                    
                                </td>
                                @endif

                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}</strong><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($report->report_time)->format('h:i A') }}</small>
                                </td>
                                <td>{{ $report->report }}</td>
                                <td>{{ ucfirst($report->shift) }}</td>
                                <td>{{ $report->zone ?? '-' }}</td>

                                <td>{{ $report->user->name ?? 'N/A' }}</td>
                                <td id="manager-{{ $report->id }}">{{ $report->manager_input ?? 'N/A' }}</td>
                                <td id="director-{{ $report->id }}">{{ $report->director_input ?? 'N/A' }}</td>
                            </tr>
                        @empty
                           <tr>
                                 @if (in_array(auth()->user()->role, ['director', 'manager']))
                            <td style="width: 160px;"></td>
                            @endif
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

<div class="modal fade" id="reportInputModal" tabindex="-1" aria-labelledby="reportInputModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="reportInputForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reportInputModalLabel">Add Your Input</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="report_id" id="report_id">
          <input type="hidden" name="role" id="report_input_role">
          <div class="mb-3">
            <label for="report_input_text" class="form-label">Your Input</label>
            <textarea class="form-control" id="report_input_text" name="input_text" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Input</button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const form = this.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
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

<!-- Column Visibility button (This is the one you're missing) -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<script>
    $(document).ready(function() {
        $('#zonalTable').DataTable(
            {
            dom: 'Bfrtip',
            buttons: [
        {
            extend: 'copy',
            exportOptions: { columns: ':not(:first-child)' }
        },
       
        {
            extend: 'csv',
            exportOptions: { columns: ':not(:first-child)' }
        },
        {
            extend: 'excel',
            exportOptions: { columns: ':not(:first-child)' }
        },
        {
            extend: 'pdf',
            exportOptions: { columns: ':not(:first-child)' },
            orientation: 'landscape',
            pageSize: 'A4'
        },
        {
            extend: 'print',
            exportOptions: { columns: ':not(:first-child)' }
        },
        'colvis'
    ]
        }
        );
         // Custom search box
        $('#customSearchBox').on('keyup', function () {
            table.search(this.value).draw();
        });
    });
</script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    let inputModal = document.getElementById('reportInputModal');
    let inputForm = document.getElementById('reportInputForm');
    let submitUrl = '';

    // Set modal data from button click
    document.querySelectorAll('.btn-add-report-input').forEach(button => {
        button.addEventListener('click', () => {
            const reportId = button.dataset.id;
            const role = button.dataset.role;
            submitUrl = button.dataset.inputUrl;

            document.getElementById('report_id').value = reportId;
            document.getElementById('report_input_role').value = role;
        });
    });

    // Handle modal form submit
    inputForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const reportId = formData.get('report_id');
        const inputText = formData.get('input_text');
        const role = formData.get('role');

        fetch(submitUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {

                bootstrap.Modal.getInstance(inputModal).hide();
                inputForm.reset();
                // Optionally show feedback
                Swal.fire({
                    icon: 'success',
                    title: 'Input saved',
                    toast: true,
                    timer: 2000,
                    showConfirmButton: false,
                    position: 'top-end'
                });

                // Update respective cell
                if (role === 'manager') {
                    document.getElementById(`manager-${reportId}`).innerText = inputText;
                } else if (role === 'director') {
                    document.getElementById(`director-${reportId}`).innerText = inputText;
                }

                inputForm.reset();
                bootstrap.Modal.getInstance(inputModal).hide();
            } else {
                alert('Something went wrong.');
            }
        })
        .catch(() => Swal.fire('Error', 'Submission failed.', 'error'));
    });
});
</script>
@endpush



