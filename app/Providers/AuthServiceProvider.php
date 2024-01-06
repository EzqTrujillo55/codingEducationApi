<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

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
        /*
        $this->registerPolicies();
         //SE DEBE EJECUTAR LA MIGRACION DE ROLES AQUI PARA QUE NO DE ERROR

        // Load roles and permissions from config file
        $roles = Config::get('rolesAndPermission.roles');
        $permissions = Config::get('rolesAndPermission.permissions');

        // Create roles and permissions if they don't exist
        foreach ($roles as $name => $label) {
            Role::firstOrCreate(['name' => $name], ['description' => $label]);
        }

        foreach ($permissions as $name => $label) {
            Permission::firstOrCreate(['name' => $name], ['description' => $label]);
        }

        // Create admin user if it doesn't exist
        $admin = Role::where('name', 'admin')->first();
        if (!$admin) {
            $admin = Role::create(['name' => 'admin', 'description' => 'Administrator']);
        }

        $email = 'admin@example.com';
        $password = "123456789";

        $validator = Validator::make(
            [
                'email' =>  $email,
                'password' =>  $password,
            ],
            [
                'email'    => 'required|email|unique:users,email',
                'password' => 'required',
            ]
        );

        if ($validator->fails()) {
            error_log('User not created. See error messages below:');
            foreach ($validator->errors()->all() as $error) {
                error_log($error);
            }
            return;
        }

        $admin_user = User::firstOrCreate(
            ['email' => $email],
            ['password' => bcrypt($password)]
        );

        $admin_user->assignRole($admin);
        */
    }
}
