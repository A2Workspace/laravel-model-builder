<?php

namespace App\Services\Builders;

use App\Models\DummyModel;
use A2Workspace\ModelBuilder\ModelBuilder;

class DummyModelBuilder extends ModelBuilder
{
    /**
     * 定義是否為子生成器。
     *
     * @var bool
     */
    protected $isSubtask = true;

    // =========================================================================
    // = Make
    // =========================================================================

    /**
     * {@inheritDoc}
     */
    public function make(): DummyModel
    {
        return tap(new DummyModel, function (DummyModel $dummy) {
            $dummy->parent_id = $this->getPrimary()->getKey();
        });
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            // ...
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function messages(): array
    {
        return [
            // ...
        ];
    }

    // =========================================================================
    // = Mutators
    // =========================================================================

    public function setFoobarAttribute($value)
    {
        $this->attributes['foobar'] = $value;
    }
}
