<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    use HasUuid;
    use HasSlug;

    protected $fillable = ['uuid', 'title', 'slug'];
}
