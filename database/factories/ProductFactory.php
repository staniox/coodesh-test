<?php

namespace Database\Factories;

use App\Models\Product;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->word,
            'status' => $this->faker->word,
            'imported_t' => $this->faker->dateTime,
            'url' => $this->faker->url,
            'creator' => $this->faker->name,
            'created_t' => $this->faker->dateTime->getTimestamp(),
            'last_modified_t' => $this->faker->dateTime->getTimestamp(),
            'product_name' => $this->faker->word,
            'quantity' => $this->faker->randomNumber(),
            'brands' => $this->faker->words(3, true),
            'categories' => $this->faker->words(3, true),
            'labels' => $this->faker->words(3, true),
            'cities' => $this->faker->words(2, true),
            'purchase_places' => $this->faker->words(2, true),
            'stores' => $this->faker->words(2, true),
            'ingredients_text' => $this->faker->paragraph,
            'traces' => $this->faker->words(3, true),
            'serving_size' => $this->faker->word,
            'serving_quantity' => $this->faker->randomFloat(2),
            'nutriscore_score' => $this->faker->numberBetween(0, 100),
            'nutriscore_grade' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'main_category' => $this->faker->word,
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}
