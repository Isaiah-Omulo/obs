<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Occurrence;
use App\Models\User;
use App\Models\Hostel;
use App\Models\StudentStatistic;
use Carbon\Carbon;
use App\Models\Zone;
use App\Models\DailyReport;

class MainController extends Controller
{
    //

     public function dashboardV2() {
             $today = Carbon::today();

             $zonalReportsToday = DailyReport::whereDate('report_date', $today)
                ->whereHas('user', fn($query) => $query->where('role', 'zonal_officer'))
                ->count();

            $adminReportsToday = DailyReport::whereDate('report_date', $today)
                ->whereHas('user', fn($query) => $query->whereIn('role', ['administrator', 'coordinator']))
                ->count();

            $zoneBreakdown = DailyReport::whereDate('created_at', $today)
            ->whereNotNull('zone') // Only reports that have a zone
            ->whereHas('user', function ($query) {
                $query->where('role', 'zonal_officer');
            })
            ->get()
            ->groupBy('zone')
            ->map(function ($group) {
                return $group->count();
            });
            $hostelBreakdown = Hostel::with(['studentStatistics' => function ($query) use ($today) {
                $query->whereDate('record_date', $today);
            }])->get()->map(function ($hostel) {
                $hostel->students_present = $hostel->studentStatistics->sum('students_present');
                return $hostel;
            });

             $startOfWeek = Carbon::now()->startOfWeek();

             $adminDailyStatsSubmitted = DailyReport::whereDate('created_at', $today)
            ->whereHas('user', function ($query) {
                $query->whereIn('role', ['administrator', 'coordinator']);
            })
            ->count();

            // Total Present Today
            $totalToday = StudentStatistic::whereDate('record_date', $today)->sum('students_present');

            // Total Present This Week
            $totalWeek = StudentStatistic::whereBetween('record_date', [$startOfWeek, $today])->sum('students_present');

            // Breakdown by Hostel
            $byHostel = StudentStatistic::selectRaw('hostel_id, SUM(students_present) as total')
                ->whereDate('record_date', $today)
                ->groupBy('hostel_id')
                ->with('hostel') // To access hostel name
                ->get();

            // Breakdown by Shift
            $byShift = StudentStatistic::selectRaw('shift, SUM(students_present) as total')
                ->whereDate('record_date', $today)
                ->groupBy('shift')
                ->get();

            // Breakdown by Zone
            $byZone = Zone::with(['hostels.studentStatistics' => function ($query) use ($today) {
                $query->whereDate('record_date', $today);
            }])->get()->map(function ($zone) {
                $zone->students_present = $zone->hostels->sum(function ($hostel) {
                    return $hostel->studentStatistics->sum('students_present');
                });
                return $zone;
            });



        // Total occurrences
            $totalOccurrences = Occurrence::count();

            // Unresolved occurrences
            $unresolvedOccurrences = Occurrence::where('resolved', 'no')->count();

            // Today's occurrences
            $todaysOccurrences = Occurrence::whereDate('date', $today)->count();

            // Hostel attendants (assuming 'hostel_attendant' is the role name)
            $hostelAttendants = User::where('role', 'hostel_attendant')->count();

            // Total hostels
            $totalHostels = Hostel::count();

            // Student stats submitted today
            $dailyStatsSubmitted = StudentStatistic::whereDate('record_date', $today)->count();
            $totalByType = Occurrence::selectRaw('occurrence_type, COUNT(*) as total')
            ->groupBy('occurrence_type')
            ->get();

            $dailyReports = DailyReport::whereDate('created_at', $today)->get();


        return view('pages/dashboard-v2', compact(
            'totalOccurrences',
            'unresolvedOccurrences',
            'todaysOccurrences',
            'hostelAttendants',
            'totalHostels',
            'dailyStatsSubmitted',
            'totalToday', 'totalWeek', 'byHostel', 'byShift', 'byZone',         
            
            'zonalReportsToday', 'adminReportsToday','zoneBreakdown',
            'adminDailyStatsSubmitted','hostelBreakdown','totalByType','dailyReports'
        ));

    }

    public function loginV2() {
        return view('pages/login-v2');
    }

}
