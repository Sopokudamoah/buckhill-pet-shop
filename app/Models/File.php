<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = ['name', 'path', 'size', 'type'];

    protected $appends = ['url'];

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Storage::drive('images')->url($attributes['path']),
        );
    }


    public function download()
    {
        return Storage::drive('images')->download($this->path, $this->name);
    }
}
