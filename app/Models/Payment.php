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
        'booking_id',
        'payment_reference',
        'amount',
        'currency',
        'payment_method',
        'status',
        'payment_details',
        'provider_transaction_id',
        'provider_response',
        'processed_by',
        'processed_at',
        'verified_at',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'provider_response' => 'array',
        'processed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'processing' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            'cancelled' => 'gray',
        ];

        return $colors[$this->status] ?? 'gray';
    }
}