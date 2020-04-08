<?php

return [
    'username' => env('TWILIO_USERNAME'), // optional when using auth token
    'password' => env('TWILIO_PASSWORD'), // optional when using auth token
    'auth_token' => env('TWILIO_AUTH_TOKEN'), // optional when using username and password
    'account_sid' => env('TWILIO_ACCOUNT_SID'),

    'from' => env('TWILIO_FROM'), // optional
    'alphanumeric_sender' => null,

    'ignored_error_codes' => [
        21608,
        21211,
        21614,
        21408,
    ],
];
