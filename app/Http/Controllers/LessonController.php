<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Contracts\Support\Renderable;

class LessonController extends Controller
{
    public function show(string $slug, int $number): Renderable
    {
        /** @var Course $course */
        $course = Course::query()
            ->where('slug', $slug)
            ->firstOrFail();

        /** @var Lesson $lesson */
        $lesson = Lesson::query()
            ->with('quizzes')
            ->where('number', $number)
            ->firstOrFail();

        return view('lessons.show', [
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }
}
