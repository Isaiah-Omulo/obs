@extends('layouts.default')
@section('title', 'All reports')



@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

@endpush

@push('css')

<style>
    
    .custom-tabs .nav-link {
        color: black;
        border: 3px solid transparent;
        border-bottom-color: #dee2e6;
    }

    .custom-tabs .nav-link.active {
        color: #fff; 
        background-color: #0d6efd; 
        border-color: #0d6efd;
    }

    
    .custom-tabs .nav-link:not(.active):hover {
        border-color: #94a5a4 #94a5a4 #94a5a4;
        color: #94a5a4;
    }
</style>
@endpush

@php
 $currentUserRole = Auth::user()->role;
 $isKeeper = Str::contains($currentUserRole, 'keeper') || Str::contains($currentUserRole, 'attendant');
@endphp

@section('content')
<div class="container mt-5">

    {{-- =================================================================== --}}
    {{-- 1. FIRST ROW: HEADER CONTROLS                                       --}}
    {{-- =================================================================== --}}
    <div class="row bg-light p-3 mb-4 rounded align-items-center" style="border: 3px solid #dee2e6;">
        
        {{-- Column for the Title --}}
        <div class="col-md-4">
            <h4 class="mb-0">Reports Section</h4>
        </div>
        
        {{-- Column for the Date Filter --}}
        <div class="col-md-4 ">
           
        </div>
        
        {{-- Column for the Add New Report Button --}}
        <div class="col-md-4 text-end">
            <a href="{{ route('daily_reports.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Report 
            </a>
        </div>

    </div>


 {{-- session alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
  
  @if(!$isKeeper)
    <div class="row">
        <div class="col-12">
            <!-- The Tab Navigation Links with custom class -->
            <ul class="nav nav-tabs custom-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#attendants_tab">Night Attendant / House Keeper </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#supervisors_tab">Supervisor / Zonal Officer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#duty_officers_tab">Duty Officer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#coordinators_tab">Coordinator / Administrator</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- The Tab Content Panes -->
<div class="tab-content mt-3">
    
    {{-- Pane 1: Corresponds to the first tab link --}}
    <div class="tab-pane container active" id="attendants_tab">
        <div class="card card-body">
            @include('daily_report.pages._attendants_report_table')
        </div>
    </div>

    {{-- Pane 2: Corresponds to the second tab link --}}
    <div class="tab-pane container fade" id="supervisors_tab">
        <div class="card card-body">
            {{-- Reports for Supervisors/Zonal Officers will be listed here --}}
             @include('daily_report.pages._zonal_report_table')
        </div>
    </div>

    {{-- Pane 3: Corresponds to the third tab link --}}
    <div class="tab-pane container fade" id="duty_officers_tab">
        <div class="card card-body">
            {{-- Reports for Duty Officers will be listed here --}}
           @include('daily_report.pages._duty_report_table')
        </div>
    </div>

    {{-- Pane 4: Corresponds to the fourth tab link --}}
    <div class="tab-pane container fade" id="coordinators_tab">
        <div class="card card-body">
            {{-- Reports for Coordinators/Administrators will be listed here --}}
            @include('daily_report.pages._coordinator_report_table')
           
        </div>
    </div>

</div>

@else
 <div class="card card-body">
            @include('daily_report.pages._attendants_report_table')
 </div>
@endif

   

    {{-- table 
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
                            <th>Report By</th>
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

                                <td data-order="{{ $report->report_date }} {{ $report->report_time }}">
                                    <strong>{{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}</strong><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($report->report_time)->format('h:i A') }}</small>
                                </td>
                              
                                <td>{!! nl2br(e(Str::words($report->report, 40, '...'))) !!}
                                    <br>
                                    <a href="#" 
                                       class="text-primary fw-bold view-full-report" 
                                       data-report="{{ nl2br(e($report->report)) }}"
                                       style="cursor: pointer;">View More</a>
                                </td>

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
--}}

<!-- Full Report Modal -->
<div class="modal fade" id="fullReportModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Full Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div id="fullReportContent" style="white-space: pre-line;"></div>
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

$(document).on('click', '.view-full-report', function (e) {
    e.preventDefault();

    let fullText = $(this).data('report');

    $('#fullReportContent').html(fullText);

    let modal = new bootstrap.Modal(document.getElementById('fullReportModal'));
    modal.show();
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
        $('#attendantTable').DataTable(
            {
            dom: 'Bfrtip',
            order: [[0, 'desc']],
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
    $(document).ready(function() {
        $('#zonalTable').DataTable(
            {
            dom: 'Bfrtip',
            order: [[0, 'desc']],
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

<script>
    $(document).ready(function() {
        $('#coordinatorTable').DataTable(
            {
            dom: 'Bfrtip',
            order: [[0, 'desc']],
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
    $(document).ready(function() {
        $('#dutyTable').DataTable(
            {
            dom: 'Bfrtip',
            order: [[0, 'desc']],
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
@endpush



