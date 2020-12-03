<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $course = Course::factory()->create();
            $this->addLessonsToCourse($course);
        }
    }

    private function addLessonsToCourse(Course $course): void
    {
        $numberOfLessons = random_int(8, 15);

        for ($i = 0; $i < $numberOfLessons; $i++) {
            $lesson = Lesson::factory()->state([
                'course_id' => $course->getKey(),
                'number' => $i + 1,
            ])->create();
            $this->addQuizzesToLesson($lesson);
        }
    }

    private function addQuizzesToLesson(Lesson $lesson): void
    {
        $numberOfQuizzes = random_int(1, 5);

        for ($i = 0; $i < $numberOfQuizzes; $i++) {
            Quiz::factory()->state([
                'lesson_id' => $lesson->getKey(),
                'max_score' => random_int(5, 10),
            ])->create();
        }
    }
}
