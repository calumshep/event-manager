<?php

namespace Database\Seeders;

use App\Models\Competitor;
use App\Models\User;
use App\Models\Event;
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
            // Seed with dummy users with 0-3 competitors each
            User::factory(10)
                ->hasCompetitors(rand(0, 3))
                ->hasEvents(rand(0,2))
                ->create();

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
