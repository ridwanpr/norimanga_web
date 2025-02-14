<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    public const ADMIN = 1;
    public const USER = 2;

    protected $table = 'roles';
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
