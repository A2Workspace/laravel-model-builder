<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\EloquentModelStub;
use A2Workspace\ModelBuilder\ModelBuilder;
use A2Workspace\ModelBuilder\Exceptions\ValidationException;

class ModelBuilderValidationTest extends TestCase
{
    public function testValidates()
    {
        $builder = new ModelBuilderValidationStub;
        $builder->name = 'M24型德國香腸';
        $builder->price = 1924;

        $product = $builder->create();

        $this->assertInstanceOf(EloquentModelStub::class, $product);
    }

    public function testValidatesFailsTask1()
    {
        $this->expectException(ValidationException::class);

        $builder = new ModelBuilderValidationStub;
        $builder->create(); // throw errors.
    }

    public function testValidatesFailsTask2()
    {
        $this->expectException(ValidationException::class);

        $builder = new ModelBuilderValidationStub;
        $builder->name = 'M24型德國香腸同梱包組';
        $builder->price = 13468;

        $builder->create(); // throw errors.
    }
}

class ModelBuilderValidationStub extends ModelBuilder
{
    public function make()
    {
        $this->validate();

        return tap(new EloquentModelStub, function ($newModel) {
            $newModel->username = $this->username;
        });
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'price' => 'integer|min:1|max:10000',
        ];
    }
}
