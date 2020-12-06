<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Leaderboards\CountryRanking;
use App\Leaderboards\LeaderBoardFactory;
use App\Leaderboards\LeaderboardBuilder;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Leaderboards\WorldRanking;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class CourseEnrollmentController extends Controller
{

    /**
     * @var LeaderboardBuilder
     */
    private LeaderboardBuilder $sectionsBuilder;

    /**
     * CourseEnrollmentController constructor.
     * @param  LeaderboardBuilder  $sectionsBuilder
     */
    public function __construct(LeaderboardBuilder $sectionsBuilder)
    {
        $this->sectionsBuilder = $sectionsBuilder;
    }

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

        $worldLeaderboard = (new LeaderBoardFactory($this->sectionsBuilder))
            ->getLeaderboard((new WorldRanking($course->id)), $user->id);

        $countryLeaderboard = (new LeaderBoardFactory($this->sectionsBuilder))
            ->getLeaderboard((new CountryRanking($course->id, $user->country_code)), $user->id);

        return view('courseEnrollments.show', [
                'enrollment' => $enrollment,
                'countryRanking' => $countryLeaderboard->getSections(),
                'countryRank' => $countryLeaderboard->getUserRank(),
                'worldRanking' => $worldLeaderboard->getSections(),
                'worldRank' => $worldLeaderboard->getUserRank(),
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
