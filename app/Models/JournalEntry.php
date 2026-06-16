<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'account_no',
        'debit',
        'credit',
        'reference_type',
        'reference_id',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_no', 'account_no_new');
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function scopeForAccount($query, string $accountNo)
    {
        return $query->where('account_no', $accountNo);
    }

    public function scopeDateRange($query, $from, $to)
    {
        if ($from) {
            $query->where('date', '>=', $from);
        }
        if ($to) {
            $query->where('date', '<=', $to);
        }
        return $query;
    }
}
