@extends('layouts.app')

@section('content')

    <h1>Create a Project</h1>

    <form action="/projects" method="post">
        @csrf

        <label for="">Title</label><br>
        <input type="text" name="title">
        <br><br>
        <label for="">Description</label><br>
        <textarea name="description" id="" cols="30" rows="10"></textarea>
        <br><br>
        <button type="submit">Create a Project</button>
    </form>

@endsection
