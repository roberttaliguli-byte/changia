<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChangiaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $permission
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If no specific permission required, just proceed
        if (!$permission) {
            return $next($request);
        }

        // Handle different permission types
        return match($permission) {
            'admin' => $this->checkAdmin($user, $request, $next),
            'accountant' => $this->checkAccountant($user, $request, $next),
            'event_user' => $this->checkEventUser($user, $request, $next),
            'can_approve' => $this->checkCanApprove($user, $request, $next),
            'event_access' => $this->checkEventAccess($user, $request, $next),
            'event_owner' => $this->checkEventOwner($user, $request, $next),
            'contributor_access' => $this->checkContributorAccess($user, $request, $next),
            'report_access' => $this->checkReportAccess($user, $request, $next),
            'card_access' => $this->checkCardAccess($user, $request, $next),
            'message_access' => $this->checkMessageAccess($user, $request, $next),
            default => $this->checkDefault($user, $permission, $request, $next),
        };
    }

    /**
     * Check if user is admin
     */
    private function checkAdmin($user, Request $request, Closure $next): Response
    {
        if ($user->role === 'admin') {
            return $next($request);
        }
        
        return $this->unauthorized($request, 'Sehemu hii inapatikana kwa wasimamizi pekee.');
    }

    /**
     * Check if user is accountant or admin
     */
    private function checkAccountant($user, Request $request, Closure $next): Response
    {
        if ($user->role === 'accountant' || $user->role === 'admin') {
            return $next($request);
        }
        
        return $this->unauthorized($request, 'Sehemu hii inapatikana kwa wahasibu pekee.');
    }

    /**
     * Check if user is event user (can create and manage events)
     */
    private function checkEventUser($user, Request $request, Closure $next): Response
    {
        if (in_array($user->role, ['event_user', 'user', 'admin'])) {
            return $next($request);
        }
        
        return $this->unauthorized($request, 'Sehemu hii inapatikana kwa waratibu wa matukio pekee.');
    }

    /**
     * Check if user can approve contributions (accountant or admin)
     */
    private function checkCanApprove($user, Request $request, Closure $next): Response
    {
        if ($user->role === 'admin' || $user->role === 'accountant') {
            return $next($request);
        }
        
        return $this->unauthorized($request, 'Huna ruhusa ya kuthibitisha michango.');
    }

    /**
     * Check if user has access to specific event
     */
    private function checkEventAccess($user, Request $request, Closure $next): Response
    {
        $event = $request->route('event');
        
        if (!$event) {
            return $next($request);
        }
        
        if ($user->role === 'admin') {
            return $next($request);
        }
        
        if (in_array($user->role, ['event_user', 'user'])) {
            if ($event->user_id == $user->id) {
                return $next($request);
            }
        }
        
        if ($user->role === 'accountant') {
            if ($user->events()->where('event_id', $event->id)->exists()) {
                return $next($request);
            }
        }
        
        return $this->unauthorized($request, 'Huna ruhusa ya kufikia tukio hili.');
    }

    /**
     * Check if user owns the event (can edit/delete)
     */
    private function checkEventOwner($user, Request $request, Closure $next): Response
    {
        $event = $request->route('event');
        
        if (!$event) {
            return $next($request);
        }
        
        if ($event->user_id == $user->id || $user->role === 'admin') {
            return $next($request);
        }
        
        return $this->unauthorized($request, 'Huna ruhusa ya kubadilisha tukio hili.');
    }

    /**
     * Check contributor access
     */
    private function checkContributorAccess($user, Request $request, Closure $next): Response
    {
        $event = $request->route('event');
        
        if (!$event) {
            return $next($request);
        }
        
        if ($user->role === 'admin') {
            return $next($request);
        }
        
        if (in_array($user->role, ['event_user', 'user'])) {
            if ($event->user_id == $user->id) {
                return $next($request);
            }
        }
        
        if ($user->role === 'accountant') {
            if ($user->events()->where('event_id', $event->id)->exists()) {
                return $next($request);
            }
        }
        
        return $this->unauthorized($request, 'Huna ruhusa ya kufikia orodha ya wachangiaji.');
    }

    /**
     * Check report access
     */
    private function checkReportAccess($user, Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Check card access
     */
    private function checkCardAccess($user, Request $request, Closure $next): Response
    {
        if (in_array($user->role, ['event_user', 'user', 'admin'])) {
            return $next($request);
        }
        
        return $this->unauthorized($request, 'Sehemu hii inapatikana kwa waratibu wa matukio pekee.');
    }

    /**
     * Check message access
     */
    private function checkMessageAccess($user, Request $request, Closure $next): Response
    {
        if (in_array($user->role, ['event_user', 'user', 'admin'])) {
            return $next($request);
        }
        
        return $this->unauthorized($request, 'Sehemu hii inapatikana kwa waratibu wa matukio pekee.');
    }

    /**
     * Default permission check
     */
    private function checkDefault($user, string $permission, Request $request, Closure $next): Response
    {
        if ($user->role === $permission) {
            return $next($request);
        }
        
        $roles = explode('|', $permission);
        if (in_array($user->role, $roles)) {
            return $next($request);
        }
        
        return $this->unauthorized($request, 'Huna ruhusa ya kufikia ukurasa huu.');
    }

    /**
     * Return unauthorized response
     */
    private function unauthorized(Request $request, string $message = 'Huna ruhusa ya kufikia ukurasa huu.'): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => $message
            ], 403);
        }
        
        if ($request->ajax()) {
            return response()->json([
                'error' => $message
            ], 403);
        }
        
        abort(403, $message);
    }
}