<?php
use Illuminate\Support\Facades\Route;

$config = file_get_contents(__DIR__.'/gp247.json');
$config = json_decode($config, true);

if(gp247_extension_check_active($config['configGroup'], $config['configKey'])) {

    // Frontend routes for social authentication
    Route::group(
        [
            'middleware' => GP247_FRONT_MIDDLEWARE,
            'prefix'    => 'auth/social',
            'namespace' => 'App\GP247\Plugins\LoginSocial\Controllers',
        ],
        function () {
            // Redirect to provider
            Route::get('{provider}/{guard?}', 'SocialAuthController@redirectToProvider')
                ->name('social.redirect')
                ->where('provider', 'facebook|google|github');
            
            // Provider callback
            Route::get('{provider}/callback', 'SocialAuthController@handleProviderCallback')
                ->name('social.callback')
                ->where('provider', 'facebook|google|github');
        }
    );

    // Admin routes for plugin configuration
    Route::group(
        [
            'prefix' => GP247_ADMIN_PREFIX.'/loginsocial',
            'middleware' => GP247_ADMIN_MIDDLEWARE,
            'namespace' => '\App\GP247\Plugins\LoginSocial\Admin',
        ], 
        function () {
            Route::get('/', 'AdminController@index')
                ->name('admin_loginsocial.index');
            
            Route::post('/save', 'AdminController@save')
                ->name('admin_loginsocial.save');
        }
    );
}