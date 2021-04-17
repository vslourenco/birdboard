<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_user()
    {
        $user = $this->signIn();

        $project = app(ProjectFactory::class)
            ->ownedBy($user)
            ->create();

        $this->assertEquals($user->id, $project->activity->first()->user->id);
    }
}
