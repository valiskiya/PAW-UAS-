<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'customer_id',
        'cashier_id',
        'transaction_date',
        'transaction_time',
        'subtotal',
        'discount_amount',
        'discount_percentage',
        'free_shipping',
        'shipping_cost',
        'total',
        'payment_amount',
        'change_amount',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'transaction_time' => 'datetime:H:i',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'free_shipping' => 'boolean',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Helper: Generate kode transaksi
    public static function generateTransactionCode()
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', now())->latest()->first();
        
        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->transaction_code, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return 'TRX' . $date . $newNumber;
    }
}