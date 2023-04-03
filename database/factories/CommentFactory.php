<?php

namespace Database\Factories;

use App\Track;
use Common\Comments\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->realText(),
            'commentable_type' => Track::class,
            'position' => $this->faker->numberBetween(0, 100),
        ];
    }
}
