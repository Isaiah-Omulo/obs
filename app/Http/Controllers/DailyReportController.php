<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DailyReportController extends Controller
{
    // Display a list of reports
       public function index()
    {
        $dailyReports = DailyReport::whereHas('user', function ($query) {
            $query->whereIn('role', ['zonal_officer']);
        })->latest()->get();


        return view('daily_report.index', compact('dailyReports'));
    }

    // Show the form for creating a new report
    public function create()
    {
        $user = Auth::user();
        $zones = Zone::all();
        return view('daily_report.create', compact('user', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'report' => 'required|string',
            'datetime' => 'required|date',
            'zone' => 'nullable|string'
        ]);

        // Split datetime into date and time
        $datetime = \Carbon\Carbon::parse($request->datetime);
        $reportDate = $datetime->toDateString(); // YYYY-MM-DD
        $reportTime = $datetime->toTimeString(); // HH:MM:SS

        DailyReport::create([
            'user_id' => Auth::id(),
            'zone' => $request->zone,
            'shift' => $this->getCurrentShift(),
            'report' => $request->report,
            'report_date' => $reportDate,
            'report_time' => $reportTime,
        ]);

        $user = Auth::user();

        if (in_array($user->role, ['administrator', 'coordinator'])) {
            return redirect()->route('daily_reports.admin')->with('success', 'Report submitted successfully.');
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
            'zone' => 'nullable|string'
        ]);

        // Parse datetime
        $datetime = \Carbon\Carbon::parse($request->datetime);
        
        // Assign fields manually
        $dailyreport->zone = $request->zone;
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

        return view('daily_report.admin', compact('dailyReports'));
    }
}
