{{-- Occurrence Files Tab --}}
<div class="tab-pane fade" id="tab-files">
    <div class="card shadow-sm mt-3 border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Files</h5>
            <button class="btn btn-light btn-sm text-primary border border-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#uploadFilesModal">
                <i class="fas fa-plus me-1"></i> Add File
            </button>
        </div>

        <div class="card-body">
            @if($occurrence->files->count())
                <ul class="list-group list-group-flush">
                    @foreach($occurrence->files as $file)
                        @php $filePath = public_path('uploads/occurrence_files/' . $file->original_name); @endphp
                        @if(file_exists($filePath))
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                    <a href="{{ asset('uploads/occurrence_files/' . $file->original_name) }}" target="_blank" class="text-decoration-none fw-medium">
                                        {{ $file->original_name }}
                                    </a>
                                </div>
                                <div class="text-muted small text-end">
                                    Uploaded by: <span class="fw-semibold">{{ $file->uploaded_by ?? 'Unknown' }}</span><br>
                                    {{ \Carbon\Carbon::parse($file->created_at)->format('d M Y g:i A') }}
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @else
                <p class="text-muted text-center mb-0">No files attached.</p>
            @endif
        </div>
    </div>
</div>

<!-- Upload Files Modal -->
<div class="modal fade" id="uploadFilesModal" tabindex="-1" aria-labelledby="uploadFilesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="uploadFilesForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="uploadFilesModalLabel"><i class="fas fa-upload me-2"></i>Upload Files</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="attachment" class="form-label fw-semibold">Select files</label>
                        <input type="file" class="form-control" id="attachmentOccurrence" name="attachment[]" multiple>
                        <small class="text-muted">Max file size: 5MB each</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-success fw-semibold">
                        <i class="fas fa-check me-1"></i> Upload
                    </button>
                    <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .list-group-item {
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .list-group-item a:hover {
        text-decoration: underline;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endpush
