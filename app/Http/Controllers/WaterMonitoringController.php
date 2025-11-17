<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\WaterMonitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class WaterMonitoringController extends Controller
{
    /**
     * Display a listing of the water monitoring records.
     */
      public function index(Request $request)
    {
        // 1. Get and validate the date, defaulting to today.
        $date = $request->input('date', now()->toDateString());
        try {
            $carbonDate = Carbon::parse($date);
        } catch (\Exception $e) {
            $carbonDate = now();
            $date = $carbonDate->toDateString();
        }

        // 2. Fetch zones and their hostels. For each hostel, load only the
        //    water monitoring records that match the specific date.
        $zones = Zone::with([
            'hostels',
            'hostels.waterMonitorings' => function ($query) use ($date) {
                $query->where('date', $date)->with('user'); // Eager load the user too!
            }
        ])->get();

        // 3. Structure the data for the view.
        //    The records are already loaded, we just need to key them by time.
        foreach ($zones as $zone) {
            foreach ($zone->hostels as $hostel) {
                // The relationship gives us a collection. We key it by 'time' for easy lookup in Blade.
                $hostel->records = $hostel->waterMonitorings->keyBy('time');
            }
        }

        // 4. Prepare data for view navigation.
        $previousDate = $carbonDate->copy()->subDay()->toDateString();
        $nextDate = $carbonDate->copy()->addDay()->toDateString();
        $isCurrentDate = $carbonDate->isToday();

       //  dd($zones->toArray());

        return view('water_monitoring.index', compact(
            'zones',
            'date',
            'previousDate',
            'nextDate',
            'isCurrentDate'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hostels = \App\Models\Hostel::with('zone')->orderBy('name')->get();
        return view('water_monitoring.create', compact('hostels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // ... inside WaterMonitoringController class

    public function store(Request $request)
    {
        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'time' => 'required|string',
            'is_water' => 'required|in:Yes,No',
            
            // This is the key change:
            // 'amount' is only required IF 'is_water' is 'Yes'.
            // It must also be one of the two allowed values.
            // 'nullable' allows it to be empty if 'is_water' is 'No'.
            'amount' => 'required_if:is_water,Yes|in:Plenty,Little|nullable',
            'is_hot_water' => 'nullable|in:Yes,No,N/A',
            'remarks' => 'nullable|string',
        ]);

        $hostel = \App\Models\Hostel::findOrFail($request->hostel_id);

        // If there is no water, ensure the amount is null.
        $amount = ($request->is_water === 'Yes') ? $request->amount : null;

        \App\Models\WaterMonitoring::create([
            'hostel_id' => $hostel->id,
            'zone_id' => $hostel->zone_id,
            'date' => now()->toDateString(),
            'time' => $request->time,
            'is_water' => $request->is_water,
            'amount' => $amount, 
            'is_hot_water' => $request->is_hot_water,
            'remarks' => $request->remarks,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('water_monitoring.index')->with('success', 'Record added successfully!');
    }


}