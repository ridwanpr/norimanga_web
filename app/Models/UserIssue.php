<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIssue extends Model
{
    protected $table = 'user_issues';
    protected $fillable = [
        'url', 'desc', 'created_at', 'is_solved', 'updated_at'
    ];
}
