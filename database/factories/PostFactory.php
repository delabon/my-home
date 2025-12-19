<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
final class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
            'body' => fake()->paragraphs(asText: true),
            'status' => PostStatus::Draft,
        ];
    }

    public function published(): self
    {
        return $this->state([
            'status' => PostStatus::Published,
        ]);
    }
}
