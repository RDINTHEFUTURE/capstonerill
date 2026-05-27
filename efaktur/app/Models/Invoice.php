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

        // Direktur / Pejabat
        'pejabat',
        'admin_supplier_perusahaan',
    ];




    protected $casts = [
        'tanggal' => 'date',
        'total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}


