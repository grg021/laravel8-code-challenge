<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Api\SectionsBuilder;
use App\Api\UserRankingsInterface;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\RankItem;
use App\Api\UserRankingsQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class CourseEnrollmentController extends Controller
{

    /**
     * @var UserRankingsQuery
     */
    private UserRankingsInterface $rankingsQuery;
    /**
     * @var SectionsBuilder
     */
    private SectionsBuilder $sectionsBuilder;

    /**
     * CourseEnrollmentController constructor.
     * @param  UserRankingsInterface  $rankingsQuery
     * @param  SectionsBuilder  $sectionsBuilder
     */
    public function __construct(UserRankingsInterface $rankingsQuery, SectionsBuilder $sectionsBuilder)
    {
        $this->rankingsQuery = $rankingsQuery;
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

        $rankings = $this->rankingsQuery->course($course->id)->get();

        $worldRankings = $this->sectionsBuilder
            ->initialize($rankings, $user->id)
            ->build()
            ->transform(RankItem::class)
            ->get();

        $rankings = $this->rankingsQuery->course($course->id)->country($user->country_code)->get();

        $countryRankings = $this->sectionsBuilder
            ->initialize($rankings, $user->id)
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
