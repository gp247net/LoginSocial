@extends('gp247-core::layout')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-share-alt"></i> {{ gp247_language_render('Plugins/LoginSocial::lang.admin.title') }}
                </h3>
            </div>

            <form action="{{ route('admin_loginsocial.save') }}" method="POST">
                @csrf
                <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if (session('warning'))
                            <div class="alert alert-warning">{{ session('warning') }}</div>
                        @endif
                        @if (isset($error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                            </div>
                        @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> {{ gp247_language_render('Plugins/LoginSocial::lang.admin.description') }}
                    </div>

                    <!-- Available Guards -->
                    <div class="form-group">
                        <label>{{ gp247_language_render('Plugins/LoginSocial::lang.admin.available_guards') }}</label>
                        <div class="alert alert-secondary">
                            @foreach($guards as $guard)
                                @php
                                    $guardConfig = config('Plugins/LoginSocial.guards.' . $guard);
                                    $isEnabled = $guardConfig['enabled'] ?? 0;
                                    $statusText = $isEnabled 
                                        ? gp247_language_render('Plugins/LoginSocial::lang.admin.guard_enabled')
                                        : gp247_language_render('Plugins/LoginSocial::lang.admin.guard_disabled');
                                @endphp
                                <span class="badge badge-{{ $isEnabled ? 'success' : 'secondary' }}" title="{{ $statusText }}">
                                    {{ $guard }}
                                    @if($isEnabled)
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-times-circle"></i>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                        <small class="form-text text-muted">
                            {{ gp247_language_render('Plugins/LoginSocial::lang.admin.guards_help') }}
                        </small>
                    </div>

                    <hr>

                    @foreach($providers as $provider)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h4 class="mb-0">
                                <i class="fab fa-{{ $provider }}"></i> 
                                {{ ucfirst($provider) }} Configuration
                            </h4>
                        </div>
                        <div class="card-body">
                            <!-- Enabled -->
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="{{ $provider }}_enabled" 
                                           name="{{ $provider }}_enabled" 
                                           value="1"
                                           {{ $configs[$provider]['enabled'] ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="{{ $provider }}_enabled">
                                        {{ gp247_language_render('Plugins/LoginSocial::lang.admin.enable_provider', ['provider' => ucfirst($provider)]) }}
                                    </label>
                                </div>
                            </div>

                            <!-- Client ID -->
                            <div class="form-group">
                                <label for="{{ $provider }}_client_id">
                                    {{ gp247_language_render('Plugins/LoginSocial::lang.admin.client_id') }}
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="{{ $provider }}_client_id" 
                                       name="{{ $provider }}_client_id" 
                                       value="{{ $configs[$provider]['client_id'] }}"
                                       placeholder="Enter {{ ucfirst($provider) }} Client ID">
                            </div>

                            <!-- Client Secret -->
                            <div class="form-group">
                                <label for="{{ $provider }}_client_secret">
                                    {{ gp247_language_render('Plugins/LoginSocial::lang.admin.client_secret') }}
                                </label>
                                @if(!empty($configs[$provider]['client_secret']))
                                    <!-- Secret exists - show masked input -->
                                    <input type="password" 
                                           class="form-control" 
                                           id="{{ $provider }}_client_secret" 
                                           name="{{ $provider }}_client_secret" 
                                           value=""
                                           placeholder="{{ gp247_language_render('Plugins/LoginSocial::lang.admin.secret_placeholder') }}">
                                    <small class="form-text text-muted">
                                        {{ gp247_language_render('Plugins/LoginSocial::lang.admin.secret_hidden') }}
                                    </small>
                                @else
                                    <!-- No secret - show normal input -->
                                    <input type="password" 
                                           class="form-control" 
                                           id="{{ $provider }}_client_secret" 
                                           name="{{ $provider }}_client_secret" 
                                           value=""
                                           placeholder="{{ gp247_language_render('Plugins/LoginSocial::lang.admin.secret_new_placeholder') }}">
                                @endif
                            </div>

                            <!-- Redirect URL -->
                            <div class="form-group">
                                <label for="{{ $provider }}_redirect">
                                    {{ gp247_language_render('Plugins/LoginSocial::lang.admin.redirect_url') }}
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="{{ $provider }}_redirect" 
                                       name="{{ $provider }}_redirect" 
                                       value="{{ $configs[$provider]['redirect'] ?: url('auth/social/'.$provider.'/callback') }}"
                                       placeholder="{{ url('auth/social/'.$provider.'/callback') }}">
                                <small class="form-text text-muted">
                                    {{ gp247_language_render('Plugins/LoginSocial::lang.admin.redirect_help') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Usage Instructions -->
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-code"></i> {{ gp247_language_render('Plugins/LoginSocial::lang.admin.usage_title') }}</h5>
                        <p>{{ gp247_language_render('Plugins/LoginSocial::lang.admin.usage_description') }}</p>
                        <pre class="bg-dark text-white p-3"><code>&lt;a href="@{{ route('social.redirect', ['provider' => 'facebook', 'guard' => 'customer']) }}"&gt;
    &lt;i class="fab fa-facebook"&gt;&lt;/i&gt; Login with Facebook
&lt;/a&gt;</code></pre>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ gp247_language_render('Plugins/LoginSocial::lang.admin.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('styles')
<style>
    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    }
    pre code {
        font-size: 12px;
        line-height: 1.5;
    }
    .badge {
        font-size: 14px;
        padding: 8px 12px;
        margin-right: 8px;
        margin-bottom: 5px;
        display: inline-block;
    }
    .badge i {
        margin-left: 5px;
    }
    .badge-success {
        background-color: #28a745;
    }
    .badge-secondary {
        background-color: #6c757d;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Handle provider enable/disable
    $('input[type="checkbox"][id$="_enabled"]').change(function() {
        var provider = $(this).attr('id').replace('_enabled', '');
        var card = $(this).closest('.card-body');
        
        if ($(this).is(':checked')) {
            card.find('input[type="text"]').prop('disabled', false);
        } else {
            card.find('input[type="text"]').prop('disabled', true);
        }
    });

    // Initialize state
    $('input[type="checkbox"][id$="_enabled"]').trigger('change');
});
</script>
@endpush
