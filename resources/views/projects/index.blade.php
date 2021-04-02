@extends('layouts.app')

@section('content')

    <div class="flex mb-3 items-center">
        <a href="/projects/create">New Project</a>
    </div>

    <div class="flex">
        @forelse($projects as $project)
            <div class="bg-white mr-4 p-5 rounded shadow w-1/3" style="height: 200px">
                <h3 class="font-normal text-xl py-4">
                    {{ $project->title }}
                </h3>
                <div class="text-grey">
                    {{ Str::limit($project->description, 150) }}
                </div>
            </div>
        @empty
            <h3> No projects yet. </h3>
        @endforelse
    </div>

@endsection
