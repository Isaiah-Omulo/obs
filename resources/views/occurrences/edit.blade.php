@extends('layouts.default')
@section('title', 'Edit Occurrence')

@push('styles')
<style>
.is-invalid {
    border-color: #dc3545;
}
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Edit Occurrence</h4>
        <a href="{{ route('occurrence.index') }}" class="btn btn-outline-secondary btn-sm">All Occurrences</a>
    </div>

    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <strong>Step <span id="step-title">1</span>: Reporter Details</strong>
        </div>
        <div class="card-body">
            <form id="occurrenceForm" method="POST" action="{{ route('occurrence.update', $occurrence->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-step active">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="user_id" class="form-control" value="{{ $occurrence->user->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Role</label>
                        <input type="text" name="reporter_role" class="form-control" value="{{ $occurrence->user->role }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Shift</label>
                        <select id="shift_display" class="form-control" disabled>
                            <option value="">Select shift</option>
                            <option value="Day" {{ $occurrence->shift === 'Day' ? 'selected' : '' }}>Day</option>
                            <option value="Night" {{ $occurrence->shift === 'Night' ? 'selected' : '' }}>Night</option>
                        </select>
                        <input type="hidden" name="shift" id="shift" value="{{ $occurrence->shift }}" />
                    </div>

                    <div class="mb-3">
                        <label>Hostel</label>
                        <select name="location" class="form-control" required>
                            <option value="">Select Hostel</option>
                            @foreach($hostels as $hostel)
                                <option value="{{ $hostel->name }}" {{ $occurrence->hostel === $hostel->name ? 'selected' : '' }}>
                                    {{ $hostel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-step">
                    <div class="mb-3">
                        <label>Date of Occurrence</label>
                        <input type="date" name="date" class="form-control" value="{{ $occurrence->date }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Time of Occurrence</label>
                        <input type="time" name="time" class="form-control" value="{{ $occurrence->time }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Nature of Occurrence</label>
                        <textarea name="nature" class="form-control" rows="3" required>{{ $occurrence->nature }}</textarea>
                    </div>
                </div>

                <div class="form-step">
                    <div class="mb-3">
                        <label>Action Taken</label>
                        <textarea name="action_taken" class="form-control" rows="3" required>{{ $occurrence->action_taken }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Resolution / Outcome</label>
                        <textarea name="resolution" class="form-control" rows="3" required>{{ $occurrence->resolution }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Upload Supporting Files</label>
                        <input type="file" name="attachment[]" class="form-control" multiple>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" id="prevStep" style="display:none;">Previous</button>
                    <button type="button" class="btn btn-primary" id="nextStep">Next</button>
                    <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    const nextBtn = document.getElementById('nextStep');
    const prevBtn = document.getElementById('prevStep');
    const submitBtn = document.getElementById('submitBtn');
    const stepTitle = document.getElementById('step-title');
    let currentStep = 0;

    function showStep(index) {
        steps.forEach((step, i) => {
            step.style.display = (i === index) ? 'block' : 'none';
        });
        stepTitle.textContent = index + 1;
        prevBtn.style.display = index > 0 ? 'inline-block' : 'none';
        nextBtn.style.display = index < steps.length - 1 ? 'inline-block' : 'none';
        submitBtn.style.display = index === steps.length - 1 ? 'inline-block' : 'none';
    }

    nextBtn.addEventListener('click', () => {
        const currentFields = steps[currentStep].querySelectorAll('input, select, textarea');
        let isValid = true;

        currentFields.forEach(field => {
            if (field.hasAttribute('required') && !field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (isValid && currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    showStep(currentStep);
});
</script>
@endpush
@endsection
