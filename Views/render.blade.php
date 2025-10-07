{{-- 
    Render social login buttons
    Usage: @include('Plugins/LoginSocial::render', ['guard' => 'customer', 'providers' => ['facebook', 'google', 'github']])
--}}

@php
    $guard = $guard ?? 'customer';
    $providers = $providers ?? ['facebook', 'google', 'github'];
    $buttonClass = $buttonClass ?? 'btn btn-block mb-2';
@endphp

<div class="social-login-wrapper">
    @if(isset($title) && $title)
        <h4 class="social-login-title">{{ gp247_language_render('Plugins/LoginSocial::lang.social_login_title') }}</h4>
    @endif
    
    @if(isset($description) && $description)
        <p class="social-login-description">{{ gp247_language_render('Plugins/LoginSocial::lang.social_login_description') }}</p>
    @endif
    
    @foreach($providers as $provider)
        @if(\App\GP247\Plugins\LoginSocial\Helpers\ProviderConfig::isProviderEnabled($provider))
            <a href="{{ route('social.redirect', ['provider' => $provider, 'guard' => $guard]) }}" 
               class="{{ $buttonClass }} btn-{{ $provider }}"
               title="{{ gp247_language_render('Plugins/LoginSocial::lang.connecting_to') }} {{ ucfirst($provider) }}">
                <i class="fab fa-{{ $provider }}"></i> 
                {{ gp247_language_render('Plugins/LoginSocial::lang.login_with_' . $provider) ?? 'Login with ' . ucfirst($provider) }}
            </a>
        @endif
    @endforeach
</div>

<style>
.social-login-wrapper {
    margin: 15px 0;
}

.social-login-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.social-login-description {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
}

.btn-facebook {
    background-color: #3b5998;
    color: white;
}

.btn-facebook:hover {
    background-color: #2d4373;
    color: white;
}

.btn-google {
    background-color: #dd4b39;
    color: white;
}

.btn-google:hover {
    background-color: #c23321;
    color: white;
}

.btn-github {
    background-color: #333;
    color: white;
}

.btn-github:hover {
    background-color: #000;
    color: white;
}
</style>


