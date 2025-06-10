<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ad>
 */
class AdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "ad_title" => $this->faker->sentence,
            "ad_content" => $this->faker->paragraph,
            "ad_banner" => $this->faker->imageUrl(),

        ];
    }
}
