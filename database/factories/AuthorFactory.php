<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Author::class;

    public function definition()
    {
        return [
            //
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Author $author) {
            $author->profile()->make();
        });
    }
}
