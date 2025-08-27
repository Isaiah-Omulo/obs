<?php

namespace App\Http\Controllers;

use App\Models\Occurrence;
use App\Models\OccurrenceInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OccurrenceInputController extends Controller
{
    /**
     * Store a stakeholder input or reply for an occurrence
     */
   public function store(Request $request, Occurrence $occurrence)
{
    $request->validate([
        'input_text' => 'required|string|max:2000',
        'role'       => 'required|string|max:100',
        'parent_id'  => 'nullable|exists:occurrence_inputs,id',
    ]);

    $input = OccurrenceInput::create([
        'occurrence_id' => $occurrence->id,
        'user_id'       => Auth::id(),
        'role'          => $request->role,
        'input_text'    => $request->input_text,
        'parent_id'     => $request->parent_id, // null if top-level comment
    ]);

    Log::info("Occurrence Input added", [
        'occurrence_id' => $occurrence->id,
        'input_id'      => $input->id,
        'user_id'       => Auth::id(),
        'parent_id'     => $request->parent_id
    ]);

    // Return JSON if the request expects JSON (AJAX/fetch)
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Your input has been added successfully!',
            'input'   => $input
        ]);
    }

    // Otherwise, fallback to normal redirect
    return back()->with('success', 'Your input has been added successfully.');
}

}
