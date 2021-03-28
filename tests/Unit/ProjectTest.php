<?php

namespace Tests\Unit;

use App\Models\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    /** @test */
    public function it_has_a_path()
    {
        $project = Project::factory()->create();

        $this->assertEquals('/projects/'.$project->id, $project->path());
    }
}
