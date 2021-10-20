<?php

namespace A2Workspace\ModelBuilder\Concerns;

use Exception;
use InvalidArgumentException;
use A2Workspace\ModelBuilder\ModelBuilder;
use Illuminate\Database\Eloquent\Model;
use A2Workspace\ModelBuilder\Exceptions\CallSubtaskException;

trait HasSubtasks
{
    /**
     * 定義是否為子生成器。
     *
     * 子生成器只能透過另一個生成器呼叫使用。
     *
     * @var bool
     */
    protected $isSubtask = false;

    /**
     * 當次模型創見任務的上層生成器。
     *
     * @var \A2Workspace\ModelBuilder\ModelBuilder|null
     */
    protected ?ModelBuilder $superior = null;

    /**
     * 子任務清單。
     *
     * @var \A2Workspace\ModelBuilder\ModelBuilder[]
     */
    protected array $subtasks = [];

    /**
     * 指定上層生成器。
     *
     * @param  \A2Workspace\ModelBuilder\ModelBuilder  $superior
     * @return void
     */
    protected function setSuperior(ModelBuilder $superior): void
    {
        $this->superior = $superior;
    }

    /**
     * 取得當次任務的上層生成器。
     *
     * @return \A2Workspace\ModelBuilder\ModelBuilder|null
     */
    public function getSuperior(): ?ModelBuilder
    {
        return $this->superior;
    }

    /**
     * 檢查子生成器是否有效。
     *
     * @return void
     *
     * @throws \A2Workspace\ModelBuilder\Exceptions\CallSubtaskException
     */
    public function subtaskChecking()
    {
        if ($this->isSubtask && is_null($this->superior)) {
            throw new CallSubtaskException($this, '不能直接調用子生成器');
        }
    }

    /**
     * 呼叫子任務。參數二可傳遞陣列或是函式來處理子生成器。
     *
     * 範例：
     *
     * ```php
     * <php
     *
     * $this->call(Member::class, [
     *     'name' => 'John'
     * ]);
     *
     * $this->call(Member::class, function ($builder) {
     *     $builder->name = 'John';
     * });
     *
     * ?>
     * ```
     *
     * @param  \A2Workspace\ModelBuilder\ModelBuilder|string  $class
     * @param  array|callable  $appends
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \A2Workspace\ModelBuilder\Exceptions\CallSubtaskException
     */
    public function call($class, $appends = null)
    {
        try {
            return $this->callSubtask($class, $appends);
        } catch (Exception $e) {
            throw new CallSubtaskException($this, $e->getMessage());
        }
    }

    /**
     * 執行呼叫子任務的動作。
     *
     * @param  \A2Workspace\ModelBuilder\ModelBuilder|string  $class
     * @param  array|callable  $appends
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function callSubtask($class, $appends)
    {
        $attributes = $this->getAttributes();

        // 若參數二為陣列，則將其與 $attributes 合併。
        if (is_array($appends)) {
            $attributes = array_merge($attributes, $appends);
            $appends = null;
        }

        // 將給定的類別轉換為 ModelBuilder ，並加到子任務列表內。
        $builder = $this->transformModelBuilder($class, [$attributes, $this]);
        $this->pushSubtask($builder);

        // 若參數二為可呼叫的函式，則將子任務傳遞給它處理。
        if ($appends && is_callable($appends)) {
            call_user_func($appends, $builder);
        }

        // 最終我們執行子生成器的創見方法
        return tap($builder->create(), function ($newModel) {
            $this->pushProduct($newModel);
        });
    }

    /**
     * 將給定的類別名稱轉換為 ModelBuilder。失敗則會拋錯。
     *
     * @param  mixed  $class
     * @return \A2Workspace\ModelBuilder\ModelBuilder
     *
     * @throws \InvalidArgumentException
     */
    protected function transformModelBuilder($class, array $params = []): ModelBuilder
    {
        if (is_a($class, ModelBuilder::class, true)) {
            return new $class(...$params);
        }

        throw new InvalidArgumentException(sprintf(
            '並非有效的資料模型生成器: %s',
            $class
        ));
    }

    /**
     * 註冊子任務。
     *
     * @param  \A2Workspace\ModelBuilder\ModelBuilder  $subtask
     * @return void
     */
    protected function pushSubtask(ModelBuilder $subtask)
    {
        $this->subtasks[] = $subtask;
    }

    /**
     * 取得當次任務呼叫的子任務列表。
     *
     * @return \A2Workspace\ModelBuilder\ModelBuilder[]
     */
    public function getSubtasks(): array
    {
        return $this->subtasks;
    }

    /**
     * 取得上層最後生成的資料模型。
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getPrimary(): ?Model
    {
        if (is_null($this->superior)) {
            return null;
        }

        return $this->superior->getProducts()->first();
    }
}
