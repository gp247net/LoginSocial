<?php

namespace App\GP247\Plugins\LoginSocial\Controllers;

use App\GP247\Plugins\LoginSocial\AppConfig;
use App\GP247\Plugins\LoginSocial\Models\SocialAccount;
use GP247\Front\Controllers\RootFrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends RootFrontController
{
    public $plugin;

    public function __construct()
    {
        parent::__construct();
        $this->plugin = new AppConfig();
    }

    /**
     * Redirect to provider for authentication
     *
     * @param string $provider
     * @param string $guard
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($provider, $guard = null)
    {
        try {
            // Get provider configuration directly from database
            $providerConfig = \App\GP247\Plugins\LoginSocial\Helpers\ProviderConfig::getProviderConfig($provider);
            
            if (!$providerConfig['enabled']) {
                return redirect()->back()->with('error', gp247_language_render('Plugins/LoginSocial::lang.provider_not_enabled'));
            }

            // Store guard in session for callback
            $guard = $guard ?? config('Plugins/LoginSocial.default_guard');
            session(['socialite_guard' => $guard]);

            // Configure socialite
            config([
                'services.' . $provider . '.client_id' => $providerConfig['client_id'],
                'services.' . $provider . '.client_secret' => $providerConfig['client_secret'],
                'services.' . $provider . '.redirect' => $providerConfig['redirect'],
            ]);

            return Socialite::driver($provider)->redirect();
            
        } catch (\Exception $e) {
            gp247_report('LoginSocial: Redirect to provider failed: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle provider callback
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            // Get provider configuration directly from database
            $providerConfig = \App\GP247\Plugins\LoginSocial\Helpers\ProviderConfig::getProviderConfig($provider);
            
            if (!$providerConfig['enabled']) {
                return redirect()->route('login')->with('error', gp247_language_render('Plugins/LoginSocial::lang.provider_not_enabled'));
            }

            // Configure socialite
            config([
                'services.' . $provider . '.client_id' => $providerConfig['client_id'],
                'services.' . $provider . '.client_secret' => $providerConfig['client_secret'],
                'services.' . $provider . '.redirect' => $providerConfig['redirect'],
            ]);

            // Get user from provider
            $providerUser = Socialite::driver($provider)->user();
            
            // Get guard from session
            $guard = session('socialite_guard', config('Plugins/LoginSocial.default_guard'));
            $guardConfig = config('Plugins/LoginSocial.guards.' . $guard);

            if (!$guardConfig) {
                return redirect()->route('login')->with('error', gp247_language_render('Plugins/LoginSocial::lang.invalid_guard'));
            }

            // Find or create social account
            $socialAccount = SocialAccount::where('provider', $provider)
                ->where('provider_id', $providerUser->getId())
                ->where('user_type', $guard)
                ->first();

            if ($socialAccount) {
                // Login existing user
                $user = $this->getUserByGuard($guard, $socialAccount->user_id);
                
                if ($user && $user->status == 1) {
                    $this->loginUser($guard, $user);
                    return redirect()->route($guardConfig['redirect_after_login']);
                } else {
                    return redirect()->route('login')->with('error', gp247_language_render('Plugins/LoginSocial::lang.account_inactive'));
                }
            } else {
                // Check if user exists by email
                $modelClass = $guardConfig['model'];
                $user = $modelClass::where('email', $providerUser->getEmail())->first();

                if ($user) {
                    // Link social account to existing user
                    SocialAccount::create([
                        'user_type' => $guard,
                        'user_id' => $user->id,
                        'provider' => $provider,
                        'provider_id' => $providerUser->getId(),
                        'avatar' => $providerUser->getAvatar(),
                    ]);

                    if ($user->status == 1) {
                        $this->loginUser($guard, $user);
                        return redirect()->route($guardConfig['redirect_after_login']);
                    } else {
                        return redirect()->route('login')->with('error', gp247_language_render('Plugins/LoginSocial::lang.account_inactive'));
                    }
                } else {
                    // Create new user
                    $newUser = $this->createUserByGuard($guard, $providerUser);

                    if ($newUser) {
                        // Create social account
                        SocialAccount::create([
                            'user_type' => $guard,
                            'user_id' => $newUser->id,
                            'provider' => $provider,
                            'provider_id' => $providerUser->getId(),
                            'avatar' => $providerUser->getAvatar(),
                        ]);

                        $this->loginUser($guard, $newUser);
                        return redirect()->route($guardConfig['redirect_after_login']);
                    } else {
                        return redirect()->route('login')->with('error', gp247_language_render('Plugins/LoginSocial::lang.create_user_failed'));
                    }
                }
            }
        } catch (\Exception $e) {
            gp247_report('LoginSocial: Handle provider callback failed: ' . $e->getMessage());
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }

    /**
     * Get user by guard and ID
     *
     * @param string $guard
     * @param int $userId
     * @return mixed
     */
    protected function getUserByGuard($guard, $userId)
    {
        $guardConfig = config('Plugins/LoginSocial.guards.' . $guard);
        $modelClass = $guardConfig['model'];
        
        return $modelClass::find($userId);
    }

    /**
     * Create user by guard
     *
     * @param string $guard
     * @param mixed $providerUser
     * @return mixed
     */
    protected function createUserByGuard($guard, $providerUser)
    {
        $guardConfig = config('Plugins/LoginSocial.guards.' . $guard);
        $modelClass = $guardConfig['model'];

        $userData = [
            'email' => $providerUser->getEmail(),
            'name' => $providerUser->getName() ?? $providerUser->getNickname(),
            'password' => Hash::make(Str::random(16)), // Random password
            'status' => 1,
        ];

        // Handle specific fields for different guards
        if ($guard === 'customer') {
            $userData['first_name'] = $providerUser->getName() ?? '';
            $userData['last_name'] = '';
            unset($userData['name']);
        }

        return $modelClass::create($userData);
    }

    /**
     * Login user by guard
     *
     * @param string $guard
     * @param mixed $user
     * @return void
     */
    protected function loginUser($guard, $user)
    {
        if ($guard === 'customer') {
            // For customer guard, use customer authentication
            session(['customer' => $user->id]);
            session(['customerAuth' => $user->id]);
        } elseif ($guard === 'vendor') {
            // For vendor guard
            Auth::guard('vendor')->login($user);
        } else {
            // For other guards (admin, pmo, etc.)
            Auth::guard($guard)->login($user);
        }
    }
}
