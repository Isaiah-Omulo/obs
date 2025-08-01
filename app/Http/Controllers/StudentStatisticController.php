<?php

namespace App\Http\Controllers;

use App\Models\StudentStatistic;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StudentStatisticController extends Controller
{
       public function index(Request $request)
        {
            $query = StudentStatistic::with(['user', 'hostel'])->latest();

            if ($request->filter === 'today') {
                $query->whereDate('record_date', Carbon::today());
            }

            $studentStatistics = $query->get();

            return view('student_statistics.index', compact('studentStatistics'));
        }


    public function create()
    {
        $hostels = Hostel::all();
        return view('student_statistics.create', compact('hostels'));
    }

   public function store(Request $request)
 {
        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'record_date' => 'required|date',
            'shift' => 'required|in:Day,Night',
            'students_present' => 'required|integer|min:0',
            'comments' => 'nullable|string',
        ]);

        StudentStatistic::create([
            'user_id' => auth()->id(),
            'hostel_id' => $request->hostel_id,
            'record_date' => $request->record_date,
            'shift' => $request->shift,
            'students_present' => $request->students_present,
            'comments' => $request->comments,
        ]);

        return redirect()->route('student_statistics.index')->with('success', 'Student statistics recorded successfully.');
    }



    public function destroy($id)
    {
        StudentStatistic::findOrFail($id)->delete();
        return back()->with('success', 'Record deleted.');
    }

    public function chartData(Request $request)
    {
         Log::info("Here is me: "."reached");
        try {


             $period = $request->get('period', 'monthly');
            $now = now();
            $labels = [];
            $counts = [];

            switch ($period) {
                case 'daily':
                    $dates = collect(range(0, 6))->map(fn($i) => $now->copy()->subDays($i)->format('Y-m-d'))->reverse();
                    foreach ($dates as $date) {
                        $labels[] = Carbon::parse($date)->format('D');
                        $counts[] = StudentStatistic::whereDate('record_date', $date)->sum('students_present');
                    }
                    break;

                case 'weekly':
                    $weeks = collect(range(0, 3))->map(fn($i) => $now->copy()->subWeeks($i))->reverse();
                    foreach ($weeks as $weekStart) {
                        $start = $weekStart->copy()->startOfWeek();
                        $end = $weekStart->copy()->endOfWeek();
                        $labels[] = $start->format('M d');
                        $counts[] = StudentStatistic::whereBetween('record_date', [$start, $end])->sum('students_present');
                    }
                    break;

                case 'yearly':
                    $months = range(1, 12);
                    foreach ($months as $month) {
                        $labels[] = Carbon::create(null, $month, 1)->format('M');
                        $counts[] = StudentStatistic::whereYear('record_date', $now->year)
                            ->whereMonth('record_date', $month)
                            ->sum('students_present');
                    }
                    break;

                default: // monthly
                    $daysInMonth = $now->daysInMonth;
                    for ($i = 1; $i <= $daysInMonth; $i++) {
                        $date = $now->copy()->day($i)->format('Y-m-d');
                        $labels[] = $i;
                        $counts[] = StudentStatistic::whereDate('record_date', $date)->sum('students_present');
                    }
                    break;
            }

            return response()->json([
                'labels' => $labels,
                'counts' => $counts,
            ]);
        } catch (Exception $e) {
            Log::error("The Error is: ".$e->getMessage());
            
        }
       
    }
}
