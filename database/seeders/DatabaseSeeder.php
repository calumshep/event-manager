<?php

namespace Database\Seeders;

use App\Models\Entrant;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            // Seed with dummy users
            User::factory(10)

                // Give each dummy user 0-3 entrants that they own
                ->hasEntrants(rand(0, 3))

                // Give each dummy user 0-2 events that they own
                ->has(
                    Event::factory()
                        ->count(rand(0,2))

                        // Ensure the event belongs to the user
                        ->state(function (array $attributes, User $user) {
                            return [
                                'user_id' => $user->id
                            ];
                        })

                        // Give each dummy event 0-2 tickets
                        ->has(
                            Ticket::factory()
                                ->count(rand(0,2))

                                // Set the validity time of the ticket in relation to the event
                                ->state(function (array $attributes, Event $event) {
                                    return [
                                        'time' => fake()->dateTimeBetween($event->start, $event->end ?: $event->start . ' +12 hours')
                                    ];

            // Save all the dummy data
            })))->create();

            // Create specific testing account (for logging in with)
            $user = User::factory()->create([
                'first_name'    => 'Test',
                'last_name'     => 'User',
                'email'         => 'test@example.com'
            ]);
            $user->entrants()->saveMany(Entrant::factory()->count(2)->make());
            $user->events()->saveMany(Event::factory()->count(2)->make());
        }
    }
}
