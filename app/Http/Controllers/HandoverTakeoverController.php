<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\HandoverTakeover;
use App\Models\Item;
use Illuminate\Support\Facades\Log;

class HandoverTakeoverController extends Controller
{
    //


     public function index()
    {
        // Eager load relationships to prevent N+1 query problem
            $changeovers = HandoverTakeover::with(['actingUser', 'involvedUser', 'hostel', 'items', 'children.actingUser', 'children.involvedUser'])
            ->whereNull('parent_id') // only top-level handovers
            ->orderBy('created_at', 'desc')
            ->get();

            
        return view('handover_takeovers.index', compact('changeovers'));
    }


    public function store(Request $request) 
    {
        \Log::info('Store method called', $request->all());

        $validatedData = $request->validate([
            'changeover_type' => ['required', Rule::in(['take-over', 'hand-over'])],
            'user_id'         => ['required', 'exists:users,id'],
            'hostel_id'       => ['required', 'exists:hostels,id'],
            'shift'           => ['required', Rule::in(['Day', 'Night'])],
            'comments'        => ['required', 'string', 'min:10'],
            'items'           => ['nullable', 'array'], // hand-over selects
            'confirmed_items' => ['nullable', 'array'], // take-over checkboxes
            'parent_id'       => ['nullable', 'exists:handover_takeovers,id'],
        ]);

        \Log::info('Validated Data', $validatedData);

        $handover = HandoverTakeover::create([
            'changeover_type'   => $validatedData['changeover_type'],
            'acting_user_id'    => Auth::id(),
            'involved_user_id'  => $validatedData['user_id'],
            'hostel_id'         => $validatedData['hostel_id'],
            'shift'             => $validatedData['shift'],
            'comments'          => $validatedData['comments'],
            'status'            => 'pending',
            'parent_id'         => $validatedData['changeover_type'] === 'take-over'
                                    ? $validatedData['parent_id']
                                    : null,
        ]);

        \Log::info('Handover created', ['handover_id' => $handover->id]);

        // Determine which field to use
        $itemsInput = $validatedData['changeover_type'] === 'take-over'
                        ? $validatedData['confirmed_items'] ?? []
                        : $validatedData['items'] ?? [];

        \Log::info('Items input to process', $itemsInput);

        // Attach items (numeric or new names)
        $itemIds = collect($itemsInput)->map(function ($item) {
            if (is_numeric($item)) {
                \Log::info('Existing item ID', ['item' => $item]);
                return (int) $item;
            } else {
                $newItem = Item::firstOrCreate(['name' => $item]);
                \Log::info('Created new item', ['item_name' => $item, 'item_id' => $newItem->id]);
                return $newItem->id;
            }
        })->toArray();

        \Log::info('Final item IDs to attach', $itemIds);

        $handover->items()->sync($itemIds);




        \Log::info('Items attached to handover', ['handover_id' => $handover->id, 'items' => $itemIds]);

         if ($validatedData['changeover_type'] === 'take-over') {
                // Complete parent
                if ($handover->parent_id) {
                    HandoverTakeover::where('id', $handover->parent_id)
                        ->update(['status' => 'completed']);
                    \Log::info('Parent handover marked as completed', ['parent_id' => $handover->parent_id]);
                }

                // Complete child (this handover)
                $handover->update(['status' => 'completed']);
                \Log::info('Child handover marked as completed', ['child_id' => $handover->id]);
            }


        return redirect()->back()->with('success', 'Changeover recorded successfully!');
    }





    public function create()
    {
        
       $users = User::where('id', '!=', Auth::id())->get();
        $hostels = Hostel::all();
        $items = Item::all();

        return view("handover_takeovers.create", compact('hostels','users','items'));
    }


    public function storeItems(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:items,name',
        ]);

        $item = Item::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'item' => $item
        ]);
    }




      public function getPending(Request $request) 
        {
            try {
                Log::info('Handover pending request received', [
                    'hostel_id' => $request->hostel_id,
                    'shift' => $request->shift,
                    'current_user' => Auth::id()
                ]);

                $handover = HandoverTakeover::with(['actingUser', 'items'])
                    ->where('changeover_type', 'hand-over')
                    ->where('hostel_id', $request->hostel_id)
                    ->where('shift', $request->shift)
                    ->where('status', 'pending')
                    ->where('acting_user_id', '!=', Auth::id()) // Exclude self
                    ->get();

                // Format results with clean labels + items
                $handover = $handover->map(function ($item) {
                    $formattedDate = \Carbon\Carbon::parse($item->created_at)->format('d/m/Y');
                    $formattedTime = \Carbon\Carbon::parse($item->created_at)->format('g:i A');

                    return [
                        'id' => $item->id,
                        'label' => "Handover by " . ($item->actingUser->name ?? 'Unknown') .
                                   " on {$formattedDate} at {$formattedTime}",
                        'hostel_id' => $item->hostel_id,
                        'user_id' => $item->actingUser->id,
                        'shift' => $item->shift,
                        'comments' => $item->comments,
                        'status' => $item->status,
                        'items' => $item->items->map(function ($i) {
                            return [
                                'id' => $i->id,
                                'name' => $i->name,
                            ];
                        })->toArray(),
                    ];
                });

                Log::info('Handover pending results', [
                    'count' => $handover->count(),
                    'handover_ids' => $handover->pluck('id')->toArray()
                ]);

                return response()->json($handover);

            } catch (\Exception $e) {
                Log::error('Error fetching pending handovers', [
                    'message' => $e->getMessage(),
                    'stack' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'error' => 'Failed to retrieve pending handovers. Please try again later.'
                ], 500);
            }
        }




    public function getPendingItems($id)
    {
        try {
            $handover = HandoverTakeover::with('items')
                ->where('id', $id)
                ->where('status', 'pending')
                ->firstOrFail();

            return response()->json([
                'handover_id' => $handover->id,
                'user_name' => $handover->actingUser->name,
                'comments' => $handover->comments,
                'items' => $handover->items->map(function ($i) {
                    return [
                        'id' => $i->id,
                        'name' => $i->name,
                    ];
                })->toArray()
            ]);

            Log::info("user_name: ".$handover->actingUser->name);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve handover items.'
            ], 500);
        }
    }


    public function show($id)
    {
        $handover = HandoverTakeover::with(['hostel', 'actingUser', 'involvedUser', 'items'])
            ->findOrFail($id);

        return view('handover_takeovers.show', compact('handover'));
    }






}
