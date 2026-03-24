<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Contributor;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContributionController extends Controller
{
    /**
     * Display list of contributors for an event
     */
    public function index(Event $event)
    {
        $this->authorizeEventAccess($event);
        
        $contributors = $event->contributors()
            ->with(['contributions' => function($q) {
                $q->latest();
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalPromised = $event->contributors()->sum('promised_amount');
        $totalPaid = $event->contributors()->sum('paid_amount');
        $totalRemaining = $event->contributors()->sum('remaining_amount');
        
        // FIX: Specify table name 'contributions' in where clause
        $pendingContributions = $event->contributions()
            ->where('contributions.status', 'pending')
            ->count();
        
        return view('contributors.index', compact(
            'event', 
            'contributors', 
            'totalPromised', 
            'totalPaid', 
            'totalRemaining',
            'pendingContributions'
        ));
    }

    /**
     * Show form to create a new contributor (manual registration)
     */
    public function create(Event $event)
    {
        $this->authorizeEventAccess($event);
        return view('contributors.create', compact('event'));
    }

/**
 * Store a new contributor (manual registration)
 */
public function store(Request $request, Event $event)
{
    $this->authorizeEventAccess($event);
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'nullable|email|max:255',
        'promised_amount' => 'required|numeric|min:0',
        'initial_payment' => 'required|numeric|min:0',
        'notes' => 'nullable|string'
        // Removed payment_method validation
    ]);
    
    DB::beginTransaction();
    
    try {
        // Check if contributor already exists for this event
        $existingContributor = Contributor::where('event_id', $event->id)
            ->where('phone', $validated['phone'])
            ->first();
        
        if ($existingContributor) {
            return redirect()->back()
                ->with('error', 'Mchangiaji huyu tayari yupo. Tafadhali ongeza malipo kwenye orodha.')
                ->withInput();
        }
        
        // Create contributor
        $contributor = Contributor::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'promised_amount' => $validated['promised_amount'],
            'paid_amount' => 0,
            'remaining_amount' => $validated['promised_amount'],
            'status' => $validated['initial_payment'] > 0 ? 'partial' : 'pending',
            'registration_method' => 'manual',
            'notes' => $validated['notes'] ?? null,
            'registered_at' => now()
        ]);
        
        // Create initial payment if any
        if ($validated['initial_payment'] > 0) {
            $contribution = Contribution::create([
                'contributor_id' => $contributor->id,
                'amount' => $validated['initial_payment'],
                'payment_method' => 'pending', // Set as pending until actual payment method is recorded
                'status' => 'pending',
                'approved_by' => null,
                'notes' => 'Malipo ya awali - Njia ya malipo itarekodiwa wakati wa malipo halisi'
            ]);
            
            $contributor->updateRemainingAmount();
            
            Log::info('Initial contribution added', [
                'contributor_id' => $contributor->id,
                'amount' => $validated['initial_payment'],
                'user_id' => Auth::id()
            ]);
        }
        
        DB::commit();
        
        $this->sendRegistrationConfirmation($contributor);
        
        return redirect()->route('contributors.index', $event)
            ->with('success', 'Mchangiaji amesajiliwa kikamilifu! Malipo yanasubiri kuthibitishwa.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error creating contributor: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.')
            ->withInput();
    }
}
 /**
 * Add payment to existing contributor
 */
public function addPayment(Request $request, Event $event, Contributor $contributor)
{
    $this->authorizeEventAccess($event);
    
    if ($contributor->event_id !== $event->id) {
        abort(404, 'Mchangiaji hapatikani.');
    }
    
    $validated = $request->validate([
        'amount' => 'required|numeric|min:1000|max:' . $contributor->remaining_amount,
        'notes' => 'nullable|string'
        // Removed payment_method validation
    ]);
    
    DB::beginTransaction();
    
    try {
        // Get payment method from request if provided, otherwise default to 'pending'
        $paymentMethod = $request->input('payment_method', 'pending');
        
        // Only allow valid payment methods if provided
        if ($paymentMethod !== 'pending' && !in_array($paymentMethod, ['cash', 'mpesa', 'bank'])) {
            $paymentMethod = 'pending';
        }
        
        $contribution = Contribution::create([
            'contributor_id' => $contributor->id,
            'amount' => $validated['amount'],
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'approved_by' => null,
            'notes' => $validated['notes'] ?? null
        ]);
        
        Log::info('Contribution created', [
            'id' => $contribution->id,
            'contributor_id' => $contributor->id,
            'amount' => $validated['amount']
        ]);
        
        $contributor->updateRemainingAmount();
        
        if ($contributor->remaining_amount == 0) {
            $contributor->update(['status' => 'completed']);
            $this->sendCompletionNotification($contributor);
        } elseif ($contributor->paid_amount > 0) {
            $contributor->update(['status' => 'partial']);
        }
        
        DB::commit();
        
        // Notify accountants safely - check if relationship exists
        $this->notifyAccountants($event, $contribution);
        
        // Create appropriate success message based on payment method
        $message = 'Malipo yameongezwa kikamilifu! Yanasubiri kuthibitishwa.';
        if ($paymentMethod === 'pending') {
            $message = 'Malipo yameongezwa kikamilifu! Njia ya malipo itarekodiwa wakati wa uthibitisho. Yanasubiri kuthibitishwa.';
        }
        
        return redirect()->back()
            ->with('success', $message);
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error adding payment: ' . $e->getMessage(), [
            'exception' => $e,
            'contributor_id' => $contributor->id,
            'event_id' => $event->id
        ]);
        return redirect()->back()
            ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.');
    }
}

    /**
     * Approve a contribution
     */
    public function approve(Contribution $contribution)
    {
        $user = Auth::user();
        
        if (!$user->canApproveContributions()) {
            abort(403, 'Huna ruhusa ya kuthibitisha michango.');
        }
        
        if ($contribution->status === 'approved') {
            return redirect()->back()
                ->with('info', 'Mchango huu tayari umethibitishwa.');
        }
        
        DB::beginTransaction();
        
        try {
            $contribution->approve($user->id);
            $contribution->contributor->updateRemainingAmount();
            $this->sendApprovalNotification($contribution);
            
            DB::commit();
            
            Log::info('Contribution approved', [
                'contribution_id' => $contribution->id,
                'amount' => $contribution->amount,
                'approved_by' => $user->id
            ]);
            
            return redirect()->back()
                ->with('success', 'Mchango umethibitishwa kikamilifu!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving contribution: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.');
        }
    }

    /**
     * Reject a contribution
     */
    public function reject(Contribution $contribution)
    {
        $user = Auth::user();
        
        if (!$user->canApproveContributions()) {
            abort(403, 'Huna ruhusa ya kukataa michango.');
        }
        
        if ($contribution->status === 'approved') {
            return redirect()->back()
                ->with('error', 'Huwezi kukataa mchango ambao tayari umethibitishwa.');
        }
        
        DB::beginTransaction();
        
        try {
            $contribution->reject($user->id);
            $contribution->contributor->updateRemainingAmount();
            
            DB::commit();
            
            Log::info('Contribution rejected', [
                'contribution_id' => $contribution->id,
                'amount' => $contribution->amount,
                'rejected_by' => $user->id
            ]);
            
            return redirect()->back()
                ->with('warning', 'Mchango umekataliwa.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting contribution: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.');
        }
    }

    /**
     * Register contributor via SMS/WhatsApp link
     */
    public function registerViaSMS(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'event_id' => 'required|exists:events,id'
        ]);
        
        $event = Event::findOrFail($validated['event_id']);
        $token = Str::random(64);
        
        cache()->put('registration_token_' . $token, [
            'phone' => $validated['phone'],
            'event_id' => $event->id
        ], now()->addHours(24));
        
        $registrationLink = route('api.contribution.form', ['token' => $token]);
        
        $this->sendSMS($validated['phone'], "Karibu {$event->event_name}! Bonyeza link hii kusajili na kutoa mchango wako: {$registrationLink}");
        
        return response()->json([
            'success' => true,
            'message' => 'Ujumbe umetumwa kikamilifu.',
            'link' => $registrationLink
        ]);
    }

    /**
     * Show contribution form from SMS link
     */
    public function showContributionForm($token)
    {
        $data = cache()->get('registration_token_' . $token);
        
        if (!$data) {
            abort(404, 'Kiungo hiki hakifai au kimeisha muda wake.');
        }
        
        $event = Event::findOrFail($data['event_id']);
        $phone = $data['phone'];
        
        return view('contributors.sms-form', compact('event', 'phone', 'token'));
    }

    /**
     * Submit contribution via link
     */
    public function submitViaLink(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'promised_amount' => 'required|numeric|min:1000',
            'event_id' => 'required|exists:events,id'
        ]);
        
        $tokenData = cache()->get('registration_token_' . $validated['token']);
        
        if (!$tokenData || $tokenData['phone'] !== $validated['phone']) {
            return redirect()->back()
                ->with('error', 'Kiungo hiki hakifai. Tafadhali omba kiungo kipya.')
                ->withInput();
        }
        
        $event = Event::findOrFail($validated['event_id']);
        
        DB::beginTransaction();
        
        try {
            $contributor = Contributor::where('event_id', $event->id)
                ->where('phone', $validated['phone'])
                ->first();
            
            if (!$contributor) {
                $contributor = Contributor::create([
                    'event_id' => $event->id,
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'promised_amount' => $validated['promised_amount'],
                    'paid_amount' => 0,
                    'remaining_amount' => $validated['promised_amount'],
                    'status' => 'pending',
                    'registration_method' => 'sms',
                    'registered_at' => now()
                ]);
            } else {
                if ($validated['promised_amount'] > $contributor->promised_amount) {
                    $contributor->update([
                        'promised_amount' => $validated['promised_amount'],
                        'remaining_amount' => $validated['promised_amount'] - $contributor->paid_amount
                    ]);
                }
            }
            
            Contribution::create([
                'contributor_id' => $contributor->id,
                'amount' => $validated['promised_amount'],
                'payment_method' => 'pending',
                'status' => 'pending',
                'approved_by' => null,
                'notes' => 'Registered via SMS link'
            ]);
            
            DB::commit();
            
            $this->notifyNewRegistration($event, $contributor);
            cache()->forget('registration_token_' . $validated['token']);
            
            return view('contributors.thank-you', compact('event', 'contributor'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting via link: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.')
                ->withInput();
        }
    }

    /**
     * Export contributors list to PDF
     */
    public function exportPDF(Event $event)
    {
        $this->authorizeEventAccess($event);
        
        $contributors = $event->contributors()
            ->with(['contributions' => function($q) {
                $q->where('status', 'approved');
            }])
            ->get();
        
        $totalPromised = $contributors->sum('promised_amount');
        $totalPaid = $contributors->sum('paid_amount');
        $totalRemaining = $contributors->sum('remaining_amount');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('contributors.export-pdf', compact(
            'event', 'contributors', 'totalPromised', 'totalPaid', 'totalRemaining'
        ));
        
        return $pdf->download('wachangiaji-' . $event->id . '.pdf');
    }

    /**
     * Get contributor details for editing
     */
    public function edit(Event $event, Contributor $contributor)
    {
        $this->authorizeEventAccess($event);
        
        if ($contributor->event_id !== $event->id) {
            abort(404);
        }
        
        return view('contributors.edit', compact('event', 'contributor'));
    }

    /**
     * Update contributor details
     */
    public function update(Request $request, Event $event, Contributor $contributor)
    {
        $this->authorizeEventAccess($event);
        
        if ($contributor->event_id !== $event->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'promised_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        
        try {
            $contributor->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? $contributor->email,
                'promised_amount' => $validated['promised_amount'],
                'remaining_amount' => $validated['promised_amount'] - $contributor->paid_amount,
                'notes' => $validated['notes'] ?? $contributor->notes
            ]);
            
            DB::commit();
            
            return redirect()->route('contributors.index', $event)
                ->with('success', 'Maelezo ya mchangiaji yamebadilishwa.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.');
        }
    }

    /**
     * Delete contributor
     */
    public function destroy(Event $event, Contributor $contributor)
    {
        $this->authorizeEventAccess($event);
        
        if ($contributor->event_id !== $event->id) {
            abort(404);
        }
        
        if ($contributor->approvedContributions()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Huwezi kufuta mchangiaji aliyetoa michango iliyothibitishwa.');
        }
        
        DB::beginTransaction();
        
        try {
            $contributor->contributions()->delete();
            $contributor->delete();
            
            DB::commit();
            
            return redirect()->route('contributors.index', $event)
                ->with('success', 'Mchangiaji amefutwa kikamilifu.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Hitilafu imetokea. Tafadhali jaribu tena.');
        }
    }

    /**
     * Private helper methods
     */
    
    private function authorizeEventAccess(Event $event)
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Hujajisajili.');
        }
        
        if (in_array($user->role, ['event_user', 'user', 'admin'])) {
            if ($event->user_id !== $user->id && $user->role !== 'admin') {
                abort(403, 'Huna ruhusa ya kufikia tukio hili.');
            }
        } elseif ($user->role === 'accountant') {
            if (!$user->events()->where('event_id', $event->id)->exists()) {
                abort(403, 'Huna ruhusa ya kufikia tukio hili.');
            }
        } else {
            abort(403, 'Huna ruhusa ya kufikia tukio hili.');
        }
    }
    
    private function sendRegistrationConfirmation(Contributor $contributor)
    {
        $message = "Asante {$contributor->name} kwa kujisajili kwenye tukio la {$contributor->event->event_name}. Mchango wako wa TSh " . number_format($contributor->promised_amount) . " umerekodiwa.";
        $this->sendSMS($contributor->phone, $message);
    }
    
    private function sendApprovalNotification(Contribution $contribution)
    {
        $contributor = $contribution->contributor;
        $message = "Mchango wako wa TSh " . number_format($contribution->amount) . " kwenye tukio la {$contributor->event->event_name} umethibitishwa. Asante kwa mchango wako!";
        $this->sendSMS($contributor->phone, $message);
    }
    
    private function sendCompletionNotification(Contributor $contributor)
    {
        $message = "Hongera! Umekamilisha mchango wako wa TSh " . number_format($contributor->promised_amount) . " kwenye tukio la {$contributor->event->event_name}. Asante sana!";
        $this->sendSMS($contributor->phone, $message);
    }
    
private function notifyAccountants(Event $event, Contribution $contribution)
{
    try {
        // Load the accountants relationship with eager loading
        $event->load('accountants');
        
        if ($event->accountants && $event->accountants->count() > 0) {
            foreach ($event->accountants as $accountant) {
                Log::info('Pending contribution notification sent to accountant', [
                    'accountant_id' => $accountant->id,
                    'accountant_name' => $accountant->name,
                    'contribution_id' => $contribution->id,
                    'amount' => $contribution->amount,
                    'event_id' => $event->id
                ]);
                
                // You can add actual notification logic here:
                // - Send email
                // - Send SMS
                // - Send push notification
            }
            
            Log::info("Notified {$event->accountants->count()} accountant(s) about contribution", [
                'contribution_id' => $contribution->id,
                'event_id' => $event->id
            ]);
        } else {
            // Only log if no accountants are assigned (for debugging)
            Log::info('No accountants assigned to event', [
                'event_id' => $event->id,
                'event_name' => $event->event_name
            ]);
        }
    } catch (\Exception $e) {
        Log::error('Error notifying accountants: ' . $e->getMessage(), [
            'event_id' => $event->id ?? 'unknown',
            'contribution_id' => $contribution->id ?? 'unknown'
        ]);
    }
}

/**
 * Show public registration form for contributors
 */
public function publicRegisterForm(Event $event, $phone = null)
{
    return view('public.contributor-register', compact('event', 'phone'));
}

/**
 * Store contributor from public registration
 */
public function publicStore(Request $request, Event $event)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'promised_amount' => 'required|numeric|min:1000',
    ]);

    // Check if contributor already exists
    $contributor = Contributor::where('event_id', $event->id)
        ->where('phone', $request->phone)
        ->first();

    if ($contributor) {
        // Update existing contributor's promised amount
        $contributor->promised_amount += $request->promised_amount;
        $contributor->remaining_amount = $contributor->promised_amount - $contributor->paid_amount;
        $contributor->save();
        
        $message = "Mchango wako umeongezwa! Jumla ya alichoahidi: " . number_format($contributor->promised_amount) . " TSh";
    } else {
        // Create new contributor
        $contributor = Contributor::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'promised_amount' => $request->promised_amount,
            'paid_amount' => 0,
            'remaining_amount' => $request->promised_amount,
            'status' => 'pending',
        ]);
        
        $message = "Asante {$contributor->name}! Umeahidi TSh " . number_format($contributor->promised_amount) . " kwa tukio la {$event->event_name}.";
    }

    // Optional: Send confirmation SMS
    // $this->sendConfirmationSms($contributor->phone, $message);

    return redirect()->back()->with('success', $message);
}
private function notifyNewRegistration(Event $event, Contributor $contributor)
{
    Log::info('New contributor registered', [
        'event_id' => $event->id,
        'contributor_id' => $contributor->id,
        'contributor_name' => $contributor->name
    ]);
    
    try {
        // Check if accountants relationship exists and is not null
        if ($event && method_exists($event, 'accountants')) {
            $accountants = $event->accountants;
            
            if ($accountants && (is_array($accountants) || is_object($accountants))) {
                foreach ($accountants as $accountant) {
                    Log::info('New contributor notification', [
                        'accountant_id' => $accountant->id ?? 'unknown',
                        'contributor_id' => $contributor->id,
                        'event_id' => $event->id
                    ]);
                }
            }
        }
    } catch (\Exception $e) {
        Log::error('Error notifying accountants about new registration: ' . $e->getMessage());
    }
}
    
    private function sendSMS($phone, $message)
    {
        // Implement SMS sending logic here
        Log::info("SMS to {$phone}: {$message}");
    }
}