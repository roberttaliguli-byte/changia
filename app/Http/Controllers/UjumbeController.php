<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\SmsUsage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SMSExport;
use Carbon\Carbon;

class UjumbeController extends Controller
{
    private $smsTemplates = [
        'wedding_invitation' => [
            'name' => 'Mwaliko wa Harusi',
            'type' => 'invitation',
            'template' => "Habari [NAME],\n\nMzee Yusuph na familia yake wanakuomba mchango wako wa hali na mali ili kufanikisha sherehe ya [EVENT_NAME] itakayofanyika tarehe [EVENT_DATE].\n\nAsante kwa ushirikiano wako."
        ],
        'contribution_request' => [
            'name' => 'Ombi la Mchango',
            'type' => 'contribution_request',
            'template' => "Habari [NAME],\n\nTunakukaribisha kuchangia katika tukio la [EVENT_NAME] linalofanyika [EVENT_DATE].\n\nAsante kwa ushirikiano wako."
        ],
        'reminder' => [
            'name' => 'Ukumbusho wa Malipo',
            'type' => 'reminder',
            'template' => "Kumbukumbu: Bado una deni la TSh [REMAINING] kwa tukio la [EVENT_NAME].\n\nTafadhali maliza deni lako."
        ]
    ];

    public function index()
    {
        $user = auth()->user();
        
        $usersWithSms = collect();
        $selectedUserSms = collect();
        $events = collect();
        $selectedUserId = null;
        $selectedUser = null;
        $totalSmsSent = 0;
        $totalCost = 0;
        $activeUsers = 0;
        $eventsWithSms = 0;
        
        $roles = [
            'admin' => 'Msimamizi',
            'accountant' => 'Mhasibu',
            'event_user' => 'Mratibu wa Tukio',
            'user' => 'Mtumiaji'
        ];
        
        $messageTypes = [
            'invitation' => 'Mwaliko',
            'contribution_request' => 'Ombi la Mchango',
            'reminder' => 'Ukumbusho'
        ];
        
        $templates = $this->smsTemplates;
        
        if ($user->role === 'admin') {
            $users = User::whereIn('role', ['event_user', 'accountant'])->get();
            foreach ($users as $u) {
                $usersWithSms->push((object)[
                    'user' => $u,
                    'total_sms' => 0,
                    'sms_count' => 0
                ]);
            }
            
            $selectedUserId = request()->get('user_id', $usersWithSms->isNotEmpty() ? $usersWithSms->first()->user->id : null);
            $selectedUser = $selectedUserId ? User::find($selectedUserId) : null;
        } else {
            $usersWithSms->push((object)[
                'user' => $user,
                'total_sms' => 0,
                'sms_count' => 0
            ]);
            $selectedUser = $user;
            $selectedUserId = $user->id;
            
            if ($user->role === 'event_user') {
                $events = $user->ownedEvents()->get();
            } elseif ($user->role === 'accountant') {
                $events = $user->events()->get();
            }
        }
        
        if (Schema::hasTable('sms_usage')) {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            
            $totalSmsSent = SmsUsage::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('sms_count') ?? 0;
            
            $totalCost = SmsUsage::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('cost') ?? 0;
            
            $activeUsers = SmsUsage::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->distinct('user_id')
                ->count('user_id');
            
            $eventsWithSms = SmsUsage::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->whereNotNull('event_id')
                ->distinct('event_id')
                ->count('event_id');
            
            if ($user->role === 'admin') {
                $newUsersWithSms = collect();
                foreach ($users as $u) {
                    $smsHistory = SmsUsage::where('user_id', $u->id)->get();
                    $newUsersWithSms->push((object)[
                        'user' => $u,
                        'total_sms' => $smsHistory->count(),
                        'sms_count' => $smsHistory->sum('sms_count')
                    ]);
                    $usersWithSms = $newUsersWithSms->sortByDesc('sms_count');
                }
                
                if ($selectedUserId) {
                    $selectedUserSms = SmsUsage::with('event')
                        ->where('user_id', $selectedUserId)
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
            } else {
                $smsHistory = SmsUsage::where('user_id', $user->id)->get();
                $usersWithSms = collect([(object)[
                    'user' => $user,
                    'total_sms' => $smsHistory->count(),
                    'sms_count' => $smsHistory->sum('sms_count')
                ]]);
                $selectedUserSms = SmsUsage::with('event')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }
        
        return view('admin.sms', [
            'usersWithSms' => $usersWithSms,
            'selectedUserId' => $selectedUserId,
            'selectedUser' => $selectedUser,
            'selectedUserSms' => $selectedUserSms,
            'totalSmsSent' => $totalSmsSent,
            'totalCost' => $totalCost,
            'activeUsers' => $activeUsers,
            'eventsWithSms' => $eventsWithSms,
            'roles' => $roles,
            'messageTypes' => $messageTypes,
            'events' => $events,
            'templates' => $templates
        ]);
    }

    public function michango()
    {
        $user = auth()->user();
        
        if (!$this->checkSmsQuota($user)) {
            return redirect()->back()->with('error', 'Umefikia kikomo cha SMS za mwezi huu. Wasiliana na msimamizi.');
        }
        
        $events = $user->role === 'event_user' 
            ? $user->ownedEvents()->get() 
            : $user->events()->get();
        
        $templates = $this->smsTemplates;
        $remainingQuota = $user->sms_quota - $user->sms_used_this_month;
        $currentMonth = Carbon::now()->format('F Y');
        
        return view('ujumbe.michango', compact('events', 'templates', 'remainingQuota', 'currentMonth'));
    }

    public function mwaliko()
    {
        $user = auth()->user();
        
        if (!$this->checkSmsQuota($user)) {
            return redirect()->back()->with('error', 'Umefikia kikomo cha SMS za mwezi huu. Wasiliana na msimamizi.');
        }
        
        $events = $user->role === 'event_user' 
            ? $user->ownedEvents()->get() 
            : $user->events()->get();
        
        $templates = $this->smsTemplates;
        $remainingQuota = $user->sms_quota - $user->sms_used_this_month;
        $currentMonth = Carbon::now()->format('F Y');
        
        return view('ujumbe.mwaliko', compact('events', 'templates', 'remainingQuota', 'currentMonth'));
    }

    private function checkSmsQuota($user)
    {
        if ($user->sms_quota_reset_at && Carbon::parse($user->sms_quota_reset_at)->lt(Carbon::now()->startOfMonth())) {
            $user->sms_used_this_month = 0;
            $user->sms_quota_reset_at = Carbon::now();
            $user->save();
        }
        
        return $user->sms_used_this_month < $user->sms_quota;
    }

    private function updateSmsUsage($user, $eventId, $phone, $messageType, $message, $smsCount, $cost)
    {
        $user->sms_used_this_month += $smsCount;
        $user->sms_balance -= $cost;
        $user->save();
        
        return SmsUsage::create([
            'user_id' => $user->id,
            'event_id' => $eventId,
            'recipient_phone' => $phone,
            'message_type' => $messageType,
            'message_length' => strlen($message),
            'sms_count' => $smsCount,
            'cost' => $cost,
            'status' => 'sent',
            'response_data' => null
        ]);
    }

    public function tumaMichango(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'phone_numbers' => 'required|string',
            'message' => 'required|string',
            'names' => 'nullable|string',
            'custom_link' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        
        if (!$this->checkSmsQuota($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Umefikia kikomo cha SMS za mwezi huu. Wasiliana na msimamizi.'
            ], 422);
        }

        $event = Event::findOrFail($request->event_id);
        $phoneNumbers = $this->parsePhoneNumbers($request->phone_numbers);
        $names = $request->names ? $this->parseNames($request->names) : [];
        $customLink = $request->custom_link;
        
        if (empty($phoneNumbers)) {
            return response()->json([
                'success' => false,
                'message' => 'Tafadhali weka angalau namba moja ya simu'
            ], 422);
        }
        
        $totalMessages = count($phoneNumbers);
        if ($user->sms_used_this_month + $totalMessages > $user->sms_quota) {
            $remaining = $user->sms_quota - $user->sms_used_this_month;
            return response()->json([
                'success' => false,
                'message' => "Huna SMS za kutosha. Umebaki na SMS {$remaining} tu kwa mwezi huu."
            ], 422);
        }
        
        $successCount = 0;
        $failedNumbers = [];
        $sentMessages = [];
        $totalCost = 0;
        $totalSmsCount = 0;

        foreach ($phoneNumbers as $index => $phone) {
            $link = $customLink ?: route('contributor.register.form', [
                'event' => $event->id, 
                'phone' => $phone
            ]);
            
            $message = $request->message;
            $message = str_replace('[NAME]', $names[$index] ?? 'Mpendwa', $message);
            $message = str_replace('[EVENT_NAME]', $event->event_name, $message);
            $message = str_replace('[EVENT_DATE]', $event->event_date->format('d/m/Y'), $message);
            $message = str_replace('[LINK]', $link, $message);
            
            // Calculate SMS count (160 chars per SMS)
            $smsCount = ceil(mb_strlen($message) / 160);
            $cost = $smsCount * 16; // 16 TSh per SMS as per API documentation
            
            $sent = $this->sendSingleSMS($phone, $message);
            
            if ($sent) {
                $successCount++;
                $totalSmsCount += $smsCount;
                $totalCost += $cost;
                
                $this->updateSmsUsage($user, $event->id, $phone, 'contribution_request', $message, $smsCount, $cost);
                
                $sentMessages[] = [
                    'phone' => $phone,
                    'name' => $names[$index] ?? '',
                    'message' => $message,
                    'sms_count' => $smsCount,
                    'cost' => $cost,
                    'status' => 'sent',
                    'time' => now()->format('Y-m-d H:i:s')
                ];
            } else {
                $failedNumbers[] = $phone;
            }
            
            usleep(500000);
        }

        session(['last_sms_export' => $sentMessages]);

        return response()->json([
            'success' => true,
            'message' => "Ujumbe {$successCount} umetumwa kikamilifu kwa SMS. (SMS: {$totalSmsCount}, Gharama: TSh {$totalCost})",
            'failed' => $failedNumbers,
            'total_sent' => $successCount
        ]);
    }

    public function tumaMwaliko(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'phone_numbers' => 'required|string',
            'message' => 'required|string',
            'names' => 'nullable|string',
            'custom_link' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        
        if (!$this->checkSmsQuota($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Umefikia kikomo cha SMS za mwezi huu. Wasiliana na msimamizi.'
            ], 422);
        }

        $event = Event::findOrFail($request->event_id);
        $phoneNumbers = $this->parsePhoneNumbers($request->phone_numbers);
        $names = $request->names ? $this->parseNames($request->names) : [];
        $customLink = $request->custom_link;
        
        if (empty($phoneNumbers)) {
            return response()->json([
                'success' => false,
                'message' => 'Tafadhali weka angalau namba moja ya simu'
            ], 422);
        }
        
        $totalMessages = count($phoneNumbers);
        if ($user->sms_used_this_month + $totalMessages > $user->sms_quota) {
            $remaining = $user->sms_quota - $user->sms_used_this_month;
            return response()->json([
                'success' => false,
                'message' => "Huna SMS za kutosha. Umebaki na SMS {$remaining} tu kwa mwezi huu."
            ], 422);
        }
        
        $successCount = 0;
        $failedNumbers = [];
        $sentMessages = [];
        $totalCost = 0;
        $totalSmsCount = 0;

        foreach ($phoneNumbers as $index => $phone) {
            $link = $customLink ?: route('events.show', $event->id);
            
            $message = $request->message;
            $message = str_replace('[NAME]', $names[$index] ?? 'Mpendwa', $message);
            $message = str_replace('[EVENT_NAME]', $event->event_name, $message);
            $message = str_replace('[EVENT_DATE]', $event->event_date->format('d/m/Y'), $message);
            $message = str_replace('[LINK]', $link, $message);
            
            $smsCount = ceil(mb_strlen($message) / 160);
            $cost = $smsCount * 16; // 16 TSh per SMS
            
            $sent = $this->sendSingleSMS($phone, $message);
            
            if ($sent) {
                $successCount++;
                $totalSmsCount += $smsCount;
                $totalCost += $cost;
                
                $this->updateSmsUsage($user, $event->id, $phone, 'invitation', $message, $smsCount, $cost);
                
                $sentMessages[] = [
                    'phone' => $phone,
                    'name' => $names[$index] ?? '',
                    'message' => $message,
                    'sms_count' => $smsCount,
                    'cost' => $cost,
                    'status' => 'sent',
                    'time' => now()->format('Y-m-d H:i:s')
                ];
            } else {
                $failedNumbers[] = $phone;
            }
            
            usleep(500000);
        }

        session(['last_sms_export' => $sentMessages]);

        return response()->json([
            'success' => true,
            'message' => "Ujumbe {$successCount} umetumwa kikamilifu kwa SMS. (SMS: {$totalSmsCount}, Gharama: TSh {$totalCost})",
            'failed' => $failedNumbers,
            'total_sent' => $successCount
        ]);
    }

    /**
     * Send single SMS using Internet SMS API V2
     * Price: 16 TSh per SMS as per API documentation
     */
    private function sendSingleSMS($phone, $message)
    {
        try {
            $apiUrl = config('sms.api_url', 'https://messaging-service.co.tz/api/sms/v2/text/single');
            $token = config('sms.token');
            $senderId = config('sms.sender_id', 'MAUZO SHEET');
            
            if (!$apiUrl || !$token) {
                Log::error('SMS API credentials not configured');
                return false;
            }
            
            $phone = $this->cleanPhoneNumber($phone);
            
            // Internet SMS API V2 payload format
            $payload = [
                'from' => $senderId,
                'to' => $phone,
                'text' => $message,
                'flash' => 0,
                'reference' => 'mauzo_' . time() . '_' . uniqid()
            ];
            
            Log::info('SMS V2 Payload', $payload);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(30)->post($apiUrl, $payload);
            
            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['messages'][0]['status'])) {
                    $status = $responseData['messages'][0]['status'];
                    $statusName = $status['name'] ?? $status['groupName'] ?? 'UNKNOWN';
                    $price = $responseData['messages'][0]['price'] ?? 16;
                    
                    Log::info("SMS sent to {$phone}", [
                        'status' => $statusName,
                        'price' => $price,
                        'messageId' => $responseData['messages'][0]['messageId'] ?? null
                    ]);
                    
                    return true;
                }
                
                return true;
            } else {
                $errorBody = $response->json();
                $errorMessage = $errorBody['message'] ?? $response->body();
                
                Log::error("SMS failed to {$phone}", [
                    'status' => $response->status(),
                    'response' => $errorMessage
                ]);
                
                return false;
            }
        } catch (\Exception $e) {
            Log::error("SMS exception: " . $e->getMessage());
            return false;
        }
    }

    public function updateSmsQuota(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'sms_quota' => 'required|integer|min:0',
            'sms_balance' => 'nullable|numeric|min:0'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = User::findOrFail($userId);
        $user->sms_quota = $request->sms_quota;
        
        if ($request->has('sms_balance')) {
            $user->sms_balance = $request->sms_balance;
        }
        
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => "Quota ya SMS imebadilishwa kwa {$user->name}"
        ]);
    }

    public function resetMonthlySmsUsage()
    {
        User::whereIn('role', ['event_user', 'accountant'])->update([
            'sms_used_this_month' => 0,
            'sms_quota_reset_at' => Carbon::now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Matumizi ya SMS kwa mwezi yamerudishwa kwenye sifuri'
        ]);
    }

    public function downloadSMSExport()
    {
        $sentMessages = session('last_sms_export', []);
        
        if (empty($sentMessages)) {
            return redirect()->back()->with('error', 'Hakuna ujumbe wa kudownload. Tuma ujumbe kwanza.');
        }
        
        return Excel::download(new SMSExport($sentMessages), 'sms_sent_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    private function parsePhoneNumbers($input)
    {
        $numbers = preg_split('/[\n\r,;\s]+/', $input);
        $formatted = [];
        
        foreach ($numbers as $number) {
            $number = trim($number);
            if (empty($number)) continue;
            
            $cleaned = $this->cleanPhoneNumber($number);
            
            if (strlen($cleaned) === 12 && substr($cleaned, 0, 3) === '255') {
                $formatted[] = $cleaned;
            }
        }
        
        return array_unique($formatted);
    }

    private function parseNames($input)
    {
        $names = preg_split('/[\n\r,;\s]+/', $input);
        return array_filter(array_map('trim', $names));
    }

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

    public function getEventContributors($eventId)
    {
        $event = Event::findOrFail($eventId);
        $contributors = $event->contributors()
            ->select('id', 'name', 'phone', 'promised_amount', 'paid_amount', 'remaining_amount', 'status')
            ->get();
        
        return response()->json($contributors);
    }

    public function getEventDetails($eventId)
    {
        $event = Event::findOrFail($eventId);
        return response()->json([
            'event_name' => $event->event_name,
            'event_date' => $event->event_date->format('d/m/Y')
        ]);
    }
}