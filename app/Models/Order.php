<?php

namespace App\Models;

use App\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'order_status',
        'shipping_address',
        'payment_method',
        'order_date'
    ];

    protected $casts = [
        'total_amount' => 'float',
        'created_at' => 'datetime',
        'order_status' => OrderStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
