<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
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
    public function updating_a_task_generates_project_activity()
    {
        $project = app(ProjectFactory::class)
            ->create();

        $project->addTask('Some Task');

        $this->assertCount(2, $project->activity);
        $this->assertEquals('created_task', $project->activity->last()->description);
    }

    /** @test */
    public function completing_a_task_generates_project_activity()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        $project = app(ProjectFactory::class)
            ->ownedBy($user)
            ->withTasks(1)
            ->create();

        $project->tasks->first()->complete();

        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed_task', $project->activity->last()->description);
    }
}
