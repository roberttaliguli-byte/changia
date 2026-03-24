<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contributor;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function summary()
    {
        $user = Auth::user();
        $isEventUser = in_array($user->role, ['event_user', 'user']);
        
        if ($isEventUser) {
            $events = $user->ownedEvents()->with(['contributors'])->get();
        } else {
            $events = $user->events()->with(['contributors'])->get();
        }
        
        $totalEvents = $events->count();
        $totalContributors = $events->sum(function($e) { return $e->contributors()->count(); });
        $totalContributions = $events->sum(function($e) { return $e->contributions()->count(); });
        $totalCollected = $events->sum(function($e) { return $e->total_collected; });
        $totalTarget = $events->sum('target_amount');
        
        // Monthly breakdown
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M Y');
            $collected = Contribution::whereIn('contributor_id', function($q) use ($events) {
                $q->select('id')->from('contributors')->whereIn('event_id', $events->pluck('id'));
            })->where('status', 'approved')
              ->whereYear('created_at', $month->year)
              ->whereMonth('created_at', $month->month)
              ->sum('amount');
            $monthlyData[] = [
                'month' => $monthName,
                'collected' => $collected
            ];
        }
        
        // Event wise breakdown
        $eventBreakdown = [];
        foreach ($events as $event) {
            $eventBreakdown[] = [
                'name' => $event->event_name,
                'collected' => $event->total_collected,
                'target' => $event->target_amount,
                'contributors' => $event->contributors()->count(),
                'contributions' => $event->contributions()->count()
            ];
        }
        
        return view('reports.summary', compact(
            'totalEvents', 'totalContributors', 'totalContributions',
            'totalCollected', 'totalTarget', 'monthlyData', 'eventBreakdown'
        ));
    }
    
    public function export(Request $request, $type)
    {
        $user = Auth::user();
        $isEventUser = in_array($user->role, ['event_user', 'user']);
        
        if ($isEventUser) {
            $events = $user->ownedEvents()->with(['contributors.contributions'])->get();
        } else {
            $events = $user->events()->with(['contributors.contributions'])->get();
        }
        
        $data = [
            'events' => $events,
            'totalCollected' => $events->sum('total_collected'),
            'totalTarget' => $events->sum('target_amount'),
            'generated_at' => now()
        ];
        
        if ($type === 'pdf') {
            $pdf = Pdf::loadView('reports.export-pdf', $data);
            return $pdf->download('ripoti-' . date('Y-m-d') . '.pdf');
        } elseif ($type === 'excel') {
            // Implement Excel export if needed
            return redirect()->back()->with('info', 'Export ya Excel itapatikana hivi karibuni.');
        }
        
        return redirect()->back();
    }
}