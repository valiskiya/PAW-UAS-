<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'product_id',
        'order_date',
        'received_date',
        'quantity_large',
        'quantity_small',
        'unit_price',
        'total_price',
        'status',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'order_date' => 'date',
        'received_date' => 'date',
        'quantity_large' => 'integer',
        'quantity_small' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Helper: Generate PO Number
    public static function generatePONumber()
    {
        $date = now()->format('Ymd');
        $lastPO = self::whereDate('created_at', now())->latest()->first();
        
        if ($lastPO) {
            $lastNumber = intval(substr($lastPO->po_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return 'PO' . $date . $newNumber;
    }
}