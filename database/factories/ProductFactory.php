<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Currency;

// Додайте це, якщо у вас є модель Currency
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'currency_id' => 1, // Якщо ви маєте модель Currency
        ];
    }
}
