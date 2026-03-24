<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Contributor;
use App\Models\Contribution;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UjumbeController extends Controller
{
    /**
     * Display the michango (contribution requests) page
     */
    public function michango()
    {
        $user = auth()->user();
        $events = $user->role === 'event_user' 
            ? $user->ownedEvents()->get() 
            : $user->events()->get();
        
        return view('ujumbe.michango', compact('events'));
    }

    /**
     * Display the mwaliko (invitation) page
     */
    public function mwaliko()
    {
        $user = auth()->user();
        $events = $user->role === 'event_user' 
            ? $user->ownedEvents()->get() 
            : $user->events()->get();
        
        return view('ujumbe.mwaliko', compact('events'));
    }

    /**
     * Send contribution request messages
     */
    public function tumaMichango(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'phone_numbers' => 'required|string',
            'message' => 'nullable|string'
        ]);

        $event = Event::findOrFail($request->event_id);
        $phoneNumbers = $this->parsePhoneNumbers($request->phone_numbers);
        
        $successCount = 0;
        $failedNumbers = [];

        foreach ($phoneNumbers as $phone) {
            $link = route('contributor.register.form', ['event' => $event->id, 'phone' => $phone]);
            
            $message = $request->message ?: "Habari, tunakualika kuchangia katika tukio la {$event->event_name}. Tafadhali bonyeza link hii kuahidi mchango wako: {$link}";
            
            $sent = $this->sendSms($phone, $message);
            
            if ($sent) {
                $successCount++;
            } else {
                $failedNumbers[] = $phone;
            }
        }

        if ($successCount > 0) {
            return redirect()->back()->with('success', "Ujumbe {$successCount} umetumwa kikamilifu.");
        } else {
            return redirect()->back()->with('error', "Ujumbe haukutumwa. Tafadhali jaribu tena.");
        }
    }

    /**
     * Send invitation messages
     */
    public function tumaMwaliko(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'phone_numbers' => 'required|string',
            'message' => 'nullable|string'
        ]);

        $event = Event::findOrFail($request->event_id);
        $phoneNumbers = $this->parsePhoneNumbers($request->phone_numbers);
        
        $successCount = 0;
        $failedNumbers = [];

        foreach ($phoneNumbers as $phone) {
            $inviteLink = route('events.show', $event->id);
            
            $message = $request->message ?: "Karibu katika tukio la {$event->event_name}! Tarehe: {$event->event_date->format('d/m/Y')}. Kwa maelezo zaidi, tembelea: {$inviteLink}";
            
            $sent = $this->sendSms($phone, $message);
            
            if ($sent) {
                $successCount++;
            } else {
                $failedNumbers[] = $phone;
            }
        }

        if ($successCount > 0) {
            return redirect()->back()->with('success', "Ujumbe {$successCount} umetumwa kikamilifu.");
        } else {
            return redirect()->back()->with('error', "Ujumbe haukutumwa. Tafadhali jaribu tena.");
        }
    }

    /**
     * Send reminder to all contributors
     */
    public function sendReminder(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($request->event_id);
        $contributors = $event->contributors()->where('remaining_amount', '>', 0)->get();
        
        $successCount = 0;
        $failedNumbers = [];

        foreach ($contributors as $contributor) {
            $remaining = number_format($contributor->remaining_amount);
            $link = route('contributor.payment.form', ['contributor' => $contributor->id]);
            
            $message = "Kumbukumbu: Bado una deni la TSh {$remaining} kwa tukio la {$event->event_name}. Tafadhali lipa kupitia: {$link}";
            
            $sent = $this->sendSms($contributor->phone, $message);
            
            if ($sent) {
                $successCount++;
            } else {
                $failedNumbers[] = $contributor->phone;
            }
        }

        if ($successCount > 0) {
            return redirect()->back()->with('success', "Vikumbusho {$successCount} vimetumwa kikamilifu.");
        } else {
            return redirect()->back()->with('error', "Vikumbusho havikutumwa. Tafadhali jaribu tena.");
        }
    }

    /**
     * Parse phone numbers from textarea input
     */
    private function parsePhoneNumbers($input)
    {
        // Split by new lines, commas, spaces, or semicolons
        $numbers = preg_split('/[\n\r,;\s]+/', $input);
        
        // Clean and format phone numbers
        $formatted = [];
        foreach ($numbers as $number) {
            $number = trim($number);
            if (empty($number)) continue;
            
            // Format to Tanzanian format (starting with 255)
            if (substr($number, 0, 1) === '0') {
                $number = '255' . substr($number, 1);
            } elseif (substr($number, 0, 3) !== '255' && strlen($number) === 9) {
                $number = '255' . $number;
            }
            
            $formatted[] = $number;
        }
        
        return array_unique($formatted);
    }

    /**
     * Send SMS (placeholder - integrate with actual SMS service)
     */
    private function sendSms($phone, $message)
    {
        // TODO: Integrate with actual SMS service (e.g., Twilio, Africa's Talking, etc.)
        // For now, log the message
        Log::info("SMS to {$phone}: {$message}");
        
        // Simulate success for demonstration
        return true;
    }
}