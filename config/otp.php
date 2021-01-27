<?php
    return [
        'default' => env('OTP_DEFAULT', 'firebase'),
        'firebase' => [
            'api_key' => env("FIREBASE_API_KEY"),
        ]
    ];
