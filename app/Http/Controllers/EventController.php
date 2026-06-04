<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contribution;
use App\Models\Contributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventController extends Controller
{
   
    /**
     * Generate a unique registration link for an event
     */
    public function generateRegistrationLink(Event $event)
    {
        $this->authorizeEventAccess($event);
        
        // Generate a unique token for this event
        $token = Str::random(64);
        
        // Store the token in cache or database (using cache for simplicity)
        cache()->put('event_registration_' . $token, [
            'event_id' => $event->id,
            'generated_by' => Auth::id(),
            'generated_at' => now()
        ], now()->addDays(30)); // Link expires after 30 days
        
        $registrationLink = route('public.contributor.register', ['token' => $token]);
        
        // If AJAX request, return JSON
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'link' => $registrationLink,
                'qr_code' => $this->generateQRCode($registrationLink)
            ]);
        }
        
        return redirect()->back()->with('registration_link', $registrationLink);
    }
    
    /**
     * Generate QR Code for the link
     */
    private function generateQRCode($url)
    {
        // Using Google Charts API for QR code
        return "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . urlencode($url) . "&choe=UTF-8";
    }
    
    /**
     * Show registration form via public link
     */
    public function showPublicRegistrationForm($token)
    {
        $data = cache()->get('event_registration_' . $token);
        
        if (!$data) {
            abort(404, 'Kiungo hiki hakifai au kimeisha muda wake. Tafadhali wasiliana na mratibu wa tukio.');
        }
        
        $event = Event::findOrFail($data['event_id']);
        
        return view('public.register-contributor', compact('event', 'token'));
    }
    
    /**
     * Store contributor from public registration
     */
    public function storePublicRegistration(Request $request, $token)
    {
        $data = cache()->get('event_registration_' . $token);
        
        if (!$data) {
            return redirect()->route('home')
                ->with('error', 'Kiungo hiki hakifai au kimeisha muda wake.');
        }
        
        $event = Event::findOrFail($data['event_id']);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'promised_amount' => 'required|numeric|min:1000',
            'notes' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Check if contributor already exists
            $existingContributor = Contributor::where('event_id', $event->id)
                ->where('phone', $request->phone)
                ->first();
            
            if ($existingContributor) {
                // Update existing contributor's promised amount
                $existingContributor->promised_amount += $request->promised_amount;
                $existingContributor->remaining_amount = $existingContributor->promised_amount - $existingContributor->paid_amount;
                $existingContributor->notes = $request->notes ?? $existingContributor->notes;
                $existingContributor->save();
                
                $contributor = $existingContributor;
                $message = "Mchango wako umeongezwa! Jumla ya alichoahidi: " . number_format($contributor->promised_amount) . " TSh";
            } else {
                // Create new contributor
                $contributor = Contributor::create([
                    'event_id' => $event->id,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'promised_amount' => $request->promised_amount,
                    'paid_amount' => 0,
                    'remaining_amount' => $request->promised_amount,
                    'status' => 'pending',
                    'registration_method' => 'public_link',
                    'notes' => $request->notes,
                    'registered_at' => now()
                ]);
                
                $message = "Asante {$contributor->name}! Umeahidi TSh " . number_format($contributor->promised_amount) . " kwa tukio la {$event->event_name}.";
            }
            
            DB::commit();
            
            // Send confirmation message (optional)
            // $this->sendConfirmationSms($contributor->phone, $message);
            
            return view('public.registration-success', compact('event', 'contributor'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in public registration: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.')
                ->withInput();
        }
    }
    
    /**
     * Get registration link for event (for AJAX)
     */
    public function getRegistrationLink(Event $event)
    {
        $this->authorizeEventAccess($event);
        
        // Check if a valid link exists
        $existingToken = null;
        $cacheKeys = cache()->get('event_registration_*');
        
        // Generate new token
        $token = Str::random(64);
        cache()->put('event_registration_' . $token, [
            'event_id' => $event->id,
            'generated_by' => Auth::id(),
            'generated_at' => now()
        ], now()->addDays(30));
        
        $registrationLink = route('public.contributor.register', ['token' => $token]);
        
        return response()->json([
            'success' => true,
            'link' => $registrationLink,
            'qr_code' => "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . urlencode($registrationLink),
            'token' => $token
        ]);
    }

    /**
     * Display a listing of events with filters
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isEventUser = in_array($user->role, ['event_user', 'user', 'admin']);
        
        // Base query
        if ($isEventUser) {
            $query = $user->ownedEvents();
        } else {
            $query = $user->events();
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $query->where('event_name', 'like', '%' . $request->search . '%');
        }
        
        // Apply status filter
        if ($request->filled('status') && in_array($request->status, ['active', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }
        
        // Apply sorting
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('event_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('event_name', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        // Get events with counts
        $events = $query->withCount(['contributors', 'contributions'])->paginate(12);
        
        // Calculate total collected amount for each event - FIXED: specify table name
        foreach ($events as $event) {
            // Specify the table name 'contributions' for the status column
            $event->total_collected = $event->contributions()
                ->where('contributions.status', 'approved')
                ->sum('amount');
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
                'description' => $validated['description'] ?? null,
                'status' => 'active'
            ]);

            DB::commit();

            Log::info('Event created', [
                'event_id' => $event->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('events.index')
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
     * Display the specified event - Now redirects to contributors index
     */
    public function show(Event $event)
    {
        $this->authorizeEventAccess($event);
        
        return redirect()->route('contributors.index', $event->id)
            ->with('info', 'Tazama wachangiaji na michango ya tukio hili');
    }

    /**
     * Show form to edit an event - Returns JSON for modal
     */
    public function edit(Event $event)
    {
        $this->authorizeEventAccess($event);
        
        if (request()->wantsJson()) {
            return response()->json([
                'id' => $event->id,
                'event_name' => $event->event_name,
                'event_type' => $event->event_type,
                'event_date' => $event->event_date instanceof \Carbon\Carbon ? $event->event_date->format('Y-m-d') : date('Y-m-d', strtotime($event->event_date)),
                'target_amount' => $event->target_amount,
                'description' => $event->description,
                'status' => $event->status
            ]);
        }
        
        return redirect()->route('events.index');
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
            'event_date' => 'required|date',
            'target_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,completed,cancelled'
        ]);
        
        DB::beginTransaction();
        
        try {
            $event->update($validated);
            DB::commit();
            
            Log::info('Event updated', ['event_id' => $event->id, 'user_id' => Auth::id()]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Tukio limehaririwa kikamilifu!']);
            }
            
            return redirect()->route('events.index')
                ->with('success', 'Tukio limehaririwa kikamilifu!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating event: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Hitilafu imetokea. Tafadhali jaribu tena.'], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.');
        }
    }

    /**
     * Remove the specified event - Deletes everything (contributions + contributors)
     */
    public function destroy(Request $request, Event $event)
    {
        $this->authorizeEventAccess($event);
        
        DB::beginTransaction();
        
        try {
            // Get counts for logging
            $contributionsCount = $event->contributions()->count();
            $contributorsCount = $event->contributors()->count();
            
            // Delete all contributions first (due to foreign key constraints)
            $event->contributions()->delete();
            
            // Delete all contributors
            $event->contributors()->delete();
            
            // Finally delete the event
            $event->delete();
            
            DB::commit();
            
            Log::info('Event deleted with all related data', [
                'event_id' => $event->id, 
                'user_id' => Auth::id(),
                'contributions_deleted' => $contributionsCount,
                'contributors_deleted' => $contributorsCount
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Tukio na michango yake yote imefutwa kikamilifu!'
                ]);
            }
            
            return redirect()->route('events.index')
                ->with('success', 'Tukio limefutwa kikamilifu pamoja na michango yake!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting event: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Hitilafu imetokea: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea: ' . $e->getMessage());
        }
    }

    /**
     * Get event data for editing (AJAX)
     */
    public function getEventData(Event $event)
    {
        $this->authorizeEventAccess($event);
        
        return response()->json([
            'id' => $event->id,
            'event_name' => $event->event_name,
            'event_type' => $event->event_type,
            'event_date' => $event->event_date instanceof \Carbon\Carbon ? $event->event_date->format('Y-m-d') : date('Y-m-d', strtotime($event->event_date)),
            'target_amount' => $event->target_amount,
            'description' => $event->description,
            'status' => $event->status
        ]);
    }

    /**
     * Get event summary statistics (for dashboard)
     */
    public function getEventStats(Event $event)
    {
        $this->authorizeEventAccess($event);
        
        $totalCollected = $event->contributions()
            ->where('contributions.status', 'approved')
            ->sum('contributions.amount');
            
        $totalPending = $event->contributions()
            ->where('contributions.status', 'pending')
            ->sum('contributions.amount');
            
        $totalContributors = $event->contributors()->count();
        $totalContributions = $event->contributions()->count();
        
        return response()->json([
            'total_collected' => $totalCollected,
            'total_pending' => $totalPending,
            'total_contributors' => $totalContributors,
            'total_contributions' => $totalContributions,
            'target_amount' => $event->target_amount,
            'progress' => $event->target_amount > 0 ? round(($totalCollected / $event->target_amount) * 100, 2) : 0
        ]);
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