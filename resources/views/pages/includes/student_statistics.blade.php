




{{--
<div class="card shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <!-- Header and Filter -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <h4 class="card-title mb-2 mb-md-0">📊 Student Attendance Statistics</h4>
            <select id="periodFilter" class="form-select w-auto ms-md-3">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly" selected>Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
        </div>

        <!-- Scrollable Chart -->
        <div style="overflow-x: auto;">
            <div id="studentChart" style="height: 350px; min-width: 600px;"></div>
        </div>
    </div>
</div>
    Add this div right before the card containing the 
    "Weekly Hostel Occupancy Report" table.
--}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light py-2 px-3">
                 <h5 class="card-title mb-0 fw-bold text-secondary">
                    Weekly Occupancy Trends
                 </h5>
            </div>
            <div class="card-body">
                {{-- This is the chart container --}}
                <div id="weeklyHostelOccupancyChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row"> 
<div class="col-xl-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light py-2 px-3">
             <h5 class="card-title mb-0 fw-bold text-secondary">
                Students Present Today by Hostel
             </h5>
        </div>
        <div class="card-body">
            <div id="studentsByHostelChart"></div>
        </div>
    </div>
</div>



<div class="col-xl-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light py-2 px-3">
             <h5 class="card-title mb-0 fw-bold text-secondary">
                Breakdown of Occurrence Types
             </h5>
        </div>
        <div class="card-body">
            <div id="occurrenceTypeChart"></div>
        </div>
    </div>
</div>

</div>

<div class="row">
    <div class="col-xl-6 mb-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light py-2 px-3">
             <h5 class="card-title mb-0 fw-bold text-secondary">
                Students Present Today by Zone
             </h5>
        </div>
        <div class="card-body">
            {{-- This is the chart container --}}
            <div id="zonalStudentsChart"></div>
        </div>
    </div>
</div>
</div>