@extends('layouts.app')

@section('content')

    <header class="flex mb-3 py-4 items-center">
        <div class="flex justify-between items-center w-full">
            <h2 class="text-grey font-normal">
                My Projects
            </h2>
            <a href="/projects/create" class="button">
                New Project
            </a>
        </div>
    </header>

    <main class="lg:flex lg:flex-wrap -mx-3">
        @forelse($projects as $project)
            <div class="lg:w-1/3 px-3 pb-6">
                <div class="bg-white p-5 rounded-lg shadow" style="height: 200px">
                    <h3 class="font-normal text-xl py-4 -ml-5 pl-4 mb-3 border-l-4 border-blue-light">
                        <a class="text-black no-underline" href="{{ $project->path() }}">{{ $project->title }}</a>
                    </h3>
                    <div class="text-grey">
                        {{ Str::limit($project->description, 150) }}
                    </div>
                </div>
            </div>
        @empty
            <h3> No projects yet. </h3>
        @endforelse
    </main>

@endsection
