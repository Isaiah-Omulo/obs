<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EscalationMatrix;
use Illuminate\Support\Facades\Mail;
use App\Mail\EscalationMail;

class EscalationController extends Controller
{
    public function create()
    {
        $recipients = EscalationMatrix::all(); // Fetch all matrix entries
        return view('escalation.create', compact('recipients'));
    }

   public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $subject = $request->input('subject');
        $message = $request->input('message');

        $recipients = \App\Models\EscalationMatrix::pluck('email');

        foreach ($recipients as $email) {
            Mail::to($email)->send(new EscalationMail($subject, $message));
        }

        return redirect()->back()->with('success', 'Escalation message sent successfully.');
    }
}
