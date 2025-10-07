# LoginSocial Plugin for GP247

## Overview

The LoginSocial plugin allows users to log into the GP247 system using social media accounts such as Facebook, Google, GitHub, and other providers. The plugin supports multiple guards (admin, customer, vendor, pmo...) for flexible user authentication management.

## Features

- ✅ Login support via Facebook, Google, GitHub
- ✅ Multiple guards support: admin, customer, vendor, pmo
- ✅ Automatic account creation on first login
- ✅ Link social accounts with existing accounts
- ✅ OAuth configuration management in Admin panel
- ✅ User-friendly and easy-to-use admin interface
- ✅ Multilingual support (Vietnamese, English)

## System Requirements

- GP247 Core >= 1.2
- Laravel 12.x
- PHP >= 8.2
- Laravel Socialite ^5.0

## Installation

### Step 1: Install Laravel Socialite package

```bash
composer require laravel/socialite
```

### Step 2: Install the plugin

1. Copy the plugin folder to `app/GP247/Plugins/LoginSocial`
2. Access Admin Panel > Extensions > Plugins
3. Find "LoginSocial" and click "Install"
4. After successful installation, click "Enable" to activate the plugin

### Step 3: Configure OAuth Providers

> **Note**: LoginSocial plugin uses database-only configuration, no ENV variables needed.

#### Configure Facebook Login

1. Visit [Facebook Developers](https://developers.facebook.com/)
2. Create a new app or use an existing one
3. In Settings > Basic:
   - Get **App ID** (Client ID)
   - Get **App Secret** (Client Secret)
4. In Products > Facebook Login > Settings:
   - Add **Redirect URL**: `https://your-domain.com/auth/social/facebook/callback`
5. **Configure in Admin Panel** (no ENV variables needed)

#### Configure Google Login

1. Visit [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable Google+ API
4. Create OAuth 2.0 credentials:
   - Application type: Web application
   - Authorized redirect URIs: `https://your-domain.com/auth/social/google/callback`
5. Get **Client ID** and **Client Secret**
6. **Configure in Admin Panel** (no ENV variables needed)

#### Configure GitHub Login

1. Visit [GitHub Developer Settings](https://github.com/settings/developers)
2. Click "New OAuth App"
3. Fill in the information:
   - Application name: Your app name
   - Homepage URL: `https://your-domain.com`
   - Authorization callback URL: `https://your-domain.com/auth/social/github/callback`
4. Get **Client ID** and **Client Secret**
5. **Configure in Admin Panel** (no ENV variables needed)

### Step 4: Configure in Admin Panel

1. Access **Admin Panel > Plugins > LoginSocial**
2. Enable/disable desired providers
3. Enter Client ID, Client Secret for each provider
4. Verify the Redirect URL (callback URL) is correct
5. Click "Save Configuration"

> **Important**: All configurations are stored in database, no ENV variables setup required.

## Usage

### Add Social Login Buttons to Template

#### Login for Customer (default)

```blade
<a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" class="btn btn-primary">
    <i class="fab fa-facebook"></i> Login with Facebook
</a>

<a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="btn btn-danger">
    <i class="fab fa-google"></i> Login with Google
</a>

<a href="{{ route('social.redirect', ['provider' => 'github']) }}" class="btn btn-dark">
    <i class="fab fa-github"></i> Login with GitHub
</a>
```

#### Login for Admin

```blade
<a href="{{ route('social.redirect', ['provider' => 'google', 'guard' => 'admin']) }}" class="btn btn-primary">
    <i class="fab fa-google"></i> Admin Login with Google
</a>
```

#### Login for Vendor

```blade
<a href="{{ route('social.redirect', ['provider' => 'facebook', 'guard' => 'vendor']) }}" class="btn btn-primary">
    <i class="fab fa-facebook"></i> Vendor Login with Facebook
</a>
```

### Complete Example in Login Form

```blade
<div class="social-login-buttons">
    <h4>Or login with</h4>
    
    <div class="btn-group">
        @if(gp247_config('facebook_enabled', GP247_STORE_ID_GLOBAL, '0'))
        <a href="{{ route('social.redirect', ['provider' => 'facebook', 'guard' => 'customer']) }}" 
           class="btn btn-facebook">
            <i class="fab fa-facebook-f"></i> Facebook
        </a>
        @endif
        
        @if(gp247_config('google_enabled', GP247_STORE_ID_GLOBAL, '0'))
        <a href="{{ route('social.redirect', ['provider' => 'google', 'guard' => 'customer']) }}" 
           class="btn btn-google">
            <i class="fab fa-google"></i> Google
        </a>
        @endif
        
        @if(gp247_config('github_enabled', GP247_STORE_ID_GLOBAL, '0'))
        <a href="{{ route('social.redirect', ['provider' => 'github', 'guard' => 'customer']) }}" 
           class="btn btn-github">
            <i class="fab fa-github"></i> GitHub
        </a>
        @endif
    </div>
</div>
```

## Available Guards

The plugin supports the following guards:

- **admin**: Login for administrators
- **customer**: Login for customers (default)
- **vendor**: Login for vendors (requires MultiVendorPro plugin)
- **pmo**: Login for PMO users

## Database Structure

### social_accounts Table
The plugin creates a `social_accounts` table with the structure:

```sql
- id (bigint)
- user_type (string) - Guard type: admin, customer, vendor, pmo
- user_id (bigint) - User ID in the corresponding table
- provider (string) - Provider name: facebook, google, github
- provider_id (string) - User ID from provider
- avatar (string) - Avatar URL from provider
- created_at (timestamp)
- updated_at (timestamp)
```


## Workflow

1. User clicks "Login with Facebook/Google/GitHub" button
2. System redirects to provider for authentication
3. After successful authentication, provider redirects to callback URL
4. Plugin checks:
   - If social account exists → Login immediately
   - If email exists → Link social account with existing account
   - If new user → Create new account and login
5. Redirect user to appropriate page based on guard

## Common Error Handling

### Error: "This login provider is not enabled"

**Cause**: Provider not enabled or not configured

**Solution**:
1. Go to Admin Panel > Plugins > LoginSocial
2. Check if provider is enabled
3. Check if Client ID and Client Secret are entered correctly

### Error: "Invalid authentication guard"

**Cause**: Guard does not exist in configuration

**Solution**:
- Check guard in plugin's `config.php` file
- Ensure guard and corresponding model are configured correctly

### Error: "Client error: 400"

**Cause**: OAuth configuration incorrect

**Solution**:
1. Check Client ID and Client Secret
2. Check Redirect URL matches OAuth app configuration
3. Ensure OAuth app is approved/published

## Customization

### Add New Provider

1. Install corresponding Socialite provider (if needed)
2. Add configuration to `config.php`:

```php
'providers' => [
    'linkedin' => [
        'enabled' => env('LINKEDIN_ENABLED', false),
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_REDIRECT_URL'),
    ],
],
```

3. Update Routes to support new provider
4. Add corresponding language strings

### Add New Guard

1. Add guard configuration to `config.php`:

```php
'guards' => [
    'your_guard' => [
        'model' => '\App\Models\YourModel',
        'redirect_after_login' => 'your.route',
        'table' => 'your_table',
    ],
],
```

2. Update `createUserByGuard()` method in `SocialAuthController.php` to handle user creation logic for new guard

## Security

- ✅ Uses standard OAuth 2.0 protocol
- ✅ Client Secret stored securely in database
- ✅ Account status checked before login
- ✅ Random password for new accounts
- ✅ Session-based authentication

## Support

- Email: support@gp247.net
- Website: https://gp247.net
- Documentation: https://gp247.net/docs

## License

MIT License

## Changelog

### Version 1.0
- Initial release
- Support for Facebook, Google, GitHub
- Multiple guards support
- Admin panel configuration
- Multilingual support
