<?php

// config for Appslanka/LaravelBugFix
return [
    'api_url' => env('LBF_API_URL', 'https://laravelbugfix.com/api/reports'),
    'api_key' => env('LBF_API_KEY', 'your-api-key'),
    'include_request_details' => true,
    'include_server_details' => true,
    'exception_user_identifier_field' => 'user_id', // Default identifier field
    'masked_fields' => [
       'password',
        'credit_card_number',
        'social_security_number',
        'email',
        'phone_number',
        'date',
        'api_key',
        'token',
        'authorization',
        'auth',
        'verification',
        'credit_card',
        'cardToken', 
        'cvv',
        'iban',
        'name',
        'email',
    ],
];
