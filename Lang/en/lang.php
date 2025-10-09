<?php
return [
    'title' => 'Login Social',
    
    'admin' => [
        'title' => 'Social Login',
        'description' => 'Configure social media login providers for your application. Users can login using their social media accounts.',
        'available_guards' => 'Available Guards',
        'guards_help' => 'These guards can be used for social login. Specify the guard in the login URL. Green badges are enabled, gray badges are disabled (configured in config.php).',
        'guard_enabled' => 'Enabled',
        'guard_disabled' => 'Disabled',
        'enable_provider' => 'Enable :provider Login',
        'client_id' => 'Client ID / App ID',
        'client_secret' => 'Client Secret / App Secret',
        'secret_hidden' => 'Secret key is hidden for security. Enter new value to update.',
        'secret_placeholder' => '******** (Enter new value to update)',
        'secret_new_placeholder' => 'Enter new Client Secret',
        'redirect_url' => 'Redirect URL / Callback URL',
        'redirect_help' => 'Copy this URL to your OAuth app configuration',
        'usage_title' => 'Usage Example',
        'usage_description' => 'Add this code to your login page to enable social login:',
        'save' => 'Save Configuration',
        'save_success' => 'Configuration saved successfully!',
        'config' => [
            'facebook_enabled' => 'Enable Facebook Login',
            'facebook_client_id' => 'Facebook App ID',
            'facebook_client_secret' => 'Facebook App Secret',
            'facebook_redirect' => 'Facebook Redirect URL',
            'google_enabled' => 'Enable Google Login',
            'google_client_id' => 'Google Client ID',
            'google_client_secret' => 'Google Client Secret',
            'google_redirect' => 'Google Redirect URL',
            'github_enabled' => 'Enable GitHub Login',
            'github_client_id' => 'GitHub Client ID',
            'github_client_secret' => 'GitHub Client Secret',
            'github_redirect' => 'GitHub Redirect URL',
        ],
    ],
    
    // Frontend messages
    'provider_not_enabled' => 'This login provider is not enabled or configured properly.',
    'guard_not_enabled' => 'This authentication method is not available or configured properly.',
    'invalid_guard' => 'Invalid authentication guard specified.',
    'account_inactive' => 'Your account is inactive. Please contact administrator.',
    'create_user_failed' => 'Failed to create user account. Please try again.',
    'login_success' => 'Login successful!',
    'login_failed' => 'Login failed. Please try again.',
    
    // Social login buttons
    'login_with_facebook' => 'Login with Facebook',
    'login_with_google' => 'Login with Google',
    'login_with_github' => 'Login with GitHub',
    'login_with' => 'Login with',
    'or_login_with' => 'Or login with',
    'continue_with' => 'Continue with',
    
    // Additional messages
    'social_login_title' => 'Social Login',
    'social_login_description' => 'Quick login with your social media accounts',
    'connecting_to' => 'Connecting to',
    'please_wait' => 'Please wait...',
    'redirecting' => 'Redirecting...',
    
    // Validation messages
    'client_id_required' => 'Client ID is required when enabling provider',
    'client_secret_required' => 'Client Secret is required when enabling provider',
    'redirect_url_required' => 'Redirect URL is required when enabling provider',
    'invalid_client_id' => 'Invalid Client ID',
    'invalid_client_secret' => 'Invalid Client Secret',
    'invalid_redirect_url' => 'Invalid Redirect URL',
    
    // Success messages
    'account_linked' => 'Account linked successfully',
    'account_created' => 'New account created successfully',
    'welcome_message' => 'Welcome to the system!',
];
