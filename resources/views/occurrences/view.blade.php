@extends('layouts.default') {{-- Or your main layout file, e.g., layouts.app --}}

@section('title', 'Occurrence Details: ' . $occurrence->tracking_number)

@section('content')

@push('styles')
    {{-- We only need Font Awesome and Bootstrap, no DataTables for this view --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

<div class="container mt-5">
    
    {{-- 1. Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Occurrence Details</h2>
        <a href="{{ route('occurrence.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 2. Main Details Card --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="mb-0">
                <span class="badge fs-6 bg-transparent border border-primary text-primary d-inline-flex align-items-center">
                    <i class="fa-solid fa-tag me-2"></i>
                    <span>{{ $occurrence->tracking_number }}</span>
                </span>
            </h5>

            @if($occurrence->resolved !== 'yes')
                <button class="btn btn-outline-success" 
                        id="markResolvedBtn"
                        data-resolve-url="{{ route('occurrence.resolve', $occurrence->id) }}"
                        title="Mark this occurrence as resolved">
                    <i class="fas fa-check-circle me-1"></i> Mark as Resolved
                </button>
            @endif
            
            {{-- Action Buttons --}}
            <div class="btn-group btn-group-sm mt-2 mt-md-0" role="group" aria-label="Occurrence Actions">
                <button class="btn btn-outline-info" 
                        id="addInputBtn"
                        title="Add your Input"
                        data-bs-toggle="modal"
                        data-bs-target="#inputModal">
                    <i class="fas fa-comment-medical me-1"></i> Input
                </button>
                <a href="{{ route('escalate.create', ['id' => $occurrence->id]) }}" class="btn btn-outline-warning" title="Escalate this Occurrence">
                    <i class="fas fa-exclamation-triangle me-1"></i> Escalate
                </a>
                {{-- Add Edit/Delete buttons if needed, restricted by role --}}
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                {{-- Left Column: Core Details --}}
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Reported By</dt>
                        <dd class="col-sm-8">{{ $occurrence->user->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Shift</dt>
                        <dd class="col-sm-8">{{ $occurrence->shift }}</dd>

                        <dt class="col-sm-4">Hostel</dt>
                        <dd class="col-sm-8">{{ $occurrence->hostel }}</dd>
                        
                        <dt class="col-sm-4">Date</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($occurrence->date)->format('F j, Y') }}</dd>

                        <dt class="col-sm-4">Time of Occurrence</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($occurrence->time_of_occurrence)->format('g:i A') }}</dd>

                        <dt class="col-sm-4">Time of Reporting</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($occurrence->time_of_reporting)->format('g:i A') }}</dd>
                    </dl>
                </div>
                {{-- Right Column: Occurrence Specifics --}}
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Occurrence Type</dt>
                        <dd class="col-sm-8">{{ $occurrence->occurrence_type }}</dd>

                        <dt class="col-sm-4">Resolved</dt>
                       <dd class="col-sm-8">
                        <span id="resolvedStatusContainer" class="badge {{ $occurrence->resolved === 'Yes' ? 'bg-success' : 'bg-danger' }}">
                            {{ $occurrence->resolved }}
                        </span>
                    </dd>

                    </dl>
                </div>

                {{-- Full Width Details --}}
                <div class="col-12">
                    <hr>
                    <h6>Nature of Occurrence</h6>
                    <p class="text-muted">{{ $occurrence->nature }}</p>

                    <h6>Action Taken</h6>
                    <p class="text-muted">{{ $occurrence->action_taken }}</p>

                    <h6>Resolution</h6>
                    <p class="text-muted">{{ $occurrence->resolution ?? 'No resolution provided yet.' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Stakeholder Inputs & Files --}}
    <div class="row g-4">
        {{-- Inputs Column --}}
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header"><i class="fas fa-comments me-2"></i>Stakeholder Inputs</div>
                <ul class="list-group list-group-flush">
                    @if (!in_array(auth()->user()->role, ['house_keeper', 'hostel_attendant']))
                        <li class="list-group-item"><strong>Hostel Level:</strong><p class="mb-0 text-muted ps-3">{{ $occurrence->hostel_input ?? 'No input yet.' }}</p></li>
                        <li class="list-group-item"><strong>Zonal Officer:</strong><p class="mb-0 text-muted ps-3">{{ $occurrence->zonal_officer_input ?? 'No input yet.' }}</p></li>
                        <li class="list-group-item"><strong>Administrator:</strong><p class="mb-0 text-muted ps-3">{{ $occurrence->administrator_input ?? 'No input yet.' }}</p></li>
                        <li class="list-group-item"><strong>Manager:</strong><p class="mb-0 text-muted ps-3">{{ $occurrence->manager_input ?? 'No input yet.' }}</p></li>
                        <li class="list-group-item"><strong>Director:</strong><p class="mb-0 text-muted ps-3">{{ $occurrence->director_input ?? 'No input yet.' }}</p></li>
                    @else
                        <li class="list-group-item"><strong>Your Input:</strong><p class="mb-0 text-muted ps-3">{{ $occurrence->hostel_input ?? 'No input yet.' }}</p></li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Files Column --}}
        <div class="col-lg-5">
            <div class="card shadow-sm">
                 <div class="card-header"><i class="fas fa-paperclip me-2"></i>Attached Files</div>
                 <div class="list-group list-group-flush">
                    @forelse($occurrence->files as $file)
                        <a href="{{ asset('storage/occurrence_files/' . $file->original_name) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-file-alt text-primary me-3"></i>
                            <span>{{ \Illuminate\Support\Str::limit($file->original_name, 40) }}</span>
                        </a>
                    @empty
                        <div class="list-group-item">
                            <span class="text-muted">No files were attached.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal for adding input --}}
<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="inputModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="inputForm" action="{{ route('occurrence.input', $occurrence->id) }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="inputModalLabel">Add Your Input</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {{-- The occurrence ID and role are now static, no need for hidden fields from JS --}}
          <input type="hidden" name="occurrence_id" value="{{ $occurrence->id }}">
          <input type="hidden" name="role" value="{{ auth()->user()->role }}">
          <div class="mb-3">
            <label for="input_text" class="form-label">Your Comment or Observation</label>
            <textarea class="form-control" id="input_text" name="input_text" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Input</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
{{-- No need for DataTables or complex AJAX since it's a detail page --}}
{{-- The form will submit normally, which is fine for this action --}}
{{-- If you prefer AJAX submission, the script can be simplified as follows: --}}<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputForm = document.getElementById('inputForm');
    
    inputForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Stop normal form submission

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');

        // Optional: Disable button to prevent double-submission
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Saving...
        `;

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json' // We expect a JSON response
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success case
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Your input has been saved successfully!',
                    timer: 2000, // Auto-close after 2 seconds
                    showConfirmButton: false
                }).then(() => {
                    // We use .then() to ensure the page reloads *after* the alert is closed
                    window.location.reload(); 
                });

            } else {
                // Server-side error case (e.g., validation failed)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message || 'An error occurred. Please try again.'
                });
            }
        })
        .catch(error => {
            // Network or other unexpected error case
            console.error('Submission Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'A network error occurred. Please check your connection and try again.'
            });
        })
        .finally(() => {
            // Re-enable the button regardless of outcome
            submitButton.disabled = false;
            submitButton.innerHTML = 'Save Input';
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ... your existing modal script ...

    const markResolvedBtn = document.getElementById('markResolvedBtn');

    if (markResolvedBtn) {
        markResolvedBtn.addEventListener('click', function () {
            const resolveUrl = this.dataset.resolveUrl;

            Swal.fire({
                title: 'Are you sure?',
                text: "This will mark the occurrence as resolved.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, mark as resolved!',
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we update the status.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(resolveUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest' // Helps Laravel identify AJAX
                        }
                    })
                    .then(response => {
                        // ✅ First, check if the response is OK and looks like JSON
                                   console.log("Status:", response.status);
                                    console.log("Content-Type:", response.headers.get('content-type'));
                        const contentType = response.headers.get('content-type');
                        if (response.ok && contentType && contentType.includes('application/json')) {
                            return response.json(); // If it is, proceed to parse it
                        }
                        // ✅ If not, handle it as an unexpected error
                        throw new Error('Server returned a non-JSON response.');
                    })
                    .then(data => {
                        // ✅ This block now only runs if we received valid JSON

                        if (data.success) {
                            console.log("✅ Success branch reached");
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Resolved!',
                                text: data.message,
                            });

                            // Dynamically update the UI
                            document.getElementById('resolvedStatusContainer').innerHTML = `<span class="badge bg-success">Yes</span>`;
                            markResolvedBtn.remove();
                        } else {
                            Swal.fire('Error!', data.message || 'Could not update the status.', 'error');
                        }
                    })
                    .catch(error => {
                        // ✅ This catch now handles both network errors and non-JSON responses
                        console.error('Error:', error);
                        Swal.fire('Request Failed!', 'An unexpected error occurred. Please check the console for details.', 'error');
                    });
                }
            });
        });
    }
});
</script>
@endpush