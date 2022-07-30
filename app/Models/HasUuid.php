<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

trait HasUuid
{
    /**
     * @var string
     */
    protected string $uuid_column = 'uuid';


    public static function bootHasUuid() {
        static::creating(function ($model) {
            $model->{$model->uuid_column} = Uuid::uuid4()->toString();
        });
    }

    /**
     * @param $uuid
     * @return Model|null
     */
    public static function findByUuid($uuid) {
        $instance = new static();
        return static::newModelInstance()->where($instance->uuid_column, '=', $uuid)->firstOrFail();
    }
}