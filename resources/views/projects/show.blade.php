@extends('layouts.app')

@section('content')
    <header class="flex mb-3 py-4 items-center">
        <div class="flex justify-between items-end w-full">
            <p class="text-grey font-normal">
                <a href="/projects" class="text-grey font-normal no-underline">My Projects</a> / {{ $project->title }}
            </p>
            <a href="/projects/create" class="button">
                New Project
            </a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-grey font-normal text-lg mb-3">
                        Tasks
                    </h2>

                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">
                            {{ $task->body }}
                        </div>
                    @endforeach
                </div>

                <div>
                    <h2 class="text-grey font-normal text-lg mb-3">
                        General Notes
                    </h2>
                    <textarea class="card w-full" style="min-height: 150px">Lorem ipsum</textarea>
                </div>
            </div>
            <div class="lg:w-1/4 px-3">
                @include('projects.card')
            </div>
        </div>
    </main>



@endsection
