<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class TriggersActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project_generates_activity()
    {
        $project = app(ProjectFactory::class)
            ->create();

        $this->assertCount(1, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
    }

    /** @test */
    public function updating_a_project_generates_activity()
    {
        $project = app(ProjectFactory::class)
            ->create();

        $project->update(['title' => 'changed']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated', $project->activity->last()->description);
    }

    /** @test */
    public function creating_a_task_generates_project_activity()
    {
        $project = app(ProjectFactory::class)
            ->create();

        $project->addTask('Some Task');

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function($activity){
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('Some Task', $activity->subject->body);
        });
    }

    /** @test */
    public function completing_a_task_generates_project_activity()
    {
        $user = $this->signIn();

        $project = app(ProjectFactory::class)
            ->ownedBy($user)
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => true
        ]);

        $this->assertCount(3, $project->activity);

        tap($project->activity->last(), function($activity){
            $this->assertEquals('completed_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('foobar', $activity->subject->body);
        });
    }

    /** @test */
    public function incompleting_a_task_generates_project_activity()
    {
        $user = $this->signIn();

        $project = app(ProjectFactory::class)
            ->ownedBy($user)
            ->withTasks(1)
            ->create();


        $this->patch($project->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => true
        ]);

        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => false
        ]);

        $project->refresh();

        $this->assertCount(4, $project->activity);

        tap($project->activity->last(), function($activity){
            $this->assertEquals('incompleted_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('foobar', $activity->subject->body);
        });
    }

    /** @test */
    public function deleting_a_task_generates_project_activity()
    {
        $user = $this->signIn();

        $project = app(ProjectFactory::class)
            ->ownedBy($user)
            ->withTasks(1)
            ->create();

        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);
    }
}
