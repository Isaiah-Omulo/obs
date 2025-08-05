<?php

namespace App\Http\Controllers;

use App\Models\EscalationMatrix;
use Illuminate\Http\Request;

class EscalationMatrixController extends Controller
{
    public function index()
    {
        $matrices = EscalationMatrix::all();
        return view('escalation_matrix.index', compact('matrices'));
    }

    public function create()
    {
        return view('escalation_matrix.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required|string|max:255',
            'email' => 'required|email|unique:escalation_matrix,email',
        ]);

        EscalationMatrix::create($request->only('department_name', 'email'));

        return redirect()->route('escalation.index')->with('success', 'Escalation Matrix entry created successfully.');
    }
}
