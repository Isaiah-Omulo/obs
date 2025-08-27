<div class="card shadow-sm mt-3 border-0 rounded-4">
    <div class="card-header bg-success text-white rounded-top-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Resolutions</h5>

        @if($occurrence->resolved !== 'yes')
            <a href="{{ route('occurrence.resolution.create', $occurrence->id) }}" 
               class="btn btn-info"
               title="Mark this occurrence as resolved">
                <i class="fas fa-check-circle me-1"></i> Mark as Resolved
            </a>
        @else
            <button
                class="btn btn-light btn-m text-primary border border-primary fw-semibold"
                id="addFileBtn"              
            >
                <i class="fas fa-plus me-1"></i> Add File
            </button>


            
        @endif
    </div>

    <div class="card-body">


        <!-- Dynamic file form -->
        <div id="fileFormContainer" style="display: none;">
            <form action="{{ route('occurrence.resolution.upload-files', $occurrence->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  id="fileUploadForm">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Select File</label>
                    <input type="file" class="form-control" name="attachment[]" id="file" required>
                </div>
                
                <button type="submit" class="btn btn-success">Upload</button>
                <button type="button" id="cancelFileBtn" class="btn btn-secondary">Cancel</button>
            </form>
        </div>

        @if($occurrence->resolutions->count())
            @foreach($occurrence->resolutions as $resolution)
                <div class="mb-4 p-3 border rounded-3 shadow-sm">
                    <div class="row mb-2">
                        <div class="col-12 col-md-3 fw-semibold text-muted">Resolved By:</div>
                        <div class="col-12 col-md-9">
                            <span class="fw-medium">{{ $resolution->resolver->name ?? 'Unknown' }}</span>
                            <span class="badge bg-info text-dark ms-2">{{ $resolution->resolver->role ?? 'User' }}</span>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12 col-md-3 fw-semibold text-muted">Description:</div>
                        <div class="col-12 col-md-9">{{ $resolution->description }}</div>
                    </div>

                    @if($resolution->comments)
                        <div class="row mb-2">
                            <div class="col-12 col-md-3 fw-semibold text-muted">Comments:</div>
                            <div class="col-12 col-md-9">{{ $resolution->comments }}</div>
                        </div>
                    @endif

                    <div class="row mb-2">
                        <div class="col-12 col-md-3 fw-semibold text-muted">Date & Time:</div>
                        <div class="col-12 col-md-9">
                            {{ \Carbon\Carbon::parse($resolution->resolution_date)->format('M d, Y') }} at
                            {{ \Carbon\Carbon::parse($resolution->resolution_time)->format('g:i A') }}
                        </div>
                    </div>

                    @if($resolution->files->count())
                        <div class="row">
                            <div class="col-12 col-md-3 fw-semibold text-muted">Attachments:</div>
                            <div class="col-12 col-md-9">
                                @foreach($resolution->files as $file)
                                    <a href="{{ asset('uploads/occurrence_files/' . $file->original_name) }}" 
                                       target="_blank" 
                                       class="d-block mb-1 text-decoration-none">
                                        <i class="fas fa-file-alt me-1 text-primary"></i>{{ $file->original_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <p class="text-muted text-center mb-0">No resolutions added yet.</p>
        @endif
    </div>
</div>



{{-- Optional: Smart hover effect for "Mark as Resolved" --}}
@push('styles')
<style>
    #markResolvedBtn:hover {
        transform: scale(1.05);
        transition: all 0.2s ease-in-out;
    }

    .card-body a:hover {
        text-decoration: underline;
    }

    .badge {
        font-size: 0.85rem;
    }
</style>
@endpush
