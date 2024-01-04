<?php

namespace Database\Factories;

use App\Models\Director;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'image' => fake()->imageUrl(),
            'trailer' => fake()->url(),
            'year' => fake()->year(),
            'genre' => fake()->word(),
            'duration' => fake()->numberBetween(1, 300),
            'director_id' => Director::all()->random()->id,
        ];
    }
}
