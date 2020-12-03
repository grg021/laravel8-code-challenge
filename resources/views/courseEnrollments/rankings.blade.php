@foreach($rankings as $key => $rankSection)
    @include('courseEnrollments.section', compact('rankSection'))
    @if ($key + 1 < $rankings->count())
        <hr/>
    @endif
@endforeach
