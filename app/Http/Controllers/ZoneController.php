<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\Hostel;



class ZoneController extends Controller
{
    // Display all zones
    public function index()
    {
        $zones = Zone::all();
        return view('zones.index', compact('zones'));
    }

    // Show form to create a new zone
    public function create()
    {
        return view('zones.create');
    }

    // Store new zone
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:zones,name'
        ]);

        Zone::create([
            'name' => $request->name
        ]);

        return redirect()->route('zones.index')->with('success', 'Zone created successfully.');
    }

    // Show form to edit an existing zone
    public function edit($id)
    {
        $zone = Zone::findOrFail($id);
        return view('zones.edit', compact('zone'));
    }

    // Update zone
    public function update(Request $request, $id)
    {
        $zone = Zone::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:zones,name,' . $zone->id
        ]);

        $zone->update([
            'name' => $request->name
        ]);

        return redirect()->route('zones.index')->with('success', 'Zone updated successfully.');
    }

    // Delete zone
    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);
        $zone->delete();

        return redirect()->route('zones.index')->with('success', 'Zone deleted successfully.');
    }



            // List hostels for a specific zone
       // List all hostels with their zones
        public function hostelIndex()
        {
            $hostels = Hostel::with('zone')->get();
            return view('hostels.index', compact('hostels'));
        }


        // Show create hostel form
        public function hostelCreate()
        {
            $zones = Zone::all();
            return view('hostels.create', compact('zones'));
        }

       // Store new hostel
        public function hostelStore(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'zone_id' => 'required|exists:zones,id',
                'number_of_students' => 'required|integer|min:1'
            ]);

            Hostel::create([
                'zone_id' => $request->zone_id,
                'name' => $request->name,
                'number_of_students' => $request->number_of_students
            ]);

            return redirect()->route('hostels.index')->with('success', 'Hostel created successfully.');
        }


        // Edit form for hostel
        public function hostelEdit($id)
        {
            $hostel = Hostel::findOrFail($id);
            $zones = Zone::all(); // In case you want to allow zone change

            return view('hostels.edit', compact('hostel', 'zones'));
        }


        // Update hostel
        public function hostelUpdate(Request $request, $id)
        {
            $hostel = Hostel::findOrFail($id);

            $request->validate([
                'name' => 'required',
                'number_of_students' => 'required|integer'
            ]);

            $hostel->update([
                'name' => $request->name,
                'number_of_students' => $request->number_of_students
            ]);

            return redirect()->route('hostels.index', $hostel->zone_id)->with('success', 'Hostel updated.');
        }

        // Delete hostel
        public function hostelDestroy($id)
        {
            $hostel = Hostel::findOrFail($id);
            $zone_id = $hostel->zone_id;
            $hostel->delete();

            return redirect()->route('hostels.index', $zone_id)->with('success', 'Hostel deleted.');
        }
}
