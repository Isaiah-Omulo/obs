<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{

      public function index(Request $request)
    {
        $usersQuery = User::query();

        if ($request->filter === 'hostel') {
            $usersQuery->whereIn('role', ['hostel_attendant', 'house_keeper']);
        }

        $users = $usersQuery->orderBy('created_at', 'desc')->get();

        return view('user.index', compact('users'));
    }


    public function edit()
    {
        $user = Auth::user();
        return view('user.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone', 'role']));

        return back()->with('success', 'Profile updated successfully!');
    }


    

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        // Sanitize inputs (e.g., trim email)
        $request->merge([
            'email' => trim($request->email),
        ]);

        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone|max:20',
            'role' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        try {
            User::create($data);
            return redirect()->route('user.create')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully!');
    }




}
