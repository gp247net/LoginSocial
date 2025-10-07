<?php

namespace App\GP247\Plugins\LoginSocial\Helpers;

use GP247\Core\Models\AdminConfig;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class ProviderConfig
{
    /**
     * Get provider configuration directly from database
     *
     * @param string $provider
     * @return array
     */
    public static function getProviderConfig($provider)
    {
        try {
            $enabled = gp247_config($provider . '_enabled', GP247_STORE_ID_GLOBAL, '0');
            $clientId = gp247_config($provider . '_client_id', GP247_STORE_ID_GLOBAL, '');
            $clientSecret = gp247_config($provider . '_client_secret', GP247_STORE_ID_GLOBAL, '');
            $redirect = gp247_config($provider . '_redirect', GP247_STORE_ID_GLOBAL, url('auth/social/'.$provider.'/callback'));

            return [
                'enabled' => (bool)$enabled,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect' => $redirect,
            ];

        } catch (\Exception $e) {
            gp247_report("LoginSocial: Failed to get config for provider {$provider}: " . $e->getMessage());
            return self::getDefaultConfig($provider);
        }
    }

    /**
     * Get default configuration for provider
     *
     * @param string $provider
     * @return array
     */
    public static function getDefaultConfig($provider)
    {
        return [
            'enabled' => false,
            'client_id' => '',
            'client_secret' => '',
            'redirect' => url('auth/social/'.$provider.'/callback'),
        ];
    }

    /**
     * Check if provider is enabled
     *
     * @param string $provider
     * @return bool
     */
    public static function isProviderEnabled($provider)
    {
        $config = self::getProviderConfig($provider);
        return $config['enabled'];
    }

    /**
     * Get all providers configuration
     *
     * @return array
     */
    public static function getAllProvidersConfig()
    {
        $providers = ['facebook', 'google', 'github'];
        $configs = [];

        foreach ($providers as $provider) {
            $configs[$provider] = self::getProviderConfig($provider);
        }

        return $configs;
    }

    /**
     * Check database health
     *
     * @return array
     */
    public static function checkDatabaseHealth()
    {
        $health = [
            'database_connected' => false,
            'config_available' => false,
            'error_message' => null,
        ];

        try {
            // Check database connection
            \DB::connection()->getPdo();
            $health['database_connected'] = true;

            // Check if gp247_config helper works
            try {
                // Test gp247_config helper
                $testValue = gp247_config('test_key', GP247_STORE_ID_GLOBAL, 'default');
                $health['config_available'] = true;
            } catch (\Exception $e) {
                $health['error_message'] = 'gp247_config helper not working: ' . $e->getMessage();
                gp247_report('LoginSocial: gp247_config helper failed: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            $health['error_message'] = 'Database connection failed: ' . $e->getMessage();
            gp247_report('LoginSocial: Database connection failed: ' . $e->getMessage());
        }

        return $health;
    }
}
