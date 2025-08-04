@extends('layouts.default')

@section('title', 'Hostel Dashboard')

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
    $today = \Carbon\Carbon::today()->toDateString();
    $userId = auth()->id(); // or Auth::user()->id
@endphp


@section('content')
<!-- HOSTEL ATTENDANT DASHBOARD -->
<div id="content" class="app-content" style="margin-right: 10%;">
    <h1 class="page-header">Hostel Attendant Dashboard</h1>

    <!-- TOP ROW: DASHBOARD CARDS -->
    <div class="row">
        <!-- Occurrences Today -->
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('occurrence.index', ['user_id' => $userId, 'date' => $today]) }}" class="text-decoration-none">
            <div class="card border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="mb-1 text-primary"><i class="fas fa-exclamation-circle me-1"></i> Occurrences Today</h6>
                    <h3 class="fw-bold">{{ $occurrencesToday }}</h3>
                </div>
            </div>
        </a>
        </div>

        <!-- Students Present Today -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="mb-1 text-success"><i class="fas fa-user-check me-1"></i> Students Present Today</h6>
                    <h3 class="fw-bold">{{ $studentsPresentToday }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('occurrence.index', ['filter' => 'unresolved']) }}" class="text-decoration-none">
                <div class="card border-start border-danger border-4 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="mb-1 text-danger">
                            <i class="fas fa-tasks me-1"></i> Pending Issues
                        </h6>
                        <h3 class="fw-bold text-dark">{{ $pendingIssues }}</h3>
                    </div>
                </div>
            </a>
        </div>


        <!-- Shift Summary -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="mb-1 text-info"><i class="fas fa-clock me-1"></i> Shift</h6>
                    <p class="mb-0">{{ $shift }}</p>
                </div>
            </div>
        </div>

        <!-- My Hostel Info -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="mb-1 text-warning"><i class="fas fa-building me-1"></i> My Hostel</h6>
                    <p class="mb-0">{{ $myHostel }}</p>
                </div>
            </div>
        </div>

        <!-- Last Report Summary -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-start border-secondary border-4 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="mb-1 text-secondary"><i class="fas fa-file-alt me-1"></i> Last Report Summary</h6>
                    <p class="mb-0">
                        {{ $lastStatistic 
                            ? 'Present: ' . $lastStatistic->students_present . ' | Date: ' . \Carbon\Carbon::parse($lastStatistic->record_date)->format('M d, Y')
                            : 'No previous report' 
                        }}
                    </p>
                </div>
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
document.addEventListener('DOMContentLoaded', function () {
    const chartEl = document.querySelector("#studentChart");
    let chart;

    function fetchChartData(period = 'monthly') {
        fetch(`{{ route('attendants.chartData') }}?period=${period}`)
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
