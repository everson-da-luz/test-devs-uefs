<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence();

        return [
            'users_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraph()
        ];
    }
}
