<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'max_score' => random_int(5, 10),
            'question' => sprintf('Is %s similar to %s?', $this->faker->colorName, $this->faker->colorName),
        ];
    }
}
