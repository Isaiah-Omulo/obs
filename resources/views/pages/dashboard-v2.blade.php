@extends('layouts.default')

@section('title', 'Dashboard OBS')

@push('css')
	<link href="/assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
	<link href="/assets/plugins/datepickk/dist/datepickk.min.css" rel="stylesheet" />
	<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
	<link href="/assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />
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
						<div class="stats-title">HOSTEL ATTENDANTS</div>
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




@endsection


@push('scripts')

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



@endpush
