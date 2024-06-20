<?php

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 week', '+1 year');
        $end = $this->faker->optional()->dateTimeBetween($start, $start->format('d-m-Y') . ' + 1 week');

        return [
            'name'              => $this->faker->sentence(2),
            'start'             => $start,
            'end'               => $end,
            'type'              => array_rand(array_flip(['generic', 'race'])),
            'slug'              => Str::of($this->faker->sentence(2))->slug(),
            'short_desc'        => $this->faker->paragraph(2),
            'long_desc'         => $this->faker->paragraphs(2, true),
            'user_id'           => User::factory(),
            'organisation_id'   => Organisation::factory(),
        ];
    }
}
