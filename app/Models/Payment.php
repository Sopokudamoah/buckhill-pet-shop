<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = ['type', 'details'];

    protected $casts = ['details' => 'array'];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}
