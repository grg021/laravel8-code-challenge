@foreach($rankSection as $rank)
<li class="courseRanking__rankItem"
    style="display: flex; flex-direction: row; padding: 10px;">
    <div class="position"
         style="font-size: 28px; color: rgb(132, 132, 132); text-align: right; width: 80px; padding-right: 10px;">
        {{ $rank->rank }}
    </div>
    <div class="info">
        <div class="{{ isset($rank->highlight) ? 'font-weight-bold' : '' }}" style="font-size: 16px;">
            {{ $rank->title }}
        </div>
        <div class="score" style="font-size: 10px; color: rgb(132, 132, 132);">
            {{ $rank->subtitle }}
        </div>
    </div>
</li>
@endforeach
