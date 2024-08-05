<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'code' => $this->faker->currencyCode,
            'exchange_rate' => $this->faker->randomFloat(6, 0.5, 2), // Генерує курс валют
        ];
    }
}
