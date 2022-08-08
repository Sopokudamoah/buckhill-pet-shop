<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 *
 */
trait Sluggable
{
    /**
     * @var string
     */
    protected string $sluggable_column = 'title';


    /**
     * @var string
     */
    protected string $slug_column = 'slug';


    /**
     * @return void
     */
    public static function bootSluggable()
    {
        static::creating(function (Model $model) {
            $model->{$model->slug_column} = Str::slug($model->{$model->sluggable_column});
        });

        static::updating(function (Model $model) {
            $model->{$model->slug_column} = Str::slug($model->{$model->sluggable_column});
        });
    }
}
