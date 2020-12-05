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
                                <h4>You are ranked <b>{{ $countryRank }}</b> in {{ auth()->user()->country->name }}</h4>
                                {{--Replace this stub markup by your code--}}
                                <ul style="padding: 0px;">
                                    @include('courseEnrollments.rankings', ['rankings' => $countryRanking])
                                </ul>

                            </div>
                            <div class="col-md-6">
                                <h4>You are ranked <b>{{ $worldRank }}</b> Worldwide</h4>
                                {{--Replace this stub markup by your code--}}
                                <ul style="padding: 0px;">
                                    @include('courseEnrollments.rankings', ['rankings' => $worldRanking])
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
