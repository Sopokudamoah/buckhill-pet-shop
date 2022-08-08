<?php

namespace App\Models\Traits;

use Ramsey\Uuid\Uuid;

trait HasUuid
{
    /**
     * @var string
     */
    protected string $uuid_column = 'uuid';


    /**
     * @return void
     */
    public static function bootHasUuid()
    {
        static::creating(function ($model) {
            $model->{$model->uuid_column} = Uuid::uuid4()->toString();
        });
    }

    /**
     * @param $uuid
     * @param string[] $columns
     * @return $this|null
     */
    public static function findByUuid($uuid, array $columns = ['*'])
    {
        $instance = new static();
        return $instance->where($instance->uuid_column, '=', $uuid)->firstOrFail($columns);
    }

    /**
     *  Use uuid column for route resolver
     *
     * @return mixed|string
     */
    public function getRouteKeyName()
    {
        return $this->uuid_column;
    }
}
