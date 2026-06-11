<?php

return [
    'client_id' => env('EVE_CLIENT_ID'),
    'client_secret' => env('EVE_CLIENT_SECRET'),
    'redirect_uri' => env('APP_URL') . '/auth/callback',
    'scopes' => env('EVE_SCOPES'),
];
