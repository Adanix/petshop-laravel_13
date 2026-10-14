<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'tracking_token',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'city_or_district',
        'postal_code',
        'total_weight_grams',
        'courier_code',
        'courier_service',
        'shipping_cost',
        'tracking_number',
        'subtotal',
        'grand_total',
        'payment_method',
        'payment_status',
        'payment_reference',
        'paid_at',
        'order_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_weight_grams' => 'integer',
            'shipping_cost' => 'integer',
            'subtotal' => 'integer',
            'grand_total' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($order) {
            if (empty($order->tracking_token)) {
                $order->tracking_token = (string) Str::uuid();
            }
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));
        return "PET-{$date}-{$random}";
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedShippingCostAttribute(): string
    {
        return 'Rp ' . number_format($this->shipping_cost, 0, ',', '.');
    }

    public function getFormattedGrandTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    public function getFormattedWeightAttribute(): string
    {
        if ($this->total_weight_grams >= 1000) {
            $kg = round($this->total_weight_grams / 1000, 2);
            return number_format($this->total_weight_grams, 0, ',', '.') . " gr ({$kg} Kg)";
        }
        return number_format($this->total_weight_grams, 0, ',', '.') . ' gr';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function getCourierLabelAttribute(): string
    {
        $couriers = [
            'jne' => 'JNE Express',
            'jnt' => 'J&T Express',
            'sicepat' => 'SiCepat',
            'instant' => 'Kurir Instan Lokal',
        ];

        $code = strtolower($this->courier_code);
        $name = $couriers[$code] ?? strtoupper($code);
        return "{$name} ({$this->courier_service})";
    }
}
