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


      public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // ignore the same user
            'phone' => 'nullable|string|max:20',
            'role'  => 'nullable|string',
        ]);

        // Collect the allowed fields
        $data = $request->only(['name', 'email', 'phone', 'role']);

        // If 'role' is not sent, keep the current one
        if (empty($data['role'])) {
            $data['role'] = $user->role;
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'User updated successfully!');
    }




    

    public function create()
    {
        $baseRoles = ['house_keeper', 'hostel_attendant', 'administrator', 'coordinator', 'zonal_officer', 'director', 'manager'];

        // Get unique roles from users table, excluding null/empty
        $existingRoles = User::whereNotNull('role')
            ->pluck('role')
            ->unique()
            ->toArray();

        // Merge base roles and existing roles
        $roles = array_unique(array_merge($baseRoles, $existingRoles));

        // Optionally, exclude roles that the currently authenticated user cannot assign
        $disallowedRoles = ['hostel_attendant', 'house_keeper'];
        if (in_array(auth()->user()->role, $disallowedRoles)) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.create', compact('roles'));
    }


    public function store(Request $request)
    {
        $request->merge(['email' => trim($request->email)]);

        // Determine final role
        $finalRole = $request->role === 'other' ? trim($request->other_role) : $request->role;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone|max:20',
            'role' => 'required|string',
            'other_role' => $request->role === 'other' ? 'required|string|max:50' : 'nullable',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $finalRole,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        try {
            $user = User::create($data);
            Mail::to($user->email)->send(new UserRegisteredMail($user));
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
