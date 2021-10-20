<?php

namespace A2Workspace\ModelBuilder\Exceptions;

use Exception;
use A2Workspace\ModelBuilder\ModelBuilder;

class ModelBuilderException extends Exception
{
    /**
     * 生成器實體。
     *
     * @var \A2Workspace\ModelBuilder\ModelBuilder
     */
    public ModelBuilder $builder;

    /**
     * 建立一個生成器驗證錯誤類別。
     *
     * @param  \A2Workspace\ModelBuilder\ModelBuilder  $builder
     * @param  string|null
     * @return void
     */
    public function __construct(ModelBuilder $builder, $message = null)
    {
        parent::__construct($message);

        $this->builder = $builder;
    }

    /**
     * Get all of the validation error messages.
     *
     * @link https://github.com/laravel/framework/blob/6.x/src/Illuminate/Validation/ValidationException.php#L85
     *
     * @return array
     */
    public function getModelBuilder(): ModelBuilder
    {
        return $this->builder;
    }
}
