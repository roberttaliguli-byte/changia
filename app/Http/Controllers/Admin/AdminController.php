<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Card;
use App\Models\Contributor;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;  // ADD THIS
use Illuminate\Support\Facades\Log;    // ADD THIS
use Illuminate\Support\Str;            // ADD THIS
class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // User Statistics
        $totalUsers = User::count();
        $eventUsers = User::where('role', 'event_user')->count();
        $accountants = User::where('role', 'accountant')->count();
        $admins = User::where('role', 'admin')->count();
        
        // Event Statistics
        $totalEvents = Event::count();
        $activeEvents = Event::where('status', 'active')->count();
        $completedEvents = Event::where('status', 'completed')->count();
        
        // Card Statistics
        $totalCards = Card::count();
        
        // Contribution Statistics
        $totalCollected = Contribution::where('status', 'approved')->sum('amount');
        $pendingContributions = Contribution::where('status', 'pending')->count();
        
        // Recent Data
        $recentUsers = User::latest()->take(5)->get();
        $recentEvents = Event::with('user')->latest()->take(5)->get();
        
        // Monthly Stats for Chart
        $monthlyStats = DB::table('contributions')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN status = "approved" THEN amount ELSE 0 END) as total')
            )
            ->where('status', 'approved')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();
        
        return view('admin.dashboard', compact(
            'totalUsers', 'eventUsers', 'accountants', 'admins',
            'totalEvents', 'activeEvents', 'completedEvents',
            'totalCards', 'totalCollected', 'pendingContributions',
            'recentUsers', 'recentEvents', 'monthlyStats'
        ));
    }

    /**
     * ============================================
     * USER MANAGEMENT
     * ============================================
     */
    
    public function users(Request $request)
    {
        $query = User::query();
        
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $users = $query->latest()->paginate(15);
        $roles = ['admin' => 'Msimamizi', 'accountant' => 'Mhasibu', 'event_user' => 'Mratibu wa Tukio', 'user' => 'Mtumiaji'];
        
        return view('admin.users', compact('users', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'role' => 'required|in:admin,accountant,event_user,user',
            'password' => 'required|min:5|confirmed',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        
        return redirect()->route('admin.users')->with('success', 'Mtumiaji ameundwa kikamilifu.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $id,
            'role' => 'required|in:admin,accountant,event_user,user',
        ]);
        
        $user->update($request->only('name', 'email', 'phone', 'role'));
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:5|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }
        
        return redirect()->route('admin.users')->with('success', 'Mtumiaji amebadilishwa kikamilifu.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Huwezi kujifuta mwenyewe.');
        }
        
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Mtumiaji amefutwa kikamilifu.');
    }

    public function editUserAjax($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'role' => $user->role
        ]);
    }

    public function downloadUsers(Request $request)
    {
        $query = User::query();
        
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $users = $query->latest()->get();
        
        $roles = [
            'admin' => 'Msimamizi',
            'accountant' => 'Mhasibu',
            'event_user' => 'Mratibu wa Tukio',
            'user' => 'Mtumiaji'
        ];
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="watumiaji_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($users, $roles) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'ID', 'Jina Kamili', 'Namba ya Simu', 'Barua Pepe',
                'Jukumu', 'Idadi ya Matukio', 'Tarehe ya Kujiunga', 'Status'
            ]);
            
            foreach ($users as $user) {
                $eventCount = 0;
                if ($user->role == 'event_user') {
                    $eventCount = $user->ownedEvents()->count();
                } elseif ($user->role == 'accountant') {
                    $eventCount = $user->events()->count();
                }
                
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->phone ?? '-',
                    $user->email ?? '-',
                    $roles[$user->role] ?? $user->role,
                    $eventCount,
                    $user->created_at->format('d/m/Y H:i'),
                    $user->email_verified_at ? 'Imethibitishwa' : 'Haijathibitishwa'
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * ============================================
     * EVENT MANAGEMENT
     * ============================================
     */
    
    public function events(Request $request)
    {
        $query = Event::with('user');
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where('event_name', 'like', "%{$request->search}%");
        }
        
        $events = $query->latest()->paginate(15);
        
        return view('admin.events', compact('events'));
    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        
        return redirect()->route('admin.events')->with('success', 'Tukio limefutwa kikamilifu.');
    }

    public function downloadEvents(Request $request)
    {
        $query = Event::with('user');
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where('event_name', 'like', "%{$request->search}%");
        }
        
        $events = $query->latest()->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="matukio_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($events) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'ID', 'Jina la Tukio', 'Aliyesajili', 'Namba ya Simu',
                'Jukumu', 'Tarehe ya Tukio', 'Hali', 'Tarehe ya Usajili'
            ]);
            
            foreach ($events as $event) {
                fputcsv($file, [
                    $event->id,
                    $event->event_name,
                    $event->user->name ?? '-',
                    $event->user->phone ?? '-',
                    $event->user->role_display ?? '-',
                    $event->event_date ? $event->event_date->format('d/m/Y') : '-',
                    $event->status == 'active' ? 'Inaendelea' : ($event->status == 'completed' ? 'Imekamilika' : 'Imefutwa'),
                    $event->created_at->format('d/m/Y H:i')
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }


    
    public function smsManagement()
    {
        $users = User::whereIn('role', ['event_user', 'user'])->get();
        $events = Event::all();
        $templates = [
            'general' => 'Habari [NAME], ...',
            'event_reminder' => 'Kumbukumbu: Tukio la [EVENT_NAME] litafanyika [EVENT_DATE]...',
            'contribution' => 'Asante kwa mchango wako wa [AMOUNT] TSh...'
        ];
        
        return view('admin.sms', compact('users', 'events', 'templates'));
    }

    public function sendSms(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required',
            'message' => 'required|string|min:5',
        ]);
        
        // SMS sending logic here
        return redirect()->route('admin.sms')->with('success', 'Ujumbe umetumwa kikamilifu.');
    }

    /**
     * ============================================
     * REPORTS
     * ============================================
     */
    
    public function reports()
    {
        $monthlyStats = DB::table('contributions')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'approved')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
        
        $roleDistribution = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get();
        
        $eventStats = Event::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        return view('admin.reports', compact('monthlyStats', 'roleDistribution', 'eventStats'));
    }

    public function eventsReport()
    {
        $events = Event::with(['user', 'contributors'])
            ->withCount('contributors')
            ->withSum('contributions as total_collected', 'amount')
            ->latest()
            ->paginate(20);
        
        $totalEvents = Event::count();
        $totalCollected = Contribution::where('status', 'approved')->sum('amount');
        $totalPromised = Event::with('contributors')->get()->sum(function($e) {
            return $e->contributors->sum('promised_amount');
        });
        
        $eventStats = Event::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        $monthlyEvents = Event::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
        
        return view('admin.reportevents', compact(
            'events', 'totalEvents', 'totalCollected', 'totalPromised',
            'eventStats', 'monthlyEvents'
        ));
    }

    /**
     * ============================================
     * PROFILE & SETTINGS
     * ============================================
     */
    
    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
        ]);
        
        $user->update($request->only('name', 'email', 'phone'));
        
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:5|confirmed',
                'current_password' => 'required|current_password',
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }
        
        return redirect()->route('admin.profile')->with('success', 'Profaili imebadilishwa kikamilifu.');
    }

    public function settings()
    {
        $settings = [
            'site_name' => config('app.name', 'Changia Smart'),
            'site_description' => config('app.description', 'Mfumo wa Usimamizi wa Michango'),
            'timezone' => config('app.timezone', 'Africa/Dar_es_Salaam'),
            'currency' => 'TZS',
            'enable_sms' => true,
            'email_notifications' => true,
        ];
        
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'timezone' => 'required|string',
            'enable_sms' => 'boolean',
            'email_notifications' => 'boolean',
        ]);
        
        return redirect()->route('admin.settings')->with('success', 'Mipangilio imehifadhiwa kikamilifu.');
    }
    /**
 * Card Management - List all card requests
 */
public function cards(Request $request)
{
    $query = Card::with('user');
    
    if ($request->has('card_type') && $request->card_type != '') {
        $query->where('card_type', $request->card_type);
    }
    
    if ($request->has('admin_status') && $request->admin_status != '') {
        $query->where('admin_status', $request->admin_status);
    }
    
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhereHas('user', function($uq) use ($search) {
                  $uq->where('name', 'like', "%{$search}%")
                     ->orWhere('phone', 'like', "%{$search}%");
              });
        });
    }
    
    $cards = $query->latest()->paginate(15);
    
    return view('admin.cards', compact('cards'));
}

/**
 * Get card details for modal
 */
public function getCardDetails($id)
{
    $card = Card::with('user')->findOrFail($id);
    
    // Generate correct image URL
    $imageUrl = null;
    if ($card->design_file_path && file_exists(storage_path('app/public/' . $card->design_file_path))) {
        $imageUrl = asset('storage/' . $card->design_file_path);
    }
    
    return response()->json([
        'id' => $card->id,
        'title' => $card->title,
        'card_type' => $card->card_type,
        'groom_name' => $card->groom_name,
        'bride_name' => $card->bride_name,
        'honoree_name' => $card->honoree_name,
        'event_date' => $card->event_date ? $card->event_date->format('d/m/Y') : '-',
        'event_time' => $card->event_time ? date('h:i A', strtotime($card->event_time)) : '-',
        'location' => $card->location,
        'description' => $card->description,
        'suggested_amount' => $card->suggested_amount,
        'contact_phone' => $card->contact_phone,
        'contact_email' => $card->contact_email,
        'views' => $card->views,
        'shares' => $card->shares,
        'admin_status' => $card->admin_status,
        'admin_notes' => $card->admin_notes,
        'design_file_path' => $card->design_file_path,
        'design_file_url' => $imageUrl,  // Add this for easy access
        'design_cost' => $card->design_cost,
        'created_at' => $card->created_at->format('d/m/Y H:i'),
        'user' => [
            'name' => $card->user->name,
            'phone' => $card->user->phone,
            'email' => $card->user->email
        ]
    ]);
}

/**
 * Process card request (approve/reject/complete)
 */
public function processCard(Request $request, $id)
{
    $card = Card::findOrFail($id);
    
    $request->validate([
        'admin_status' => 'required|in:pending,approved,rejected,completed'
    ]);
    
    $card->admin_status = $request->admin_status;
    $card->admin_notes = $request->admin_notes;
    $card->admin_processed_at = now();
    $card->processed_by = auth()->id();
    
    if ($request->has('design_cost') && $request->design_cost) {
        $card->design_cost = $request->design_cost;
    }
    
    // Handle file upload correctly
    if ($request->hasFile('design_file')) {
        $file = $request->file('design_file');
        
        // Check if file is valid
        if ($file->isValid()) {
            // Generate unique filename
            $filename = 'card_' . $card->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store the file
            $path = $file->storeAs('cards/designs', $filename, 'public');
            
            if ($path) {
                // Save the relative path
                $card->design_file_path = 'cards/designs/' . $filename;
                
                // Log success
                \Log::info('File uploaded successfully: ' . $path);
                \Log::info('Full storage path: ' . storage_path('app/public/' . $card->design_file_path));
            } else {
                \Log::error('Failed to store file');
            }
        } else {
            \Log::error('Uploaded file is not valid');
        }
    } else {
        \Log::info('No file was uploaded');
    }
    
    $card->save();
    
    // Notify card owner
    $this->notifyCardOwner($card);
    
    return redirect()->route('admin.cards')->with('success', 'Ombi la kadi limebadilishwa kikamilifu.');
}

/**
 * Delete card
 */
public function deleteCard($id)
{
    $card = Card::findOrFail($id);
    $card->delete();
    
    return redirect()->route('admin.cards')->with('success', 'Kadi imefutwa kikamilifu.');
}

/**
 * Download cards as CSV
 */
public function downloadCards(Request $request)
{
    $query = Card::with('user');
    
    if ($request->has('card_type') && $request->card_type != '') {
        $query->where('card_type', $request->card_type);
    }
    
    if ($request->has('admin_status') && $request->admin_status != '') {
        $query->where('admin_status', $request->admin_status);
    }
    
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhereHas('user', function($uq) use ($search) {
                  $uq->where('name', 'like', "%{$search}%");
              });
        });
    }
    
    $cards = $query->latest()->get();
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="maombi_kadi_' . date('Y-m-d_His') . '.csv"',
    ];
    
    $callback = function() use ($cards) {
        $file = fopen('php://output', 'w');
        fputs($file, "\xEF\xBB\xBF");
        
        fputcsv($file, [
            'ID', 'Jina la Kadi', 'Aina', 'Mwombaji', 'Simu ya Mwombaji',
            'Tarehe ya Tukio', 'Mahali', 'Hali', 'Tarehe ya Ombi'
        ]);
        
        foreach ($cards as $card) {
            fputcsv($file, [
                $card->id,
                $card->title,
                $card->card_type == 'invitation' ? 'Mwaliko' : 'Mchango',
                $card->user->name ?? '-',
                $card->user->phone ?? '-',
                $card->event_date ? $card->event_date->format('d/m/Y') : '-',
                $card->location,
                $card->admin_status,
                $card->created_at->format('d/m/Y H:i')
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

/**
 * Notify card owner about status change
 */
private function notifyCardOwner($card)
{
    try {
        $statusMessages = [
            'approved' => 'Ombi lako la kadi limeidhinishwa. Tunasubiri kubuniwa.',
            'rejected' => 'Ombi lako la kadi limekataliwa. Sababu: ' . ($card->admin_notes ?? 'Hakuna sababu iliyotolewa'),
            'completed' => 'Kadi yako imekamilika. Unaweza kuitumia kwa kushiriki kiungo.'
        ];
        
        $message = $statusMessages[$card->admin_status] ?? 'Hali ya ombi lako la kadi imebadilika.';
        
        // Send WhatsApp message to card owner
        $this->sendWhatsAppMessage($card->user->phone, "CHANGIA SMART: " . $message);
        
    } catch (\Exception $e) {
        Log::error('Failed to notify card owner: ' . $e->getMessage());
    }
}

/**
 * Send WhatsApp message
 */
private function sendWhatsAppMessage($phone, $message)
{
    try {
        $apiUrl = env('WHATSAPP_API_URL', 'https://messaging-service.co.tz/api/whatsapp/v2/text/single');
        $token = env('WHATSAPP_TOKEN');
        
        if (!$apiUrl || !$token) {
            Log::info("WhatsApp message to {$phone}: " . $message);
            return true;
        }
        
        $phone = $this->cleanPhoneNumber($phone);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'recipient' => $phone,
            'message' => $message,
            'account' => env('WHATSAPP_ACCOUNT', 'BST CEO')
        ]);
        
        return $response->successful();
    } catch (\Exception $e) {
        Log::error('WhatsApp send error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Clean phone number
 */
private function cleanPhoneNumber($phone)
{
    $phone = preg_replace('/[^0-9+]/', '', $phone);
    $phone = ltrim($phone, '+');
    
    if (substr($phone, 0, 1) === '0') {
        $phone = '255' . substr($phone, 1);
    }
    
    if (strlen($phone) === 9) {
        $phone = '255' . $phone;
    }
    
    return $phone;
}

}