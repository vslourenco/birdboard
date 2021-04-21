<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_projects()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    public function a_user_has_accessible_projects()
    {
        $userOne = $this->signIn();

        app(ProjectFactory::class)
            ->ownedBy($userOne)
            ->create();

        $this->assertCount(1, $userOne->accessibleProjects());

        $userTwo = User::factory()->create();
        $userThree = User::factory()->create();

        $project = app(ProjectFactory::class)
            ->ownedBy($userTwo)
            ->create();

        $project->invite($userThree);
        $this->assertCount(1, $userOne->accessibleProjects());

        $project->invite($userOne);
        $this->assertCount(2, $userOne->accessibleProjects());
    }
}
