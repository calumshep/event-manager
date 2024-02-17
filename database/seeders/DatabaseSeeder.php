<?php

namespace Database\Seeders;

use App\Models\Organisation;
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
            // Create specific testing account (for logging in with)
            $user = User::factory()
                ->create([
                'first_name'    => 'Test',
                'last_name'     => 'User',
                'email'         => 'test@example.com'
            ]);
        }
    }
}
