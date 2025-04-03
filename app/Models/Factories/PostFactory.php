<?php

namespace App\Models\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array {
        return [
            'writer_id' => $this->faker->randomNumber(),
            'title' => $this->faker->sentence(),
            'text' => $this->faker->text(),
        ];
    }
}
