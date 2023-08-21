<?php

namespace Database\Seeders;

use App\Models\Competitor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            // Main account for testing
            User::factory()->create([
                'first_name'    => 'Calum',
                'last_name'     => 'Shepherd',
                'email'         => 'test@example.com',
            ]);

            // Seed with dummy users with 0-3 competitors each
            User::factory(10)->hasCompetitors(rand(0, 3))->create();
        }
    }
}
