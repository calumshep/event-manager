<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TicketTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'              => $this->faker->sentence(2),
            'description'       => $this->faker->paragraph(),
            'price'             => $this->faker->randomNumber(5),
            'capacity'          => $this->faker->randomNumber(1),
            'show_remaining'    => $this->faker->boolean(),
            'details'           => $this->faker->randomHtml(),
        ];
    }
}
