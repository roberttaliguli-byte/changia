<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index()
    {
        $user = Auth::user();
        $isEventUser = in_array($user->role, ['event_user', 'user', 'admin']);
        
        if ($isEventUser) {
            $events = $user->ownedEvents()
                ->withCount(['contributors', 'contributions'])
                ->latest()
                ->paginate(12);
        } else {
            $events = $user->events()
                ->withCount(['contributors', 'contributions'])
                ->latest()
                ->paginate(12);
        }
        
        return view('events.index', compact('events'));
    }

    /**
     * Show form to create a new event
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_type' => 'required|string|in:harusi,sendoff,birthday,graduation,kitchen,baby,fundraising,other',
            'event_date' => 'required|date|after_or_equal:today',
            'target_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        DB::beginTransaction();
        
        try {
            $event = Event::create([
                'user_id' => Auth::id(),
                'event_name' => $validated['event_name'],
                'event_type' => $validated['event_type'],
                'event_date' => $validated['event_date'],
                'target_amount' => $validated['target_amount'] ?? 0,
                'description' => $validated['description'],
                'status' => 'active'
            ]);

            DB::commit();

            Log::info('Event created', [
                'event_id' => $event->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('events.show', $event)
                ->with('success', 'Tukio limeundwa kikamilifu!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating event: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.')
                ->withInput();
        }
    }

/**
 * Display the specified event
 */
public function show(Event $event)
{
    $this->authorizeEventAccess($event);
    
    $event->load(['contributors' => function($q) {
        $q->with(['contributions' => function($cq) {
            $cq->latest();
        }]);
    }]);
    
    // Specify table names in these queries
    $totalCollected = $event->contributions()->where('contributions.status', 'approved')->sum('contributions.amount');
    $totalContributors = $event->contributors()->count();
    $totalContributions = $event->contributions()->count();
    $pendingContributions = $event->contributions()->where('contributions.status', 'pending')->count();
    $approvedContributions = $event->contributions()->where('contributions.status', 'approved')->count();
    
    return view('events.show', compact(
        'event', 
        'totalCollected', 
        'totalContributors', 
        'totalContributions', 
        'pendingContributions',
        'approvedContributions'
    ));
}

    /**
     * Show form to edit an event
     */
    public function edit(Event $event)
    {
        $this->authorizeEventAccess($event);
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        $this->authorizeEventAccess($event);
        
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_type' => 'required|string|in:harusi,sendoff,birthday,graduation,kitchen,baby,fundraising,other',
            'event_date' => 'required|date|after_or_equal:today',
            'target_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,completed,cancelled'
        ]);
        
        DB::beginTransaction();
        
        try {
            $event->update($validated);
            DB::commit();
            
            Log::info('Event updated', ['event_id' => $event->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('events.show', $event)
                ->with('success', 'Tukio limehaririwa kikamilifu!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating event: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.');
        }
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event)
    {
        $this->authorizeEventAccess($event);
        
        // Check if there are any contributions
        if ($event->contributions()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Huwezi kufuta tukio lenye michango. Zifute michango kwanza.');
        }
        
        DB::beginTransaction();
        
        try {
            $event->contributors()->delete();
            $event->delete();
            DB::commit();
            
            Log::info('Event deleted', ['event_id' => $event->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('dashboard')
                ->with('success', 'Tukio limefutwa kikamilifu!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting event: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.');
        }
    }

    /**
     * Authorize event access based on user role
     */
    private function authorizeEventAccess(Event $event)
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Hujajisajili.');
        }
        
        // Admin has full access
        if ($user->role === 'admin') {
            return true;
        }
        
        // Event owner (event_user or user) has access to their own events
        if (in_array($user->role, ['event_user', 'user'])) {
            if ($event->user_id === $user->id) {
                return true;
            }
            abort(403, 'Huna ruhusa ya kufikia tukio hili.');
        }
        
        // Accountant has access only to assigned events
        if ($user->role === 'accountant') {
            if ($user->events()->where('event_id', $event->id)->exists()) {
                return true;
            }
            abort(403, 'Huna ruhusa ya kufikia tukio hili.');
        }
        
        abort(403, 'Huna ruhusa ya kufikia tukio hili.');
    }
}