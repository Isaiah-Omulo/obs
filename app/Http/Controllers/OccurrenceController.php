<?php

namespace App\Http\Controllers;
use App\Models\Hostel;
use Illuminate\Http\Request;
use App\Models\Occurrence;
use App\Models\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Spatie\Activitylog\Models\Activity;


class OccurrenceController extends Controller
{

       public function index(Request $request)
    {
        $query = Occurrence::with(['user', 'files'])->latest();

        // Check for unresolved filter
        if ($request->filter === 'unresolved') {
            $query->where('resolved', 'no');
        }

        if ($request->has('user_id')) {
        $query->where('user_id', $request->user_id);
        }

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        $occurrences = $query->get();

        return view('occurrences.index', compact('occurrences'));
    }



    public function create()
    {
        

    $hostels = Hostel::all();

    $userId = Auth::id();
    $lastHostel = Occurrence::lastUsedHostelByUser($userId);

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

    return view('occurrences.create', compact('hostels', 'occurrenceTypes','lastHostel'));

    }

    

    public function store(Request $request)
    {
        Log::info('Shift is'.$request->shift);
        try {

            $request->validate([
                'shift' => 'required',
                'location' => 'required',
                'date' => 'required|date',
                'time_of_reporting' => 'required',
                'nature' => 'required',
                'action_taken' => 'required',
                'occurrence_type' => 'required',
                'resolution' => 'required',
                'time_of_occurrence' => 'required',
                'resolved' => 'required',
                'attachments.*' => 'nullable|file|max:5120' // Max 5MB per file
            ]);

           $occurrence_type = $request->occurrence_type === 'Other' ? $request->custom_nature : $request->occurrence_type;

            $occurrenceType = $request->occurrence_type;

           $tableName = 'occurrences';

             $statement = DB::select("SHOW TABLE STATUS LIKE '{$tableName}'");
             $nextId = $statement[0]->Auto_increment; 


            $typePrefix = substr($occurrenceType, 0, 3); 

            
            $trackingNumber = "obs_{$nextId}_{$typePrefix}";


            DB::beginTransaction();

            $occurrence = Occurrence::create([
                'tracking_number' => $trackingNumber,
                'user_id' => auth()->id(),
                'shift' => $request->shift,
                'hostel' => $request->location,
                'date' => $request->date,
                'time_of_reporting' => date('H:i:s', strtotime($request->time)),
                'nature' => $request->nature,
                'occurrence_type' => $occurrence_type,
                'resolved' => $request->resolved,
                'action_taken' => $request->action_taken,
                'resolution' => $request->resolution,
                'time_of_occurrence' => $request->time_of_occurrence
            ]);

            Log::info('Occurrence created:', ['id' => $occurrence->id]);
            if ($request->hasFile('attachment')) {
            $uploadPath = public_path('uploads/occurrence_files');

            foreach ($request->file('attachment') as $file) {
                // Unique filename
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                // Move to public folder
                $file->move($uploadPath, $fileName);

                // Store filename in DB
                $occurrence->files()->create([
                    'occurrence_id' => $occurrence->id,
                    'original_name' => $fileName,
                    'uploaded_by' => auth()->user()->name

                ]);
            }
        }


        if ($request->resolved === 'yes' && $request->resolution) {
            $occurrence->resolutions()->create([
                'occurrence_id'    => $occurrence->id,
                'resolved_by' => auth()->id(),
                'description' => $request->resolution,
                'resolution_date' => $request->date,
                'resolution_time' => $request->time_of_reporting,
            ]);
        }



            DB::commit();
                activity()
                ->performedOn($occurrence)
                ->event('created')
                ->causedBy(auth()->user())
                ->withProperties([
                    'hostel' => $occurrence->hostel,
                    'nature' => $occurrence->nature,
                    'occurrence_type' => $occurrence->occurrence_type,
                ])
                ->log(auth()->user()->name.' created a new occurrence');

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
        'role' => 'required|in:manager,director,zonal_officer,administrator,coordinator,house_keeper,hostel_attendant'
    ]);
    $separator = "\n\n"; 
    if ($request->role === 'manager') {

        if (!empty($occurrence->manager_input)) {
            $occurrence->manager_input .= $separator;
        }
        $occurrence->manager_input .= $request->input_text;

        $occurrence->manager_input = $request->input_text;
    } else if ($request->role === 'director')  {

        if (!empty($occurrence->director_input)) {
            $occurrence->director_input .= $separator;
        }
        $occurrence->director_input .= $request->input_text;
        
    }
    else if ($request->role === 'zonal_officer')  {
        $newInput = $request->input_text . " (By: ". auth()->user()->name . ")";
        if (!empty($occurrence->zonal_officer_input)) {
            $occurrence->zonal_officer_input .= $separator;
        }
        $occurrence->zonal_officer_input .= $newInput;
    }
    else if ($request->role === 'administrator' || $request->role === 'coordinator' )  {
        $occurrence->administrator_input .= $request->input_text . ":By ". auth()->user()->name;
    }

    else if ($request->role === 'house_keeper' || $request->role === 'hostel_attendant' )  {
        $occurrence->hostel_input .= $request->input_text . ":By ". auth()->user()->name;
    }

    $occurrence->save();

    return response()->json(['success' => true]);
}



  public function show(Occurrence $occurrence)
{
    // Eager load related models: files, user, resolutions, and escalations with their users
    $occurrence->load([
        'files', 
        'user', 
        'resolutions.resolver',  // if resolutions have a resolver relationship
        'escalations.user'       // load the user who sent each escalation
    ]);

    // Pass the occurrence with all related data to the view
    return view('occurrences.view', [
        'occurrence' => $occurrence
    ]);
}

    public function markAsResolved(Occurrence $occurrence)
    {
        // Authorization Check: Ensure the user is not a housekeeper or attendant.
       

         try {
            $occurrence->resolved = 'yes';
            $occurrence->save();

            return response()->json([ // This should already be correct
                'success' => true,
                'message' => 'Occurrence has been marked as resolved.'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to resolve occurrence ' . $occurrence->id . ': ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected server error occurred.'
            ], 500);
        }
    
    }


public function uploadFiles(Request $request, Occurrence $occurrence)
{
    // Validate request
    $request->validate([
        'attachment.*' => 'required|file|max:10240', // max 10 MB per file
    ]);

    $uploadedFiles = [];

    if ($request->hasFile('attachment')) {
        $uploadPath = public_path('uploads/occurrence_files');

        foreach ($request->file('attachment') as $file) {
            $fileSize = $file->getSize(); // in bytes
            $maxSize = 10 * 1024 * 1024; // 10 MB

            if ($fileSize > $maxSize) {
                return response()->json([
                    'success' => false,
                    'message' => 'File ' . $file->getClientOriginalName() . ' exceeds the maximum allowed size of 10 MB.'
                ]);
            }

            // Unique filename
            $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

            // Move to public folder
            $file->move($uploadPath, $fileName);

            // Store filename in DB
            $occurrenceFile = $occurrence->files()->create([
                'occurrence_id' => $occurrence->id,
                'original_name' => $fileName,
                'uploaded_by' => auth()->user()->name

            ]);

            $uploadedFiles[] = [
                'file_name' => $occurrenceFile->original_name,
                'file_url' => asset('uploads/occurrence_files/' . $occurrenceFile->original_name)
            ];
        }
    }

    return response()->json([
        'success' => true,
        'files' => $uploadedFiles
    ]);
}




}
