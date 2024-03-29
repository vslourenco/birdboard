<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = Project::factory()->raw(['owner_id' => auth()->id()]);

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $user = $this->signIn();


        $project = app(ProjectFactory::class)
            ->ownedBy($user)
            ->create();

        $this->get($project->path().'/edit')->assertStatus(200);

        $this->patch($project->path(), $attributes = ['title' => 'Changed', 'description' => 'Changed', 'notes' => 'Changed'])
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_update_a_project_general_notes()
    {
        $user = $this->signIn();


        $project = app(ProjectFactory::class)
            ->ownedBy($user)
            ->create();

        $this->get($project->path())->assertStatus(200);

        $this->patch($project->path(), $attributes = ['notes' => 'Changed'])
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->create();

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function a_user_can_delete_their_project()
    {
        $project = app(ProjectFactory::class)
            ->ownedBy($this->signIn())
            ->create();

        $this->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function unauthorized_user_cannot_delete_a_project()
    {
        $project = app(ProjectFactory::class)
            ->create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())
            ->assertStatus(403);

        $project->invite($user);

        $this->delete($project->path())
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_project_of_others()
    {
        $this->signIn();
        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_project_of_others()
    {
        $this->signIn();
        $project = Project::factory()->create();

        $this->patch($project->path())->assertStatus(403);
    }

    /** @test */
    public function an_user_can_see_all_projects_they_have_been_invited()
    {
        $userOne = $this->signIn();

        $project = tap(app(ProjectFactory::class)->create())->invite($userOne);

        $this->get('/projects')->assertSee($project->title);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = Project::factory()->raw(['title'=>'']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = Project::factory()->raw(['description'=>'']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function guests_cannot_manage_projects()
    {
        $project = Project::factory()->create();

        $this->get('/projects')->assertRedirect('/login');
        $this->get('/projects/create')->assertRedirect('/login');
        $this->get($project->path().'/edit')->assertRedirect('/login');
        $this->post('/projects', $project->toArray())->assertRedirect('/login');
        $this->get($project->path())->assertRedirect('/login');
    }
}
