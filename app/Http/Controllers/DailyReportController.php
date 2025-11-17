<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;

use App\Models\Zone;
use Illuminate\Http\Request;
use App\Models\Hostel; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;         // <-- Import User model


class DailyReportController extends Controller
{
    // Display a list of reports
      public function index(Request $request) // <-- Inject the Request object
    {
        
         $filterDateString = $request->input('filter_date', now()->format('Y-m-d'));
            
            // Use Carbon for powerful and safe date manipulation
        $carbonDate = Carbon::parse($filterDateString);

         $filterDate  = $carbonDate->format('Y-m-d');
         $previousDate  = $carbonDate->copy()->subDay()->format('Y-m-d');
         $nextDate      = $carbonDate->copy()->addDay()->format('Y-m-d');
         $isCurrentDate = $carbonDate->isToday();

        // --- Authorization Check (Keep this logic) ---
        $currentUserRole = Auth::user()->role;
        $isKeeper = Str::contains($currentUserRole, 'keeper') || Str::contains($currentUserRole, 'attendant');

        if ($isKeeper) {
            \Log::info("Hello, House Keeper");
        }
        
        // --- Dynamic Query Building ---
        
        // 1. Start a base query for reports, eager-loading the user.
        $query = DailyReport::with('user');

        // 2. Apply the role filter if it's present in the request.
        if ($request->filled('role')) {
            $roleFilter = $request->input('role');
            
            $query->whereHas('user', function ($q) use ($roleFilter) {
                $q->where('role', $roleFilter);
            });
        }
        
        // 3. Execute the query, getting the latest reports first.
        $dailyReports = $query->latest()->get();

        // 4. Get a list of unique user roles to populate the filter dropdown.
        $roles = User::select('role')->distinct()->orderBy('role')->get();


        $attendantReports = DailyReport::with('user')
        ->whereHas('user', function ($query) {
            $query->where('role', 'like', '%Night Attendant%')
                  ->orWhere('role', 'like', '%hostel%')
                  ->orWhere('role', 'like', '%keeper%')
                  ->orWhere('role', 'like', '%Keeper%')
                  ->orWhere('role', 'like', '%Attendant%')
                  ->orWhere('role', 'like', '%attendant%')
                  ->orWhere('role', 'like', '%House Keeper%');
        })
        ->whereDate('created_at', $carbonDate) // Filter by the selected date
        ->latest()
        ->get();

        $supervisorReports = DailyReport::with('user')
        ->whereHas('user', function ($query) {
            $query->where('role', 'like', '%Supervisor%')
                  ->orWhere('role', 'like', '%Zonal Officer%')
                  ->orWhere('role', 'like', '%Zonal%')
                  ->orWhere('role', 'like', '%zonal%')
                  ->orWhere('role', 'like', '%zonal_officer%');
        })
        ->whereDate('created_at', $carbonDate)
        ->latest()
        ->get();


        $dutyOfficerReports = DailyReport::with('user')
        ->whereHas('user', function ($query) {
            $query->where('role', 'like', '%Duty%');
        })
        ->whereDate('created_at', $carbonDate)
        ->latest()
        ->get();

        // Group 4: Coordinator / Administrator
        $coordinatorReports = DailyReport::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'like', '%Coordinator%')
                      ->orWhere('role', 'like', '%Administrator%');
            })
            ->whereDate('created_at', $carbonDate)
            ->latest()
            ->get();


        
        // 5. Return the view with the reports and roles data.
        return view('daily_report.index', compact('dailyReports', 
        'attendantReports',
        'supervisorReports',
        'dutyOfficerReports',
        'coordinatorReports',
        'filterDate',
        'previousDate',
        'nextDate',
        'isCurrentDate',
        'filterDate' ,
        'previousDate' ,
        'nextDate'   ,  
        'isCurrentDate' ,
         'roles'));
    }


    public function all()
    {
        $user = Auth::user();
        $query = DailyReport::latest();

        
        \Log::info("the user role is: ".$user->role);

        // If the user is a keeper or attendant → only reports with hostel
       if (str_contains($user->role, 'keeper') || str_contains($user->role, 'attendant')) {
            $query->whereNotNull('hostel_id')
                  ->where('hostel_id', '!=', 0);
        }


        $dailyReports = $query->get();

        return view('daily_report.all', compact('dailyReports'));
    }


    // Show the form for creating a new report
    public function create()
    {
        $user = Auth::user();
        $zones = Zone::all();
        $hostels = Hostel::all();
        $userId = Auth::id();

        $lastZone = DailyReport::lastUsedZoneByUser($userId);
        $lastHostel = DailyReport::lastUsedHostelByUser($userId); // ✅ new

        return view('daily_report.create', compact('user', 'zones', 'hostels', 'lastZone', 'lastHostel'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'report' => 'required|string',
            'datetime' => 'required|date',
            'zone' => 'nullable|string',
            'hostel_id' => 'nullable|exists:hostels,id'
        ]);

        // Split datetime into date and time
        $datetime = \Carbon\Carbon::parse($request->datetime);
        $reportDate = $datetime->toDateString(); // YYYY-MM-DD
        $reportTime = $datetime->toTimeString(); // HH:MM:SS

        DailyReport::create([
            'user_id' => Auth::id(),
            'zone' => $request->zone,
            'hostel_id' => $request->hostel_id,
            'shift' => $this->getCurrentShift(),
            'report' => $request->report,
            'report_date' => $reportDate,
            'report_time' => $reportTime,
        ]);

        $user = Auth::user();

         $role = Auth::user()->role;
        $isZonal = $role === 'zonal_officer';
        $isKeeper = Str::contains($role, 'keeper') || Str::contains($role, 'attendant');

        if (in_array($user->role, ['administrator', 'coordinator'])) {
            return redirect()->route('daily_reports.index')->with('success', 'Report submitted successfully.');
        }
        else if ($isKeeper) {
             return redirect()->route('daily_reports.index')->with('success', 'Report submitted successfully.');
        }

        return redirect()->route('daily_reports.index')->with('success', 'Report submitted successfully.');

       
        
    }

    // Show the form to edit a report
    public function edit(DailyReport $dailyreport)
    {
        return view('daily_report.edit', compact('dailyreport'));
    }

    // Update the report
      public function update(Request $request, DailyReport $dailyreport)
    {
        $request->validate([
            'report' => 'required|string',
            'datetime' => 'required|date',
            'zone' => 'nullable|string',
            'hostel_id' => 'nullable|exists:hostels,id'
        ]);

        // Parse datetime
        $datetime = \Carbon\Carbon::parse($request->datetime);
        
        // Assign fields manually
        $dailyreport->zone = $request->zone;
        $dailyreport->hostel_id = $request->hostel_id; 
        $dailyreport->report = $request->report;
        $dailyreport->report_date = $datetime->toDateString();
        $dailyreport->report_time = $datetime->toTimeString();

        // Save the changes
        $dailyreport->save();

        return redirect()->route('daily_reports.index')->with('success', 'Report updated.');
    }



    // Delete the report
    public function destroy($id)
    {
        DailyReport::destroy($id);
        return redirect()->route('daily_reports.index')->with('success', 'Report deleted.');
    }

    // Manager/Director can input remarks
    public function input(DailyReport $dailyreport)
    {
        return view('daily_reports.input', compact('dailyreport'));
    }

    public function storeInput(Request $request, DailyReport $daily_report)
        {
           
            Log::info("Resolved DailyReport ID: " . $daily_report->id);
            
             Log::info("The input is: ".$request->input_text);
            try {

                if (!$daily_report) {
                abort(404, 'Daily Report not found');
                }

                $role = auth()->user()->role;

            if ($role === 'manager') {
                $request->validate([
                    'input_text' => 'required|string|max:5000',
                ]);
                $daily_report->manager_input = $request->input_text;
            } elseif ($role === 'director') {
                $request->validate([
                    'input_text' => 'required|string|max:5000',
                ]);
                $daily_report->director_input = $request->input_text;
            }

            $daily_report->save();

         

        // ✅ Return JSON for fetch()
        return response()->json(['success' => true]);
            } catch (Exception $e) {

                Log::error("Error: ".$e->getMessage());
                
            }
        }


    // Determine shift based on Nairobi time
    private function getCurrentShift()
    {
        $hour = Carbon::now('Africa/Nairobi')->hour;
        return ($hour >= 6 && $hour < 18) ? 'day' : 'night';
    }

    public function admin(Request $request)
    {
        $query = DailyReport::whereHas('user', function ($query) {
            $query->whereIn('role', ['administrator', 'coordinator']);
        });

        if ($request->query('filter') === 'today') {
            $query->whereDate('created_at', Carbon::today());
        }

        $dailyReports = $query->latest()->get();

         $role = Auth::user()->role;
        $isZonal = $role === 'zonal_officer';
        $isKeeper = Str::contains($role, 'keeper') || Str::contains($role, 'attendant');

       
        if ($isKeeper) {
             return redirect()->route('daily_reports.all');
        }

        return view('daily_report.admin', compact('dailyReports'));
    }
}
