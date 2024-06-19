<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Organisation;
use App\Models\TicketType;
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

        // Create mock data
        if (! App::environment('production')) {
            // Create specific testing account (for logging in with) that has an organisation and associated event.
            $event = Event::factory()->recycle(
                User::factory()->create([
                    'first_name'    => 'Test',
                    'last_name'     => 'User',
                    'email'         => 'test@example.com'
                ])->assignRole('administrator')
            )->create();

            // Create tickets for the event
            TicketType::factory()->for($event)->count(2)->create([
                'time' => $event->days()[array_rand($event->days())]->format('Y-m-d'),
            ]);

            // Create regular user who can manage events
            User::factory()->create([
                'first_name'    => 'Event',
                'last_name'     => 'User',
                'email'         => 'user@example.com'
            ])->assignRole('event organiser');
        }
    }
}
