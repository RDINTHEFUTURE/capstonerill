<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'success',
        'ip_address',
        'user_agent',
        'logged_at',
    ];

    protected $casts = [
        'success' => 'boolean',
        'logged_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
