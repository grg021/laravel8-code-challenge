@foreach($rankSection as $rank)
<li class="courseRanking__rankItem"
    style="display: flex; flex-direction: row; padding: 10px;">
    <div class="position"
         style="font-size: 28px; color: rgb(132, 132, 132); text-align: right; width: 80px; padding-right: 10px;">
        {{ $rank->rank }}
    </div>
    <div class="info">
        <div class="{{ ($rank->highlight == '1') ? 'font-weight-bold' : '' }}" style="font-size: 16px;">
            {{ $rank->getTitle() }}
        </div>
        <div class="score" style="font-size: 10px; color: rgb(132, 132, 132);">
            {{ $rank->getSubtitle() }}
        </div>
    </div>
</li>
@endforeach
