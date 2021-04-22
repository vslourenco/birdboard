@extends('layouts.app')

@section('content')
    <header class="flex mb-3 py-4 items-center">
        <div class="flex justify-between items-end w-full">
            <p class="text-grey font-normal">
                <a href="/projects" class="text-grey font-normal no-underline">My Projects</a> / {{ $project->title }}
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
                    <h2 class="text-grey font-normal text-lg mb-3">
                        Tasks
                    </h2>

                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">
                            <form action="{{ $task->path() }}" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="flex items-center">
                                    <input type="text" name="body" class="w-full border-0 {{ $task->completed ? 'text-grey line-through' : '' }}" value="{{ $task->body }}">
                                    <input type="checkbox" name="completed" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <div class="card mb-3">
                        <form action="{{ $project->path().'/tasks' }}" method="POST">
                            @csrf
                            <input type="text" name="body" placeholder="Add a task..." class="w-full border-0">
                        </form>
                    </div>
                </div>

                <div>
                    <h2 class="text-grey font-normal text-lg mb-3">
                        General Notes
                    </h2>
                    <form action="{{ $project->path() }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <textarea name="notes" class="card w-full mb-4" style="min-height: 150px">{{ $project->notes }}</textarea>
                        <button type="submit" class="button">Save</button>
                    </form>

                    @if ($errors->any())
                        <div class="field mt-6">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-500">{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="lg:w-1/4 px-3">
                @include('projects.card')
                @include('projects.activity.card')
            </div>
        </div>
    </main>



@endsection
