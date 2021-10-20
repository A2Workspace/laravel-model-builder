<?php

namespace A2Workspace\ModelBuilder\Exceptions;

use A2Workspace\ModelBuilder\ModelBuilder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException as IValidationException;

class ValidationException extends ModelBuilderException
{
    /**
     * 驗證器實體。
     *
     * @var \Illuminate\Contracts\Validation\Validator
     */
    public $validator;

    /**
     * 建立一個生成器驗證錯誤類別。
     *
     * @param  \A2Workspace\ModelBuilder\ModelBuilder  $builder
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    public function __construct(ModelBuilder $builder, Validator $validator)
    {
        parent::__construct($builder, '參數設定驗證失敗: '.$validator->errors()->first());

        $this->validator = $validator;
    }

    /**
     * 將 Laravel 的 ValidationException 類轉換為當前類別。
     *
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @param  \A2Workspace\ModelBuilder\ModelBuilder  $builder
     * @return static
     */
    public static function convert(IValidationException $exception, ModelBuilder $builder): self
    {
        return new static($builder, $exception->validator);
    }

    /**
     * Get all of the validation error messages.
     *
     * @link https://github.com/laravel/framework/blob/6.x/src/Illuminate/Validation/ValidationException.php#L85
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors()->messages();
    }
}
