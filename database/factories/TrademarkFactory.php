<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trademark>
 */
class TrademarkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => rand(1, 3),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'owner' => $this->faker->name(),
            'logo' => $this->faker->image('public/storage/logos', 640, 480, null, false),
            'certificate' => $this->faker->image('public/storage/certificates', 640, 480, null, false),
            'signature' => $this->faker->image('public/storage/signatures', 640, 480, null, false),
        ];
    }
}
