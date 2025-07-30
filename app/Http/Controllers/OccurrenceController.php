<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Occurrence;

class OccurrenceController extends Controller
{
    public function create()
    {
        return view('occurrences.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'nature' => 'required|string|max:255',
            'action_taken' => 'required|string',
            'resolution' => 'nullable|string',
            'manager' => 'required|string',
            'director' => 'nullable|string',
        ]);

        Occurrence::create([
            'date' => $request->date,
            'nature' => $request->nature,
            'action_taken' => $request->action_taken,
            'resolution' => $request->resolution,
            'manager' => $request->manager,
            'director' => $request->director,
        ]);

        return redirect()->back()->with('success', 'Occurrence recorded successfully.');
    }
}
