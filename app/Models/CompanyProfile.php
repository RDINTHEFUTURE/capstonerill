<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'npwp',
        'address',
        'phone',
        'email',
        'logo',
    ];

    public static function getProfile(): self
    {
        return static::firstOrCreate([], [
            'name' => 'PT Perusahaan Indonesia',
        ]);
    }
}
