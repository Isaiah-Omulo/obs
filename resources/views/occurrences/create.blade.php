@extends('layouts.default')
@section('title', 'Report New Occurrence')



@push('styles')

<style>
.is-invalid {
    border-color: #dc3545;
}
</style>

@endpush

@section('content')
<div class="container mt-4">

    <!-- Top Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Report New Occurrence</h4>
        <a href="{{ route('occurrence.index') }}" class="btn btn-outline-secondary btn-sm">All Occurrences</a>
    </div>

    <!-- Multi-Step Panel -->
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <strong>Step <span id="step-title">1</span>: <span id="step-name">Reporter Details</span> </strong>
        </div>
        <div class="card-body">
            <form id="occurrenceForm" method="POST" action="{{ route('occurrence.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Step 1: Reporter Details -->
                <div class="form-step active">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="user_id" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Role</label>
                        <input type="text" name="reporter_role" class="form-control" value="{{ Auth::user()->role }}" readonly>
                    </div>

                   <div class="mb-3">
                        <label>Shift</label>
                        <select id="shift_display" class="form-control" disabled>
                            <option value="">Select shift</option>
                            <option value="Day">Day</option>
                            <option value="Night">Night</option>
                        </select>
                        <input type="hidden" name="shift" id="shift" />
                    </div>

                    <div class="mb-3">
                        <label>Hostel</label>
                        <select name="location" class="form-control" required>
                            <option value="">Select Hostel</option>
                            @foreach($hostels as $hostel)
                                <option value="{{ $hostel->name }}" {{ (old('location', Auth::user()->assigned_location ?? '') == $hostel->name) ? 'selected' : '' }}>
                                    {{ $hostel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <!-- Step 2: Occurrence Details -->
                <div class="form-step">
                    <div class="mb-3">
                        <label>Date of Occurrence</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Time of Occurrence</label>
                        <input type="time" name="time" class="form-control" required>
                    </div>
                    

                    <!-- Occurrence Type Dropdown -->
                    <div class="mb-3">
                        <label for="nature" class="form-label">Type of Occurrence</label>
                        <select name="occurrence_type" id="occurrence_type" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            @foreach($occurrenceTypes as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Custom input if "Other" selected -->
                    <div class="mb-3 d-none" id="customNatureWrapper">
                        <label for="custom_nature" class="form-label">Specify Occurrence Type</label>
                        <input type="text" name="custom_nature" id="custom_nature" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Nature of Occurrence</label>
                        <textarea name="nature" class="form-control" rows="3" required></textarea>
                    </div>
                </div>

                <!-- Step 3: Actions -->
                <div class="form-step">
                    <div class="mb-3">
                        <label>Action Taken</label>
                        <textarea name="action_taken" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resolved" class="form-label">Resolved</label>
                        <select name="resolved" id="resolved" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Resolution / Outcome</label>
                        <textarea name="resolution" class="form-control" rows="3" required></textarea>
                    </div>

                   

                    <div class="mb-3">
                    <label>Upload Supporting Files</label>
                    <input type="file" name="attachment[]" class="form-control" multiple>
                </div>

                </div>

                <!-- Navigation -->
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" id="prevStep" style="display:none;">Previous</button>
                    <button type="button" class="btn btn-primary" id="nextStep">Next</button>
                    <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Step-by-step logic -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    const nextBtn = document.getElementById('nextStep');
    const prevBtn = document.getElementById('prevStep');
    const submitBtn = document.getElementById('submitBtn');
    const stepTitle = document.getElementById('step-title');
    const stepName = document.getElementById('step-name');
    let currentStep = 0;
    const stepNames = [
    'Reporter Details',
    'Occurrence Details',
    'Actions and Attachments'
];


    function showStep(index) {
        steps.forEach((step, i) => {
            step.style.display = (i === index) ? 'block' : 'none';
        });
        stepTitle.textContent = index + 1;
        stepName.textContent = stepNames[index];
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

            if (isValid) {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    showStep(currentStep);
                }
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const shiftDisplay = document.getElementById('shift_display');
        const shiftHidden = document.getElementById('shift');

        const nairobiTimeStr = new Date().toLocaleString("en-US", { timeZone: "Africa/Nairobi" });
        const nairobiDate = new Date(nairobiTimeStr);

        const hours = nairobiDate.getHours();
        const minutes = nairobiDate.getMinutes();

        const totalMinutes = hours * 60 + minutes;

        const dayStart = 6 * 60 + 59;
        const dayEnd = 18 * 60 + 59;

        const shift = (totalMinutes >= dayStart && totalMinutes <= dayEnd) ? 'Day' : 'Night';

        shiftDisplay.value = shift;
        shiftHidden.value = shift;
    });
</script>

<script>
    document.getElementById('occurrence_type').addEventListener('change', function () {
        const customInput = document.getElementById('customNatureWrapper');
        if (this.value === 'Other') {
            customInput.classList.remove('d-none');
            document.getElementById('custom_nature').required = true;
        } else {
            customInput.classList.add('d-none');
            document.getElementById('custom_nature').required = false;
        }
    });
</script>

@endpush
@endsection
