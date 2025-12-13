<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Manajer - View all transactions
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'cashier']);
        
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $transactions = $query->latest('transaction_date')
            ->latest('transaction_time')
            ->paginate(20);
        
        return view('transactions.index', compact('transactions'));
    }
    
    // Kasir - View own transactions
    public function kasirIndex(Request $request)
    {
        $query = Transaction::with(['customer'])
            ->where('cashier_id', auth()->id());
        
        if ($request->filled('date')) {
            $query->whereDate('transaction_date', $request->date);
        } else {
            $query->whereDate('transaction_date', today());
        }
        
        $transactions = $query->latest('transaction_time')->paginate(20);
        
        return view('transactions.index', compact('transactions'));
    }
    
    // POS Interface
    public function pos()
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $products  = Product::where('status', 'active')->orderBy('name')->get();
        
        return view('transactions.pos', compact('customers', 'products'));
    }
    
    // Store transaction (dipanggil dari POS Kasir)
    public function store(Request $request)
    {
        // 1. Validasi basic
        $request->validate([
            'customer_id'           => 'nullable|exists:customers,id',
            'items'                 => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.unit'          => 'required|in:large,small',
            'payment_amount'        => 'required|numeric|min:0',
            'payment_method'        => 'required|in:cash,transfer,card',
        ], [
            'items.required'        => 'Keranjang masih kosong, tambahkan produk terlebih dahulu.',
            'items.*.product_id.*'  => 'Produk tidak valid.',
            'items.*.quantity.*'    => 'Jumlah harus minimal 1.',
        ]);
        
        try {
            // 2. Bungkus semuanya dalam transaction DB
            $transaction = DB::transaction(function () use ($request) {
                // Get customer & tipe
                $customer = $request->customer_id ? Customer::find($request->customer_id) : null;
                $customerType = $customer ? $customer->type : 'non_member';
                
                $subtotal = 0;
                $itemsData = [];
                
                // 3. Loop item keranjang
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    
                    // Harga per unit kecil berdasarkan tipe customer
                    $price = $product->getPriceByCustomerType($customerType);
                    
                    // Konversi qty ke unit kecil
                    $quantitySmall = $item['unit'] === 'large'
                        ? $item['quantity'] * $product->conversion_factor
                        : $item['quantity'];
                    
                    // Cek stok
                    $totalStockSmall = ($product->stock_large * $product->conversion_factor) + $product->stock_small;
                    if ($totalStockSmall < $quantitySmall) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi");
                    }
                    
                    $itemSubtotal = $price * $quantitySmall;
                    $subtotal += $itemSubtotal;
                    
                    $itemsData[] = [
                        'product'        => $product,
                        'quantity'       => $item['quantity'],
                        'unit'           => $item['unit'],
                        'quantity_small' => $quantitySmall,
                        'price'          => $price,
                        'subtotal'       => $itemSubtotal,
                    ];
                }
                
                // 4. Hitung diskon & ongkir
                $discountPercentage = 0;
                $freeShipping       = false;
                
                if ($customer) {
                    // Aturan grosir: 5% atau 10% + free shipping
                    $discountPercentage = $customer->getDiscountPercentage();
                    $freeShipping       = $customer->hasFreeShipping();
                }
                
                $discountAmount = ($subtotal * $discountPercentage) / 100;
                // Untuk sekarang ongkir 0, kalau nanti ada aturan ongkir bisa diisi di sini
                $shippingCost   = $freeShipping ? 0 : 0;
                $total          = $subtotal - $discountAmount + $shippingCost;
                
                // 5. Validasi pembayaran
                if ($request->payment_amount < $total) {
                    throw new \Exception("Jumlah pembayaran kurang dari total yang harus dibayar");
                }
                
                $change = $request->payment_amount - $total;
                
                // 6. Simpan header transaksi
                $transaction = Transaction::create([
                    'transaction_code'   => Transaction::generateTransactionCode(),
                    'customer_id'        => $request->customer_id,
                    'cashier_id'         => auth()->id(),
                    'transaction_date'   => now()->toDateString(),
                    'transaction_time'   => now()->toTimeString(),
                    'subtotal'           => $subtotal,
                    'discount_amount'    => $discountAmount,
                    'discount_percentage'=> $discountPercentage,
                    'free_shipping'      => $freeShipping,
                    'shipping_cost'      => $shippingCost,
                    'total'              => $total,
                    'payment_amount'     => $request->payment_amount,
                    'change_amount'      => $change,
                    'payment_method'     => $request->payment_method,
                    'status'             => 'completed',
                ]);
                
                // 7. Simpan detail + update stok + kartu stok
                foreach ($itemsData as $item) {
                    $product = $item['product'];
                    
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id'     => $product->id,
                        'quantity'       => $item['quantity'],
                        'unit'           => $item['unit'],
                        'unit_price'     => $item['price'],
                        'subtotal'       => $item['subtotal'],
                        'discount'       => 0,
                        'total'          => $item['subtotal'],
                    ]);
                    
                    // Simpan stok sebelum perubahan
                    $beforeLarge = $product->stock_large;
                    $beforeSmall = $product->stock_small;
                    
                    $totalSmallToDeduct = $item['quantity_small'];
                    
                    // Kurangi stok kecil dulu
                    if ($product->stock_small >= $totalSmallToDeduct) {
                        $product->stock_small -= $totalSmallToDeduct;
                    } else {
                        $remaining = $totalSmallToDeduct - $product->stock_small;
                        $product->stock_small = 0;
                        
                        // Kurangi stok besar
                        $largeNeeded = (int) ceil($remaining / $product->conversion_factor);
                        $product->stock_large -= $largeNeeded;
                        
                        // Kelebihan dikonversi kembali ke stok kecil
                        $excess = ($largeNeeded * $product->conversion_factor) - $remaining;
                        $product->stock_small = $excess;
                    }
                    
                    $product->save();
                    
                    // Catat di kartu stok
                    StockCard::create([
                        'product_id'             => $product->id,
                        'transaction_date'       => now(),
                        'type'                   => 'out',
                        'reference_type'         => 'Transaction',
                        'reference_id'           => $transaction->id,
                        'quantity_before_large'  => $beforeLarge,
                        'quantity_before_small'  => $beforeSmall,
                        'quantity_change_large'  => $product->stock_large - $beforeLarge,
                        'quantity_change_small'  => $product->stock_small - $beforeSmall,
                        'quantity_after_large'   => $product->stock_large,
                        'quantity_after_small'   => $product->stock_small,
                        'notes'                  => "Transaksi: {$transaction->transaction_code}",
                        'user_id'                => auth()->id(),
                    ]);
                }
                
                return $transaction;
            });

            // 8. Response: kalau request dari JS (AJAX) tetap bisa JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success'     => true,
                    'message'     => 'Transaksi berhasil',
                    'transaction' => $transaction,
                    'redirect'    => route('kasir.transactions.show', $transaction->id),
                ]);
            }

            // Kalau form biasa → redirect ke detail transaksi kasir
            return redirect()
                ->route('kasir.transactions.show', $transaction->id)
                ->with('success', 'Transaksi berhasil disimpan');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()
                ->withErrors(['pos' => $e->getMessage()])
                ->withInput();
        }
    }
    
    public function show(Transaction $transaction)
    {
        $transaction->load(['customer', 'cashier', 'details.product']);
        
        return view('transactions.show', compact('transaction'));
    }
    
    public function print(Transaction $transaction)
    {
        $transaction->load(['customer', 'cashier', 'details.product']);
        
        return view('transactions.print', compact('transaction'));
    }
    
    public function return(Request $request, Transaction $transaction)
    {
        if ($transaction->status === 'returned') {
            return back()->with('error', 'Transaksi sudah di-return sebelumnya');
        }
        
        $request->validate([
            'notes' => 'required|string',
        ]);
        
        DB::transaction(function () use ($request, $transaction) {
            // Restore stock
            foreach ($transaction->details as $detail) {
                $product = $detail->product;
                
                $beforeLarge = $product->stock_large;
                $beforeSmall = $product->stock_small;
                
                if ($detail->unit === 'large') {
                    $product->stock_large += $detail->quantity;
                } else {
                    $product->stock_small += $detail->quantity;
                    
                    // Handle overflow
                    while ($product->stock_small >= $product->conversion_factor) {
                        $product->stock_large += 1;
                        $product->stock_small -= $product->conversion_factor;
                    }
                }
                
                $product->save();
                
                // Record in stock card
                StockCard::create([
                    'product_id'             => $product->id,
                    'transaction_date'       => now(),
                    'type'                   => 'in',
                    'reference_type'         => 'Return',
                    'reference_id'           => $transaction->id,
                    'quantity_before_large'  => $beforeLarge,
                    'quantity_before_small'  => $beforeSmall,
                    'quantity_change_large'  => $product->stock_large - $beforeLarge,
                    'quantity_change_small'  => $product->stock_small - $beforeSmall,
                    'quantity_after_large'   => $product->stock_large,
                    'quantity_after_small'   => $product->stock_small,
                    'notes'                  => "Return: {$transaction->transaction_code}",
                    'user_id'                => auth()->id(),
                ]);
            }
            
            // Update transaction status
            $transaction->update([
                'status' => 'returned',
                'notes'  => $request->notes,
            ]);
        });
        
        return back()->with('success', 'Transaksi berhasil di-return dan stok dikembalikan');
    }
}
