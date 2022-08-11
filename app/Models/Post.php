<?php

namespace App\Models;

use App\Models\Traits\HasSlug;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use HasUuid;
    use HasSlug;

    protected $fillable = ['uuid', 'title', 'slug', 'content', 'metadata'];

    protected $casts = ['metadata' => 'array'];
}
