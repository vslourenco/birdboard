@extends('layouts.app')

@section('content')

    <ul class="mt-4 ml-10">
        @forelse($projects as $project)
            <li>
                <a href="{{ $project->path() }}">{{ $project->title }}</a>
            </li>
        @empty
            <h3> No projects yet. </h3>
        @endforelse
    </ul>

@endsection
