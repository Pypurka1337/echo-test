<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'image' => $this->faker->imageUrl(600, 600, 'image', true),
            'preview_text' => $this->faker->text(),
            'detail_text' => $this->faker->text(),
            'author_id' => Author::all()->random()->id,
        ];
    }
}
