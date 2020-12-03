<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $typicalWords = [
            'Design',
            'Thinking',
            'UX',
            'UI',
            'User Experience',
            'How to',
            'Product',
            'Web Design',
            'Visualization',
            'Accessibility',
            'Interaction',
            'Information',
            'Psychology',
            'Management',
            'Emotional',
            'Patterns',
            'for',
            'and',
            'or',
            'with',
            'from scratch',
        ];

        $title = collect($typicalWords)->shuffle()->take(random_int(3, 7))->implode(' ');

        return [
            'title' => $title,
            'slug' => Str::slug($title),
        ];
    }
}
