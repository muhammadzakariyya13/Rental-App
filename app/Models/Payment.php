<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_id',
        'amount',
        'status',
        'method',
        'customer_name',
        'customer_email',
        'customer_phone',
        'expired_at',
        'paid_at',
        'gateway_response',
        'payment_url',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'gateway_response' => 'array',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PAID,
            self::STATUS_FAILED,
            self::STATUS_EXPIRED,
            self::STATUS_CANCELLED,
        ];
    }

    // Relationships
    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'order_id', 'id_pemesanan');
    }

    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'customer_email', 'email');
    }

    // Scopes
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeExpiring(Builder $query): Builder
    {
        return $query->where('expired_at', '<=', now())
                    ->where('status', self::STATUS_PENDING);
    }

    public function scopeForCustomer(Builder $query, string $email): Builder
    {
        return $query->where('customer_email', $email);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED || $this->expired_at->isPast();
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING]);
    }

    public function markAsPaid(array $gatewayResponse = []): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
            'gateway_response' => array_merge($this->gateway_response ?? [], $gatewayResponse),
        ]);
    }

    public function markAsFailed(array $gatewayResponse = []): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'gateway_response' => array_merge($this->gateway_response ?? [], $gatewayResponse),
        ]);
    }

    public function markAsExpired(): void
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    public function cancel(): void
    {
        if ($this->canBeCancelled()) {
            $this->update([
                'status' => self::STATUS_CANCELLED,
            ]);
        }
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
