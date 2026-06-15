<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $table = 'chart_of_accounts';
    protected $primaryKey = 'account_no_new';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'account_no_new',
        'account_no_old_1',
        'account_no_old_2',
        'account_name',
        'is_header',
        'account_type',
    ];

    protected $casts = [
        'account_no_old_2' => 'integer',
    ];
}
