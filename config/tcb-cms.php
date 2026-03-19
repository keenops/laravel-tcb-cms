<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | TCB CMS API Key
    |--------------------------------------------------------------------------
    |
    | The API key provided by TCB Bank for authenticating requests to the
    | Cash Management System API.
    |
    */
    'api_key' => env('TCB_CMS_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Partner Code
    |--------------------------------------------------------------------------
    |
    | Your unique partner code assigned by TCB Bank. This is used to identify
    | your organization in API requests.
    |
    */
    'partner_code' => env('TCB_CMS_PARTNER_CODE'),

    /*
    |--------------------------------------------------------------------------
    | Profile ID
    |--------------------------------------------------------------------------
    |
    | The profile ID associated with your TCB CMS account.
    |
    */
    'profile_id' => env('TCB_CMS_PROFILE_ID'),

    /*
    |--------------------------------------------------------------------------
    | API Base URLs
    |--------------------------------------------------------------------------
    |
    | The base URLs for the TCB CMS API. The reconciliation endpoint uses a
    | different port (8444) than the main API.
    |
    */
    'base_url' => env('TCB_CMS_BASE_URL', 'https://partners.tcbbank.co.tz'),
    'reconciliation_base_url' => env('TCB_CMS_RECONCILIATION_BASE_URL', 'https://partners.tcbbank.co.tz:8444'),

    /*
    |--------------------------------------------------------------------------
    | IPN (Instant Payment Notification) Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the route for receiving payment notifications from TCB Bank.
    |
    */
    'ipn' => [
        'route' => env('TCB_CMS_IPN_ROUTE', '/tcb-cms/ipn'),
        'middleware' => ['api'],
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Verification
    |--------------------------------------------------------------------------
    |
    | When enabled, IPN callbacks will only be accepted from the specified
    | IP addresses. This adds an extra layer of security.
    |
    */
    'verify_ip' => env('TCB_CMS_VERIFY_IP', false),
    'allowed_ips' => array_filter(explode(',', env('TCB_CMS_ALLOWED_IPS', ''))),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Settings
    |--------------------------------------------------------------------------
    |
    | Configure timeout and retry settings for API requests.
    |
    */
    'timeout' => (int) env('TCB_CMS_TIMEOUT', 30),
    'retry_times' => (int) env('TCB_CMS_RETRY_TIMES', 3),
    'retry_sleep' => (int) env('TCB_CMS_RETRY_SLEEP', 100),

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Enable or disable transaction logging to the database. When enabled,
    | all API requests and responses are logged to the tcb_cms_transactions
    | table for auditing purposes.
    |
    */
    'logging' => [
        'enabled' => env('TCB_CMS_LOGGING_ENABLED', true),
        'table' => 'tcb_cms_transactions',
    ],
];
