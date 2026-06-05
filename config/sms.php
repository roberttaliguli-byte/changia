<?php

return [
    'api_url' => env('SMS_API_URL', 'https://messaging-service.co.tz/api/sms/v2/text/single'),
    'token' => env('SMS_TOKEN'),
    'sender_id' => env('SMS_SENDER_ID', 'MAUZO SHEET'),
    'cost_per_sms' => env('SMS_COST_PER_SMS', 16),
];