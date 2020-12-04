<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Api\UserRankingsBuilder;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\RankItem;
use App\Query\UserRankingsQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class CourseEnrollmentController extends Controller
{

    public function show(string $courseSlug): Renderable
    {
        $user = auth()->user();
        /** @var Course $course */
        $course = Course::query()->where('slug', $courseSlug)->firstOrFail();

        /** @var CourseEnrollment $enrollment */
        $enrollment = CourseEnrollment::query()
            ->with('course.lessons')
            ->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->first();

        if ($enrollment === null) {
            return view('courses.show', ['course' => $course]);
        }

        $query = new UserRankingsQuery();
        $builder = new UserRankingsBuilder(collect(), $user->id);

        $rankings = $query->course($course->id)->get();

        $worldRankings = $builder
            ->initialize($rankings)
            ->build()
            ->transform(RankItem::class)
            ->get();

        $rankings = $query->course($course->id)->country($user->country_code)->get();

        $countryRankings = $builder
            ->initialize($rankings)
            ->build()
            ->transform(RankItem::class)
            ->get();

        return view('courseEnrollments.show', [
            'enrollment' => $enrollment,
            'countryRanking' => $countryRankings,
            'worldRanking' => $worldRankings
        ]);
    }

    public function store(string $courseSlug): RedirectResponse
    {
        /** @var Course $course */
        $course = Course::query()->where('slug', $courseSlug)->firstOrFail();

        $course->enroll(auth()->user());

        return redirect()->action([self::class, 'show'], [$course->slug]);
    }
}
