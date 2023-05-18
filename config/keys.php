<?php

return [

    'paystack' => [

        'api_public_key' => "pk_test_1caece5c1d599c3d894bd3e95e9348fe31d90d07",

        'api_private_key' => "sk_test_97aaa120f67e8461b0173ff0e8db89ee833b8aea",
        
    ],

    'tetfund' => [
        'server_api_url' => env('TETFUND_URL', "http://127.0.0.1:8080/api"),
        'server_api_user' => env('TETFUND_API_USER', "testuser@tetfund.gov.ng"),
        'server_api_pwd' => env('TETFUND_API_PWD', "password"),
    ]

];
