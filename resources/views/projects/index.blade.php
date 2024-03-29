@extends('layouts.app')

@section('content')

    <header class="flex mb-3 py-4 items-center">
        <div class="flex justify-between items-end w-full">
            <h2 class="text-default font-normal">
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
                @include('projects.card')
            </div>
        @empty
            <h3> No projects yet. </h3>
        @endforelse
    </main>

@endsection
