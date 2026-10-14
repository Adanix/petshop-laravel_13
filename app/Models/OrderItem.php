<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name_snapshot',
        'price_snapshot',
        'weight_grams_snapshot',
        'quantity',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'price_snapshot' => 'integer',
            'weight_grams_snapshot' => 'integer',
            'quantity' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedPriceSnapshotAttribute(): string
    {
        return 'Rp ' . number_format($this->price_snapshot, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedWeightSnapshotAttribute(): string
    {
        if ($this->weight_grams_snapshot >= 1000) {
            $kg = round($this->weight_grams_snapshot / 1000, 2);
            return number_format($this->weight_grams_snapshot, 0, ',', '.') . " gr ({$kg} Kg)";
        }
        return number_format($this->weight_grams_snapshot, 0, ',', '.') . ' gr';
    }
}
