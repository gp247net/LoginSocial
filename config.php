<?php

return [
    // Available guards for social login
    'guards' => [
        'admin' => [
            'model' => '\App\Models\User', // Change to your admin model
            'redirect_after_login' => 'admin.home',
            'table' => 'users',
        ],
        'customer' => [
            'model' => '\GP247\Shop\Models\Customer',
            'redirect_after_login' => 'front.home',
            'table' => 'shop_customer',
        ],
        'vendor' => [
            'model' => '\App\GP247\Plugins\MultiVendorPro\Models\VendorUser',
            'redirect_after_login' => 'vendor_admin.home',
            'table' => 'vendor_users',
        ],
        'pmo' => [
            'model' => '\App\GP247\Plugins\PmoPartner\Models\PmoPartnerUser', // Change to your PMO model
            'redirect_after_login' => 'partner.home',
            'table' => 'pmo_partner_user',
        ],
    ],

    // Social providers configuration - Loaded directly from database
    // No need to define providers here as they are loaded from database when needed

    // Default guard when not specified
    'default_guard' => 'customer',
];