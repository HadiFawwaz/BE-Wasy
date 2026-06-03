<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_code',
        'admin_id',
        'customer_id',
        'service_id',
        'weight',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'payment_reason',
        'payment_proof',
        'condition_photo',
        'paid_at'
    ];

    protected $appends = [
        'payment_proof_url',
        'condition_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'total_price' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof) {
            return null;
        }

        return '/storage/' . ltrim($this->payment_proof, '/');
    }

    public function getConditionPhotoUrlAttribute(): ?string
    {
        if (!$this->condition_photo) {
            return null;
        }

        return '/storage/' . ltrim($this->condition_photo, '/');
    }
}



