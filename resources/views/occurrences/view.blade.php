@extends('layouts.default')

@section('title', 'Occurrence Details: ' . $occurrence->tracking_number)

@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />


<style>
    
  

@media (max-width: 767px) {
  .prev-button, .next-button {
    display: none !important;
  }
}

.tab-scroll-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* smooth scroll for iOS */
}
.nav-tabs .nav-item {
    flex: 0 0 auto; /* prevent wrapping on small screens */
}

@media (max-width: 767px) {
  .prev-button, .next-button {
    display: none !important;
  }
}

@media (max-width: 767px) {
    .tab-pane dl.row dt,
    .tab-pane dl.row dd {
        display: block;
        width: 100%;
    }
    .tab-pane dl.row dt {
        font-weight: 600;
    }
}


.card-body .badge {
    font-size: 0.75rem;
    padding: 0.25em 0.5em;
}

.card {
    border-radius: 0.75rem;
    transition: transform 0.15s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

.reply-btn {
    border-radius: 50%;
    padding: 0.35rem 0.45rem;
}

textarea#input_text {
    resize: vertical;
}



</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<style>
/* Ensure text truncation for table cells */
.table td .text-truncate {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
@endpush

<div class="container mt-5">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Occurrence Details</h2>
        <a href="{{ route('occurrence.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    {{-- Tabs Panel --}}
    <div class="panel panel-inverse panel-with-tabs">
        <div class="panel-heading p-0">
        <div class="tab-overflow d-flex align-items-center">
            
           
            <!-- Tabs Scrollable Container -->
            
            <div class="tab-scroll-wrapper overflow-auto" style="white-space: nowrap; -webkit-overflow-scrolling: touch;">
                <ul class="nav nav-tabs nav-tabs-inverse flex-nowrap mb-0">
                    <li class="nav-item"><a href="#tab-details" data-bs-toggle="tab" class="nav-link active">Occurrence Details</a></li>
                    <li class="nav-item"><a href="#tab-escalations" data-bs-toggle="tab" class="nav-link">Escalations</a></li>
                    <li class="nav-item"><a href="#tab-resolution" data-bs-toggle="tab" class="nav-link">Resolution</a></li>
                    <li class="nav-item"><a href="#tab-inputs" data-bs-toggle="tab" class="nav-link">Stakeholder Inputs</a></li>
                    <li class="nav-item"><a href="#tab-files" data-bs-toggle="tab" class="nav-link">Occurrence Files</a></li>
                </ul>
            </div>


          
        </div>
    </div>


        <div class="panel-body tab-content">

            {{-- Tab 1: Occurrence Details --}}

            <div class="tab-pane fade show active" id="tab-details">
                <div class="card shadow-sm mb-4 mt-3 border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Occurrence Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Left Column: Key Details -->
                            <div class="col-md-6 col-12">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <strong>Reported By</strong>
                                        <span>{{ $occurrence->user->name ?? 'N/A' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <strong>Shift</strong>
                                        <span>{{ $occurrence->shift }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <strong>Hostel</strong>
                                        <span>{{ $occurrence->hostel }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <strong>Date</strong>
                                        <span>{{ \Carbon\Carbon::parse($occurrence->date)->format('F j, Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <strong>Time of Occurrence</strong>
                                        <span>{{ \Carbon\Carbon::parse($occurrence->time_of_occurrence)->format('g:i A') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <strong>Time of Reporting</strong>
                                        <span>{{ \Carbon\Carbon::parse($occurrence->time_of_reporting)->format('g:i A') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <strong>Occurrence Type</strong>
                                        <span>{{ $occurrence->occurrence_type }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <strong>Resolved</strong>
                                        <span class="badge {{ $occurrence->resolved === 'Yes' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $occurrence->resolved }}
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Right Column: Narrative Details -->
                            <div class="col-md-6 col-12">
                                <div class="border-start ps-md-4 ps-0">
                                    <strong> <h6 class="fw-bold">Nature of Occurrence</h6> </strong>
                                    <p class="text-muted mb-3">{{ $occurrence->nature }}</p>

                                    <h6 class="fw-bold">Action Taken</h6>
                                    <p class="text-muted mb-3">{{ $occurrence->action_taken }}</p>

                                    <h6 class="fw-bold">Resolution</h6>
                                    <p class="text-muted mb-0">{{ $occurrence->resolution ?? 'No resolution provided yet.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Escalations --}}
           <div class="tab-pane fade" id="tab-escalations">
                @include('occurrences.partials.escalations')
            </div>


            {{-- Tab 3: Resolution --}}
            <div class="tab-pane fade" id="tab-resolution">
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        @include('occurrences.partials.resolutions')
                    </div>
                </div>
            </div>


            {{-- Tab 4: Stakeholder Inputs --}}
            <div class="tab-pane fade" id="tab-inputs">
                @include('occurrences.partials.stakeholders', ['occurrence' => $occurrence])
            </div>


            {{-- Tab 5: Occurrence Files --}}
          
            @include('occurrences.partials.files')

        </div> {{-- panel-body --}}
    </div> {{-- panel --}}

</div> {{-- container --}}



<!-- Input Modal -->
<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="inputModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="inputForm" action="{{ route('occurrence.input.store', $occurrence->id) }}" method="POST">
            @csrf
            <input type="hidden" name="occurrence_id" value="{{ $occurrence->id }}">
            <input type="hidden" name="role" value="{{ auth()->user()->role }}">
            <input type="hidden" name="parent_id" id="parent_id" value="">

            <div class="modal-content rounded-3 shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="inputModalLabel">
                        <i class="fas fa-comment-dots me-2"></i> Add Input
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- User Role Display -->
                    <div class="mb-3">
                        <span class="badge bg-info text-dark px-3 py-2">
                            {{ auth()->user()->role }} : {{ auth()->user()->name }}
                        </span>
                    </div>

                    <!-- Input Text -->
                    <div class="mb-3">
                        <label for="input_text" class="form-label fw-semibold">Your Comment or Observation</label>
                        <textarea class="form-control rounded-3 border-primary shadow-sm" 
                                  id="input_text" name="input_text" rows="4" 
                                  placeholder="Type your observation here..." required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span id="btnText"><i class="fas fa-save me-1"></i> Save Input</span>
                        <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@php
    $firstResolutionId = $occurrence->resolutions->first()?->id ?? '';
@endphp



@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputForm = document.getElementById('inputForm');
    
    inputForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Stop normal form submission

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');

        // Optional: Disable button to prevent double-submission
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
            Saving...
        `;

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json' // Expect JSON response
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok && data.success) {
                // Success case
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Your input has been saved successfully!',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Reload after alert
                    window.location.reload();
                });
            } else {
                // Validation or server-side error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message || 'An error occurred. Please try again.'
                });
            }
        })
        .catch(error => {
            console.error('Submission Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'A network error occurred. Please check your connection and try again.'
            });
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-save me-1"></i> Save Input';
        });
    });

    // Optional: Pre-fill parent_id when replying to a comment
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function () {
            const parentId = this.dataset.commentId; // e.g., <button class="reply-btn" data-comment-id="5">
            document.getElementById('parent_id').value = parentId;
            const modal = new bootstrap.Modal(document.getElementById('inputModal'));
            modal.show();
        });
    });
});

document.querySelectorAll('.reply-btn').forEach(button => {
    button.addEventListener('click', function () {
        const parentId = this.dataset.commentId;
        document.getElementById('parent_id').value = parentId;

        const modal = new bootstrap.Modal(document.getElementById('inputModal'));
        modal.show();
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('uploadFilesForm');
    const occurrenceId = "{{ $occurrence->id ?? '' }}";

    // Generate dynamic route with placeholder
    let uploadRouteTemplate = "{{ route('occurrence.upload-files', ':id') }}";

    form.addEventListener('submit', function(e) {
        e.preventDefault();



        const files = document.getElementById('attachmentOccurrence').files;
        
        if (!files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'No files selected',
                text: 'Please choose at least one file to upload.'
            });
            return;
        }

        const maxFileSize = 5 * 1024 * 1024; // 5MB
        for (let i = 0; i < files.length; i++) {
            if (files[i].size > maxFileSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File too large',
                    text: `${files[i].name} exceeds 5MB size limit.`
                });
                return;
            }
        }

        const formData = new FormData(form);

        // Replace placeholder with actual occurrence ID
        const uploadUrl = uploadRouteTemplate.replace(':id', occurrenceId);

        fetch(uploadUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('uploadFilesModal'));
                modal.hide();
                form.reset();

                Swal.fire({
                    icon: 'success',
                    title: 'Files uploaded',
                    text: 'Your files have been uploaded successfully.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload failed',
                    text: data.message || 'An error occurred while uploading files.'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while uploading files.'
            });
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.tab-overflow ul.nav-tabs');
    const prevBtn = document.querySelector('.prev-button a');
    const nextBtn = document.querySelector('.next-button a');

    prevBtn?.addEventListener('click', () => {
        container.scrollBy({ left: -150, behavior: 'smooth' });
    });

    nextBtn?.addEventListener('click', () => {
        container.scrollBy({ left: 150, behavior: 'smooth' });
    });
});


</script>


<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<script>
$(document).ready(function () {
    // Initialize DataTable
    var table = $('#occurrenceEscalationTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis'],
        order: [[1, 'desc']],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search escalations..."
        }
    });

    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});




</script>
{{--
<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById('uploadFilesResolutionForm');
  const modalEl = document.getElementById('uploadFilesResolutionModal');

  if (modalEl) {
    modalEl.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      if (!button) return;

      modalEl.querySelector('#occurrence_id').value = button.getAttribute('data-occurrence-id') || '';
      modalEl.querySelector('#resolution_id').value = button.getAttribute('data-resolution-id') || '';
    });
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const files = document.getElementById('attachment').files;
    if (!files.length) {
      Swal.fire({ icon: 'warning', title: 'No files selected', text: 'Please choose at least one file.' });
      return;
    }

    const maxFileSize = 5 * 1024 * 1024;
    for (let f of files) {
      if (f.size > maxFileSize) {
        Swal.fire({ icon: 'error', title: 'File too large', text: `${f.name} exceeds 5MB.` });
        return;
      }
    }

    const occurrenceId = document.getElementById('occurrence_id').value;
    const resolutionId = document.getElementById('resolution_id').value;

    const formData = new FormData(form);
    if (!formData.has('resolution_id')) formData.append('resolution_id', resolutionId);
    if (!formData.has('occurrence_id')) formData.append('occurrence_id', occurrenceId);

    const uploadUrl = "{{ route('occurrence.resolution.upload-files', ':id') }}".replace(':id', occurrenceId);

    fetch(uploadUrl, {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        form.reset();
        Swal.fire({
          icon: 'success',
          title: 'Uploaded',
          text: 'Files uploaded successfully.',
          timer: 1500,
          showConfirmButton: false
        }).then(() => location.reload());
      } else {
        Swal.fire({ icon: 'error', title: 'Upload failed', text: data.message || 'Error uploading files.' });
      }
    })
    .catch(err => {
      console.error(err);
      Swal.fire({ icon: 'error', title: 'Error', text: 'An unexpected error occurred.' });
    });
  });
});

</script>
--}}

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('uploadFilesResolutionForm');
    const modalEl = document.getElementById('uploadFilesResolutionModal');

    
    const uploadRouteTemplate = "{{ route('occurrence.resolution.upload-files', ':id') }}";

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const files = document.getElementById('attachmentResolution').files;
        if (!files.length) {
            Swal.fire({ icon: 'warning', title: 'No files selected', text: 'Please choose at least one file to upload.' });
            return;
        }

        const maxFileSize = 5 * 1024 * 1024;
        for (let f of files) {
            if (f.size > maxFileSize) {
                Swal.fire({ icon: 'error', title: 'File too large', text: `${f.name} exceeds 5MB size limit.` });
                return;
            }
        }

        const occurrenceId = document.getElementById('occurrence_id').value;
        const formData = new FormData(form);
        const uploadUrl = uploadRouteTemplate.replace(':id', occurrenceId);

        fetch(uploadUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                form.reset();
                Swal.fire({ icon: 'success', title: 'Files uploaded', text: 'Your files have been uploaded successfully.', timer: 1500, showConfirmButton: false })
                .then(() => location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'Upload failed', text: data.message || 'An error occurred while uploading files.' });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Error', text: 'An unexpected error occurred while uploading files.' });
        });
    });
});
</script>


<script>
    const addFileBtn = document.getElementById('addFileBtn');
    const fileFormContainer = document.getElementById('fileFormContainer');
    const cancelFileBtn = document.getElementById('cancelFileBtn');

    addFileBtn.addEventListener('click', () => {
        fileFormContainer.style.display = 'block';
        addFileBtn.style.display = 'none';
    });

    cancelFileBtn.addEventListener('click', () => {
        fileFormContainer.style.display = 'none';
        addFileBtn.style.display = 'inline-block';
    });
</script>


@endpush