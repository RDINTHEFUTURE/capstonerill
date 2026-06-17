<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor',
        'tanggal',

        // Signature
        'signature_data',
        'signature_name',

        // Identitas Penjual
        'npwp_penjual',
        'nama_penjual',
        'alamat_penjual',

        // Identitas Pembeli
        'npwp_pembeli',
        'nama_pembeli',
        'alamat_pembeli',

        'total',
        'currency',
        'qr_payload',
        'qr_image',

        // Status
        'status',
        'paid_at',

        // Notes
        'notes',

        // Direktur / Pejabat
        'pejabat',
        'role_penandatangan',
    ];




    protected $casts = [
        'tanggal' => 'date',
        'paid_at' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isUnpaid(): bool
    {
        return $this->status === 'unpaid';
    }

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid', 'paid_at' => now()]);
    }

    public function markAsUnpaid(): void
    {
        $this->update(['status' => 'unpaid', 'paid_at' => null]);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}


