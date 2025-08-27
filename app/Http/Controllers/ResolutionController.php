<?php

namespace App\Http\Controllers;

use App\Models\Occurrence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Resolution;
class ResolutionController extends Controller
{
   public function create($occurrenceId)
    {
        // Fetch occurrence
        $occurrence = Occurrence::findOrFail($occurrenceId);

        // Pass it to the blade
        return view('resolutions.create', compact('occurrence'));
    }


   public function store(Request $request, $occurrenceId)
{
    try {
        // Validate input
        $validated = $request->validate([
            'description'      => 'required|string',
            'resolution_date'  => 'required|date',
            'resolution_time'  => 'required',
            'comments'         => 'nullable|string',
            'files.*'          => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120'
        ]);

        // Get occurrence
        $occurrence = Occurrence::findOrFail($occurrenceId);

        // Create resolution record
        $resolution = $occurrence->resolutions()->create([
            'occurrence_id'    => $occurrenceId,
            'description'      => $validated['description'],
            'resolved_by'      => auth()->id(),
            'resolution_date'  => $validated['resolution_date'],
            'resolution_time'  => $validated['resolution_time'],
            'comments'         => $validated['comments'] ?? null,
        ]);

        // Handle attachments (if any)
        if ($request->hasFile('files')) {
            $uploadPath = public_path('uploads/occurrence_files');

            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                $file->move($uploadPath, $fileName);

                $resolution->files()->create([
                    'resolution_id' => $resolution->id,
                    'original_name' => $fileName,
                    'uploaded_by'   => auth()->id(),
                    'path'          => 'uploads/occurrence_files/' . $fileName,
                ]);

                Log::info("File uploaded for resolution ID {$resolution->id}", [
                    'file'    => $fileName,
                    'user_id' => auth()->id()
                ]);
            }
        }

        // Mark occurrence as resolved
        $occurrence->update([
            'resolved' => 'yes'
        ]);

        Log::info("Occurrence {$occurrenceId} resolved by user " . auth()->id());

        return redirect()
            ->route('occurrence.show', $occurrenceId)
            ->with('success', 'Resolution added successfully and occurrence marked as resolved.');

    } catch (\Exception $e) {
        Log::error("Failed to store resolution for occurrence {$occurrenceId}", [
            'error'   => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
            'user_id' => auth()->id(),
        ]);

        return back()->with('error', 'An error occurred while saving the resolution. Please try again.');
    }
}


    public function reUploadFiles(Request $request, Occurrence $occurrence)
    {
        Log::info('Re-upload request received', [
            'occurrence_id' => $occurrence->id,
            'all_request_data' => $request->all(),
            'uploaded_files' => $request->file('attachment') 
                ? collect($request->file('attachment'))->map->getClientOriginalName() 
                : [],
            'user_id' => auth()->id(),
        ]);

        try {
            $request->validate([
                'attachment.*' => 'required|file|max:5120', // each file max 5MB
            ]);

            // Get the first resolution of this occurrence
            $resolution = $occurrence->resolutions()->first();

            if (!$resolution) {
                return redirect()->back()->with('error', 'No resolution exists for this occurrence.');
            }

            if ($request->hasFile('attachment')) {
                $uploadPath = public_path('uploads/occurrence_files');

                foreach ($request->file('attachment') as $file) {
                    $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->move($uploadPath, $fileName);

                    $resolution->files()->create([
                        'resolution_id' => $resolution->id,
                        'original_name' => $fileName,
                        'uploaded_by'   => auth()->id(),
                        'path'          => 'uploads/occurrence_files/' . $fileName,
                    ]);

                    Log::info("File uploaded for resolution ID {$resolution->id}", [
                        'file'    => $fileName,
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Files uploaded successfully');

        } catch (\Exception $e) {
            Log::error("Upload failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }



}
