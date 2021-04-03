<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $attributes = Project::factory()->raw();
        $project = auth()->user()->projects()->create($attributes);

        $this->post($project->path().'/tasks', ['body' => 'Test body text']);

        $this->get($project->path())
            ->assertSee('Test body text');
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();

        $project = auth()->user()->projects()->create( Project::factory()->raw());

        $attributes = Task::factory()->raw(['body'=>'']);

        $this->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
