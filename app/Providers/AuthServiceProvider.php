<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //

        $roles = Config::get('rolesAndPermission.roles');
        $permissions = Config::get('rolesAndPermission.permissions');

        // Crear roles
        foreach ($roles as $name => $label) {
            Role::firstOrCreate(['name' => $name], ['description' => $label]);
        }

        // Crear permisos
        foreach ($permissions as $name => $label) {
            Permission::firstOrCreate(['name' => $name], ['description' => $label]);
        }
    }
}
