<?php

namespace App\Http\Controllers;

use App\Models\StudentStatistic;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; 
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
        $userId = Auth::id();
        $lastHostelId = StudentStatistic::lastUsedHostelIdByUser($userId);
        return view('student_statistics.create', compact('hostels', 'lastHostelId'));
    }

        public function store(Request $request)
        {
            // 1. GET CONTEXT: Before validating, fetch the hostel to find its capacity.
            $hostel = Hostel::find($request->input('hostel_id'));

            // If a hacker submits a fake hostel_id, we must catch it here.
            if (!$hostel) {
                return back()
                    ->withErrors(['hostel_id' => 'The selected hostel is invalid.'])
                    ->withInput();
            }

            // We assume the 'number_of_students' column holds the capacity.
            $capacity = $hostel->number_of_students;

            // 2. DEFINE DYNAMIC RULES: The 'max' rule now uses the capacity we found.
            $rules = [
                'hostel_id'        => 'required|exists:hostels,id',
                'record_date'      => 'required|date',
                'shift'            => 'required|in:Day,Night',
                'students_present' => "required|integer|min:0|max:{$capacity}", // This is the dynamic rule
                'comments'         => 'nullable|string|max:2000',
            ];

            // 3. DEFINE CUSTOM MESSAGES: This makes the error much more user-friendly.
            $messages = [
                'students_present.max' => "The number of students cannot exceed the hostel's capacity of {$capacity}.",
            ];

            // 4. VALIDATE: We use the Validator facade for full control.
            $validator = Validator::make($request->all(), $rules, $messages);

            // If validation fails, redirect back with the specific errors and old input.
            if ($validator->fails()) {
                return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
            }

            // 5. CREATE THE RECORD: If validation passes, get the clean data.
            $validatedData = $validator->validated();
            
            // Add the authenticated user's ID to the data.
            $validatedData['user_id'] = auth()->id();

            StudentStatistic::create($validatedData);

            // 6. REDIRECT WITH SUCCESS MESSAGE
            return redirect()->route('student_statistics.index')
                             ->with('success', 'Student statistics recorded successfully.');
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
