<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class OtherController extends Controller
{
    public function feedbackForm()
    {
        return view('other.feedback');
    }

    public function submitFeedback(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Feedback sent successfully!');
    }

    public function helpPage()
    {
        return view('other.help');
    }

    public function indexFeedback()
    {
        $feedbacks = Feedback::with('user')->latest()->get();
        return view('other.index', compact('feedbacks'));
    }
}
