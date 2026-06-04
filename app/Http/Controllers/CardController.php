<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    // WhatsApp number from admin settings
    private $adminWhatsApp = '255614356830';

    public function create()
    {
        return view('cards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_type' => 'required|in:invitation,contribution',
            'title' => 'required|string|max:100',
            'groom_name' => 'nullable|string|max:100',
            'bride_name' => 'nullable|string|max:100',
            'honoree_name' => 'nullable|string|max:100',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'required|string|max:500',
            'description' => 'nullable|string',
            'suggested_amount' => 'nullable|numeric|min:0',
            'contact_phone' => 'required|string',
            'contact_email' => 'nullable|email'
        ]);

        $card = Card::create(array_merge($validated, [
            'user_id' => auth()->id(),
            'share_link' => Str::random(32)
        ]));

        // Try to generate card image (optional)
        try {
            $imagePath = $this->generateCardImage($card);
            if ($imagePath) {
                $card->update(['card_image_path' => $imagePath]);
            }
        } catch (\Exception $e) {
            Log::error('Card image generation failed: ' . $e->getMessage());
        }

        // Send to admin WhatsApp
        $this->sendToAdminWhatsApp($card);

        return response()->json([
            'success' => true,
            'message' => 'Kadi imeundwa kikamilifu!',
            'card_id' => $card->id,
            'share_link' => route('cards.view', $card->share_link)
        ]);
    }

    public function send()
    {
        $cards = Card::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('cards.send', compact('cards'));
    }

    public function view($shareLink)
    {
        $card = Card::where('share_link', $shareLink)->firstOrFail();
        $card->increment('views');
        return view('cards.view', compact('card'));
    }

    public function share(Request $request)
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'phone_number' => 'required|string'
        ]);

        $card = Card::findOrFail($request->card_id);
        $card->increment('shares');

        // Send via WhatsApp
        $whatsappMessage = $this->generateWhatsAppMessage($card);

        $phone = $this->cleanPhoneNumber($request->phone_number);
        
        // Send WhatsApp message
        $sent = $this->sendWhatsAppMessage($phone, $whatsappMessage);

        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'Kadi imetumwa kikamilifu kupitia WhatsApp!' : 'Imeshindwa kutuma kadi. Jaribu tena.'
        ]);
    }

    private function generateWhatsAppMessage($card)
    {
        $cardLink = route('cards.view', $card->share_link);
        
        $message = "🎉 *MWALIKO* 🎉\n\n";
        
        if ($card->card_type === 'invitation') {
            $message .= "Habari za wema,\n\n";
            
            if ($card->groom_name && $card->bride_name) {
                $message .= "Tunapenda kukualika kwenye harusi ya ";
                $message .= $card->groom_name . " & " . $card->bride_name . "!\n\n";
            } elseif ($card->honoree_name) {
                $message .= "Tunapenda kukualika kwenye sherehe ya " . $card->honoree_name . "!\n\n";
            }
        } else {
            $message .= "Habari za wema,\n\n";
            $message .= "Tunakukaribisha kuchangia katika tukio letu.\n\n";
        }
        
        $message .= "📅 Tarehe: " . date('d/m/Y', strtotime($card->event_date)) . "\n";
        $message .= "⏰ Saa: " . date('h:i A', strtotime($card->event_time)) . "\n";
        $message .= "📍 Mahali: " . $card->location . "\n\n";
        
        if ($card->card_type === 'contribution' && $card->suggested_amount) {
            $message .= "💰 Kiasho cha Mchango: TSh " . number_format($card->suggested_amount) . "\n\n";
        }
        
        // FIXED: Proper link format without duplication
        $message .= "🔗 Tazama kadi kamili hapa:\n";
        $message .= $cardLink . "\n\n";
        
        $message .= "📞 Kwa maelezo zaidi, wasiliana nasi kwa: " . $card->contact_phone . "\n\n";
        $message .= "Asante kwa ushirikiano wako! 🙏";
        
        return $message;
    }

    private function generateCardImage($card)
    {
        // Check if Intervention Image is available
        if (!class_exists('Intervention\Image\ImageManager')) {
            Log::warning('Intervention Image not installed. Skipping image generation.');
            return null;
        }
        
        try {
            $manager = new \Intervention\Image\ImageManager('gd');
            $img = $manager->create(1080, 1920);
            $img->fill('#ffffff');
            
            $borderColor = $card->card_type === 'invitation' ? '#FF6F00' : '#10B981';
            $img->rectangle(20, 20, 1060, 1900, function ($draw) use ($borderColor) {
                $draw->border(3, $borderColor);
            });
            
            $img->rectangle(0, 0, 1080, 120, function ($draw) use ($borderColor) {
                $draw->fill($borderColor);
            });
            
            $title = $card->card_type === 'invitation' ? 'MWALIKO' : 'OMBI LA MCHANGO';
            $img->text($title, 540, 80, function ($font) {
                $font->size(48);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('center');
                $fontFile = $this->getFontPath();
                if ($fontFile) {
                    $font->file($fontFile);
                }
            });
            
            $eventType = strtoupper($card->title);
            $img->text($eventType, 540, 200, function ($font) use ($borderColor) {
                $font->size(36);
                $font->color($borderColor);
                $font->align('center');
                $fontFile = $this->getFontPath();
                if ($fontFile) {
                    $font->file($fontFile);
                }
            });
            
            $yOffset = 300;
            
            if ($card->card_type === 'invitation') {
                if ($card->groom_name && $card->bride_name) {
                    $names = $card->groom_name . " & " . $card->bride_name;
                } elseif ($card->honoree_name) {
                    $names = $card->honoree_name;
                } else {
                    $names = "Familia yetu";
                }
                
                $img->text($names, 540, $yOffset, function ($font) {
                    $font->size(42);
                    $font->color('#000000');
                    $font->align('center');
                    $fontFile = $this->getFontPath();
                    if ($fontFile) {
                        $font->file($fontFile);
                    }
                });
                $yOffset += 80;
                
                $img->text("wanakuomba kwa heshima na upendo", 540, $yOffset, function ($font) {
                    $font->size(28);
                    $font->color('#666666');
                    $font->align('center');
                    $fontFile = $this->getFontPath();
                    if ($fontFile) {
                        $font->file($fontFile);
                    }
                });
                $yOffset += 60;
                
                $img->text("kuwashuhudia wanapounganishwa", 540, $yOffset, function ($font) {
                    $font->size(28);
                    $font->color('#666666');
                    $font->align('center');
                    $fontFile = $this->getFontPath();
                    if ($fontFile) {
                        $font->file($fontFile);
                    }
                });
            } else {
                $img->text("Habari za wema", 540, $yOffset, function ($font) {
                    $font->size(32);
                    $font->color('#666666');
                    $font->align('center');
                    $fontFile = $this->getFontPath();
                    if ($fontFile) {
                        $font->file($fontFile);
                    }
                });
                $yOffset += 60;
                
                $img->text("Tunakukaribisha kuchangia katika", 540, $yOffset, function ($font) {
                    $font->size(28);
                    $font->color('#666666');
                    $font->align('center');
                    $fontFile = $this->getFontPath();
                    if ($fontFile) {
                        $font->file($fontFile);
                    }
                });
                $yOffset += 60;
                
                if ($card->honoree_name) {
                    $img->text($card->honoree_name, 540, $yOffset, function ($font) {
                        $font->size(40);
                        $font->color('#000000');
                        $font->align('center');
                        $fontFile = $this->getFontPath();
                        if ($fontFile) {
                            $font->file($fontFile);
                        }
                    });
                    $yOffset += 70;
                }
            }
            
            $yOffset += 50;
            
            $boxHeight = 300;
            $img->rectangle(60, $yOffset, 1020, $yOffset + $boxHeight, function ($draw) {
                $draw->fill('#f5f5f5');
                $draw->border(2, '#dddddd');
            });
            
            $detailY = $yOffset + 50;
            
            $img->text("📅 " . date('d F Y', strtotime($card->event_date)), 120, $detailY, function ($font) {
                $font->size(30);
                $font->color('#333333');
                $fontFile = $this->getFontPath();
                if ($fontFile) {
                    $font->file($fontFile);
                }
            });
            $detailY += 60;
            
            $img->text("⏰ " . date('h:i A', strtotime($card->event_time)), 120, $detailY, function ($font) {
                $font->size(30);
                $font->color('#333333');
                $fontFile = $this->getFontPath();
                if ($fontFile) {
                    $font->file($fontFile);
                }
            });
            $detailY += 60;
            
            $locationText = "📍 " . Str::limit($card->location, 50);
            $img->text($locationText, 120, $detailY, function ($font) {
                $font->size(28);
                $font->color('#333333');
                $fontFile = $this->getFontPath();
                if ($fontFile) {
                    $font->file($fontFile);
                }
            });
            
            $yOffset += $boxHeight + 80;
            
            if ($card->card_type === 'contribution' && $card->suggested_amount) {
                $img->text("KIASI CHA MCHANGO", 540, $yOffset, function ($font) use ($borderColor) {
                    $font->size(28);
                    $font->color($borderColor);
                    $font->align('center');
                    $fontFile = $this->getFontPath();
                    if ($fontFile) {
                        $font->file($fontFile);
                    }
                });
                $yOffset += 50;
                
                $img->text("TSh " . number_format($card->suggested_amount), 540, $yOffset, function ($font) {
                    $font->size(48);
                    $font->color('#000000');
                    $font->align('center');
                    $fontFile = $this->getFontPath();
                    if ($fontFile) {
                        $font->file($fontFile);
                    }
                });
                $yOffset += 80;
            }
            
            $contactBoxHeight = 120;
            $img->rectangle(60, $yOffset, 1020, $yOffset + $contactBoxHeight, function ($draw) use ($borderColor) {
                $draw->fill($borderColor . '20');
                $draw->border(2, $borderColor);
            });
            
            $contactY = $yOffset + 35;
            $img->text("📞 " . $card->contact_phone, 120, $contactY, function ($font) {
                $font->size(28);
                $font->color('#333333');
                $fontFile = $this->getFontPath();
                if ($fontFile) {
                    $font->file($fontFile);
                }
            });
            
            if ($card->contact_email) {
                $contactY += 50;
                $img->text("✉️ " . $card->contact_email, 120, $contactY, function ($font) {
                    $font->size(24);
                    $font->color('#333333');
                    $fontFile = $this->getFontPath();
                    if ($fontFile) {
                        $font->file($fontFile);
                    }
                });
            }
            
            $yOffset += $contactBoxHeight + 60;
            
            $img->rectangle(0, 1840, 1080, 1920, function ($draw) use ($borderColor) {
                $draw->fill($borderColor);
            });
            
            $img->text("Kadi imetengenezwa kwa teknolojia ya kisasa", 540, 1880, function ($font) {
                $font->size(20);
                $font->color('#ffffff');
                $font->align('center');
                $fontFile = $this->getFontPath();
                if ($fontFile) {
                    $font->file($fontFile);
                }
            });
            
            $filename = 'cards/card_' . $card->id . '_' . time() . '.png';
            $path = storage_path('app/public/' . $filename);
            
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }
            
            $img->save($path, 90);
            
            return $filename;
        } catch (\Exception $e) {
            Log::error('Card generation error: ' . $e->getMessage());
            return null;
        }
    }

    private function getFontPath()
    {
        $fonts = [
            public_path('fonts/poppins_bold.ttf'),
            public_path('fonts/poppins_regular.ttf'),
            public_path('fonts/arial.ttf'),
            public_path('fonts/OpenSans.ttf'),
            storage_path('fonts/poppins.ttf'),
        ];
        
        foreach ($fonts as $font) {
            if (file_exists($font)) {
                return $font;
            }
        }
        
        return null;
    }

    private function sendToAdminWhatsApp($card)
    {
        $message = $this->generateWhatsAppMessage($card);
        $message = "🆕 *KADI MPYA IMEUNDWA* 🆕\n\n" . $message;
        $this->sendWhatsAppMessage($this->adminWhatsApp, $message);
    }

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

    public function getCardData($id)
    {
        $card = Card::findOrFail($id);
        return response()->json($card);
    }
}