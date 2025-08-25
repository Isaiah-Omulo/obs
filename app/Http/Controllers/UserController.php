<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;

class UserController extends Controller
{

      public function index(Request $request)
    {
        $usersQuery = User::query();

         $disallowedRoles = ['hostel_attendant', 'house_keeper'];

            if (in_array(auth()->user()->role, $disallowedRoles)) {
                abort(403, 'Unauthorized action.');
            }

        if ($request->filter === 'hostel') {
            $usersQuery->whereIn('role', ['hostel_attendant', 'house_keeper']);
        }

        $users = $usersQuery->orderBy('created_at', 'desc')->get();

        return view('user.index', compact('users'));
    }


    public function edit($id)
    {
        $authUser = Auth::user();

        // If hostel_attendant or house_keeper, only allow editing their own profile
        if (in_array($authUser->role, ['hostel_attendant', 'house_keeper']) && $authUser->id != $id) {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }


   public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Use the current role if none is submitted
        $data = $request->only(['name', 'email', 'phone']);
        $data['role'] = $request->input('role', $user->role);

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }



    

    public function create()
    {
       
         $disallowedRoles = ['hostel_attendant', 'house_keeper'];

            if (in_array(auth()->user()->role, $disallowedRoles)) {
                abort(403, 'Unauthorized action.');
            }
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
            $user = User::create($data);

        // Send registration email
            Mail::to($user->email)->send(new UserRegisteredMail($user));
            // Log email sending
            Log::info("Registration email sent to user: {$user->email}, ID: {$user->id}");
            return redirect()->route('user.create')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            Log::error("Failed to create user or send email: " . $e->getMessage());
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
