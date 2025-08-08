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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection; 
use App\Models\DailyHostelSummary;



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


    public function weeklyHostelOccupancy()
        {
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            // 1. GET THE DATA
            // Query the fast summary table and group the results by hostel_id for easy lookups.
            $summariesByHostelId = DailyHostelSummary::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->get()
                ->groupBy('hostel_id');

            // Get all hostels to ensure every hostel appears, even if it has no data.
            $allHostels = Hostel::get(['id', 'name', 'number_of_students']);

            // 2. BUILD THE DATA FOR EACH HOSTEL ROW
            // This loop prepares the main body of your table.
            $hostelWeeklyData = $allHostels->map(function ($hostel) use ($summariesByHostelId) {
                
                $occupancyData = array_fill(0, 14, null);

                // Check if we have summaries for the current hostel.
                if ($summariesByHostelId->has($hostel->id)) {
                    // If so, populate the occupancy data array.
                    foreach ($summariesByHostelId->get($hostel->id) as $summary) {
                        $dayIndex = $summary->date->dayOfWeekIso - 1; // Mon=0, Tue=1...
                        $occupancyData[($dayIndex * 2)] = $summary->students_present_night;
                        $occupancyData[($dayIndex * 2) + 1] = $summary->students_present_day;
                    }
                }
                
                $latestOccupancy = last(array_filter($occupancyData));

                return (object)[
                    'name'      => $hostel->name,
                    'capacity'  => $hostel->number_of_students,
                    'occupancy' => $occupancyData,
                    'latest_occupancy' => $latestOccupancy,
                    'available' => $hostel->number_of_students - $latestOccupancy,
                ];
            });

            // 3. CALCULATE THE COLUMN TOTALS
            // This logic is now separate and clear. It sums up the data we just built.
            $totals = $this->calculateColumnTotals($hostelWeeklyData);

            // 4. RETURN THE VIEW
            return [
                 $hostelWeeklyData,
                 $totals
            ];
        }


           private function calculateColumnTotals(Collection $hostelWeeklyData): object
        {
            // Initialize placeholders for our grand totals.
            $totalCapacity = 0;
            $totalLatestOccupancy = 0;
            $totalOccupancyBySlot = array_fill(0, 14, 0);

            // Loop through the final, processed data for each hostel.
            foreach ($hostelWeeklyData as $hostelData) {
                
                // =======================================================
                // THE FIX IS HERE
                // =======================================================
                // 1. First, check if the entire $hostelData object is null.
                //    If it is, skip this iteration completely.
                if (!$hostelData) {
                    continue;
                }

                // 2. Now, when adding to totals, use the null coalescing
                //    operator (?? 0). This means "use the value, but if
                //    it's null, use 0 instead".
                $totalCapacity += $hostelData->capacity ?? 0;
                $totalLatestOccupancy += $hostelData->latest_occupancy ?? 0;

                // This inner loop is also safe because of the check above.
                foreach ($hostelData->occupancy as $index => $count) {
                    $totalOccupancyBySlot[$index] += $count ?? 0;
                }
                // =======================================================
            }

            return (object)[
                'capacity' => $totalCapacity,
                'occupancy' => $totalOccupancyBySlot,
                'latest_occupancy' => $totalLatestOccupancy,
                'available' => $totalCapacity - $totalLatestOccupancy,
            ];
        }

        private function prepareWeeklyHostelData(Request $request): array
    {
        // 1. DETERMINE THE TARGET DATE
        // Check for a 'date' in the URL. If it's not there or is invalid,
        // Carbon::parse gracefully defaults to 'now'.
        $targetDate = Carbon::parse($request->input('date', 'now'));

        // 2. CALCULATE THE DATE RANGE & NAVIGATION LINKS
        // Use ->copy() to avoid modifying the original $targetDate object.
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();

        // Dates for the "Prev" and "Next" buttons in 'YYYY-MM-DD' format.
        $previousWeekDate = $targetDate->copy()->subWeek()->toDateString();
        $nextWeekDate = $targetDate->copy()->addWeek()->toDateString();
        
        // A user-friendly string for the report header, e.g., "Aug 04, 2025 - Aug 10, 2025"
        $weekDisplay = $startOfWeek->format('M d, Y') . ' - ' . $endOfWeek->format('M d, Y');
        
        // Check if the currently viewed week is the current calendar week.
        // This is used to hide the "Next Week" button when appropriate.
        $isCurrentWeek = $targetDate->isCurrentWeek();

        // 3. GET THE DATA
        // Fetch summaries for the calculated week and group by hostel_id for efficient lookups.
        $summariesByHostelId = DailyHostelSummary::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get()->groupBy('hostel_id');

        // Get all hostels to ensure every hostel appears in the report.
        $allHostels = Hostel::get(['id', 'name', 'number_of_students']);

        // 4. BUILD THE DATA FOR EACH HOSTEL ROW
        $hostelWeeklyData = $allHostels->map(function ($hostel) use ($summariesByHostelId) {
            
            // Sanity check: If the hostel object is invalid, return null.
            if (!$hostel || !$hostel->id) {
                return null;
            }
            
            // Prepare the 14 data slots (Mon-N, Mon-D, ... Sun-D), all initially null.
            $occupancyData = array_fill(0, 14, null);

            // Check if we have summary data for this specific hostel.
            if ($summariesByHostelId->has($hostel->id)) {
                // If so, loop through its daily summaries and place them in the correct slots.
                foreach ($summariesByHostelId->get($hostel->id) as $summary) {
                    // Safety check for the summary record itself.
                    if ($summary && $summary->date) {
                        $dayIndex = $summary->date->dayOfWeekIso - 1; // Mon=0, Tue=1...
                        
                        // Ensure the calculated index is within the valid range (0-6).
                        if ($dayIndex >= 0 && $dayIndex <= 6) {
                            $occupancyData[($dayIndex * 2)] = $summary->students_present_night;
                            $occupancyData[($dayIndex * 2) + 1] = $summary->students_present_day;
                        }
                    }
                }
            }
            
            // Find the last non-null occupancy value recorded for the week.
            $latestOccupancy = last(array_filter($occupancyData, fn($val) => $val !== null));
            
            // Use the null coalescing operator for capacity as a safety measure.
            $capacity = $hostel->number_of_students ?? 0;

            // Return the final, structured object for this hostel row.
            return (object)[
                'name'      => $hostel->name,
                'capacity'  => $capacity,
                'occupancy' => $occupancyData,
                'latest_occupancy' => $latestOccupancy,
                'available' => $capacity - ($latestOccupancy ?? 0),
            ];
        })->filter(); // This crucial step removes any `null` items from the collection.

        // 5. CALCULATE TOTALS
        // Pass the cleaned, final data to the calculation helper.
        $totals = $this->calculateColumnTotals($hostelWeeklyData);

        // 6. RETURN EVERYTHING THE VIEW NEEDS
        // The main controller method will pass this array to the Blade view.
        return [
            'hostelWeeklyData' => $hostelWeeklyData,
            'totals'           => $totals,
            'weekDisplay'      => $weekDisplay,
            'previousWeekDate' => $previousWeekDate,
            'nextWeekDate'     => $nextWeekDate,
            'isCurrentWeek'    => $isCurrentWeek,
        ];
    }


            private function prepareWeeklyZonalData(Request $request): array
    {
        // Date handling is the same
        $targetDate = Carbon::parse($request->input('date', 'now'));
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();

        // 1. THE NEW, EFFICIENT QUERY
        // Get all summaries for the week, and eager-load the relationships we need.
        $weeklySummaries = DailyHostelSummary::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->with('hostel:id,zone_id') // We only need hostel's zone_id
            ->get();

        // 2. PREPARE THE SKELETON & ZONAL CAPACITIES
        $allZones = Zone::with('hostels:id,zone_id,number_of_students')->get();
        $zoneDataMap = [];
        
        foreach ($allZones as $zone) {
            $zoneDataMap[$zone->id] = (object)[
                'name'      => $zone->name,
                // Pre-calculate the total capacity for the zone
                'capacity'  => $zone->hostels->sum('number_of_students'),
                // Initialize occupancy with 0s for aggregation
                'occupancy' => array_fill(0, 14, 0),
            ];
        }

        // 3. AGGREGATE THE DATA
        foreach ($weeklySummaries as $summary) {
            // Skip if the summary doesn't have a valid hostel or zone
            if (!$summary->hostel || !$summary->hostel->zone_id) {
                continue;
            }
            $zoneId = $summary->hostel->zone_id;

            // Make sure this zone exists in our map before trying to add to it
            if (!isset($zoneDataMap[$zoneId])) {
                continue;
            }

            $dayIndex = $summary->date->dayOfWeekIso - 1; // Mon=0, Tue=1...
            if ($dayIndex >= 0 && $dayIndex <= 6) {
                // ADD the hostel's count to the zone's total for that slot
                $zoneDataMap[$zoneId]->occupancy[($dayIndex * 2)] += $summary->students_present_night ?? 0;
                $zoneDataMap[$zoneId]->occupancy[($dayIndex * 2) + 1] += $summary->students_present_day ?? 0;
            }
        }

        // 4. FINALIZE & CALCULATE TOTALS
        // Convert the map to a simple collection and calculate final values.
        $zoneWeeklyData = collect($zoneDataMap)->map(function ($zoneData) {
            $latestOccupancy = last(array_filter($zoneData->occupancy, fn($val) => $val > 0));
            $zoneData->latest_occupancy = $latestOccupancy;
            $zoneData->available = $zoneData->capacity - ($latestOccupancy ?? 0);
            return $zoneData;
        });

        // We can reuse the same totals calculator!
        $totals = $this->calculateColumnTotals($zoneWeeklyData);

        return [
            'zoneWeeklyData' => $zoneWeeklyData,
            'totals'       => $totals,
        ];
    }
     public function dashboardV2(Request $request) {
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


             $latestStatisticIds = DB::table('student_statistics')
                ->select(DB::raw('MAX(id) as id'))
                ->whereDate('record_date', $today)
                ->groupBy('hostel_id');

            // This main query gets all hostels and joins only the specific records
            // identified in the subquery.
            $hostelBreakdown = Hostel::query()
                ->leftJoin('student_statistics', function ($join) use ($latestStatisticIds) {
                    $join->on('hostels.id', '=', 'student_statistics.hostel_id')
                         ->whereIn('student_statistics.id', $latestStatisticIds);
                })
                ->select(
                    'hostels.name',
                    DB::raw('COALESCE(student_statistics.students_present, 0) as students_present')
                )
                ->orderBy('hostels.name')
                ->get();

            // --- LOGGING THE RESULTS ---
            // This will write the final collection content to your log file.
            // The toArray() method converts the collection to a plain array for clean logging.
            Log::info('Hostel Breakdown Results:', $hostelBreakdown->toArray());


               
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



       
           $weeklyReportData = $this->prepareWeeklyHostelData($request);
          $hostelWeeklyData = $weeklyReportData['hostelWeeklyData'];
          $totals = $weeklyReportData['totals'];


          $zonalReport = $this->prepareWeeklyZonalData($request);

           $zoneWeeklyData = $zonalReport['zoneWeeklyData'];
           $zonalTotals  = $zonalReport['totals'];



           $weekDisplay=$weeklyReportData['weekDisplay'];
            $previousWeekDate=$weeklyReportData['previousWeekDate'];
           $nextWeekDate=$weeklyReportData['nextWeekDate'];
            $isCurrentWeek=$weeklyReportData['isCurrentWeek'];

        return view('pages/dashboard-v2', compact(
            'totalOccurrences',
            'unresolvedOccurrences',
            'todaysOccurrences',
            'hostelAttendants',
            'totalHostels',
            'dailyStatsSubmitted',
            'totalToday', 'totalWeek', 'byHostel', 'byShift', 'byZone',         
             'hostelWeeklyData',
            'totals',
            'weekDisplay' ,
            'previousWeekDate',
            'nextWeekDate',
            'zoneWeeklyData',
            'zonalTotals',
            'isCurrentWeek' ,
            'zonalReportsToday', 'adminReportsToday','zoneBreakdown',
            'adminDailyStatsSubmitted','hostelBreakdown','totalByType','dailyReports'
        ));

    }

    public function loginV2() {
        return view('pages/login-v2');
    }

}
