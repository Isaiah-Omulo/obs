@extends('layouts.default')

@section('title', 'Add Water Monitoring Record')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow-sm border-0 rounded-3">
        
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-tint me-2"></i>Daily Hostel Water Monitoring Form</h5>
            <div>
                <a href="{{ route('water_monitoring.index') }}" class="btn btn-sm btn-outline-dark me-2"><i class="fa fa-list me-1"></i> View All</a>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-info"><i class="fa fa-arrow-left me-1"></i> Back</a>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('water_monitoring.store') }}" method="POST">
                @csrf

                {{-- ... (Hostel and Time fields remain the same) ... --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="hostel" class="form-label fw-bold">Hostel <span class="text-danger">*</span></label>
                        <select id="hostel" name="hostel_id" class="form-select" required>
                            <option value="" selected disabled>-- Select Hostel --</option>
                            @foreach($hostels as $hostel)
                                <option value="{{ $hostel->id }}">{{ $hostel->name }} ({{ $hostel->zone->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="time" class="form-label fw-bold">Time <span class="text-danger">*</span></label>
                        <select id="time" name="time" class="form-select" required>
                            <option value="" selected disabled>-- Select Time --</option>
                            <option value="06:00 PM">06:00 PM</option>
                            <option value="12:00 AM">12:00 AM</option>
                            <option value="04:00 AM">04:00 AM</option>
                            <option value="06:00 AM">06:00 AM</option>
                            <option value="12:00 PM">12:00 PM</option>
                            <option value="04:00 PM">04:00 PM</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Date</label>
                        <input type="text" class="form-control bg-light" value="{{ now()->format('Y-m-d') }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="is_water" class="form-label fw-bold">Is there water? <span class="text-danger">*</span></label>
                        <select id="is_water" name="is_water" class="form-select" required>
                            <option value="" selected disabled>-- Select --</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

               <div class="row mb-3" id="amount-field-wrapper">
                    <!-- LEFT COLUMN: Amount -->
                    <div class="col-md-6">
                        <label for="amount" class="form-label fw-bold">
                            Amount <span id="amount-required-indicator" class="text-danger d-none">*</span>
                        </label>
                        <select id="amount" name="amount" class="form-select">
                            <option value="" selected disabled>-- Select Amount --</option>
                            <option value="Plenty">Plenty</option>
                            <option value="Little">Little</option>
                        </select>
                    </div>

                    <!-- RIGHT COLUMN: Hot Water -->
                    <div class="col-md-6  mt-2">
                        <label for="is_hot_water" class="form-label fw-bold">Is there hot water?</label>
                        <select id="is_hot_water" name="is_hot_water" class="form-select">
                            <option value="" selected>-- Not specified --</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                            <option value="N/A">Not Applicable</option>
                        </select>
                    </div>
                </div>


                    <div class="col-md-4">
                        <label for="remarks" class="form-label fw-bold">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control" rows="2" placeholder="e.g., pipe leakage, low pressure..."></textarea>
                    </div>
                </div>

                <div class="text-end border-top pt-3 mt-4">
                    <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save me-2"></i>Submit Record</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- START: Added JavaScript section --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isWaterSelect = document.getElementById('is_water');
    const amountWrapper = document.getElementById('amount-field-wrapper');
    const amountSelect = document.getElementById('amount');
    const amountRequiredIndicator = document.getElementById('amount-required-indicator');

    function toggleAmountField() {
        // If 'Yes' is selected
        if (isWaterSelect.value === 'Yes') {
            amountWrapper.style.display = 'block'; // Show the field
            amountSelect.disabled = false;         // Enable the select input
            amountSelect.required = true;          // Make it required
            amountRequiredIndicator.classList.remove('d-none'); // Show the asterisk
        } else { // If 'No' or nothing is selected
            amountWrapper.style.display = 'none';  // Hide the field
            amountSelect.disabled = true;          // Disable it to prevent submission
            amountSelect.required = false;         // Make it not required
            amountSelect.value = '';               // Reset its value
            amountRequiredIndicator.classList.add('d-none'); // Hide the asterisk
        }
    }

    // Run the function when the page loads to set the initial state
    toggleAmountField();

    // Add an event listener to run the function whenever the selection changes
    isWaterSelect.addEventListener('change', toggleAmountField);
});
</script>
@endpush
