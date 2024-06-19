<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create administrator role
        Role::create(['name' => 'administrator'])
            ->syncPermissions([
                // Create permissions (and assign all to administrator role
                Permission::create(['name' => 'administer events']),
                Permission::create(['name' => 'manage own events']),
                Permission::create(['name' => 'administer orders']),
            ]);

        // Create event organiser role
        Role::create(['name' => 'event organiser'])
            ->syncPermissions([
                Permission::where('name', 'manage own events')->first(),
            ]);

        if (App::environment('local')) {
            // Create specific testing account (for logging in with)
            User::factory()
                ->create([
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'email' => 'test@example.com',
                ])->assignRole('administrator');

            // Create regular user who can manage events
            User::factory()
                ->create([
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'email' => 'user@example.com',
                ])->assignRole('event organiser');
        }
    }
}
