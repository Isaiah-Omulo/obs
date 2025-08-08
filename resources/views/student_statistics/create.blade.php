@extends('layouts.default')
@section('title', 'New Stats')

{{-- Note: Pushing styles is better done in its own section --}}
@push('styles')
<style>
    #hostelCapacityDisplay {
        display: none;
        background-color: #e0e7ff;
        color: #3730a3;
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        margin-top: 0.5rem;
        font-weight: 500;
        border: 1px solid #c7d2fe;
    }
</style>
@endpush

@section('content')
<div class="container mt-4 ml-5">
    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Record Student Statistics</h5>
            <a href="{{ route('student_statistics.index') }}" class="btn btn-success btn-sm">
                <i class="fas fa-list me-1"></i> View All Records
            </a>
        </div>

        {{-- Note: 'novalidate' is removed to allow browser validation to work alongside Laravel's --}}
        <form action="{{ route('student_statistics.store') }}" method="POST">
            @csrf
            <div class="card-body">
                {{-- Hostel Selection --}}
                <div class="mb-3">
                    <label for="hostel_id" class="form-label">Hostel</label>
                    {{-- Add 'is-invalid' class if there's an error for this field --}}
                    <select name="hostel_id" id="hostel_id" class="form-select @error('hostel_id') is-invalid @enderror" required>
                        <option value="">-- Select Hostel --</option>
                        @foreach($hostels as $hostel)
                            {{-- Use old() to retain selection after a validation error --}}
                            <option value="{{ $hostel->id }}" data-capacity="{{ $hostel->number_of_students }}" {{ old('hostel_id', $lastHostelId) == $hostel->id ? 'selected' : '' }}>
                                {{ $hostel->name }}
                            </option>
                        @endforeach
                    </select>
                    <div id="hostelCapacityDisplay"></div>
                    {{-- ERROR DISPLAY BLOCK --}}
                    @error('hostel_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Date & Shift Row --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="record_date" class="form-label">Date of Record</label>
                        <input type="date" name="record_date" id="record_date" class="form-control @error('record_date') is-invalid @enderror" value="{{ old('record_date', date('Y-m-d')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="shift" class="form-label">Shift</label>
                        <select name="shift" id="shift" class="form-select @error('shift') is-invalid @enderror" required>
                            <option value="">-- Select Shift --</option>
                            <option value="Day" {{ old('shift') == 'Day' ? 'selected' : '' }}>Day</option>
                            <option value="Night" {{ old('shift') == 'Night' ? 'selected' : '' }}>Night</option>
                        </select>
                        @error('shift')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>
               
                {{-- Students Present --}}
                <div class="mb-3">
                    <label for="students_present" class="form-label">Total Students Present</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                        <input type="number" name="students_present" id="students_present" class="form-control @error('students_present') is-invalid @enderror" value="{{ old('students_present') }}" min="0" required>
                    </div>
                    {{-- This will display our custom error message from the controller --}}
                    @error('students_present')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Comments --}}
                <div class="mb-3">
                    <label for="comments" class="form-label">Comments</label>
                    <textarea name="comments" id="comments" class="form-control @error('comments') is-invalid @enderror" rows="3" placeholder="Add any relevant comments...">{{ old('comments') }}</textarea>
                    @error('comments')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Record</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- Merged and corrected single script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hostelSelect = document.getElementById('hostel_id');
    const capacityDisplay = document.getElementById('hostelCapacityDisplay');
    const studentsPresentInput = document.getElementById('students_present');

    function updateHostelDetails() {
        const selectedOption = hostelSelect.options[hostelSelect.selectedIndex];
        const capacity = selectedOption.getAttribute('data-capacity');

        if (capacity && capacity > 0) {
            studentsPresentInput.max = capacity;
            capacityDisplay.innerHTML = `<i class="fas fa-info-circle me-1"></i>Hostel Capacity: <strong>${capacity}</strong>. The value must be between 0 and ${capacity}.`;
            capacityDisplay.style.display = 'block';
        } else {
            capacityDisplay.style.display = 'none';
            studentsPresentInput.removeAttribute('max');
        }
        // No longer need to clear the value here, as `old()` will repopulate it correctly.
    }

    hostelSelect.addEventListener('change', updateHostelDetails);
    updateHostelDetails();
});
</script>
@endpush