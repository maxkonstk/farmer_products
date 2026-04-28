<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'phone',
        'email',
        'address',
        'fulfillment_method',
        'delivery_window',
        'substitution_preference',
        'comment',
        'total_price',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'total_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if (filled($order->order_number)) {
                return;
            }

            $prefix = Str::upper((string) config('shop.order_number_prefix', 'FL'));

            do {
                $orderNumber = sprintf(
                    '%s-%s-%s',
                    $prefix,
                    now()->format('Ymd'),
                    Str::upper(Str::random(6))
                );
            } while (static::query()->where('order_number', $orderNumber)->exists());

            $order->order_number = $orderNumber;
        });
    }
}
