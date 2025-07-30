<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{


    public function logout(Request $request)
    {
        

        if (Auth::check()) {
            $user = Auth::user();
            $user->logged_out_at = now();
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login/v2'); // Redirect to login page after logout
    }

    public function showLoginForm(){
        return view('pages.login-v2');
     }

    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to log in
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Prevent session fixation
            return redirect()->intended('/dashboard/v2'); // Or any dashboard page
        }

        // If login fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

   public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Find user by email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Create new user if not found
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(str()->random(16)), // dummy password
                'email_verified_at' => now(),
                'google_id' => $googleUser->getId(),
                'logged_in_at' => now(),
            ]);
        }
         else {
            // Update logged_in_at for existing user
            $user->update(['logged_in_at' => now()]);
        }

        // Log the user in
       Auth::login($user);

        return redirect()->intended('/dashboard/v2');
    } catch (\Exception $e) {
        Log::error($e->getMessage());
        return redirect('/login')->withErrors([
            'google_login' => 'Google login failed: ' . $e->getMessage(),
        ]);
    }
}



}
