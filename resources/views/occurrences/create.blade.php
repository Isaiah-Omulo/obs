@extends('layouts.default')
@section('title', 'Report New Occurrence')

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
            <strong>Step <span id="step-title">1</span>: Reporter Details</strong>
        </div>
        <div class="card-body">
            <form id="occurrenceForm" method="POST" action="{{ route('occurrence.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Step 1: Reporter Details -->
                <div class="form-step active">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="reporter_name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Role</label>
                        <input type="text" name="reporter_role" class="form-control" value="{{ Auth::user()->role }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Shift</label>
                        <select name="shift" class="form-control" required>
                            <option value="">Select shift</option>
                            <option value="Day">Day</option>
                            <option value="Night">Night</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Hostel / Zone</label>
                        <input type="text" name="location" class="form-control" value="{{ Auth::user()->assigned_location ?? '' }}" readonly>
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
                        <input type="time" name="time" class="form-control">
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
                        <textarea name="action_taken" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Resolution / Outcome</label>
                        <textarea name="resolution" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Involved Parties (optional)</label>
                        <input type="text" name="involved_parties" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Upload Supporting Files</label>
                        <input type="file" name="attachment" class="form-control">
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
        if (currentStep < steps.length - 1) {
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
