<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'amount',
        'due_date',
        'payment_date',
        'status',
        'payment_method',
        'reference_month',
        'notes',
        'gateway_transaction_id',
        'idempotency_key',
    ];

    protected $casts = [
        'due_date' => 'date',
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Scopes Locais para filtragem financeira
     */

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')
            ->where('due_date', '>=', now()->startOfDay());
    }

    public function scopeOverdue($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'late')
                ->orWhere(function ($sq) {
                    $sq->where('status', 'pending')
                       ->where('due_date', '<', now()->startOfDay());
                });
        });
    }

    /**
     * Accessor para retornar o status formatado em português
     */
    public function getPaymentStatusAttribute(): string
    {
        if ($this->status === 'paid' || $this->payment_date !== null) {
            return 'Recebido';
        }

        if ($this->due_date < now()->startOfDay()) {
            return 'Inadimplente';
        }

        return 'Pendente';
    }
}
