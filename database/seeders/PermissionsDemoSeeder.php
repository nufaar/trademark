<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);

        Permission::create(['name' => 'create trademark']);
        Permission::create(['name' => 'edit trademark']);
        Permission::create(['name' => 'delete trademark']);
        Permission::create(['name' => 'verify trademark']);

        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'pemohon']);
        $role1->givePermissionTo('create trademark');
        $role1->givePermissionTo('edit trademark');
        $role1->givePermissionTo('delete trademark');

        $role2 = Role::create(['name' => 'admin']);
        $role2->givePermissionTo('create trademark');
        $role2->givePermissionTo('edit trademark');
        $role2->givePermissionTo('delete trademark');
        $role2->givePermissionTo('verify trademark');

        $role2->givePermissionTo('create articles');
        $role2->givePermissionTo('edit articles');
        $role2->givePermissionTo('delete articles');
        $role2->givePermissionTo('publish articles');

        $role2->givePermissionTo('create user');
        $role2->givePermissionTo('edit user');
        $role2->givePermissionTo('delete user');


        $role3 = Role::create(['name' => 'Super-Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'nufa',
            'email' => 'nufa@app.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@app.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@app.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role3);
    }
}
