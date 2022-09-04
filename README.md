<h1 align="center">Laravel Model Builder</h1>
<p align="center">
<a href="https://github.com/A2Workspace/laravel-model-builder">
    <img alt="" src="https://github.com/A2Workspace/laravel-model-builder/actions/workflows/coverage.yml/badge.svg">
</a>
<a href="https://github.com/A2Workspace/laravel-model-builder">
    <img alt="" src="https://img.shields.io/github/workflow/status/A2Workspace/laravel-model-builder/tests?style=flat-square">
</a>
<a href="https://codecov.io/gh/A2Workspace/laravel-model-builder">
    <img alt="" src="https://img.shields.io/codecov/c/github/A2Workspace/laravel-model-builder.svg?style=flat-square">
</a>
<a href="https://github.com/A2Workspace/laravel-model-builder/blob/master/LICENSE">
    <img alt="" src="https://img.shields.io/github/license/A2Workspace/laravel-model-builder?style=flat-square">
</a>
<a href="https://packagist.org/packages/a2workspace/laravel-model-builder">
    <img alt="" src="https://img.shields.io/packagist/v/a2workspace/laravel-model-builder.svg?style=flat-square">
</a>
<a href="https://packagist.org/packages/a2workspace/laravel-model-builder">
    <img alt="" src="https://img.shields.io/packagist/dt/a2workspace/laravel-model-builder.svg?style=flat-square">
</a>
</p>

`ModelBuilder` 提供創見複雜資料的模型生成器。

- [快速開始](##快速開始)
- [安裝](##安裝)
- [寫一個模型生成器](##寫一個模型生成器)
  - [定義如何製作模型](##定義如何製作模型)
- [修改器方法 (Accessors & Mutators)](##修改器方法)

## 快速開始

一個簡單範例:
```php
namespace App\ModelBuilders;

use App\Models\Product;
use A2Workspace\ModelBuilder\ModelBuilder;

class ProductBuilder extends ModelBuilder
{
    public function make()
    {
        $product = new Product;
        
        $product->name = $this->name;
        $product->price = $this->price;
        // ...

        return $product;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'required|int|min:1',
        ];
    }
}
```

## 安裝

```bash
composer require a2workspace/laravel-model-builder
```

## 寫一個模型生成器

要創見一個新的模型生成器，可以執行 `make:builder` artisan 命令：

```bash
php artisan make:builder ProductBuilder
```

### 定義如何製作模型

在 `make()` 方法中初始化並定義模型的屬性。

```php
class ProductBuilder extends ModelBuilder
{
    public function make(): Product
    {
        $product = new Product;
        
        $product->name = $this->name;
        $product->price = $this->price;
        // ...

        return $product;
    }
}
```

## 修改器方法

`ModelBuilder` 有提供類似於 `Model` 的修改器方法。詳細參考官方文件 [Accessors & Mutators](https://laravel.com/docs/8.x/eloquent-mutators#accessors-and-mutators)。

```php
class ProductBuilder extends ModelBuilder
{
    public function setPriceAttribute($value)
    {
        if (0 >= $value) {
            throw new InvalidArgumentException('價格必須大於 0');
        }

        $this->attributes['price'] = $value;
    }
}
```

## 驗證

在 `create()` 時會進行驗證。

```php
class ProductBuilder extends ModelBuilder
{
    public function rules(): array
    {
        return [
            // ...
        ];
    }

    public function messages(): array
    {
        return [
            // ...
        ];
    }
}
```