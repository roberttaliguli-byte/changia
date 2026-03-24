<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /** Default settings — single source of truth */
    private array $defaults = [
        'theme'              => 'light',
        'compact_view'       => false,
        'language'           => 'sw',
        'currency'           => 'TZS',
        'timezone'           => 'Africa/Dar_es_Salaam',
        'date_format'        => 'd/m/Y',
        'time_format'        => '24',
        'notification_email' => null,        // filled from user on first load
        'email_alerts'       => true,
        'sms_alerts'         => false,
        'push_notifications' => true,
        'items_per_page'     => 15,
        'dashboard_layout'   => 'default',
    ];

    // ── helpers ──────────────────────────────────────────────

    private function currentSettings(): array
    {
        $saved = session('settings', []);
        $defaults = $this->defaults;
        $defaults['notification_email'] = Auth::user()->email;
        return array_merge($defaults, $saved);
    }

    // ── actions ──────────────────────────────────────────────

    public function index()
    {
        $user     = Auth::user();
        $settings = $this->currentSettings();

        return view('settings.index', compact('user', 'settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            // Appearance
            'theme'              => 'nullable|in:light,dark,auto',
            'compact_view'       => 'nullable',

            // Language & Region
            'language'           => 'nullable|in:sw,en',
            'currency'           => 'nullable|in:TZS,USD,EUR,GBP',
            'timezone'           => 'nullable|timezone',
            'date_format'        => 'nullable|in:d/m/Y,m/d/Y,Y-m-d',
            'time_format'        => 'nullable|in:12,24',

            // Notifications
            'notification_email' => 'nullable|email|max:255',
            'email_alerts'       => 'nullable',
            'sms_alerts'         => 'nullable',
            'push_notifications' => 'nullable',

            // Preferences
            'items_per_page'     => 'nullable|integer|in:10,15,25,50,100',
            'dashboard_layout'   => 'nullable|in:default,compact,detailed',
        ]);

        // Checkboxes are absent from the request when unchecked — resolve explicitly
        $booleans = ['compact_view', 'email_alerts', 'sms_alerts', 'push_notifications'];
        foreach ($booleans as $key) {
            $validated[$key] = $request->boolean($key);
        }

        // Merge over existing settings so untouched panels keep their values
        $settings = array_merge($this->currentSettings(), array_filter(
            $validated,
            fn($v) => $v !== null
        ));

        // Re-apply booleans after filter (false would be removed by array_filter)
        foreach ($booleans as $key) {
            $settings[$key] = $validated[$key];
        }

        session(['settings' => $settings]);

        // Apply locale immediately for the redirect response
        App::setLocale($settings['language']);

        // Persist theme + language in long-lived cookies (30 days)
        Cookie::queue('theme',    $settings['theme'],    60 * 24 * 30);
        Cookie::queue('language', $settings['language'], 60 * 24 * 30);

        return back()->with('success', 'Mipangilio imehifadhiwa kwa mafanikio!');
    }

    public function resetDefaults()
    {
        $defaults = $this->defaults;
        $defaults['notification_email'] = Auth::user()->email;

        session(['settings' => $defaults]);

        App::setLocale($defaults['language']);
        Cookie::queue('theme',    $defaults['theme'],    60 * 24 * 30);
        Cookie::queue('language', $defaults['language'], 60 * 24 * 30);

        return back()->with('success', 'Mipangilio imerejeshwa kwenye chaguo-msingi!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Nywila imebadilishwa kwa mafanikio!');
    }

    /** AJAX — quick toggle from topbar button if needed */
    public function toggleTheme()
    {
        $settings     = $this->currentSettings();
        $newTheme     = $settings['theme'] === 'dark' ? 'light' : 'dark';
        $settings['theme'] = $newTheme;

        session(['settings' => $settings]);
        Cookie::queue('theme', $newTheme, 60 * 24 * 30);

        return response()->json(['theme' => $newTheme]);
    }
}