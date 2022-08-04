<?php

namespace App\Models;

use App\Models\Scopes\AdminScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends User
{
    use HasFactory;

    protected $table = 'users';

    protected $attributes = ['is_admin' => 1];

    protected static function booted()
    {
        static::addGlobalScope(new AdminScope());

        parent::booted();
    }
}
