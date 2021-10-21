<?php

namespace A2Workspace\ModelBuilder\Concerns;

use Illuminate\Support\Str;

trait ResolvesBuilderNames
{
    /**
     * 名稱解析器。
     *
     * @var callable
     */
    protected static $nameResolver = null;

    /**
     * 定義 ModelBuilder 的命名空間所在。
     * 
     * @var string
     */
    protected static $namespace = 'App\\ModelBuilders\\';

    /**
     * 定義 App 的命名空間。
     * 
     * @var string
     */
    protected static $appNamespace = 'App\\';

    /**
     * 透過給定的 Model 名稱，取得一個新的 ModelBuilder 實體。
     *
     * @param  string  $modelName
     * @return static
     */
    public static function builderForModel(string $modelName)
    {
        $factory = static::resolveBuilderName($modelName);

        return new $factory;
    }

    /**
     * 透過給定的 Model 名稱，嘗試取得 ModelBuilder 類別名稱。
     *
     * @param  string  $modelName
     * @return string
     */
    public static function resolveBuilderName(string $modelName)
    {
        $resolver = static::$nameResolver ?: function (string $modelName) {
            $modelName = Str::startsWith($modelName, static::$appNamespace . 'Models\\')
                ? Str::after($modelName, static::$appNamespace . 'Models\\')
                : Str::after($modelName, static::$appNamespace);

            return static::$namespace . $modelName . 'Builder';
        };

        return $resolver($modelName);
    }

    /**
     * 指定一個函式用來處理 ModelBuilder 的名稱解析。
     *
     * @param  callable  $callback
     * @return void
     */
    public static function guessNamesUsing(callable $callback)
    {
        static::$nameResolver = $callback;
    }
}