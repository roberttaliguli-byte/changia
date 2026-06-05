<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle user login - SECURE VERSION (NO HARDCODE)
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|min:5',
        ]);

        // Rate limiting - prevent brute force
        $key = 'login_attempts_' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'login' => "Majalibio mengi sana. Tafadhali jaribu tena baada ya sekunde {$seconds}.",
            ]);
        }

        // Determine if login is email or phone
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Find user by email or phone
        $user = User::where($loginType, $request->login)->first();

        // Check if user exists
        if (!$user) {
            RateLimiter::hit($key, 60);
            return back()->withErrors([
                'login' => 'Barua pepe / Namba ya simu au password si sahihi.',
            ])->onlyInput('login');
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key, 60);
            
            // Log failed attempt (you can implement logging)
            // activity()->log("Failed login attempt for user: {$request->login}");
            
            return back()->withErrors([
                'login' => 'Barua pepe / Namba ya simu au password si sahihi.',
            ])->onlyInput('login');
        }

        // Check if account is locked (optional feature)
        if ($user->is_locked ?? false) {
            return back()->withErrors([
                'login' => 'Akaunti yako imefungwa. Wasiliana na msimamizi.',
            ]);
        }

        // Clear rate limiter on success
        RateLimiter::clear($key);

        // Login the user
        Auth::login($user, $request->remember);
        
        // Regenerate session to prevent session fixation
        $request->session()->regenerate();

        // Log successful login (optional)
        // activity()->log("User {$user->name} logged in successfully");

        // Redirect based on user role
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect user based on role
     */
// In app/Http/Controllers/AuthController.php

protected function redirectBasedOnRole($user)
{
    $message = 'Karibu tena, ' . $user->name . '!';
    
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard')->with('success', $message),
        'accountant' => redirect()->route('mhasibu.confirm')->with('success', $message), // Changed this line
        default => redirect()->route('dashboard')->with('success', $message),
    };
}

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|min:5|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'event_user', // Default role for new users
            ]);

            Auth::login($user);

            return redirect()->route('events.create')
                ->with('success', 'Karibu ' . $user->name . '! Sasa unaweza kuanza kuunda matukio yako.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Kuna tatizo limejitokeza. Tafadhali jaribu tena.'])
                ->withInput();
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Umefanikiwa kutoka kwenye akaunti yako.');
    }
}