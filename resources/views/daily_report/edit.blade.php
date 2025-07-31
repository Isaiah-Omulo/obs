@extends('layouts.default')
@section('title', 'Edit Daily Report')

@section('content')
<div class="container-fluid mt-5">
    <div class="row d-flex align-items-center justify-content-center" style="min-height: 70vh;">
        <div class="col-xl-6 col-lg-8">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Edit Daily Report</h4>
                    <div class="panel-heading-btn">
                        <a href="{{ route('daily_reports.index') }}" class="btn btn-sm btn-primary">All Reports</a>
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

                    @php
                        $role = Auth::user()->role;
                        $isZonal = $role === 'zonal_officer';
                    @endphp

                    <form action="{{ route('daily_reports.update', $dailyreport->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($isZonal)
                        <div class="mb-3">
                            <label class="form-label" for="zone">Zone</label>
                            <select name="zone" id="zone" class="form-control select2" required>
                                <option value="">-- Select Zone --</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->name }}" {{ $dailyreport->zone === $zone->name ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('zone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label" for="datetime">Date & Time</label>
                            <input type="datetime-local" name="datetime" id="datetime" class="form-control" 
                                   value="{{ \Carbon\Carbon::parse($dailyreport->report_date . ' ' . $dailyreport->report_time)->format('Y-m-d\TH:i') }}" required>
                            @error('datetime') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Shift</label>
                            <select id="shift_display" class="form-control" disabled>
                                <option value="">Select shift</option>
                                <option value="Day" {{ $dailyreport->shift === 'Day' ? 'selected' : '' }}>Day</option>
                                <option value="Night" {{ $dailyreport->shift === 'Night' ? 'selected' : '' }}>Night</option>
                            </select>
                            <input type="hidden" name="shift" id="shift" value="{{ $dailyreport->shift }}" />
                            @error('shift') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="report">Report</label>
                            <textarea name="report" id="report" rows="5" class="form-control" required>{{ old('report', $dailyreport->report) }}</textarea>
                            @error('report') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fa fa-save me-1"></i> Update Report
                            </button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endpush
