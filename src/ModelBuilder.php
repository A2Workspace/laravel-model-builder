<?php

namespace A2Workspace\ModelBuilder;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Builder 資料模型建構器
 *
 * create()
 *   - beforeCreate()
 *   - make()
 *     - validate()
 *       - rules()
 *       - messages()
 *     - new Model
 *   - creating()
 *   - afterCreatingModel()
 *
 */
abstract class ModelBuilder
{
    use Concerns\HasAttributes,
        Concerns\HasSubtasks,
        Concerns\HasValidations;

    /**
     * 呼叫生成器創見後的產物集合。
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $taskProducts;

    /**
     * 建立新的生成器實體
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [], ModelBuilder $superior = null)
    {
        $this->taskProducts = new Collection;

        $this->fill($attributes);

        if ($superior) {
            $this->setSuperior($superior);
        }
    }

    /**
     * Fill the builder attributes with array.
     *
     * 給予陣列，一次設置多個屬性值。
     *
     * @param  array  $attributes
     * @return void
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Override builder attributes forcibly with array.
     *
     * 給予陣列，強制取代掉特定的屬性。
     *
     * @param array $attributes
     * @return void
     */
    public function forceFill(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    /**
     * 定義如何生成一個 Model。
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function make();

    /**
     * 開始創見任務。
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create()
    {
        $this->beforeCreate();

        $this->subtasks = [];
        $this->taskProducts = new Collection;

        return tap($this->make(), function (Model $newModel) {
            $this->pushProduct($newModel);

            $this->creating($newModel);

            $this->callAfterCreatingModel($newModel);
        });
    }

    /**
     * 在創見前做一些檢查。
     *
     * @return void
     *
     * @throws \A2Workspace\ModelBuilder\Exceptions\ModelBuilderException
     */
    protected function beforeCreate(): void
    {
        $this->subtaskChecking();

        if ($this->autoValidate) {
            $this->validate();
        }
    }

    /**
     * 保存新創見的資料模型。
     *
     * @param  \Illuminate\Database\Eloquent\Model $newModel
     * @return void
     */
    protected function creating(Model $newModel): void
    {
        $newModel->save();
    }

    /**
     * 嘗試呼叫 afterCreatingModel 方法。
     *
     * @param \Illuminate\Database\Eloquent\Model $newModel
     * @return void
     */
    protected function callAfterCreatingModel(Model $newModel)
    {
        if (method_exists($this, 'afterCreatingModel')) {
            call_user_func([$this, 'afterCreatingModel'], $newModel);
        }
    }

    /**
     * 註冊一個產物。
     *
     * @param  \Illuminate\Database\Eloquent\Model $product
     * @return void
     */
    protected function pushProduct(Model $product)
    {
        $this->taskProducts->push($product);
    }

    /**
     * 取得當次生成任務產生的所有產物的集合。
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProducts(): Collection
    {
        return $this->taskProducts;
    }

    /**
     * 動態的方式取得生成器屬性
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * 動態的方式設定生成器屬性
     *
     * @param  string  $key
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }
}
