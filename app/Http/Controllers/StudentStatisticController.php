<?php

namespace App\Http\Controllers;

use App\Models\StudentStatistic;
use App\Models\Hostel;
use Illuminate\Http\Request;

class StudentStatisticController extends Controller
{
        public function index()
    {
        $studentStatistics = StudentStatistic::with(['user', 'hostel'])->latest()->get();

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
}
