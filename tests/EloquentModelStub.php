<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;

/**
 * @link https://github.com/laravel/framework/blob/8.x/tests/Database/DatabaseEloquentModelTest.php#L2332
 */
class EloquentModelStub extends Model
{
    protected $table = 'save_stub';
    protected $guarded = [];

    public function save(array $options = [])
    {
        if ($this->fireModelEvent('saving') === false) {
            return false;
        }

        $_SERVER['__eloquent.saved'] = true;

        $this->fireModelEvent('saved', false);
    }
}
