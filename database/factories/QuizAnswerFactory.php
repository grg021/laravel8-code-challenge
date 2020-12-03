<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\QuizAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuizAnswer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'answer' => $this->faker->sentence,
            'score' => random_int(0, 5),
        ];
    }
}
