<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\EloquentModelStub;
use A2Workspace\ModelBuilder\ModelBuilder;
use A2Workspace\ModelBuilder\Exceptions\CallSubtaskException;

class ModelBuilderSubtaskTest extends TestCase
{
    public function testBasic()
    {
        $_SERVER['__eloquent.saved'] = false;

        $task = new ModelMainBuilderStub;
        $task->username = 'John';
        $task->age = 18;
        $model = $task->create();

        $this->assertTrue($_SERVER['__eloquent.saved']);
        $this->assertInstanceOf(EloquentModelStub::class, $model);

        $products = $task->getProducts();

        $this->assertCount(2, $products);
        $this->assertInstanceOf(EloquentModelStub::class, $products[0]);
        $this->assertInstanceOf(EloquentModelAppendantStub::class, $products[1]);

        $this->assertEquals('John', $products[0]->username);

        // 由主生成器往下傳遞獲得的屬性
        $this->assertEquals(18, $products[1]->age);

        // 由 call() 的附加參數獲得的屬性
        $this->assertEquals('admin', $products[1]->type);

        // 由 getAttribute() 取得上層 mutator 獲得的屬性
        $this->assertEquals('♂♂♂', $products[1]->gender);
    }

    public function testCallSubtaskDirectly()
    {
        $this->expectException(CallSubtaskException::class);

        $builder = new ModelSubtaskBuilderStub;
        $builder->create(); // throw errors.
    }

    public function testCallInvalidSubtask()
    {
        $this->expectException(CallSubtaskException::class);

        $builder = new ModelMainBuilderStub;
        $builder->call(\stdClass::class); // throw errors.
    }
}

class ModelMainBuilderStub extends ModelBuilder
{
    public function getGenderAttribute()
    {
        return '♂♂♂';
    }

    public function make()
    {
        return tap(new EloquentModelStub, function ($newModel) {
            $newModel->username = $this->username;
        });
    }

    public function afterCreatingModel($newModel)
    {
        $this->call(ModelSubtaskBuilderStub::class, [
            'type' => 'admin',
        ]);
    }
}

class ModelSubtaskBuilderStub extends ModelBuilder
{
    protected $isSubtask = true;

    public function make()
    {
        return tap(new EloquentModelAppendantStub, function ($newModel) {
            $newModel->age = $this->age;
            $newModel->type = $this->type;
            $newModel->gender = $this->gender;
        });
    }
}

class EloquentModelAppendantStub extends EloquentModelStub
{
    //
}
