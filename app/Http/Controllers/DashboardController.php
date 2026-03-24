<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contributor;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show dashboard with summary statistics
     */
    public function index()
    {
        $user = Auth::user();
        $isEventUser = in_array($user->role, ['event_user', 'user', 'admin']);
        $isAccountant = $user->role === 'accountant';
        
        // Get events based on user role
        if ($isEventUser) {
            $events = $user->ownedEvents()
                ->withCount(['contributors', 'contributions'])
                ->latest()
                ->take(5)
                ->get();
            
            $totalEvents = $user->ownedEvents()->count();
            $activeEvents = $user->ownedEvents()->where('status', 'active')->count();
            $completedEvents = $user->ownedEvents()->where('status', 'completed')->count();
            
        } elseif ($isAccountant) {
            $events = $user->events()
                ->withCount(['contributors', 'contributions'])
                ->latest()
                ->take(5)
                ->get();
            
            $totalEvents = $user->events()->count();
            $activeEvents = $user->events()->where('status', 'active')->count();
            $completedEvents = $user->events()->where('status', 'completed')->count();
        } else {
            $events = collect();
            $totalEvents = 0;
            $activeEvents = 0;
            $completedEvents = 0;
        }
        
        // Calculate totals with proper table specification
        $totalPromised = 0;
        $totalCollected = 0;
        $totalRemaining = 0;
        $pendingContributions = 0;
        $totalContributors = 0;
        
        foreach ($events as $event) {
            $totalPromised += $event->contributors()->sum('promised_amount');
            // Specify table name in where clause
            $totalCollected += $event->contributions()->where('contributions.status', 'approved')->sum('contributions.amount');
            $totalRemaining += $event->contributors()->sum('remaining_amount');
            $pendingContributions += $event->contributions()->where('contributions.status', 'pending')->count();
            $totalContributors += $event->contributors()->count();
        }
        
        // Get recent contributions - FIX THE AMBIGUOUS COLUMN ISSUE
        if ($isEventUser) {
            $eventIds = $user->ownedEvents()->pluck('id');
            
            $recentContributions = Contribution::whereIn('contributor_id', function($query) use ($eventIds) {
                $query->select('id')
                    ->from('contributors')
                    ->whereIn('event_id', $eventIds);
            })
            ->with(['contributor.event'])
            ->latest()
            ->take(10)
            ->get();
            
        } elseif ($isAccountant) {
            // FIX: Specify which table's 'id' column to use
            $eventIds = $user->events()->pluck('events.id'); // Specify 'events.id' to avoid ambiguity
            
            $recentContributions = Contribution::whereIn('contributor_id', function($query) use ($eventIds) {
                $query->select('id')
                    ->from('contributors')
                    ->whereIn('event_id', $eventIds);
            })
            ->with(['contributor.event'])
            ->latest()
            ->take(10)
            ->get();
            
        } else {
            $recentContributions = collect();
        }
        
        // Calculate overall progress
        $overallProgress = $totalPromised > 0 ? min(round(($totalCollected / $totalPromised) * 100), 100) : 0;
        
        // Get monthly data for chart
        $monthlyData = $this->getMonthlyData($isEventUser ? $user->ownedEvents()->pluck('id') : $user->events()->pluck('events.id'));
        
        return view('dashboard', compact(
            'events',
            'totalEvents',
            'activeEvents',
            'completedEvents',
            'totalPromised',
            'totalCollected',
            'totalRemaining',
            'pendingContributions',
            'totalContributors',
            'recentContributions',
            'overallProgress',
            'monthlyData',
            'isEventUser',
            'isAccountant'
        ));
    }
    
    /**
     * Get monthly contribution data for chart
     */
    private function getMonthlyData($eventIds)
    {
        $monthlyData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M Y');
            
            $collected = Contribution::whereIn('contributor_id', function($query) use ($eventIds) {
                $query->select('id')
                    ->from('contributors')
                    ->whereIn('event_id', $eventIds);
            })
            ->where('contributions.status', 'approved') // Specify table name
            ->whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->sum('amount');
            
            $monthlyData[] = [
                'month' => $monthName,
                'collected' => $collected
            ];
        }
        
        return $monthlyData;
    }
}