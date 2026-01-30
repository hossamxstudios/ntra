<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobile extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'estimated_price',
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
    ];

    public function getTaxAmount(): float
    {
        return $this->estimated_price * 0.37;
    }

    public function getTotalWithTax(): float
    {
        return $this->estimated_price + $this->getTaxAmount();
    }

    public static function findByBrandAndModel(string $brand, string $model): ?self
    {
        return self::whereRaw('LOWER(brand) = ?', [strtolower($brand)])
            ->whereRaw('LOWER(model) LIKE ?', ['%' . strtolower($model) . '%'])
            ->first();
    }
}
