<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        request()->validate([
            'body' => 'required',
        ]);

        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize($task->project);

        $attibutes = request()->validate([
            'body' => 'required',
        ]);

        $task->update($attibutes);

        $method =  request('completed') ? 'complete' : 'incomplete';
        $task->$method();

        return redirect($project->path());
    }
}
