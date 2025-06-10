<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ad>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "category_id" => $this->faker->numberBetween(1, 10),
            "supplier_id" => $this->faker->numberBetween(1, 10),
            "product_name" => $this->faker->word,
            "product_description" => $this->faker->paragraph,
            "product_image" => $this->faker->imageUrl(640, 480, 'technics', true, 'Faker', true),
            "product_price" => $this->faker->randomFloat(2, 10, 1000),
            "launch_date" => $this->faker->dateTimeBetween('-1 year', 'now'),
            "launch_status" => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
