<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JwtToken extends Model
{
    protected $fillable = ['user_id', 'unique_id', 'token_title', 'restrictions', 'permissions'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
