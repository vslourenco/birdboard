<div class="card flex flex-col" style="height: 200px">
    <h3 class="font-normal text-xl py-4 -ml-5 pl-4 mb-3 border-l-4 border-blue-light">
        <a class="text-default no-underline" href="{{ $project->path() }}">{{ $project->title }}</a>
    </h3>
    <div class="text-default mb-4 flex-1">
        {{ Str::limit($project->description, 150) }}
    </div>

    @can('manage', $project)
        <footer>
            <form method="POST" action="{{ $project->path() }}" class="text-right">
                @csrf
                @method("DELETE")

                <button class="text-xs" type="submit">Delete</button>
            </form>
        </footer>
    @endcan
</div>
