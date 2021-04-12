<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->create();

        $this->post($project->path().'/tasks', ['body' => 'Test body text']);

        $this->get($project->path())
            ->assertSee('Test body text');
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->create();

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

        $project = app(ProjectFactory::class)
            ->withTasks(1)
            ->create();


        $this->patch($project->tasks->first()->path(), ['body' => 'updated'])
            ->assertStatus(403);


        $this->assertDatabaseMissing('tasks', ['body' => 'updated']);
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks[0]->path(), [
            'body' => 'changed'
        ]);

        $this->assertDatabaseHas('tasks',  [
            'body' => 'changed'
        ]);
    }

    /** @test */
    public function a_task_can_be_completed()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks',  [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    /** @test */
    public function a_task_can_be_marked_as_incompleted()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => false
        ]);

        $this->assertDatabaseHas('tasks',  [
            'body' => 'changed',
            'completed' => false
        ]);
    }
}
