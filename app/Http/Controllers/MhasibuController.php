<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Contribution;  // Add this import
use Illuminate\Support\Facades\DB;  // Add this import
use Illuminate\Support\Facades\Log;  // Add this import
use Illuminate\Support\Facades\Mail;

class MhasibuController extends Controller
{
    /**
     * Display list of accountants for the authenticated user's events
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Get all accountants associated with user's events
        $accountants = User::where('role', 'accountant')
            ->whereHas('events', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with('events')
            ->latest()
            ->get();
        
        $events = Event::where('user_id', $userId)->get();
        
        return view('mhasibu.index', compact('accountants', 'events'));
    }
/**
 * Show pending contributions for accountant (Confirmation Dashboard)
 */
public function confirm()
{
    $user = Auth::user();
    
    // Get events assigned to this accountant
    $eventIds = DB::table('event_accountant')
        ->where('accountant_id', $user->id)
        ->pluck('event_id');
    
    // Get pending contributions with eager loading
    $pendingContributions = Contribution::whereIn('contributor_id', function($query) use ($eventIds) {
        $query->select('id')
            ->from('contributors')
            ->whereIn('event_id', $eventIds);
    })
    ->where('status', 'pending')
    ->with(['contributor.event'])
    ->latest()
    ->get();
    
    // Stats
    $pendingCount = $pendingContributions->count();
    $approvedCount = Contribution::whereIn('contributor_id', function($query) use ($eventIds) {
        $query->select('id')
            ->from('contributors')
            ->whereIn('event_id', $eventIds);
    })
    ->where('status', 'approved')
    ->count();
    
    $totalAmount = Contribution::whereIn('contributor_id', function($query) use ($eventIds) {
        $query->select('id')
            ->from('contributors')
            ->whereIn('event_id', $eventIds);
    })
    ->where('status', 'approved')
    ->sum('amount');
    
    $eventsCount = $eventIds->count();
    
    return view('mhasibu.confirm', compact(
        'pendingContributions',
        'pendingCount',
        'approvedCount',
        'totalAmount',
        'eventsCount'
    ));
}

/**
 * Approve a contribution
 */
public function approve($id)
{
    try {
        $contribution = Contribution::findOrFail($id);
        $user = Auth::user();
        $eventId = $contribution->contributor->event_id;
        
        // Verify accountant has access
        $hasAccess = DB::table('event_accountant')
            ->where('accountant_id', $user->id)
            ->where('event_id', $eventId)
            ->exists();
        
        if (!$hasAccess) {
            return redirect()->back()->with('error', 'Huna ruhusa ya kuthibitisha mchango huu.');
        }
        
        if ($contribution->status === 'approved') {
            return redirect()->back()->with('info', 'Mchango huu tayari umethibitishwa.');
        }
        
        DB::beginTransaction();
        
        // Approve contribution
        $contribution->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now()
        ]);
        
        // Update contributor's amounts
        $contributor = $contribution->contributor;
        $contributor->paid_amount += $contribution->amount;
        $contributor->remaining_amount = $contributor->promised_amount - $contributor->paid_amount;
        
        if ($contributor->remaining_amount == 0) {
            $contributor->status = 'completed';
        } elseif ($contributor->paid_amount > 0) {
            $contributor->status = 'partial';
        }
        
        $contributor->save();
        
        DB::commit();
        
        return redirect()->back()->with('success', 'Mchango wa TSh ' . number_format($contribution->amount) . ' umethibitishwa kikamilifu!');
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error approving contribution: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.');
    }
}

/**
 * Reject a contribution
 */
public function reject($id)
{
    try {
        $contribution = Contribution::findOrFail($id);
        $user = Auth::user();
        $eventId = $contribution->contributor->event_id;
        
        // Verify accountant has access
        $hasAccess = DB::table('event_accountant')
            ->where('accountant_id', $user->id)
            ->where('event_id', $eventId)
            ->exists();
        
        if (!$hasAccess) {
            return redirect()->back()->with('error', 'Huna ruhusa ya kukataa mchango huu.');
        }
        
        if ($contribution->status === 'approved') {
            return redirect()->back()->with('error', 'Huwezi kukataa mchango ambao tayari umethibitishwa.');
        }
        
        DB::beginTransaction();
        
        $contribution->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'approved_at' => now()
        ]);
        
        DB::commit();
        
        return redirect()->back()->with('warning', 'Mchango wa TSh ' . number_format($contribution->amount) . ' umekataliwa.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error rejecting contribution: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.');
    }
}
    /**
     * Show form to create a new accountant
     */
    public function create()
    {
        $events = Event::where('user_id', Auth::id())->get();
        
        if ($events->isEmpty()) {
            return redirect()->route('dashboard')
                ->with('error', 'Tafadhali unda tukio kwanza kabla ya kusajili mhasibu.');
        }
        
        return view('mhasibu.create', compact('events'));
    }

    /**
     * Store a newly created accountant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'event_id' => 'required|exists:events,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            // Check if the event belongs to the authenticated user
            $event = Event::where('id', $validated['event_id'])
                ->where('user_id', Auth::id())
                ->first();
                
            if (!$event) {
                return back()->withErrors(['event_id' => 'Tukio hili si lako.'])
                    ->withInput();
            }

            // Create the accountant user
            $accountant = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'accountant',
            ]);

            // Associate accountant with the event (you need a pivot table for this)
            // For now, we'll add a foreign key to events table or create a pivot table
            $accountant->events()->attach($event->id);

            // Optional: Send welcome email
            // Mail::to($accountant->email)->send(new AccountantWelcomeMail($accountant, $event, $validated['password']));

            return redirect()->route('mhasibu.index')
                ->with('success', 'Mhasibu amesajiliwa kikamilifu! Anatumia: ' . $validated['email'] . ' / Password kama ulivyoweka.');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Kuna tatizo limejitokeza: ' . $e->getMessage()])
                ->withInput();
        }
    }


    /**
     * Remove an accountant from an event
     */
    public function destroy($id)
    {
        try {
            $accountant = User::findOrFail($id);
            
            // Check if this accountant is assigned to any of user's events
            $hasEvent = $accountant->events()
                ->where('user_id', Auth::id())
                ->exists();
                
            if (!$hasEvent) {
                return back()->with('error', 'Huna ruhusa ya kufuta mhasibu huyu.');
            }
            
            // Detach from events instead of deleting user
            $accountant->events()->detach();
            
            // Optionally, you can delete the user account
            // $accountant->delete();
            
            return back()->with('success', 'Mhasibu ametolewa kwenye tukio lako.');
        } catch (\Exception $e) {
            return back()->with('error', 'Kuna tatizo limejitokeza.');
        }
    }
}