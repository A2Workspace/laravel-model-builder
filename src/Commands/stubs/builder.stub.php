<?php

namespace App\Services\Builders;

use App\Models\DummyModel;
use A2Workspace\ModelBuilder\ModelBuilder;

class DummyModelBuilder extends ModelBuilder
{

    // =========================================================================
    // = Make
    // =========================================================================

    /**
     * {@inheritDoc}
     */
    public function make(): DummyModel
    {
        return tap(new DummyModel, function (DummyModel $dummy) {
            $dummy->foobar = $this->foobar;
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

    /**
     * {@inheritDoc}
     */
    public function afterCreatingModel($newModel)
    {
        // $this->call(SubtaskBuilder::class);
    }

    // =========================================================================
    // = Mutators
    // =========================================================================

    public function setFoobarAttribute($value)
    {
        $this->attributes['foobar'] = $value;
    }
}
