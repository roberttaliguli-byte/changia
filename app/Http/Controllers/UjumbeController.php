<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Contributor;
use App\Models\Contribution;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SMSExport;

class UjumbeController extends Controller
{
    // SMS Templates
    private $smsTemplates = [
        'wedding_invitation' => [
            'name' => 'Mwaliko wa Harusi',
            'template' => "Habari [NAME],\n\nMzee Yusuph na familia yake wanakuomba mchango wako wa hali na mali ili kufanikisha sherehe ya [EVENT_NAME] itakayofanyika tarehe [EVENT_DATE].\n\nAsante kwa ushirikiano wako."
        ],
        'contribution_request' => [
            'name' => 'Ombi la Mchango',
            'template' => "Habari [NAME],\n\nTunakukaribisha kuchangia katika tukio la [EVENT_NAME] linalofanyika [EVENT_DATE].\n\nAsante kwa ushirikiano wako."
        ],
        'reminder' => [
            'name' => 'Ukumbusho wa Malipo',
            'template' => "Kumbukumbu: Bado una deni la TSh [REMAINING] kwa tukio la [EVENT_NAME].\n\nTafadhali maliza deni lako."
        ]
    ];

    /**
     * Display the michango page
     */
    public function michango()
    {
        $user = auth()->user();
        $events = $user->role === 'event_user' 
            ? $user->ownedEvents()->get() 
            : $user->events()->get();
        
        $templates = $this->smsTemplates;
        
        return view('ujumbe.michango', compact('events', 'templates'));
    }

    /**
     * Display the mwaliko page
     */
    public function mwaliko()
    {
        $user = auth()->user();
        $events = $user->role === 'event_user' 
            ? $user->ownedEvents()->get() 
            : $user->events()->get();
        
        $templates = $this->smsTemplates;
        
        return view('ujumbe.mwaliko', compact('events', 'templates'));
    }

    /**
     * Send contribution request messages via SMS
     */
    public function tumaMichango(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'phone_numbers' => 'required|string',
            'message' => 'required|string',
            'names' => 'nullable|string',
            'custom_link' => 'nullable|string' // Optional custom link
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
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
        
        $successCount = 0;
        $failedNumbers = [];
        $sentMessages = [];

        foreach ($phoneNumbers as $index => $phone) {
            // Generate link (custom or auto-generated)
            $link = $customLink;
            if (empty($link)) {
                $link = route('contributor.register.form', [
                    'event' => $event->id, 
                    'phone' => $phone
                ]);
            }
            
            $message = $request->message;
            // Replace placeholders
            $message = str_replace('[NAME]', $names[$index] ?? 'Mpendwa', $message);
            $message = str_replace('[EVENT_NAME]', $event->event_name, $message);
            $message = str_replace('[EVENT_DATE]', $event->event_date->format('d/m/Y'), $message);
            $message = str_replace('[LINK]', $link, $message);
            
            $sent = $this->sendSingleSMS($phone, $message);
            
            if ($sent) {
                $successCount++;
                $sentMessages[] = [
                    'phone' => $phone,
                    'name' => $names[$index] ?? '',
                    'message' => $message,
                    'status' => 'sent',
                    'time' => now()->format('Y-m-d H:i:s')
                ];
            } else {
                $failedNumbers[] = $phone;
            }
            
            usleep(500000); // 0.5 second delay
        }

        session(['last_sms_export' => $sentMessages]);

        return response()->json([
            'success' => true,
            'message' => "Ujumbe {$successCount} umetumwa kikamilifu kwa SMS.",
            'failed' => $failedNumbers,
            'total_sent' => $successCount
        ]);
    }

    /**
     * Send invitation messages via SMS
     */
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
        
        $successCount = 0;
        $failedNumbers = [];
        $sentMessages = [];

        foreach ($phoneNumbers as $index => $phone) {
            // Generate link (custom or auto-generated)
            $link = $customLink;
            if (empty($link)) {
                $link = route('events.show', $event->id);
            }
            
            $message = $request->message;
            // Replace placeholders
            $message = str_replace('[NAME]', $names[$index] ?? 'Mpendwa', $message);
            $message = str_replace('[EVENT_NAME]', $event->event_name, $message);
            $message = str_replace('[EVENT_DATE]', $event->event_date->format('d/m/Y'), $message);
            $message = str_replace('[LINK]', $link, $message);
            
            $sent = $this->sendSingleSMS($phone, $message);
            
            if ($sent) {
                $successCount++;
                $sentMessages[] = [
                    'phone' => $phone,
                    'name' => $names[$index] ?? '',
                    'message' => $message,
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
            'message' => "Ujumbe {$successCount} umetumwa kikamilifu kwa SMS.",
            'failed' => $failedNumbers,
            'total_sent' => $successCount
        ]);
    }

    /**
     * Send single SMS using API V2
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
            
            // V2 API payload format
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
                
                if (isset($responseData['messages'][0]['status']['groupId'])) {
                    $statusGroup = $responseData['messages'][0]['status']['groupName'];
                    Log::info("SMS sent to {$phone} with status: {$statusGroup}");
                    return true;
                }
                
                Log::info("SMS sent to {$phone}", $responseData);
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

    /**
     * Personalize message with name and event details
     */
    private function personalizeMessage($message, $event, $name = null, $phone = null)
    {
        $message = str_replace('[NAME]', $name ?? 'Mpendwa', $message);
        $message = str_replace('[EVENT_NAME]', $event->event_name, $message);
        $message = str_replace('[EVENT_DATE]', $event->event_date->format('d/m/Y'), $message);
        $message = str_replace('[EVENT_TIME]', $event->event_date->format('H:i'), $message);
        $message = str_replace('[PHONE]', $phone ?? '', $message);
        
        return $message;
    }

    /**
     * Download SMS export as Excel
     */
    public function downloadSMSExport()
    {
        $sentMessages = session('last_sms_export', []);
        
        if (empty($sentMessages)) {
            return redirect()->back()->with('error', 'Hakuna ujumbe wa kudownload. Tuma ujumbe kwanza.');
        }
        
        return Excel::download(new SMSExport($sentMessages), 'sms_sent_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * Parse phone numbers from input
     */
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

    /**
     * Parse names from input
     */
    private function parseNames($input)
    {
        $names = preg_split('/[\n\r,;\s]+/', $input);
        return array_filter(array_map('trim', $names));
    }

    /**
     * Clean phone number to international format
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

    /**
     * Get contributors for an event
     */
    public function getEventContributors($eventId)
    {
        $event = Event::findOrFail($eventId);
        $contributors = $event->contributors()
            ->select('id', 'name', 'phone', 'promised_amount', 'paid_amount', 'remaining_amount', 'status')
            ->get();
        
        return response()->json($contributors);
    }

    /**
     * Get event details for preview
     */
    public function getEventDetails($eventId)
    {
        $event = Event::findOrFail($eventId);
        return response()->json([
            'event_name' => $event->event_name,
            'event_date' => $event->event_date->format('d/m/Y')
        ]);
    }
}