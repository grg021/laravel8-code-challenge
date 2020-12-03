<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizAnswersSeeder extends Seeder
{
    private const NUMBER_OF_USERS = 200;

    /** @var \Illuminate\Support\Collection */
    private $countryCodes;
    /** @var \Illuminate\Support\Collection */
    private $courseIds;

    public function __construct()
    {
        $this->countryCodes = Country::all(['code'])->pluck('code');
        $this->courseIds = Course::all(['id'])->pluck('id');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < self::NUMBER_OF_USERS; $i++) {
            $user = User::factory()->state(['country_code' => $this->countryCodes->random()])->create();
            $this->enrolInCoursesAndGenerateAnswers($user, random_int(0, $this->courseIds->count()));
        }
    }

    private function enrolInCoursesAndGenerateAnswers(User $user, int $numberOfCoursesToEnrol)
    {
        $userEnrolledTo = [];

        for ($i = 0; $i < $numberOfCoursesToEnrol; $i++) {
            $courseIdToEnrol = $this->courseIds->diff($userEnrolledTo)->random();
            $userEnrolledTo[] = $courseIdToEnrol;
            $enrollment = CourseEnrollment::factory()->state([
                'course_id' => $courseIdToEnrol,
                'user_id' => $user->id,
            ])->create();

            $this->generateAnswersForEnrollment($enrollment);
        }
    }

    private function generateAnswersForEnrollment(CourseEnrollment $enrollment)
    {
        $allQuizzesFromCourse = $enrollment->course->quizzes;
        $quizzesToGenerateAnswers = $allQuizzesFromCourse->take(random_int(0, $allQuizzesFromCourse->count()));

        $quizzesToGenerateAnswers->each(function (Quiz $quiz) use ($enrollment) {
            QuizAnswer::factory()->state([
                'quiz_id' => $quiz->id,
                'user_id' => $enrollment->user->id,
                'score' => random_int(0, $quiz->max_score),
            ])->create();
        });
    }
}
