@extends('layouts.default')
@section('title', 'New Daily Report')

@section('content')
<div class="container-fluid mt-5" >
    <!-- begin row -->
    <div class="row d-flex align-items-center justify-content-center" style="min-height: 70vh;">
        <!-- begin col -->
        <div class="col-xl-6 col-lg-8">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Changeover Form</h4>
                    <div class="panel-heading-btn">
                        <a href="{{ route('takeover.index') }}" class="btn btn-sm btn-primary">All Changeovers</a>
                    </div>
                </div>

                <div class="panel-body">
                    @if(session('success'))
                        <div class="alert alert-success text-white bg-primary p-2 rounded">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger text-white bg-danger p-2 rounded">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                 

                    <form action="{{ route('takeover.store') }}" method="POST">
                        @csrf



                       <div class="mb-3">
                            <label for="changeoverType" class="form-label">Changeover Type</label>
                            {{-- The ID "changeoverType" is added to the select element --}}
                            <select name="Changeover_id" id="changeoverType" class="form-control select2" required>
                                <option value="">Select Changeover Type</option>
                                <option value="take-over">Taking Over</option>
                                <option value="hand-over">Handing Over</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            {{-- The ID "userLabel" is added to the label for easy access --}}
                            <label for="user_id" id="userLabel" class="form-label">User</label>
                            <select name="user_id" class="form-control select2" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="hostel_id" class="form-label">Hostel</label>
                            <select name="hostel_id" class="form-control select2" required>
                                <option value="">Select Hostel</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Shift</label>
                            <select id="shift_display" class="form-control" disabled>
                                <option value="Day">Day</option>
                                <option value="Night">Night</option>
                            </select>
                            <input type="hidden" name="shift" id="shift">
                        </div>

                        <div class="mb-3">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea name="comments" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select a zone",
            allowClear: true
        });
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
    // Wait for the document to be fully loaded and ready
    document.addEventListener('DOMContentLoaded', function() {
        
        // Find the elements we need to work with by their IDs
        const changeoverSelect = document.getElementById('changeoverType');
        const userLabel = document.getElementById('userLabel');

        // Check if both elements were actually found before adding an event listener
        if (changeoverSelect && userLabel) {
            
            // Listen for any change in the "Changeover Type" dropdown
            changeoverSelect.addEventListener('change', function() {
                
                // Get the currently selected value (e.g., "take-over")
                const selectedValue = this.value;

                // Update the label text based on the selection
                if (selectedValue === 'take-over') {
                    userLabel.innerText = 'Taking Over from';
                } else if (selectedValue === 'hand-over') {
                    userLabel.innerText = 'Handing Over to';
                } else {
                    // Revert to the default text if nothing is selected
                    userLabel.innerText = 'User';
                }
            });
        }
    });
</script>
@endpush


