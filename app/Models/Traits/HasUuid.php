<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
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
     * @param bool $with_trashed
     * @return $this|null
     */
    public static function findByUuid($uuid, array $columns = ['*'], $with_trashed = false)
    {
        $instance = new static();
        return $instance->where($instance->uuid_column, '=', $uuid)
            ->when(method_exists($instance, 'trashed') && $with_trashed, function ($q) {
                $q->withTrashed();
            })
            ->firstOrFail($columns);
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


    /**
     * @param Builder $builder
     * @param array|string $value
     * @return void
     */
    public function scopeUuid(Builder $builder, array|string $value)
    {
        $column = "{$this->getTable()}.{$this->uuid_column}";
        if (is_array($value)) {
            $builder->whereIn($column, $value);
        }

        if (is_string($value)) {
            $builder->where($column, '=', $value);
        }
    }
}
