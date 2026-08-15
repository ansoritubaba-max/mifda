<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // TAMBAHAN: Integrasi Absensi <-> Mifda. 'token' dipakai buat 2 arah:
    // (1) memverifikasi request masuk dari Absensi ke endpoint kita (lihat
    // VerifyIntegrationToken middleware), dan (2) dikirim sebagai Bearer
    // token pas kita manggil API Absensi (endpoint verifikasi kode). Nilai
    // token harus PERSIS SAMA dengan INTEGRATION_API_TOKEN di .env
    // aplikasi Absensi.
    'integration' => [
        'token' => env('INTEGRATION_API_TOKEN'),
        'absensi_base_url' => env('ABSENSI_API_BASE_URL'),
    ],

];
