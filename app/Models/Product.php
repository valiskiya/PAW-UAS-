<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'large_unit',
        'small_unit',
        'conversion_factor',
        'stock_large',
        'stock_small',
        'min_stock',
        'purchase_price',
        'selling_price_retail',
        'selling_price_member',
        'selling_price_wholesale_low',
        'selling_price_wholesale_high',
        'status',
    ];

    protected $casts = [
        'conversion_factor' => 'integer',
        'stock_large' => 'integer',
        'stock_small' => 'integer',
        'min_stock' => 'integer',
        'purchase_price' => 'decimal:2',
        'selling_price_retail' => 'decimal:2',
        'selling_price_member' => 'decimal:2',
        'selling_price_wholesale_low' => 'decimal:2',
        'selling_price_wholesale_high' => 'decimal:2',
    ];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function stockCards()
    {
        return $this->hasMany(StockCard::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    // Helper: Total stok dalam unit kecil
    public function getTotalStockSmallAttribute()
    {
        return ($this->stock_large * $this->conversion_factor) + $this->stock_small;
    }

    // Helper: Check apakah stok di bawah minimum
    public function isLowStock()
    {
        return $this->total_stock_small < $this->min_stock;
    }

    // Helper: Konversi unit besar ke kecil
    public function convertLargeToSmall($quantityLarge)
    {
        return $quantityLarge * $this->conversion_factor;
    }

    // Helper: Get harga berdasarkan tipe customer
    public function getPriceByCustomerType($customerType)
    {
        return match($customerType) {
            'member' => $this->selling_price_member,
            'wholesale_low' => $this->selling_price_wholesale_low,
            'wholesale_high' => $this->selling_price_wholesale_high,
            default => $this->selling_price_retail,
        };
    }
}