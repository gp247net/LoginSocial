<?php

return [
    // NOTE: The guard must exist in config('auth.guards')
    // Available guards for social login
    'guards' => [
        'admin' => [
            'model' => '\GP247\Core\Models\AdminUser', // Change to your admin model
            'redirect_after_login' => 'admin.home',
            'table' => 'users',
            'enabled' => 0, // Plugin will not load if this is 0
            'status_default' => 0, // Default status for new users
        ],
        'customer' => [
            'model' => '\GP247\Shop\Models\ShopCustomer',
            'redirect_after_login' => 'front.home',
            'table' => 'shop_customer',
            'enabled' => 1, // Plugin will not load if this is 0
            'status_default' => 1, // Default status for new users
        ],
        'vendor' => [
            'model' => '\App\GP247\Plugins\MultiVendorPro\Models\VendorUser',
            'redirect_after_login' => 'vendor_admin.home',
            'table' => 'vendor_users',
            'enabled' => 0, // Plugin will not load if this is 0
            'status_default' => 0, // Default status for new users
        ],
        'pmo' => [
            'model' => '\App\GP247\Plugins\PmoPartner\Models\PmoPartnerUser', // Change to your PMO model
            'redirect_after_login' => 'partner.home',
            'table' => 'pmo_partner_user',
            'enabled' => 0, // Plugin will not load if this is 0
            'status_default' => 0, // Default status for new users
        ],
    ],

    // Social providers configuration - Loaded directly from database
    // No need to define providers here as they are loaded from database when needed

    // Default guard when not specified
    'default_guard' => 'customer',
];