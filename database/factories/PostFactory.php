<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $published = $this->faker->boolean();
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->slug,
            'image_path' => null,
            'excerpt' => $this->faker->paragraph,
            // 'content' => $this->faker->text(2000),
            'content' => $this->faker->paragraphs(5, true),
            'is_published' => $published,
            'published_at' => $published ? $this->faker->dateTimeBetween('-1 years', 'now') : null,
            'user_id' => \App\Models\User::all()->random()->id,
            'category_id' => \App\Models\Category::all()->random()->id,
        ];
    }
}
