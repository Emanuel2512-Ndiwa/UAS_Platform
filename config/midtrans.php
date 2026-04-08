<?php

return [
    'server_key'    => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-DUMMY_KEY_SANDBOX'),
    'client_key'    => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-DUMMY_KEY_SANDBOX'),
    'merchant_id'   => env('MIDTRANS_MERCHANT_ID', 'DUMMY_MERCHANT'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
];
