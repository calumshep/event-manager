<?php

namespace Database\Factories;

use App\Enums\Gender;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Competitor>
 */
class CompetitorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'      => $this->faker->name(),
            'dob'       => $this->faker->date('Y-m-d', new DateTime('-6 years')),
            'gender'    => $this->faker->randomElement(array_column(Gender::cases(), 'value')),
        ];
    }
}