<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Register a new user
     * Default role is 'event_user' (event organizer)
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

            // Redirect to event creation page
            return redirect()->route('events.create')
                           ->with('success', 'Karibu ' . $user->name . '! Sasa unaweza kuanza kuunda matukio yako.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Kuna tatizo limejitokeza. Tafadhali jaribu tena.'])
                        ->withInput();
        }
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     * Users can login using either email OR phone number
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|min:5',
        ]);

        // Determine if login is email or phone
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Attempt to login
        if (Auth::attempt([$loginType => $request->login, 'password' => $request->password], $request->remember)) {
            $user = Auth::user();
            
            // Regenerate session to prevent session fixation
            $request->session()->regenerate();
            
            // Redirect based on user role
            if ($user->role === 'accountant') {
                return redirect()->route('dashboard')
                               ->with('success', 'Karibu ' . $user->name . '! (Mhasibu)');
            } else {
                return redirect()->route('dashboard')
                               ->with('success', 'Karibu tena, ' . $user->name . '!');
            }
        }

        // Login failed
        return back()->withErrors([
            'login' => 'Barua pepe / Namba ya simu au password si sahihi.',
        ])->onlyInput('login');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')
                        ->with('success', 'Umefanikiwa kutoka kwenye akaunti yako.');
    }
}