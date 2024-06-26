<?php

/**
 * Configuration for Appslanka/LaravelBugFix package
 */
return [
    /**
     * API endpoint URL where error reports will be sent.
     * Defaults to 'https://laravelbugfix.com/api/reports' if not provided in environment.
     */
    'api_url' => env('LBF_API_URL', 'https://laravelbugfix.com/api/reports'),

    /**
     * API key used for authentication when sending error reports.
     * Defaults to 'your-api-key' if not provided in environment.
     */
    'api_key' => env('LBF_API_KEY', 'your-api-key'),

    /**
     * Flag indicating whether to include detailed request information in error reports.
     * Set to true to include request details; false to exclude.
     */
    'include_request_details' => true,

    /**
     * Flag indicating whether to include cookies details in error reports.
     * Set to true to include server details; false to exclude.
     */
    'include_cookies' => false,

     /**
     * Flag indicating whether to include sessions details in error reports.
     * Set to true to include server details; false to exclude.
     */
    'include_sessions' => false,

    /**
     * Field name used to identify the authenticated user in error reports.
     * Default identifier field is 'id'. Customize as per application's user identifier field.
     */
    'exception_user_identifier_field' => 'id',

    /**
     * List of fields that should be masked (replaced with asterisks) in error reports.
     * Protects sensitive information such as passwords, credit card numbers, etc.
     */
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

