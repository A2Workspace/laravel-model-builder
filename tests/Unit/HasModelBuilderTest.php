<?php

namespace Tests\Unit;

require __DIR__ . '/stubs/HasModelBuilderTestModelStub.php';
require __DIR__ . '/stubs/HasModelBuilderTestBuilderStub.php';

use Tests\TestCase;
use App\Models\Post;
use App\ModelBuilders\PostBuilder;

class HasModelBuilderTest extends TestCase
{
    public function test_resolve_builder_name()
    {
        $builder = Post::builder();

        $this->assertInstanceOf(PostBuilder::class, $builder);
    }
}
