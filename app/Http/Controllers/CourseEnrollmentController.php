<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\UserRankings\CountryRanking;
use App\UserRankings\LeaderBoardFactory;
use App\UserRankings\RankingsBuilderInterface;
use App\UserRankings\CourseRankings;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\RankItem;
use App\UserRankings\CourseRankingsQuery;
use App\UserRankings\WorldRanking;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class CourseEnrollmentController extends Controller
{

    /**
     * @var CourseRankingsQuery
     */
    private CourseRankings $rankingsQuery;
    /**
     * @var RankingsBuilderInterface
     */
    private RankingsBuilderInterface $sectionsBuilder;

    /**
     * CourseEnrollmentController constructor.
     * @param  CourseRankings  $rankingsQuery
     * @param  RankingsBuilderInterface  $sectionsBuilder
     */
    public function __construct(RankingsBuilderInterface $sectionsBuilder)
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

        $rankItems = (new WorldRanking($course->id))->get();

        $worldRanking = (new LeaderBoardFactory($this->sectionsBuilder))
            ->getLeaderboard($rankItems, $user->id);

        $worldRank = $this->sectionsBuilder->getUserRank();

        $countryRankItems = (new CountryRanking($course->id, $user->country_code))->get();

        $countryRanking = (new LeaderBoardFactory($this->sectionsBuilder))
            ->getLeaderboard($countryRankItems, $user->id);

        $countryRank = $this->sectionsBuilder->getUserRank();

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
