@extends('layouts.default')

@section('title', 'Dashboard OBS')

@push('css')
	<link href="/assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
	<link href="/assets/plugins/datepickk/dist/datepickk.min.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
	<link href="/assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />

<style type="text/css">
/*
 * FINAL Professional Report Table CSS
 * (Responsive, Mobile-First, with Adjusted Desktop Columns)
 */

/* 1. CORE STYLES (Mobile-First, Scrollable)
--------------------------------------------------------------*/
.professional-report-table {
    min-width: 900px; 
    width: 100%;
    border-collapse: collapse;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background-color: #fff;
}

/* SMART BORDER HIERARCHY */
.professional-report-table th, 
.professional-report-table td {
    border: 1px solid #e9ecef; /* Minor gridline */
    padding: 0.4rem 0.5rem;
    vertical-align: middle;
    font-size: 0.8rem;
    text-align: center;
    transition: border-color 0.2s ease;
}

.professional-report-table thead { border-bottom: 2px solid #adb5bd; } /* Major gridline */
.professional-report-table tfoot { border-top: 2px solid #adb5bd; }    /* Major gridline */
.professional-report-table .day-divider { border-right: 2px solid #dee2e6; }
.professional-report-table .hostel-name-col { border-right: 2px solid #ced4da; }
.professional-report-table .available-col { border-left: 2px solid #ced4da; }

/* HOVER EFFECT */
.professional-report-table tbody tr:hover td {
    background-color: #f8f9fa;
    border-color: #ced4da;
}

/* OTHER STYLING */
.professional-report-table thead { background-color: #f8f9fa; color: #6c757d; font-weight: 600; }
.professional-report-table thead th { font-size: 0.7rem; text-transform: uppercase; }
.professional-report-table tfoot { font-weight: 700; background-color: #f8f9fa; }

/* STICKY COLUMNS */
.professional-report-table .hostel-name-col,
.professional-report-table .available-col { position: sticky; z-index: 2; }
.professional-report-table .hostel-name-col { left: 0; text-align: left !important; font-weight: 600; }
.professional-report-table .available-col { right: 0; font-weight: 700; }

/* Keep sticky column backgrounds consistent */
.professional-report-table tbody tr:hover .hostel-name-col { background-color: #f8f9fa; }
.professional-report-table .hostel-name-col,
.professional-report-table tfoot .hostel-name-col,
.professional-report-table tfoot .available-col { background-color: #f8f9fa; }

.text-muted-light { color: #adb5bd !important; }


/* 2. DESKTOP OVERRIDES (Applies only to screens 992px and wider)
--------------------------------------------------------------*/
@media (min-width: 992px) {
    .professional-report-table {
        table-layout: fixed;
        min-width: 100%;
    }

    /* --- ADJUSTED COLUMN WIDTHS --- */
    
    /* Reduced the width of the first column. Long names will now wrap. */
    .professional-report-table .hostel-name-col { 
        width: 14%; /* <-- MODIFIED */
        white-space: normal; /* Allows text wrapping */
    }
    
    /* Increased the width of the capacity column to fit larger numbers. */
    .professional-report-table .capacity-col { 
        width: 7%; /* <-- MODIFIED */
    }

    /* Slightly increased the width of the 'Available' column. */
    .professional-report-table .available-col { 
        width: 6%; /* <-- MODIFIED */
    }
    
    /* The remaining 14 Day/Night columns will automatically share the leftover 73% of width. */
}
</style>
@endpush

@push('scripts')
	<script src="/assets/plugins/d3/d3.min.js"></script>
	<script src="/assets/plugins/nvd3/build/nv.d3.js"></script>
	<script src="/assets/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
	<script src="/assets/plugins/jvectormap-content/world-mill.js"></script>
	<script src="/assets/plugins/datepickk/dist/datepickk.min.js"></script>
	<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="/assets/js/demo/dashboard-v2.js"></script>
@endpush
@php
    use Carbon\Carbon;
    $today = Carbon::today()->toDateString();
@endphp


@section('content')
<ol class="breadcrumb float-xl-end">
	<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
	<li class="breadcrumb-item active">Dashboard</li>
</ol>
<h1 class="page-header">Dashboard OBS <small>Metrics Overview</small></h1>

<div class="row">
	<div class="col-xl-3 col-md-6">
	    <a href="{{ route('occurrence.index') }}" class="text-decoration-none">
	        <div class="widget widget-stats bg-danger text-white shadow-lg rounded-3" style="cursor: pointer;">
	            <div class="stats-icon stats-icon-lg bg-white text-danger">
	                <i class="fa fa-exclamation-triangle fa-lg"></i>
	            </div>
	            <div class="stats-content">
	                <div class="stats-title text-uppercase fw-bold">Total Occurrences</div>
	                <div class="stats-number display-6 fw-bold">{{ $totalOccurrences }}</div>
	                <div class="stats-desc">All reports across hostels</div>
	            </div>
	        </div>
	    </a>
	</div>

	
		<div class="col-xl-3 col-md-6">
			<a href="{{ route('occurrence.index', ['filter' => 'unresolved']) }}" class="text-decoration-none text-white">
			<div class="widget widget-stats bg-warning">
				<div class="stats-icon stats-icon-lg"><i class="fa fa-tools"></i></div>
				<div class="stats-content">
					<div class="stats-title">UNRESOLVED ISSUES</div>
					<div class="stats-number">{{ $unresolvedOccurrences }}</div>
					<div class="stats-desc">Still pending action</div>
				</div>
			</div>
			</a>
		</div>
	

	
	    <div class="col-xl-3 col-md-6">
	    	<a href="{{ route('daily_reports.index', ['date' => $today]) }}" class="text-decoration-none text-white">
	        <div class="widget widget-stats bg-success">
	            <div class="stats-icon stats-icon-lg"><i class="fa fa-calendar-day"></i></div>
	            <div class="stats-content">
	                <div class="stats-title">TODAY'S REPORTS</div>
	                <div class="stats-number">{{ count($dailyReports) }}</div>
	                <div class="stats-desc">Logged today</div>
	            </div>
	        </div>
	    	</a>
	    </div>
	
		<div class="col-xl-3 col-md-6">
			<a href="{{ route('user.index', ['filter' => 'hostel']) }}" class="text-decoration-none text-white">
				<div class="widget widget-stats bg-primary">
					<div class="stats-icon stats-icon-lg"><i class="fa fa-user-friends"></i></div>
					<div class="stats-content">
						<div class="stats-title">HOSTEL ATTENDANTS/HOUSE KEEPER</div>
						<div class="stats-number">{{ $hostelAttendants }}</div>
						<div class="stats-desc">Active reporters</div>
					</div>
				</div>
			</a>
		</div>

	<div class="col-xl-3 col-md-6">
	    <div class="widget widget-stats bg-info text-white shadow-lg rounded-3"
	         data-bs-toggle="collapse" data-bs-target="#hostelBreakdownTable" style="cursor: pointer;">
	        <div class="stats-icon stats-icon-lg bg-white text-info">
	            <i class="fa fa-home fa-lg"></i>
	        </div>
	        <div class="stats-content">
	            <div class="stats-title text-uppercase fw-bold">Hostels Covered</div>
	            <div class="stats-number display-6 fw-bold">{{ $totalHostels }}</div>
	            <div class="stats-desc">In the system</div>
	        </div>
	    </div>
	</div>

	<div class="collapse col-12 mb-4" id="hostelBreakdownTable">
		    <div class="card card-body border shadow-sm">
		        <h6 class="mb-3 fw-bold">Hostel Breakdown – Students Present Today</h6>
		        <div class="table-responsive">
		            <table class="table table-sm table-bordered align-middle mb-0">
		                <thead class="table-light">
		                    <tr>
		                        <th>#</th>
		                        <th>Hostel Name</th>
		                        <th class="text-end">Students Present</th>
		                    </tr>
		                </thead>
		                <tbody>
		                    @forelse($hostelBreakdown as $index => $hostel)
		                        <tr>
		                            <td>{{ $index + 1 }}</td>
		                            <td>{{ $hostel->name }}</td>
		                            <td class="text-end">{{ $hostel->students_present }}</td>
		                        </tr>
		                    @empty
		                        <tr>
		                            <td colspan="3" class="text-center text-muted">No hostel data found for today.</td>
		                        </tr>
		                    @endforelse
		                </tbody>
		            </table>
		        </div>
		    </div>
	</div>


	<div class="col-xl-3 col-md-6">
		<a href="{{ route('student_statistics.index', ['filter' => 'today']) }}" class="text-decoration-none text-white">
			<div class="widget widget-stats bg-indigo">
				<div class="stats-icon stats-icon-lg"><i class="fa fa-chart-line"></i></div>
				<div class="stats-content">
					<div class="stats-title">DAILY STATS SUBMITTED</div>
					<div class="stats-number">{{ $dailyStatsSubmitted }}</div>
					<div class="stats-desc">Student reports today</div>
				</div>
			</div>
		</a>
	</div>


	<div class="col-xl-3 col-md-6">
		<a href="{{ route('daily_reports.admin', ['filter' => 'today']) }}" class="text-decoration-none">
			<div class="widget widget-stats bg-gradient-blue text-white shadow-lg rounded-3">
				<div class="stats-icon stats-icon-lg bg-white text-indigo">
					<i class="fa fa-user-shield fa-lg"></i>
				</div>
				<div class="stats-content">
					<div class="stats-title text-uppercase fw-bold">Administrator Reports</div>
					<div class="stats-number display-6 fw-bold">{{ $adminDailyStatsSubmitted }}</div>
					<div class="stats-desc">Submitted today by Admins</div>
				</div>
			</div>
		</a>
	</div>



	<div class="col-xl-3 col-md-6">
	    <div class="widget widget-stats bg-gradient-black text-white shadow-lg rounded-3"
	         data-bs-toggle="collapse" data-bs-target="#zonalDetails" style="cursor: pointer;">
	        
	        <div class="stats-icon stats-icon-lg bg-white text-blue">
	            <i class="fa fa-map-marker-alt fa-lg"></i>
	        </div>

	        <div class="stats-content">
	            <div class="stats-title text-uppercase fw-bold">Zonal Officer Reports</div>
	            <div class="stats-number display-6 fw-bold">{{ $zonalReportsToday }}</div>
	            <div class="stats-desc">Submitted Today from Zones</div>
	        </div>
	    </div>
	</div>


	<!-- Collapsible Breakdown -->
	<div class="collapse col-12 mb-4" id="zonalDetails">
	    <div class="card card-body border shadow-sm">
	        <h6 class="mb-2">Breakdown by Zone</h6>
	        <ul class="list-group list-group-flush">
	            @foreach($zoneBreakdown as $zone => $count)
	                <li class="list-group-item d-flex justify-content-between align-items-center">
	                    {{ $zone }}
	                    <span class="badge bg-primary rounded-pill">{{ $count }}</span>
	                </li>
	            @endforeach
	        </ul>
	    </div>
	</div>

	


</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-sm">
             <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
		        {{-- The title now shows the date range --}}
		        <h5 class="card-title mb-0 fw-bold text-secondary">
		            Weekly Hostel Occupancy Report <span class="text-muted">({{ $weekDisplay }})</span>
		        </h5>
		        
		        {{-- The new navigation controls --}}
		        <div class="week-navigation">
		            <a href="{{ route('dashboard-v2', ['date' => $previousWeekDate]) }}" class="btn btn-sm btn-outline-secondary">
		                <i class="fas fa-chevron-left"></i> Prev Week
		            </a>
		            
		            {{-- Only show the "Next Week" button if we are not on the current week --}}
		            @if(!$isCurrentWeek)
		                <a href="{{ route('dashboard-v2', ['date' => $nextWeekDate]) }}" class="btn btn-sm btn-outline-secondary">
		                    Next Week <i class="fas fa-chevron-right"></i>
		                </a>
		            @endif
		        </div>
		    </div>
            <div class="table-responsive">

            	{{--
					@php
                    // MOCKUP DATA...
                    $hostelWeeklyData = [
                        (object)[ 'name' => 'Nyayo 2 Annex', 'capacity' => 5000, 'occupancy' => [4950, 4980, 4945, 4975, 4940, 4970, 4935, 4965, 4100, 4120, null, null, null, null], 'latest_occupancy' => 4120 ],
                        (object)[ 'name' => 'Usambara 1', 'capacity' => 80, 'occupancy' => [75, 78, 74, 77, 72, 76, 69, 74, 68, 70, 65, 68, 64, 67], 'latest_occupancy' => 67 ],
                        (object)[ 'name' => 'Aberdare 3 Main Block', 'capacity' => 120, 'occupancy' => [80, 85, 82, 88, 78, 84, 75, 80, 72, 78, 70, 75, 68, 72], 'latest_occupancy' => 72 ]
                    ];
                    $totals = [ 'capacity' => 0, 'occupancy' => array_fill(0, 14, 0), 'latest_occupancy' => 0 ];
                @endphp
            	--}}
                

                <table class="professional-report-table">
    <thead>
        <tr>
            <th rowspan="2" class="hostel-name-col">Hostel</th>
            <th rowspan="2" class="capacity-col">Max No. of Students</th>
            <th colspan="2">Mon</th><th colspan="2">Tue</th><th colspan="2">Wed</th>
            <th colspan="2">Thu</th><th colspan="2">Fri</th><th colspan="2">Sat</th>
            <th colspan="2">Sun</th>
            <th rowspan="2" class="available-col">Avail.</th>
        </tr>
        <tr>
            <th>N</th><th class="day-divider">D</th>
            <th>N</th><th class="day-divider">D</th>
            <th>N</th><th class="day-divider">D</th>
            <th>N</th><th class="day-divider">D</th>
            <th>N</th><th class="day-divider">D</th>
            <th>N</th><th class="day-divider">D</th>
            <th>N</th><th class="day-divider">D</th>
        </tr>
    </thead>
    <tbody>
        @foreach($hostelWeeklyData as $hostel)
            <tr>
                <td class="hostel-name-col">{{ $hostel->name }}</td>
                <td class="capacity-col">{{ $hostel->capacity }}</td>
                @php $todayIndex = (now()->dayOfWeekIso - 1) * 2; @endphp
                @foreach($hostel->occupancy as $index => $count)
                   
                    <td class="{{ ($index % 2 != 0) ? 'day-divider' : '' }} 
				               {{ $index === $todayIndex || $index === $todayIndex+1 ? 'bg-cyan-200 fw-bold' : '' }}">
				        {!! $count !== null ? $count : '<span class="text-muted-light">—</span>' !!}
				    </td>
                @endforeach

                <td class="available-col fw-bold">
                  
                    <span class="{{ $hostel->available <= ($hostel->capacity * 0.1) ? 'text-danger' : 'text-success' }}">
                        {{ $hostel->available }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot class="table-info">
        
        <tr>
            <td class="hostel-name-col">Total</td>
            <!-- CHANGED: Use object syntax `->` instead of array syntax `[]` -->
            <td class="capacity-col">{{ $totals->capacity }}</td>
            
            <!-- CHANGED: Use object syntax `->` for the loop -->
            @foreach($totals->occupancy as $index => $totalCount)
                <td class="{{ ($index % 2 != 0) ? 'day-divider' : '' }}">
                    {!! $totalCount > 0 ? $totalCount : '<span class="text-muted-light">—</span>' !!}
                </td>
            @endforeach

            <td class="available-col fw-bold">
                <!-- CHANGED: Use the pre-calculated `$totals->available` and object syntax `->` -->
                <span class="{{ $totals->available <= ($totals->capacity * 0.1) ? 'text-danger' : 'text-success' }}">
                    {{ $totals->available }}
                </span>
            </td>
        </tr>
    </tfoot>
</table>

               
            </div>
        </div>
    </div>
</div>




<!-- =================================================================== -->
<!-- START: ZONAL WEEKLY OCCUPANCY REPORT TABLE                      -->
<!-- This is the new component for your Zonal data.                  -->
<!-- =================================================================== -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
             <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
		        {{-- The title is updated for the Zonal Report --}}
		        <h5 class="card-title mb-0 fw-bold text-secondary">
		            Weekly Zonal Occupancy Report <span class="text-muted">({{ $weekDisplay }})</span>
		        </h5>
		        
		        {{-- The navigation controls are identical and will control both tables --}}
		        <div class="week-navigation">
		            <a href="{{ route('dashboard-v2', ['date' => $previousWeekDate]) }}" class="btn btn-sm btn-outline-secondary">
		                <i class="fas fa-chevron-left"></i> Prev Week
		            </a>
		            
		            @if(!$isCurrentWeek)
		                <a href="{{ route('dashboard-v2', ['date' => $nextWeekDate]) }}" class="btn btn-sm btn-outline-secondary">
		                    Next Week <i class="fas fa-chevron-right"></i>
		                </a>
		            @endif
		        </div>
		    </div>
            <div class="table-responsive">
                <table class="professional-report-table">
                    <thead>
                        <tr>
                            {{-- CHANGED: The main column is now "Zone" --}}
                            <th rowspan="2" class="hostel-name-col">Zone</th>
                            <th rowspan="2" class="capacity-col">Max No. of Students</th>
                            <th colspan="2">Mon</th><th colspan="2">Tue</th><th colspan="2">Wed</th>
                            <th colspan="2">Thu</th><th colspan="2">Fri</th><th colspan="2">Sat</th>
                            <th colspan="2">Sun</th>
                            <th rowspan="2" class="available-col">Avail.</th>
                        </tr>
                        <tr>
                            <th>N</th><th class="day-divider">D</th>
                            <th>N</th><th class="day-divider">D</th>
                            <th>N</th><th class="day-divider">D</th>
                            <th>N</th><th class="day-divider">D</th>
                            <th>N</th><th class="day-divider">D</th>
                            <th>N</th><th class="day-divider">D</th>
                            <th>N</th><th class="day-divider">D</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- CHANGED: We now loop through `$zoneWeeklyData` as `$zone` --}}
                        @foreach($zoneWeeklyData as $zone)
                            <tr>
                                {{-- Use the $zone variable to display data --}}
                                <td class="hostel-name-col">{{ $zone->name }}</td>
                                <td class="capacity-col">{{ $zone->capacity }}</td>
                                
                                @foreach($zone->occupancy as $index => $count)
                                    <td class="{{ ($index % 2 != 0) ? 'day-divider' : '' }}">
                                        {!! $count > 0 ? $count : '<span class="text-muted-light">—</span>' !!}
                                    </td>
                                @endforeach

                                <td class="available-col fw-bold">
                                    <span class="{{ $zone->available <= ($zone->capacity * 0.1) ? 'text-danger' : 'text-success' }}">
                                        {{ $zone->available }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="hostel-name-col">Total</td>
                            
                            {{-- CHANGED: Use the `$zonalTotals` variable for the footer --}}
                            <td class="capacity-col">{{ $zonalTotals->capacity }}</td>
                            
                            @foreach($zonalTotals->occupancy as $index => $totalCount)
                                <td class="{{ ($index % 2 != 0) ? 'day-divider' : '' }}">
                                    {!! $totalCount > 0 ? $totalCount : '<span class="text-muted-light">—</span>' !!}
                                </td>
                            @endforeach

                            <td class="available-col fw-bold">
                                <span class="{{ $zonalTotals->available <= ($zonalTotals->capacity * 0.1) ? 'text-danger' : 'text-success' }}">
                                    {{ $zonalTotals->available }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- FINAL COMPONENT END -->
{{--
<div class="row">

    <!-- Total Today -->


    <div class="col-xl-3 col-md-6 mb-2">
	    <div class="card border-danger shadow-sm">
	        <div class="card-body d-flex align-items-center justify-content-between">
	            <div>
	                <h6 class="card-title text-danger">
	                    <i class="fas fa-calendar-day me-1"></i> Occurrences Submitted Today
	                </h6>
	                <h3>{{ $todaysOccurrences }}</h3>
	            </div>
	            <div>
	                <span class="sparkline" data-values="3,2,4,1,5,3,4"></span>
	            </div>
	        </div>
	    </div>
	</div>


   <div class="col-xl-3 col-md-6 mb-2">
	    <div class="card border-warning shadow-sm"
	         data-bs-toggle="collapse"
	         data-bs-target="#occurrenceTypeTable"
	         style="cursor: pointer;">
	        <div class="card-body d-flex align-items-center justify-content-between">
	            <div>
	                <h6 class="card-title text-warning">
	                    <i class="fas fa-layer-group me-1"></i> Occurrences by Type
	                </h6>
	                <h3>{{ count($totalByType) ?? '—' }}</h3>
	            </div>
	            <div>
	                <span class="sparkline" data-values="2,3,3,5,4,6,2"></span>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="collapse col-12 mb-4" id="occurrenceTypeTable">
	    <div class="card card-body border shadow-sm">
	        <h6 class="mb-3 fw-bold">Breakdown – All Occurrences by Type</h6>
	        <div class="table-responsive">
	            <table class="table table-sm table-bordered align-middle mb-0">
	                <thead class="table-light">
	                    <tr>
	                        <th>#</th>
	                        <th>Type</th>
	                        <th class="text-end">Total</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    @forelse($totalByType as $index => $item)
	                        <tr>
	                            <td>{{ $index + 1 }}</td>
	                            <td>{{ $item->occurrence_type }}</td>
	                            <td class="text-end">{{ $item->total }}</td>
	                        </tr>
	                    @empty
	                        <tr>
	                            <td colspan="3" class="text-center text-muted">No occurrence types found.</td>
	                        </tr>
	                    @endforelse
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>




	<div class="col-xl-3 col-md-6 mb-2">
        <div class="card border-success shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title text-success"><i class="fas fa-user-check me-1"></i> Students Present Today</h6>
                    <h3>{{ $totalToday }}</h3>
                </div>
                <div>
                    <span class="sparkline" data-values="4,5,3,6,5,8,7"></span>
                </div>
            </div>
        </div>
    </div>


    <!-- Total This Week -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-primary shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title text-primary"><i class="fas fa-calendar-week me-1"></i>Students Present This Week</h6>
                    <h3>{{ $totalWeek }}</h3>
                </div>
                <div>
                    <span class="sparkline" data-values="10,12,9,14,13,15,16"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- By Hostel -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <i class="fas fa-building me-1"></i> By Hostel (Today)
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($byHostel as $entry)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $entry->hostel->name }}
                        <span class="badge bg-info">{{ $entry->total }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- By Shift -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-clock me-1"></i> By Shift (Today)
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($byShift as $shift)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ ucfirst($shift->shift) }}
                        <span class="badge bg-warning text-dark">{{ $shift->total }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- By Zone -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-map-marked-alt me-1"></i> By Zone (Today)
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($byZone as $zone)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $zone->name }}
                        <span class="badge bg-secondary">{{ $zone->students_present }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>



--}}

 {{-- Summary Tiles Row --}}
    <div class="row">

        <!-- Tile 1: Hostels with Water Issues -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Water Issues (Today's Latest)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $hostelsWithNoWater }} No Water / {{ $hostelsWithLittleWater }} Little
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="{{ route('water_monitoring.index', ['date' => now()->toDateString()]) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Tile 2: Daily Monitoring Progress -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Water Monitoring Progress (Today)
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ $reportsSubmittedToday }} / {{ $totalExpectedReports }} Reports
                                    </div>
                                </div>
                                <div class="col">
                                    @php
                                        $progress = $totalExpectedReports > 0 ? ($reportsSubmittedToday / $totalExpectedReports) * 100 : 0;
                                    @endphp
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                     <a href="{{ route('water_monitoring.index', ['date' => now()->toDateString()]) }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

    </div>


@include('pages.includes.student_statistics')


@endsection


@push('scripts')


{{-- Your other scripts... --}}





<script>
    $(function () {
        $('.sparkline').each(function () {
            const $this = $(this);
            const values = $this.data('values').toString().split(',');
            $this.sparkline(values, {
                type: 'bar',
                height: '30',
                barColor: '#28a745',
                barWidth: 5,
                barSpacing: 2,
            });
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartEl = document.querySelector("#studentChart");
    let chart;

    function fetchChartData(period = 'monthly') {
        fetch(`{{ route('student_statistics.chartData') }}?period=${period}`)
            .then(res => res.json())
            .then(data => {
                const options = {
                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: { show: false }
                    },
                    series: [{
                        name: 'Students Present',
                        data: data.counts
                    }],
                    xaxis: {
                        categories: data.labels
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    markers: {
                        size: 4
                    },
                    colors: ['#4e73df']
                };

                if (chart) {
                    chart.updateOptions(options);
                } else {
                    chart = new ApexCharts(chartEl, options);
                    chart.render();
                }
            });
    }

    fetchChartData();

    document.getElementById('periodFilter').addEventListener('change', function () {
        fetchChartData(this.value);
    });
});
</script>





{{-- Your existing scripts are here... --}}

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function () {
    
    // 1. Get the PHP data and safely convert it to JavaScript
    const hostelData = @json($hostelWeeklyData);

    // 2. Prepare the data for ApexCharts
    const series = hostelData.map(hostel => {
        return {
            name: hostel.name,
            // Filter out null values from occupancy data
            data: hostel.occupancy.filter(d => d !== null) 
        };
    });

    const categories = [
        'Mon (N)', 'Mon (D)', 'Tue (N)', 'Tue (D)', 'Wed (N)', 'Wed (D)', 
        'Thu (N)', 'Thu (D)', 'Fri (N)', 'Fri (D)', 'Sat (N)', 'Sat (D)', 
        'Sun (N)', 'Sun (D)'
    ];

    // 3. Define the chart options
    var options = {
        chart: {
            height: 400,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                }
            }
        },
        series: series,
        xaxis: {
            categories: categories,
            title: {
                text: 'Day of the Week (N: Night, D: Day)'
            }
        },
        yaxis: {
            title: {
                text: 'Number of Students'
            }
        },
        title: {
            text: 'Hostel Occupancy Trends',
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 'bold',
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 5
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
        },
        grid: {
            borderColor: '#f1f1f1',
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
        }
    };

    // 4. Create and render the chart
    var chart = new ApexCharts(
        document.querySelector("#weeklyHostelOccupancyChart"),
        options
    );
    chart.render();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Get data from the hostel breakdown variable
    const breakdownData = @json($hostelBreakdown);

    // Prepare data
    const labels = breakdownData.map(hostel => hostel.name);
    const values = breakdownData.map(hostel => hostel.students_present);

    var options = {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'Students Present',
            data: values
        }],
        xaxis: {
            categories: labels
        },
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 4
            }
        },
        dataLabels: {
            enabled: false
        },
        title: {
            text: 'Current Hostel Population',
            align: 'center'
        },
        colors: ['#008ffb']
    };

    var chart = new ApexCharts(document.querySelector("#studentsByHostelChart"), options);
    chart.render();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Get data from the occurrence type variable
    const typeData = @json($totalByType);

    // Prepare data
    const labels = typeData.map(item => item.occurrence_type);
    const values = typeData.map(item => item.total);

    var options = {
        chart: {
            type: 'donut',
            height: 350,
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                }
            }
        },
        series: values,
        labels: labels,
        title: {
            text: 'Proportion of Occurrence Types',
            align: 'center'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#occurrenceTypeChart"), options);
    chart.render();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Get PHP data for the Zonal breakdown
    // This assumes the $byZone variable is available from your controller.
    const zonalData = @json($byZone);

    // 2. Prepare the data for the chart
    const zoneLabels = zonalData.map(zone => zone.name);
    const zoneValues = zonalData.map(zone => zone.students_present);

    // 3. Define the chart options
    var options = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: true, // Optional: hide the toolbar for a cleaner look
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                }
            }
        },
        series: [{
            name: 'Students Present',
            data: zoneValues
        }],
        xaxis: {
            categories: zoneLabels
        },
        yaxis: {
            title: {
                text: 'Number of Students'
            }
        },
        plotOptions: {
            bar: {
                // This gives each bar its own color from the colors array
                distributed: true, 
                horizontal: false,
                borderRadius: 4
            }
        },
        dataLabels: {
            enabled: false
        },
        // Hiding the legend as the x-axis labels are clear enough
        legend: {
            show: false
        },
        title: {
            text: 'Current Student Population by Zone',
            align: 'center'
        },
        // A nice color palette for the bars
        colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#26a69a', '#D10CE8'] 
    };

    // 4. Create and render the chart
    var chart = new ApexCharts(document.querySelector("#zonalStudentsChart"), options);
    chart.render();
});
</script>
@endpush
