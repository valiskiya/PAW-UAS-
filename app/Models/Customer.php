<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'phone',
        'email',
        'address',
        'member_since',
        'discount_percentage',
        'free_shipping',
        'status',
    ];

    protected $casts = [
        'member_since' => 'date',
        'discount_percentage' => 'decimal:2',
        'free_shipping' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Helper: Get discount berdasarkan tipe
    public function getDiscountPercentage()
    {
        return match($this->type) {
            'wholesale_low' => 5,
            'wholesale_high' => 10,
            default => 0,
        };
    }

    // Helper: Check apakah dapat free shipping
    public function hasFreeShipping()
    {
        return $this->type === 'wholesale_high';
    }
}