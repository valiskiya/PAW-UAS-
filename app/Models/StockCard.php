<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'transaction_date',
        'type',
        'reference_type',
        'reference_id',
        'quantity_before_large',
        'quantity_before_small',
        'quantity_change_large',
        'quantity_change_small',
        'quantity_after_large',
        'quantity_after_small',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity_before_large' => 'integer',
        'quantity_before_small' => 'integer',
        'quantity_change_large' => 'integer',
        'quantity_change_small' => 'integer',
        'quantity_after_large' => 'integer',
        'quantity_after_small' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}