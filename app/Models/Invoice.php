<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor',
        'tanggal',

        // Signature: one of 'qr' (DJP stamp) or 'hand' (drawn signature)
        'signature_type',
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

        // Audit: tracks which user created this invoice (immutable after creation)
        'created_by',

        // Approval workflow
        'approval_status',
        'reviewed_by',
        'reviewed_at',
        'revision_notes',
    ];

    public const APPROVAL_PENDING = 'pending_review';
    public const APPROVAL_REVISION = 'revision_needed';
    public const APPROVAL_APPROVED = 'approved';

    protected $casts = [
        'tanggal' => 'date',
        'paid_at' => 'datetime',
        'reviewed_at' => 'datetime',
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

    public function isPendingReview(): bool
    {
        return $this->approval_status === self::APPROVAL_PENDING;
    }

    public function isRevisionNeeded(): bool
    {
        return $this->approval_status === self::APPROVAL_REVISION;
    }

    public function isApproved(): bool
    {
        return $this->approval_status === self::APPROVAL_APPROVED;
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}


