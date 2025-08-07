<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EscalationMatrix;
use App\Models\Escalation;
use Illuminate\Support\Facades\Mail;
use App\Mail\EscalationMail;
use Illuminate\Support\Facades\Log;

class EscalationController extends Controller
{
       
        public function create($id)
        {
            $recipients = EscalationMatrix::all(); // Fetch all matrix entries
            return view('escalation.create', compact('recipients', 'id'));
        }

   public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
            'occurrence_id' => 'required|exists:occurrences,id',
            'recipient_email' => 'required'
        ]);

        $subject = $request->input('subject');
        $message = $request->input('message');
        $occurrenceId = $request->input('occurrence_id');
        $recipient_email = $request->input('recipient_email');

        $recipients = \App\Models\EscalationMatrix::pluck('email');

        
        Mail::to($recipient_email)->send(new EscalationMail($subject, $message, $occurrenceId));
        

        // Optional: store the escalation for record
        Escalation::create([
            'occurrence_id' => $occurrenceId,
            'user_id'   => auth()->user()->id,
            'subject' => $subject,
            'message' => $message,
            'recipient_email' => $recipient_email,
        ]);

        return redirect()->back()->with('success', 'Escalation message sent successfully.');
    }

        public function all()
        {
            $escalations = Escalation::with('user')->latest()->get();

            Log::info("Called");
            return view('escalation.index', compact('escalations'));
        }




}
