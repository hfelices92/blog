<?php

return [

    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
    ],

    // Necesario para compatibilidad
    'cloud_url' => env('CLOUDINARY_URL'),
    'url'       => env('CLOUDINARY_URL'),

    // Campos opcionales
    'notification_url' => null,
    'upload_preset' => null,
    'upload_route' => null,
    'upload_action' => null,
];
