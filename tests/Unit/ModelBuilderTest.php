<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\EloquentModelStub;
use A2Workspace\ModelBuilder\ModelBuilder;

class ModelBuilderTest extends TestCase
{
    public function testBasicTest()
    {
        $task = new BuilderStub;
        $model = $task->create();

        $this->assertInstanceOf(EloquentModelStub::class, $model);
    }

    public function testMutator()
    {
        $_SERVER['__eloquent.saved'] = false;

        $task = new BuilderStub;
        $task->username = 'a2workspace';
        $task->password = 'secret';
        $model = $task->create();

        $this->assertTrue($_SERVER['__eloquent.saved']);

        $attributes = $model->getAttributes();

        $this->assertEquals('a2workspace', $attributes['username']);

        $hash = 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4';

        $this->assertEquals($hash, $attributes['password']);
        $this->assertEquals($hash, $model->password);
    }
}

class BuilderStub extends ModelBuilder
{
    public function getPasswordAttribute($value)
    {
        return sha1($value);;
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = $value;
    }

    public function make()
    {
        return tap(new EloquentModelStub, function ($newModel) {
            $newModel->username = $this->username;
            $newModel->password = $this->password;
        });
    }
}
