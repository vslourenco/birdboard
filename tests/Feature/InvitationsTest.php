<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function no_owners_cannot_invite_users()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();

        $assertInvitationForbidden = function () use ($user, $project){
            $this->actingAs($user)->post($project->path().'/invitations', [
                    'email' => 'mail@mail.com'
                ])
                ->assertStatus(403);
        };

        $assertInvitationForbidden();

        $project->invite($user);

        $assertInvitationForbidden();
    }

    /** @test */
    public function a_project_owner_can_invite_a_user()
    {
        $project = Project::factory()->create();
        $invitedUser = User::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path().'/invitations', [
                'email' => $invitedUser->email
            ])
            ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($invitedUser));
    }

    /** @test */
    public function the_invited_email_address_must_be_associated_with_a_valid_account()
    {
        $project = Project::factory()->create();
        $this->actingAs($project->owner)
            ->post($project->path().'/invitations', [
                'email' => 'invalidaccount@mail.com'
            ])
            ->assertSessionHasErrors([
                'email' => 'The user you are inviting must have an account.'
            ], null, 'invitations');
    }

    /** @test */
    public function invited_users_may_update_project()
    {
        $project = Project::factory()->create();

        $project->invite($newUser = User::factory()->create());

        $this->signIn($newUser);
        $this->post(action('App\Http\Controllers\ProjectTasksController@store', $project), $task = ['body' => 'Test body text']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
