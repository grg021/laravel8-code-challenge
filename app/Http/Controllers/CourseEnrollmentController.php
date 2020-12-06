<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\UserRankings\CountryRanking;
use App\UserRankings\LeaderBoardFactory;
use App\UserRankings\LeaderboardBuilder;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\UserRankings\WorldRanking;
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

        $worldRankList = (new WorldRanking($course->id))->get();
        $worldRank = getUserRank($worldRankList, $user->id);
        $worldRanking = (new LeaderBoardFactory($this->sectionsBuilder))
            ->getLeaderboard($worldRankList, $user->id);

        $countryRankList = (new CountryRanking($course->id, $user->country_code))->get();
        $countryRank = getUserRank($countryRankList, $user->id);
        $countryRanking = (new LeaderBoardFactory($this->sectionsBuilder))
            ->getLeaderboard($countryRankList, $user->id);

        return view(
            'courseEnrollments.show',
            compact('enrollment', 'countryRanking', 'countryRank', 'worldRanking', 'worldRank')
        );
    }

    public function store(string $courseSlug): RedirectResponse
    {
        /** @var Course $course */
        $course = Course::query()->where('slug', $courseSlug)->firstOrFail();

        $course->enroll(auth()->user());

        return redirect()->action([self::class, 'show'], [$course->slug]);
    }

}
