<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = BlogPost::class;
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(10),
            'content' => $this->faker->paragraphs(5, true),
            'created_at' => $this->faker->dateTimeBetween('-3 months'),

        ];
    }

    public function addstate()
    {
        return $this->state([
            'title' => 'New Title',
            'content' => 'Content of the blog',

        ]);
    }

}
