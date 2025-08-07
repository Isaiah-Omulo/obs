<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\HandoverTakeover;

class HandoverTakeoverController extends Controller
{
    //


     public function index()
    {
        // Eager load relationships to prevent N+1 query problem
        $changeovers = HandoverTakeover::with(['hostel', 'actingUser', 'involvedUser'])
            ->latest() // Order by the most recent records
            ->get();
            
        return view('handover_takeovers.index', compact('changeovers'));
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'Changeover_id' => ['required', Rule::in(['take-over', 'hand-over'])],
            'user_id' => ['required', 'exists:users,id'],
            'hostel_id' => ['required', 'exists:hostels,id'],
            'shift' => ['required', Rule::in(['Day', 'Night'])],
            'comments' => ['required', 'string', 'min:10'],
        ]);

        // 2. Create the record using the validated data
        HandoverTakeover::create([
            'changeover_type' => $validatedData['Changeover_id'],
            'acting_user_id' => Auth::id(), // The logged-in user
            'involved_user_id' => $validatedData['user_id'], // The user selected in the form
            'hostel_id' => $validatedData['hostel_id'],
            'shift' => $validatedData['shift'],
            'comments' => $validatedData['comments'],
        ]);

        // 3. Redirect back with a success message
        return redirect()->back()->with('success', 'Changeover recorded successfully!');
    }



    public function create()
    {
        
        $users = User::where('id', '!=', Auth::id())->get();
         $hostels = Hostel::all();

        return view("handover_takeovers.create", compact('hostels','users'));
    }


}
