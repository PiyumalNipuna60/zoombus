<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'nexmo' => [
        'key' => env('NEXMO_KEY'),
        'secret' => env('NEXMO_SECRET'),
        'sms_from' => 'Zoombus',
    ],

    'google-recaptcha' => [
        'secret' => '6LcstsMUAAAAAOJ2EtGxmc4BWIkLpqtCrRA-XmdR',
        'site' => '6LcstsMUAAAAAGp_J4ZaetoDdTUjiRK3WgVY9Px7'
    ],

    'google-maps' => [
        'project_id' => 'metal-filament-282009',
        'key' => 'AIzaSyApA4SHaQl3plrKGyPNuaRailDgq3k034g',
    ],

    'paypal' => [
        'client' => 'ATDFY67EXkNIzGq23zZMfZuREnS8vuFuY-SYmIZ4Vfj8f3ZwutQ49u16sJReSXpBiQuZn5Kk-iZ68Ft2',
        'secret' => 'EDPpPk4pu9ZBz0MJHR7b1zJCiCXax1vF3nw25pCXlssAKH7_DZg1ahcxz6sRYvgPRdiDcAI4fKaNdRDU',
        'sandbox_client' => 'AaQK6DXJmPwO_ZfAzAB-e0Z4z2Hgg-8zVqF6zBJTtDewwAREmcQHM4Tncg8buDhwQHdiBUp3QidjcDxn',
        'sandbox_secret' => 'EAPRUzPEHm9_U-ypwvfGABNClB0OcrY5agEP7maJA1XsPBsI0IZhJkXyv3YHz7eQupm2j7-BZko3BRpt'
    ],



];
