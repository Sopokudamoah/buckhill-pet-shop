<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use HasUuid;
    use Sluggable;

    protected $fillable = ['uuid', 'title', 'slug'];
}
