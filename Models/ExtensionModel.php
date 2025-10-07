<?php
#App\GP247\Plugins\LoginSocial\Models\ExtensionModel.php
namespace App\GP247\Plugins\LoginSocial\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use GP247\Core\Models\AdminMenu;

class ExtensionModel
{
    public function uninstallExtension()
    {
        // Drop social_accounts table
        Schema::dropIfExists('social_accounts');
        // Remove admin menu if created
        (new AdminMenu)->where('uri', 'route_admin::admin_loginsocial.index')->delete();
    }

    public function installExtension()
    {
        // Create social_accounts table if not exists
        if (!Schema::hasTable('social_accounts')) {
            Schema::create('social_accounts', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->string('user_type'); // Guard type: admin, customer, vendor, pmo
                $table->unsignedBigInteger('user_id'); // User ID from respective table
                $table->string('provider'); // facebook, google, github, etc.
                $table->string('provider_id'); // Provider's user ID
                $table->string('avatar')->nullable(); // User avatar from provider
                $table->timestamps();
                
                // Add indexes for better performance
                $table->index(['user_type', 'user_id']);
                $table->index(['provider', 'provider_id']);
                $table->unique(['provider', 'provider_id', 'user_type']);
            });
        }

        // Ensure admin menu root exists under SECURITY group if needed
        $checkMenu = AdminMenu::where('key','LoginSocial')->first();
        if (!$checkMenu) {
            $menuSecurity = AdminMenu::where('key', 'ADMIN_SECURITY')->first();
            AdminMenu::insert([
                'parent_id' => $menuSecurity->id,
                'title' => 'Plugins/LoginSocial::lang.admin.title',
                'icon' => 'fas fa-boxes',
                'uri' => 'route_admin::admin_loginsocial.index',
            ]);
        }

    }
    
}
