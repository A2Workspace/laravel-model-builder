<?php

namespace Tests\Unit;

use Tests\TestCase;
use A2Workspace\ModelBuilder\Concerns\ResolvesBuilderNames;

class ResolvesBuilderNamesTest extends TestCase
{
    public function test_resolve_builder_name()
    {
        $this->assertEquals(
            'App\\ModelBuilders\\PostBuilder',
            ModelBuilder::resolveBuilderName('App\\Models\\Post')
        );

        $this->assertEquals(
            'App\\ModelBuilders\\PostBuilder',
            ModelBuilder::resolveBuilderName('App\\Post')
        );

        $this->assertEquals(
            'App\\ModelBuilders\\Admin\\PostBuilder',
            ModelBuilder::resolveBuilderName('App\\Admin\\Post')
        );
    }
}

// =============================================================================
// = Stubs
// =============================================================================

abstract class ModelBuilder
{
    use ResolvesBuilderNames;
}
