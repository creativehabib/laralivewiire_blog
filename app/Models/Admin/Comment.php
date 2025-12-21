<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'name',
        'email',
        'website',
        'comment',
        'status',
        'user_id',
        'ip_address',
        'user_agent',
    ];
}
