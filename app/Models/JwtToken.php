<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JwtToken extends Model
{
    protected $fillable = ['user_id', 'unique_id', 'token_title', 'restrictions', 'permissions'];

    protected $casts = ['permissions' => 'json'];
}
