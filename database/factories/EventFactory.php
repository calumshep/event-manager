<?php

namespace Database\Factories;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 week', '+1 year');
        $end = $this->faker->dateTimeBetween($start, $start->format('d-m-Y') . ' + 1 week');

        $name = $this->faker->sentence(2);

        return [
            'name'              => $name,
            'start'             => $start,
            'end'               => rand(0,1) ? $end : null,
            'slug'              => Str::of($name)->slug(),
            'short_desc'        => $this->faker->paragraph(2),
            'long_desc'         => $this->faker->paragraphs(2, true),
            'organisation_id'   => Organisation::factory(),
        ];
    }
}
