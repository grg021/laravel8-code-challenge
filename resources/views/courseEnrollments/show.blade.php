<?php
/**
 * @var \App\CourseEnrollment $enrollment
 */
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h2 class="card-header">Lessons</h2>
                    <div class="card-body">
                        <ol>
                            @foreach($enrollment->course->lessons as $lesson)
                                <li>
                                    <a href="{{ route('lessons.show', ['slug' => $enrollment->course->slug, 'number' => $lesson->number]) }}">
                                        {{ $lesson->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                <div class="card mt-4">
                    <h2 class="card-header">Statistics</h2>
                    <div class="card-body">

                        <p>
                            Your rankings improve every time you answer a question correctly.
                            Keep learning and earning course points to become one of our top learners!
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                @if($countryRank)
                                    <h4>You are ranked <b>{{ $countryRank }}</b> in {{ auth()->user()->country->name }}</h4>
                                @else
                                    <h5>You are not yet ranked in {{ auth()->user()->country->name }}</h5>
                                @endif
                                {{--Replace this stub markup by your code--}}
                                <ul style="padding: 0px;">
                                    @if($countryRanking->count() === 0)
                                        <li class="courseRanking__rankItem"
                                            style="display: flex; flex-direction: row; padding: 10px;">
                                            <div>
                                                Quizzes on going...
                                            </div>
                                        </li>
                                    @else
                                        @include('courseEnrollments.rankings', ['rankings' => $countryRanking])
                                    @endif
                                </ul>

                            </div>
                            <div class="col-md-6">
                                @if($worldRank)
                                    <h4>You are ranked <b>{{ $worldRank }}</b> Worldwide</h4>
                                    @else
                                    <h5>You are not yet ranked Worldwide</h5>
                                @endif
                                {{--Replace this stub markup by your code--}}
                                <ul style="padding: 0px;">
                                    @if($worldRanking->count() === 0)
                                        <li class="courseRanking__rankItem"
                                            style="display: flex; flex-direction: row; padding: 10px;">
                                            <div>
                                                Quizzes on going...
                                            </div>
                                        </li>
                                    @else
                                        @include('courseEnrollments.rankings', ['rankings' => $worldRanking])
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
