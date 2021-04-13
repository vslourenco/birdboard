<div class="card mt-3">
    <ul class="text-sm font-bold list-reset">
        @foreach ($project->activity as $activity)
            <li class="{{$loop->last ? '' : 'mb-1'}}">
                @include("projects.activity.{$activity->description}")
                &nbsp;&nbsp;
                <span class="text-grey font-light">
                    {{ $activity->created_at->diffForHumans() }}
                </span>
            </li>
        @endforeach
    </ul>
</div>
