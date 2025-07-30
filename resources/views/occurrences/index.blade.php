@extends('layouts.default') {{-- Or your main layout --}}
@section('title', 'All Occurrences')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

@endpush
<div class="container">
    <h2 class="mb-4">All Occurrences</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle"  id="occurrencesTable">
            <thead class="table-dark">
                <tr>
                    <th>Actions</th>
                    <th>User</th>
                    <th>Shift</th>
                    <th>Hostel</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Nature of Occurrence</th>
                    <th>Action Taken</th>
                    <th>Resolution</th>

                    

                    <th>Files</th>
                    @if (!in_array(auth()->user()->role, ['house_keeper', 'hostel_attendant']))
                        <th>Manager</th>
                        <th>Director</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($occurrences as $occurrence)
                    <tr id="occurrence-row-{{ $occurrence->id }}">
                        <td>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Occurrence Actions">
                                {{-- Edit --}}

                                <div class="m-2">
                                     <a href="{{ route('occurrence.edit', $occurrence->id) }}" class="btn btn-outline-primary" title="Edit Occurrence">
                                    <i class="fas fa-edit"></i>
                                </a>
                                </div>
                               
                                <div class="m-2">
                                {{-- Add Input --}}

                                 @if (in_array(auth()->user()->role, ['manager', 'director']))
                                    <button class="btn btn-outline-info btn-add-input" 
                                            data-id="{{ $occurrence->id }}"
                                            data-role="{{ auth()->user()->role }}"
                                            data-input-url="{{ route('occurrence.input', $occurrence->id) }}"
                                            title="Add your Input"
                                            data-bs-toggle="modal"
                                            data-bs-target="#inputModal">
                                        <i class="fas fa-comment-medical"></i>
                                    </button>
                                @endif



                                
                                </div>

                                <div class="m-2">

                                   <button 
                                        class="btn btn-outline-danger btn-delete-occurrence" 
                                        data-id="{{ $occurrence->id }}" 
                                        title="Delete Occurrence">
                                        <i class="fas fa-trash"></i>
                                    </button>

                               </div>
                            </div>
                        </td>

                        <td>{{ $occurrence->user->name ?? 'N/A' }}</td>
                        <td>{{ $occurrence->shift }}</td>
                        <td>{{ $occurrence->hostel }}</td>
                        <td>{{ $occurrence->date }}</td>
                        <td>{{ $occurrence->time }}</td>
                        <td>{{ $occurrence->nature }}</td>
                        <td>{{ $occurrence->action_taken }}</td>
                        <td>{{ $occurrence->resolution }}</td>
                        <td>
                            @if($occurrence->files && $occurrence->files->count())
                                <ul class="list-unstyled">
                                    @foreach($occurrence->files as $file)
                                        <li>
                                            <a href="{{ asset('storage/occurrence_files/' . $file->original_name) }}" target="_blank">
                                                📄 {{ \Illuminate\Support\Str::limit($file->original_name, 20) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </td>
                        @if (!in_array(auth()->user()->role, ['house_keeper', 'hostel_attendant']))
                            <td id="manager-{{ $occurrence->id }}">{{ $occurrence->manager_input ?? '' }}</td>
                            <td id="director-{{ $occurrence->id }}">{{ $occurrence->director_input ?? '' }}</td>
                        @endif


                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No occurrences found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="inputModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="inputForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="inputModalLabel">Add Your Input</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="occurrence_id" id="occurrence_id">
          <input type="hidden" name="role" id="input_role">
          <div class="mb-3">
            <label for="input_text" class="form-label">Your Input</label>
            <textarea class="form-control" id="input_text" name="input_text" rows="3" required></textarea>
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
    let inputModal = document.getElementById('inputModal');
    let inputForm = document.getElementById('inputForm');
    let submitUrl = ''; // dynamic URL set from button

    // On button click, populate modal and store URL
    document.querySelectorAll('.btn-add-input').forEach(button => {
        button.addEventListener('click', () => {
            const occurrenceId = button.dataset.id;
            const role = button.dataset.role;
            submitUrl = button.dataset.inputUrl; // <-- Get the correct dynamic URL

            document.getElementById('occurrence_id').value = occurrenceId;
            document.getElementById('input_role').value = role;
        });
    });

    // Handle form submission
    inputForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const occurrenceId = formData.get('occurrence_id');
        const role = formData.get('role');
        const inputText = formData.get('input_text');

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
                // Update respective cell
                if (role === 'manager') {
                    document.getElementById(`manager-${occurrenceId}`).innerText = inputText;
                } else if (role === 'director') {
                    document.getElementById(`director-${occurrenceId}`).innerText = inputText;
                }

                inputForm.reset();
                bootstrap.Modal.getInstance(inputModal).hide();
            } else {
                alert('Something went wrong.');
            }
        })
        .catch(() => alert('Submission failed.'));
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
        $('#occurrencesTable').DataTable(
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

@endpush
