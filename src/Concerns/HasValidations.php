<?php

namespace A2Workspace\ModelBuilder\Concerns;

use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use A2Workspace\ModelBuilder\Exceptions\ValidationException as BuilderValidationException;

trait HasValidations
{
    /**
     * 定義是否啟用自動驗證功能。
     *
     * 啟用時會在 beforeCreate() 內呼叫 validate()。
     *
     * @var bool
     */
    public $autoValidate = true;

    /**
     * 驗證生成器設置的值是否符合規則。
     *
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return array
     *
     * @throws \A2Workspace\ModelBuilder\Exceptions\ValidationException
     */
    public function validate(?array $rules = null, array $messages = [], array $customAttributes = []): array
    {
        try {
            return $this->validateWithoutWrapping($rules, $messages, $customAttributes);
        } catch (ValidationException $e) {
            throw BuilderValidationException::convert($e, $this);
        }
    }

    /**
     * 驗證生成器設置的值是否符合規則。
     *
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateWithoutWrapping(array $rules = null, array $messages = null, array $customAttributes = null): array
    {
        $rules = $rules ?: $this->rules();
        $messages = $messages ?: $this->messages();
        $customAttributes = $customAttributes ?: $this->customAttributes();

        return $this->getValidationFactory()->make(
            $this->getAttributes(),
            $rules,
            $messages,
            $customAttributes
        )->validate();
    }

    /**
     * 定義生成器的驗證規則。
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * 定義生成器的驗證錯誤訊息。
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            //
        ];
    }

    /**
     * 定義生成器的自訂欄位。
     *
     * @return array
     */
    public function customAttributes(): array
    {
        return [
            //
        ];
    }

    /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    protected function getValidationFactory()
    {
        return Container::getInstance()->make(Factory::class);
    }
}
