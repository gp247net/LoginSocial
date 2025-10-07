<?php

namespace App\GP247\Plugins\LoginSocial\Commands;

use Illuminate\Console\Command;
use App\GP247\Plugins\LoginSocial\Helpers\ProviderConfig;

class CheckHealthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loginsocial:health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check LoginSocial plugin health and configuration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Checking LoginSocial Plugin Health...');
        $this->line('');

        $health = ProviderConfig::checkDatabaseHealth();

        // Database Health
        $this->info('📊 Database Health:');
        $this->line('  Database Connected: ' . ($health['database_connected'] ? '✅ Yes' : '❌ No'));
        $this->line('  Config Available: ' . ($health['config_available'] ? '✅ Yes' : '❌ No'));
        
        if ($health['error_message']) {
            $this->error('  Error: ' . $health['error_message']);
        }
        $this->line('');

        // Provider Status
        $this->info('🔧 Provider Configuration:');
        $providers = ['facebook', 'google', 'github'];
        
        foreach ($providers as $provider) {
            $config = ProviderConfig::getProviderConfig($provider);
            $enabled = $config['enabled'] ? '✅ Enabled' : '❌ Disabled';
            $configured = !empty($config['client_id']) && !empty($config['client_secret']) ? '✅ Configured' : '❌ Not Configured';
            
            $this->line("  {$provider}: {$enabled} | {$configured}");
            
            if (!$configured && $config['enabled']) {
                $this->warn("    ⚠️  {$provider} is enabled but not properly configured");
            }
        }
        $this->line('');

        // Overall Status
        $this->info('📈 Overall Status:');
        if ($health['database_connected'] && $health['table_exists'] && $health['config_available']) {
            $this->info('  ✅ Plugin is healthy and ready to use');
            $status = 'ok';
        } elseif ($health['database_connected'] && $health['table_exists']) {
            $this->warn('  ⚠️  Plugin has some issues but may still work');
            $status = 'warning';
        } else {
            $this->error('  ❌ Plugin has critical issues and may not work properly');
            $status = 'error';
        }

        // Recommendations
        $this->line('');
        $this->info('💡 Recommendations:');
        
        if (!$health['database_connected']) {
            $this->warn('  - Check database connection settings');
        }
        
        if (!$health['config_available']) {
            $this->warn('  - Check gp247_config helper functionality');
        }

        $configuredProviders = 0;
        foreach ($providers as $provider) {
            $config = ProviderConfig::getProviderConfig($provider);
            if (!empty($config['client_id']) && !empty($config['client_secret'])) {
                $configuredProviders++;
            }
        }

        if ($configuredProviders === 0) {
            $this->warn('  - Configure at least one OAuth provider in Admin Panel');
        }

        return $status === 'ok' ? 0 : 1;
    }
}
