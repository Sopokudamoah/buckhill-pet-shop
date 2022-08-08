<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'category_uuid', 'description', 'uuid', 'metadata', 'price'];

    protected $casts = ['metadata' => 'array'];

    protected $attributes = ['metadata' => '[]'];

    /**
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }

    /**
     * @return Brand|null
     */
    public function brand()
    {
        if (!empty($this->metadata) && isset($this->metadata['brand'])) {
            return Brand::findByUuid($this->metadata['brand']);
        }
        return null;
    }
}
