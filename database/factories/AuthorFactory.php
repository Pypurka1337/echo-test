<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->firstName(), // не нашел подходящих данных
            'last_name' => $this->faker->lastName(),
            'avatar' => $this->faker->imageUrl(600, 600, 'avatar', true),
            'birth_year' => $this->faker->year(),
            'biography' => $this->faker->text(),
        ];
    }
}
