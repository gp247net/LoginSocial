<?php
#App\GP247\Plugins\LoginSocial\Admin\AdminController.php

namespace App\GP247\Plugins\LoginSocial\Admin;

use GP247\Core\Controllers\RootAdminController;
use App\GP247\Plugins\LoginSocial\AppConfig;
use GP247\Core\Models\AdminConfig;
use Illuminate\Http\Request;

class AdminController extends RootAdminController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig;
    }

    /**
     * Display admin configuration page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Get current configuration directly from database
            $providers = ['facebook', 'google', 'github'];
            $guards = array_keys(config('Plugins/LoginSocial.guards'));
            
            $configs = [];
            foreach ($providers as $provider) {
                try {
                    $configs[$provider] = [
                        'enabled' => gp247_config($provider . '_enabled', GP247_STORE_ID_GLOBAL, '0'),
                        'client_id' => gp247_config($provider . '_client_id', GP247_STORE_ID_GLOBAL, ''),
                        'client_secret' => gp247_config($provider . '_client_secret', GP247_STORE_ID_GLOBAL, ''),
                        'redirect' => gp247_config($provider . '_redirect', GP247_STORE_ID_GLOBAL, ''),
                    ];
                } catch (\Exception $e) {
                    gp247_report("LoginSocial: Failed to load config for provider {$provider}: " . $e->getMessage());
                    $configs[$provider] = [
                        'enabled' => '0',
                        'client_id' => '',
                        'client_secret' => '',
                        'redirect' => '',
                    ];
                }
            }

            return view($this->plugin->appPath.'::Admin', [
                'title' => gp247_language_render('Plugins/LoginSocial::lang.admin.title'),
                'providers' => $providers,
                'guards' => $guards,
                'configs' => $configs,
            ]);
            
        } catch (\Exception $e) {
            gp247_report('LoginSocial: Database connection failed in admin controller: ' . $e->getMessage());
            
            $providers = ['facebook', 'google', 'github'];
            $configs = $this->getDefaultConfigs();
            
            return view($this->plugin->appPath.'::Admin', [
                'title' => gp247_language_render('Plugins/LoginSocial::lang.admin.title'),
                'providers' => $providers,
                'guards' => array_keys(config('Plugins/LoginSocial.guards')),
                'configs' => $configs,
                'error' => 'Database connection failed: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Get default configuration for all providers
     *
     * @return array
     */
    private function getDefaultConfigs()
    {
        $configs = [];
        $providers = ['facebook', 'google', 'github'];
        foreach ($providers as $provider) {
            $configs[$provider] = [
                'enabled' => '0',
                'client_id' => '',
                'client_secret' => '',
                'redirect' => '',
            ];
        }
        return $configs;
    }

    /**
     * Save configuration to database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $data = $request->all();
        try {
            // Process each provider configuration
            $savedCount = 0;
            $errorCount = 0;
            
            // Process all provider configurations at once
            $providers = ['facebook', 'google', 'github'];
            foreach ($providers as $provider) {
                try {
                    // Update enabled status
                    $enabled = $data[$provider . '_enabled'] ?? '0';
                    AdminConfig::updateOrCreate(
                        [
                            'code' => 'LoginSocial_config',
                            'key' => $provider . '_enabled',
                            'store_id' => GP247_STORE_ID_GLOBAL,
                        ],
                        [
                            'group' => 'Plugins',
                            'value' => $enabled,
                            'detail' => 'Plugins/LoginSocial::lang.admin.config.' . $provider . '_enabled',
                        ]
                    );
                    $savedCount++;

                    // Update client_id
                    $clientId = $data[$provider . '_client_id'] ?? '';
                    AdminConfig::updateOrCreate(
                        [
                            'code' => 'LoginSocial_config',
                            'key' => $provider . '_client_id',
                            'store_id' => GP247_STORE_ID_GLOBAL,
                        ],
                        [
                            'group' => 'Plugins',
                            'value' => $clientId,
                            'detail' => 'Plugins/LoginSocial::lang.admin.config.' . $provider . '_client_id',
                        ]
                    );
                    $savedCount++;

                    // Update client_secret only if not empty
                    $clientSecret = $data[$provider . '_client_secret'] ?? '';
                    if (!empty(trim($clientSecret))) {
                        AdminConfig::updateOrCreate(
                            [
                                'code' => 'LoginSocial_config',
                                'key' => $provider . '_client_secret',
                                'store_id' => GP247_STORE_ID_GLOBAL,
                            ],
                            [
                                'group' => 'Plugins',
                                'value' => $clientSecret,
                                'detail' => 'Plugins/LoginSocial::lang.admin.config.' . $provider . '_client_secret',
                            ]
                        );
                        $savedCount++;
                    }

                    // Update redirect
                    $redirect = $data[$provider . '_redirect'] ?? '';
                    AdminConfig::updateOrCreate(
                        [
                            'code' => 'LoginSocial_config',
                            'key' => $provider . '_redirect',
                            'store_id' => GP247_STORE_ID_GLOBAL,
                        ],
                        [
                            'group' => 'Plugins',
                            'value' => $redirect,
                            'detail' => 'Plugins/LoginSocial::lang.admin.config.' . $provider . '_redirect',
                        ]
                    );
                    $savedCount++;

                } catch (\Exception $e) {
                    gp247_report("LoginSocial: Failed to save config for provider {$provider}: " . $e->getMessage());
                    $errorCount++;
                }
            }

            if ($errorCount > 0) {
                return redirect()->back()->with('warning', "Saved {$savedCount} configurations, but {$errorCount} failed. Check logs for details.");
            }

            return redirect()->back()->with('success', gp247_language_render('Plugins/LoginSocial::lang.admin.save_success'));
            
        } catch (\Exception $e) {
            gp247_report('LoginSocial: Database connection failed in save method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Database connection failed: ' . $e->getMessage());
        }
    }
}
