<?php

namespace Database\Seeders;

use App\Models\Competitor;
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

                // Give each dummy user 0-3 competitors that they own
                ->hasCompetitors(rand(0, 3))

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
                                ->state(function (array $attributes, Event $event) {
                                    return [
                                        'time' => fake()->dateTimeBetween($event->start, $event->end ?: $event->start . ' +12 hours')
                                    ];
                })))->create();

            $user = User::factory()->create([
                'first_name'    => 'Calum',
                'last_name'     => 'Shepherd',
                'email'         => 'test@example.com'
            ]);

            $user->competitors()->saveMany(Competitor::factory()->count(2)->make());
            $user->events()->saveMany(Event::factory()->count(2)->make());
        }
    }
}
