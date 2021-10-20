# A2Workspace/ModelBuilder

VSCode 可開啟 `Ctrl + Shift + V` 閱讀。

- [簡介](##簡介)
- [安裝](##安裝)
- [寫一個模型生成器](##寫一個模型生成器)
  - [定義如何製作模型](##定義如何製作模型)
- [修改器方法 (Accessors & Mutators)](##修改器方法)

## 簡介

`ModelBuilder` 提供創見複雜資料的模型生成器。

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
composer config repositories.hooks vcs https://github.com/A2Workspace/laravel-model-builder.git
composer require "a2workspace/model-builder:*"
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