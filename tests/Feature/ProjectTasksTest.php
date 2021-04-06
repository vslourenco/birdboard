<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
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

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = Project::factory()->create();

        $this->post($project->path().'/tasks')->assertRedirect('/login');
    }

    /** @test */
    public function only_the_project_owner_can_add_tasks()
    {
        $this->signIn();

        $project = Project::factory()->create();


        $this->post($project->path().'/tasks', ['body' => 'Test body text'])
            ->assertStatus(403);


        $this->assertDatabaseMissing('tasks', ['body' => 'Test body text']);
    }

    /** @test */
    public function only_the_project_owner_can_update_a_task()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $task = $project->addTask('Test body text');


        $this->patch($task->path(), ['body' => 'updated'])
            ->assertStatus(403);


        $this->assertDatabaseMissing('tasks', ['body' => 'updated']);
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $this->signIn();

        $project = auth()->user()->projects()->create(Project::factory()->raw());

        $task = $project->addTask('Test Task');

        $this->patch($task->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks',  [
            'body' => 'changed',
            'completed' => true
        ]);
    }
}
