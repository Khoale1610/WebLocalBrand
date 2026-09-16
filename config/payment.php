<?php

return [
    'vietqr' => [
        'bank_id' => env('VIETQR_BANK_ID', 'MB'),
        'account_no' => env('VIETQR_ACCOUNT_NO', '0987654321'),
        'account_name' => env('VIETQR_ACCOUNT_NAME', 'LOCAL BRAND OFFICIAL'),
    ],
    'vnpay' => [
        'tmn_code' => env('VNP_TMN_CODE', '2QXUI4J4'),
        'hash_secret' => env('VNP_HASH_SECRET', 'RAOCTAV2AWWJLLGPH822WGYXZCJGTGMN'),
        'url' => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNP_RETURN_URL', 'http://127.0.0.1:8000/checkout/vnpay-return'),
    ],
];
