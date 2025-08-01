<?php

namespace App\Http\Controllers;
use App\Models\Hostel;
use Illuminate\Http\Request;
use App\Models\Occurrence;
use App\Models\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class OccurrenceController extends Controller
{

       public function index(Request $request)
    {
        $query = Occurrence::with(['user', 'files'])->latest();

        // Check for unresolved filter
        if ($request->filter === 'unresolved') {
            $query->where('resolved', 'no');
        }

        $occurrences = $query->get();

        return view('occurrences.index', compact('occurrences'));
    }



    public function create()
    {
        

    $hostels = Hostel::all();

    // Get distinct occurrence types from DB
    $existingTypes = Occurrence::select('occurrence_type')
    ->distinct()
    ->whereNotNull('occurrence_type')
    ->where('occurrence_type', '!=', '')
    ->pluck('occurrence_type')
    ->toArray();


    // Filter to include only fire, theft, and other types
    $defaultTypes = ['Fire', 'Theft'];
    $additionalTypes = array_diff($existingTypes, $defaultTypes);
    $occurrenceTypes = array_unique(array_merge($defaultTypes, $additionalTypes));

    return view('occurrences.create', compact('hostels', 'occurrenceTypes'));

    }

    

    public function store(Request $request)
    {
        Log::info('Shift is'.$request->shift);
        try {

            $request->validate([
                'shift' => 'required',
                'location' => 'required',
                'date' => 'required|date',
                'time' => 'required',
                'nature' => 'required',
                'action_taken' => 'required',
                'occurrence_type' => 'required',
                'resolution' => 'required',
                'resolved' => 'required',
                'attachments.*' => 'nullable|file|max:5120' // Max 5MB per file
            ]);

           $occurrence_type = $request->occurrence_type === 'Other' ? $request->custom_nature : $request->occurrence_type;


            DB::beginTransaction();

            $occurrence = Occurrence::create([
                'user_id' => auth()->id(),
                'shift' => $request->shift,
                'hostel' => $request->location,
                'date' => $request->date,
                'time' => $request->time,
                'nature' => $request->nature,
                'occurrence_type' => $occurrence_type,
                'resolved' => $request->resolved,
                'action_taken' => $request->action_taken,
                'resolution' => $request->resolution
            ]);

            Log::info('Occurrence created:', ['id' => $occurrence->id]);
            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName(); // Unique filename
                    $file->storeAs('occurrence_files', $fileName, 'public');

                    $occurrence->files()->create([
                        'occurrence_id' =>  $occurrence->id,
                        'original_name' => $fileName // Store only the filename
                    ]);

                    Log::info('Attachment stored for occurrence', [
                        'occurrence_id' => $occurrence->id,
                        'file_name' => $fileName
                    ]);
                }
            }


            DB::commit();

            return redirect()->route('occurrence.index')->with('success', 'Occurrence logged successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store occurrence:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);

            return back()->with('error', 'Failed to log occurrence. Please try again.');
        }
    }

        public function destroy($id)
    {
        try {
            $occurrence = Occurrence::with('files')->findOrFail($id);

            // Delete associated files
            foreach ($occurrence->files as $file) {
                if (Storage::exists($file->original_name)) {
                    Storage::delete($file->original_name);
                }
                $file->delete(); // remove file record from DB
            }

            $occurrence->delete();

            return response()->json(['message' => 'Occurrence deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete occurrence.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $occurrence = Occurrence::with('files')->findOrFail($id);
        $hostels = Hostel::all(); // Make sure this model is imported
        //$occurrenceTypes = ['theft', 'violence', 'fire', 'injury', 'accident']; // update with your actual types
        $existingTypes = Occurrence::select('occurrence_type')
        ->distinct()
        ->whereNotNull('occurrence_type')
        ->where('occurrence_type', '!=', '')
        ->pluck('occurrence_type')
        ->toArray();


    // Filter to include only fire, theft, and other types
    $defaultTypes = ['Fire', 'Theft'];
    $additionalTypes = array_diff($existingTypes, $defaultTypes);
    $occurrenceTypes = array_unique(array_merge($defaultTypes, $additionalTypes));;


        return view('occurrences.edit', compact('occurrence', 'hostels', 'occurrenceTypes'));
    }

    public function update(Request $request, $id)
    {
        Log::info('Updating occurrence ID: ' . $id);
        
        try {
             $request->validate([
                'shift' => 'required',
                'location' => 'required',
                'date' => 'required|date',
                'time' => 'required',
                'nature' => 'required',
                'action_taken' => 'required',
                'occurrence_type' => 'required',
                'resolution' => 'required',
                'resolved' => 'required',
                'attachments.*' => 'nullable|file|max:5120' // Max 5MB per file
            ]);

           $occurrence_type = $request->occurrence_type === 'Other' ? $request->custom_nature : $request->occurrence_type;

            DB::beginTransaction();

            $occurrence = Occurrence::findOrFail($id);

            $occurrence->update([
               
                'shift' => $request->shift,
                'hostel' => $request->location,
                'date' => $request->date,
                'time' => $request->time,
                'nature' => $request->nature,
                'occurrence_type' => $occurrence_type,
                'resolved' => $request->resolved,
                'action_taken' => $request->action_taken,
                'resolution' => $request->resolution
            ]);

            // Handle new attachments
            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->storeAs('occurrence_files', $fileName, 'public');

                    $occurrence->files()->create([
                        'original_name' => $fileName
                    ]);

                    Log::info('New attachment added to occurrence', [
                        'occurrence_id' => $occurrence->id,
                        'file_name' => $fileName
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('occurrence.index')->with('success', 'Occurrence updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update occurrence:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'occurrence_id' => $id,
                'request' => $request->all()
            ]);

            return back()->with('error', 'Failed to update occurrence. Please try again.');
        }
    }


    public function input(Request $request, Occurrence $occurrence)
{
    $request->validate([
        'input_text' => 'required|string',
        'role' => 'required|in:manager,director',
    ]);

    if ($request->role === 'manager') {
        $occurrence->manager_input = $request->input_text;
    } else {
        $occurrence->director_input = $request->input_text;
    }

    $occurrence->save();

    return response()->json(['success' => true]);
}





}
