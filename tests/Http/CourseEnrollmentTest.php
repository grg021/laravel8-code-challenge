<?php

namespace Tests\Http;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CourseEnrollmentTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function make_sure_page_is_working()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $response = $this->actingAs($user)
            ->get("/courses/$course->slug");

        $response->assertOk();
    }

}
