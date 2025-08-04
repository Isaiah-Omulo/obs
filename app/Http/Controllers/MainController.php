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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    //



       public function HostelChart(Request $request)
    {
                Log::info("ChartData function accessed");

            try {
                $period = $request->get('period', 'monthly');
                $now = now();
                $labels = [];
                $counts = [];

                $userId = auth()->id();
                $hostelId = StudentStatistic::lastUsedHostelIdByUser($userId);

                switch ($period) {
                    case 'daily':
                        $dates = collect(range(0, 6))->map(fn($i) => $now->copy()->subDays($i)->format('Y-m-d'))->reverse();
                        foreach ($dates as $date) {
                            $labels[] = Carbon::parse($date)->format('D');
                            $counts[] = StudentStatistic::where('user_id', $userId)
                                ->where('hostel_id', $hostelId)
                                ->whereDate('record_date', $date)
                                ->sum('students_present');
                        }
                        break;

                    case 'weekly':
                        $weeks = collect(range(0, 3))->map(fn($i) => $now->copy()->subWeeks($i))->reverse();
                        foreach ($weeks as $weekStart) {
                            $start = $weekStart->copy()->startOfWeek();
                            $end = $weekStart->copy()->endOfWeek();
                            $labels[] = $start->format('M d');
                            $counts[] = StudentStatistic::where('user_id', $userId)
                                ->where('hostel_id', $hostelId)
                                ->whereBetween('record_date', [$start, $end])
                                ->sum('students_present');
                        }
                        break;

                    case 'yearly':
                        $months = range(1, 12);
                        foreach ($months as $month) {
                            $labels[] = Carbon::create(null, $month, 1)->format('M');
                            $counts[] = StudentStatistic::where('user_id', $userId)
                                ->where('hostel_id', $hostelId)
                                ->whereYear('record_date', $now->year)
                                ->whereMonth('record_date', $month)
                                ->sum('students_present');
                        }
                        break;

                    default: // monthly
                        $daysInMonth = $now->daysInMonth;
                        for ($i = 1; $i <= $daysInMonth; $i++) {
                            $date = $now->copy()->day($i)->format('Y-m-d');
                            $labels[] = $i;
                            $counts[] = StudentStatistic::where('user_id', $userId)
                                ->where('hostel_id', $hostelId)
                                ->whereDate('record_date', $date)
                                ->sum('students_present');
                        }
                        break;
                }

                return response()->json([
                    'labels' => $labels,
                    'counts' => $counts,
                ]);
            } catch (\Exception $e) {
                Log::error("ChartData error: " . $e->getMessage());
                return response()->json(['error' => 'Something went wrong.'], 500);
            }
    
    }


    public function hostelAttendantDashboard()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Occurrences reported today in this user's hostel
           $occurrencesToday = Occurrence::where('user_id', $user->id)
        ->whereDate('date', $today)
        ->count();

        // Step 2: Get the user's most recently used hostel
        $hostel = Occurrence::lastUsedHostelByUser($user->id);

        $studentStat = StudentStatistic::where('user_id', $user->id)
            ->whereDate('record_date', $today)
            ->first();

        $studentsPresentToday = $studentStat ? $studentStat->students_present : 0;

        // Pending Issues (unresolved occurrences)
        $pendingIssues = Occurrence::where('user_id', $user->id)
        ->where('resolved', 'no')
        ->count();

        $userId = Auth::id();

                // Latest occurrence by user
        $latestOccurrence = Occurrence::where('user_id', $userId)->latest()->first();

        // Get shift and hostel from latest occurrence (if exists)
        $shift = $latestOccurrence ? $latestOccurrence->shift : 'Not Recorded';
        $myHostel = $latestOccurrence ? $latestOccurrence->hostel : 'Not Recorded';

            $lastStatistic = StudentStatistic::where('user_id', $userId)
            ->latest('record_date')
            ->first();

        // Attendance trend (past 30 days)
        $attendanceTrend = StudentStatistic::selectRaw('DATE(record_date) as day, SUM(students_present) as total')
        ->where('hostel_id', $user->hostel_id) 
        ->whereDate('record_date', '>=', Carbon::now()->subDays(30))
        ->groupBy('day')
        ->orderBy('day')
        ->get();

        return view('pages/.hostel', compact(
            'occurrencesToday',
            'studentsPresentToday',
            'pendingIssues',
            'shift',
            'myHostel',
            'lastStatistic',
            'attendanceTrend'
        ));
    }

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



        $user = Auth::user();
        $role = $user->role; // assuming your User model has a 'role' column

        if (in_array($role, ['house_keeper', 'hostel_attendant'])) {
            return redirect()->route('dashboard.attendant');
        }


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
