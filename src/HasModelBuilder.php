<?php

namespace A2Workspace\ModelBuilder;

use UnexpectedValueException;

trait HasModelBuilder
{
    /**
     * Get a new model builder instance.
     *
     * @param  array  $attributes
     * @return \A2Workspace\ModelBuilder\ModelBuilder
     */
    public static function builder(array $attributes = []): ModelBuilder
    {
        $builder = static::newModelBuilder() ?: ModelBuilder::builderForModel(get_called_class());

        if ($builder instanceof ModelBuilder) {
            $builder->fill($attributes);
            return $builder;
        }

        throw new UnexpectedValueException('必須實做 newModelBuilder() 並回傳 ModelBuilder 實例');

    }

    /**
     * Create a new model builder instance.
     *
     * @return \A2Workspace\ModelBuilder\ModelBuilder
     */
    protected static function newModelBuilder()
    {
        //
    }
}
