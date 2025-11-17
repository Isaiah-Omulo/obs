@extends('layouts.default')
@section('title', 'Water Monitoring Records')
@section('content')
<div class="container-fluid mt-3">

    {{-- 🔹 Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800">Water Monitoring Records</h1>
        {{-- This button will take the user to the previous page they were on --}}
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary d-none d-sm-inline-block">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>



  {{-- 🔹 Top Navigation: Date Controls & Actions --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Left Side: Date Navigation --}}
        <div class="btn-group">
            <a href="{{ route('water_monitoring.index', ['date' => $previousDate]) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-chevron-left"></i> Prev
            </a>
            
            <input type="date" class="form-control form-control-sm mx-2" value="{{ $date }}"
                   onchange="window.location.href='{{ route('water_monitoring.index') }}?date=' + this.value;"
                   style="width: auto;">

            <a href="{{ route('water_monitoring.index', ['date' => $nextDate]) }}" 
               class="btn btn-sm btn-outline-secondary {{ $isCurrentDate ? 'disabled' : '' }}"
               aria-disabled="{{ $isCurrentDate ? 'true' : 'false' }}">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        {{-- Right Side: Action Button --}}
        <div>
            <a href="{{ route('water_monitoring.create') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> New Record
            </a>
        </div>
    </div>


    {{-- 🔹 Zones Accordion --}}
    <div class="accordion" id="zonesAccordion">
        @php
            // The times array defines the rows for each hostel's table
           
    // The times array defines the rows for each hostel's table
         $times = ['06:00 PM', '12:00 AM', '04:00 AM', '06:00 AM', '12:00 PM', '04:00 PM'];

        @endphp

        @forelse ($zones as $zone)
        <div class="accordion-item shadow-sm mb-3 rounded border-0">
            <h2 class="accordion-header" id="headingZone{{ $zone->id }}">
                <button class="accordion-button fw-bold" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseZone{{ $zone->id }}"
                    aria-expanded="true" aria-controls="collapseZone{{ $zone->id }}">
                    <i class="fas fa-layer-group me-2 text-primary"></i>
                    {{ $zone->name }}
                </button>
            </h2>
            <div id="collapseZone{{ $zone->id }}" class="accordion-collapse collapse show"
                aria-labelledby="headingZone{{ $zone->id }}" data-bs-parent="#zonesAccordion">

                {{-- Hostels Accordion --}}
                <div class="accordion-body bg-light-subtle">
                    <div class="accordion" id="hostelsAccordion{{ $zone->id }}">
                        @forelse ($zone->hostels as $hostel)
                        <div class="accordion-item border mb-3 rounded">
                            <h2 class="accordion-header" id="headingHostel{{ $hostel->id }}">
                                <button class="accordion-button collapsed py-2 fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseHostel{{ $hostel->id }}"
                                    aria-expanded="false" aria-controls="collapseHostel{{ $hostel->id }}">
                                    <i class="fas fa-building me-2 text-secondary"></i>
                                    {{ $hostel->name }}
                                </button>
                            </h2>
                            <div id="collapseHostel{{ $hostel->id }}" class="accordion-collapse collapse"
                                aria-labelledby="headingHostel{{ $hostel->id }}"
                                data-bs-parent="#hostelsAccordion{{ $zone->id }}">
                                <div class="accordion-body p-3">

                                    {{-- 💧 Water Monitoring Table --}}
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover align-middle shadow-sm mb-0" style="background-color: #fff; border-radius: 10px;">
                                           <thead class="table-primary text-dark">
                                            <tr class="text-center">
                                                {{-- Adjusted column widths --}}
                                                <th style="width: 15%;">Time</th>
                                                <th style="width: 10%;">Is Water</th>
                                                <th style="width: 10%;">Amount</th>
                                                <th style="width: 10%;">Hot Water</th> {{-- New Column Header --}}
                                                <th style="width: 25%;">Remarks</th>
                                                <th style="width: 15%;">Checked By</th>
                                                <th style="width: 15%;">Designation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($times as $time)
                                            @php
                                                $record = $hostel->records->get($time);
                                            @endphp
                                            <tr>
                                                <td class="text-center fw-semibold">{{ $time }}</td>
                                                <td class="text-center">
                                                    @if($record)
                                                        @if($record->is_water === 'Yes')
                                                            <span class="badge bg-success rounded-pill px-3 py-1">Yes</span>
                                                        @else
                                                            <span class="badge bg-danger rounded-pill px-3 py-1">No</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($record && $record->is_water === 'Yes')
                                                        @if($record->amount === 'Plenty')
                                                            <span class="badge bg-primary rounded-pill px-3 py-1">Plenty</span>
                                                        @elseif($record->amount === 'Little')
                                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1">Little</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>

                                                {{-- START: New Column Data --}}
                                                <td class="text-center">
                                                    @if($record && $record->is_hot_water)
                                                        @if($record->is_hot_water === 'Yes')
                                                            <span class="badge bg-success rounded-pill px-3 py-1">Yes</span>
                                                        @elseif($record->is_hot_water === 'No')
                                                            <span class="badge bg-danger rounded-pill px-3 py-1">No</span>
                                                        @elseif($record->is_hot_water === 'N/A')
                                                            <span class="badge bg-secondary rounded-pill px-3 py-1">N/A</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                {{-- END: New Column Data --}}

                                                <td>{{ $record->remarks ?? '-' }}</td>
                                                <td>{{ $record->user->name ?? '-' }}</td>
                                                <td>{{ $record->user->role ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @empty
                            <p class="text-center text-muted">No hostels found in this zone.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="alert alert-info text-center">
                No zones found. Please add zones and hostels in the admin panel.
            </div>
        @endforelse
    </div>
</div>

{{-- 🔹 Script to rotate chevron icons on accordion toggle --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.accordion-button').forEach(btn => {
        const icon = btn.querySelector('i.fas.fa-layer-group, i.fas.fa-building');
        if (!icon) return;

        // Set initial state based on whether it's collapsed or not
        if (btn.classList.contains('collapsed')) {
            icon.style.transform = 'rotate(0deg)';
        } else {
            icon.style.transform = 'rotate(90deg)';
        }

        btn.addEventListener('click', () => {
            // A short delay allows the collapse class to be toggled first
            setTimeout(() => {
                if (btn.classList.contains('collapsed')) {
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    icon.style.transform = 'rotate(90deg)';
                }
                icon.style.transition = 'transform 0.2s ease-in-out';
            }, 10); 
        });
    });
});
</script>
@endpush
@endsection