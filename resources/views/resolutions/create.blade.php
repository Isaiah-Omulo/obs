@extends('layouts.default')

@section('title', 'Occurrence Details: ' . $occurrence->tracking_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Card -->
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Resolution for Occurrence #{{ $occurrence->id }}</h4>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('occurrence.resolution.store', $occurrence->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Resolution Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Resolution Description</label>
                        <textarea name="description" id="description" class="form-control rounded-3" rows="4" placeholder="Describe how this occurrence was resolved..." required></textarea>
                    </div>

                    <!-- Resolution Date & Time -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="resolution_date" class="form-label fw-bold">Resolution Date</label>
                            <input type="date" name="resolution_date" id="resolution_date" 
                                class="form-control rounded-3" 
                                value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="resolution_time" class="form-label fw-bold">Resolution Time</label>
                            <input type="time" name="resolution_time" id="resolution_time" 
                                class="form-control rounded-3" 
                                value="{{ now()->format('H:i') }}" required>
                        </div>
                    </div>

                    <!-- Additional Comments -->
                    <div class="mb-3">
                        <label for="comments" class="form-label fw-bold">Additional Comments</label>
                        <textarea name="comments" id="comments" class="form-control rounded-3" rows="3" placeholder="Optional notes or remarks..."></textarea>
                    </div>

                    <!-- Attach Files -->
                    <div class="mb-4">
                        <label for="files" class="form-label fw-bold">Attach Files</label>
                        <input type="file" name="files[]" id="files" class="form-control rounded-3" multiple>
                        <small class="text-muted">You can upload multiple files (PDF, images, docs, etc.)</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Save Resolution
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Card -->
    </div>
</div>
@endsection
