@extends('layouts.app')

@section('content')
    <header class="flex mb-3 py-4 items-center">
        <div class="flex justify-between items-end w-full">
            <p class="text-default font-normal">
                <a href="/projects" class="text-default font-normal no-underline">My Projects</a> / {{ $project->title }}
            </p>
            <div class="flex items-center">
                @foreach ($project->members as $member)
                    <img src="{{ gravatar_url($member->email) }}" alt="{{ $member->name }}'s avatar" class="rounded-full w-8 mr-2">
                @endforeach
                <img src="{{ gravatar_url($project->owner->email) }}" alt="{{ $project->owner->name }}'s avatar" class="rounded-full w-8 mr-2">
                <a href="{{ $project->path().'/edit' }}" class="button ml-6">
                    Edit Project
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-default font-normal text-lg mb-3">
                        Tasks
                    </h2>

                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">
                            <form action="{{ $task->path() }}" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="flex items-center">
                                    <input type="text" name="body" class="w-full bg-card text-default border-0 {{ $task->completed ? 'text-default line-through' : '' }}" value="{{ $task->body }}">
                                    <input type="checkbox" name="completed" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <div class="card mb-3">
                        <form action="{{ $project->path().'/tasks' }}" method="POST">
                            @csrf
                            <input type="text" name="body" placeholder="Add a task..." class="w-full border-0 bg-card text-default">
                        </form>
                    </div>
                </div>

                <div>
                    <h2 class="text-default font-normal text-lg mb-3">
                        General Notes
                    </h2>
                    <form action="{{ $project->path() }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <textarea name="notes" class="card w-full mb-4" style="min-height: 150px">{{ $project->notes }}</textarea>
                        <button type="submit" class="button">Save</button>
                    </form>

                    @include('errors')
                </div>
            </div>
            <div class="lg:w-1/4 px-3 lg:py-8">
                @include('projects.card')
                @include('projects.activity.card')

                @can('manage', $project)
                    <div class="card flex flex-col mt-3">
                        <h3 class="font-normal text-xl py-4 -ml-5 pl-4 mb-3 border-l-4 border-blue-light">
                            Invite a User
                        </h3>
                        <form method="POST" action="{{ $project->path().'/invitations' }}">
                            @csrf

                            <div class="mb-3">
                                <input type="email" name="email" class="border border-grey rounded w-full py-2 px-3" placeholder="Email address">
                            </div>

                            <button class="button" type="submit">Invite</button>
                        </form>
                        @include('errors', ['bag' => 'invitations'])
                    </div>
                @endcan

            </div>
        </div>
    </main>



@endsection
