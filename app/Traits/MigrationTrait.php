<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait MigrationTrait {
    public function getTable(Model $model) {
        return $model->getTable();
    }
}
