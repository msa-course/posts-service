<?php

namespace App\Models\Factories;

use App\Models\Writer;
use Illuminate\Database\Eloquent\Factories\Factory;

class WriterFactory extends Factory
{
    protected $model = Writer::class;

    public function definition(): array {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'active' => $this->faker->boolean(),
        ];
    }
}
