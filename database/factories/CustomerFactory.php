<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [

            'name' => $this->faker->name(),

            'phone_number' => $this->faker->unique()->numerify('080########'),

            'address' => $this->faker->address(),

            'status' => $this->faker->randomElement([
                'Active',
                'Active',
                'Active',
                'Active',
                'Inactive',
            ]),

        ];
    }
}