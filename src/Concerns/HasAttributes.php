<?php

namespace A2Workspace\ModelBuilder\Concerns;

use Illuminate\Support\Str;
use InvalidArgumentException;
use A2Workspace\ModelBuilder\ModelBuilder;
use Illuminate\Database\Eloquent\Model;

trait HasAttributes
{
    /**
     * 生成器的屬性參數
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * Get an attribute.
     *
     * @link https://github.com/laravel/framework/blob/6.x/src/Illuminate/Database/Eloquent/Concerns/HasAttributes.php#L312
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (!$key) {
            return;
        }

        // 首先我們在這裡先判斷是否有對應的 "get" mutator 方法。
        // 有的話嘗試將屬性自列表取出，然後傳遞給 mutator 方法做處理。
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $this->getAttributeFromArray($key));
        }

        // 接著我們判斷參數若存在的話，就直接回傳。
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        // 再不行的話，這邊我們嘗試取得上層生成器的屬性。
        if ($superior = $this->getSuperior()) {
            return $superior->$key;
        }

        return null;
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @link https://github.com/laravel/framework/blob/6.x/src/Illuminate/Database/Eloquent/Concerns/HasAttributes.php#L377
     *
     * @param  string  $key
     * @return mixed
     */
    protected function getAttributeFromArray($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @link https://github.com/laravel/framework/blob/6.x/src/Illuminate/Database/Eloquent/Concerns/HasAttributes.php#L440
     *
     * @param  string  $key
     * @return bool
     */
    protected function hasGetMutator($key): bool
    {
        return method_exists($this, 'get' . Str::studly($key) . 'Attribute');
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @link https://github.com/laravel/framework/blob/6.x/src/Illuminate/Database/Eloquent/Concerns/HasAttributes.php#L452
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get' . Str::studly($key) . 'Attribute'}($value);
    }

    /**
     * Set a given attribute on the model.
     *
     * @link https://github.com/laravel/framework/blob/6.x/src/Illuminate/Database/Eloquent/Concerns/HasAttributes.php#L567
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if ($this->hasSetMutator($key)) {
            return $this->setMutatedAttributeValue($key, $value);
        }

        return $this->attributes[$key] = $value;
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @link https://github.com/laravel/framework/blob/6.x/src/Illuminate/Database/Eloquent/Concerns/HasAttributes.php#L605
     *
     * @param  string  $key
     * @return bool
     */
    protected function hasSetMutator($key): bool
    {
        return method_exists($this, 'set' . Str::studly($key) . 'Attribute');
    }

    /**
     * Set the value of an attribute using its mutator.
     *
     * @link https://github.com/laravel/framework/blob/6.x/src/Illuminate/Database/Eloquent/Concerns/HasAttributes.php#L617
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function setMutatedAttributeValue($key, $value)
    {
        return $this->{'set' . Str::studly($key) . 'Attribute'}($value);
    }

    /**
     * 取得當前生成器的所有屬性。
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
