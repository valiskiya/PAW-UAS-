<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $products = $query->orderBy('name')->paginate(20);
        $categories = Product::distinct()->pluck('category');
        
        return view('products.index', compact('products', 'categories'));
    }
    
    public function create()
    {
        $categories = Product::distinct()->pluck('category');
        return view('products.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:products,code',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'large_unit' => 'required|string|max:50',
            'small_unit' => 'required|string|max:50',
            'conversion_factor' => 'required|integer|min:1',
            'min_stock' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price_retail' => 'required|numeric|min:0',
            'selling_price_member' => 'required|numeric|min:0',
            'selling_price_wholesale_low' => 'required|numeric|min:0',
            'selling_price_wholesale_high' => 'required|numeric|min:0',
        ]);
        
        Product::create($request->all());
        
        return redirect()->route('manajer.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }
    
    public function show(Product $product)
    {
        $stockCards = StockCard::where('product_id', $product->id)
            ->with('user')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
            
        return view('products.show', compact('product', 'stockCards'));
    }
    
    public function edit(Product $product)
    {
        $categories = Product::distinct()->pluck('category');
        return view('products.edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code' => 'required|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'large_unit' => 'required|string|max:50',
            'small_unit' => 'required|string|max:50',
            'conversion_factor' => 'required|integer|min:1',
            'min_stock' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price_retail' => 'required|numeric|min:0',
            'selling_price_member' => 'required|numeric|min:0',
            'selling_price_wholesale_low' => 'required|numeric|min:0',
            'selling_price_wholesale_high' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);
        
        $product->update($request->all());
        
        return redirect()->route('manajer.products.index')
            ->with('success', 'Produk berhasil diupdate');
    }
    
    public function destroy(Product $product)
    {
        // Cek apakah produk pernah digunakan dalam transaksi
        if ($product->transactionDetails()->count() > 0) {
            return back()->with('error', 'Produk tidak dapat dihapus karena sudah ada dalam riwayat transaksi');
        }
        
        $product->delete();
        
        return redirect()->route('manajer.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
    
    public function adjustStock(Request $request, Product $product)
    {
        $request->validate([
            'adjustment_type' => 'required|in:add,subtract',
            'quantity_large' => 'required|integer|min:0',
            'quantity_small' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);
        
        DB::transaction(function () use ($request, $product) {
            $beforeLarge = $product->stock_large;
            $beforeSmall = $product->stock_small;
            
            if ($request->adjustment_type === 'add') {
                $product->stock_large += $request->quantity_large;
                $product->stock_small += $request->quantity_small;
                $changeLarge = $request->quantity_large;
                $changeSmall = $request->quantity_small;
            } else {
                $product->stock_large -= $request->quantity_large;
                $product->stock_small -= $request->quantity_small;
                $changeLarge = -$request->quantity_large;
                $changeSmall = -$request->quantity_small;
            }
            
            // Handle overflow/underflow
            while ($product->stock_small >= $product->conversion_factor) {
                $product->stock_large += 1;
                $product->stock_small -= $product->conversion_factor;
            }
            
            while ($product->stock_small < 0 && $product->stock_large > 0) {
                $product->stock_large -= 1;
                $product->stock_small += $product->conversion_factor;
            }
            
            $product->save();
            
            // Record in stock card
            StockCard::create([
                'product_id' => $product->id,
                'transaction_date' => now(),
                'type' => $request->adjustment_type === 'add' ? 'in' : 'out',
                'reference_type' => 'Manual Adjustment',
                'quantity_before_large' => $beforeLarge,
                'quantity_before_small' => $beforeSmall,
                'quantity_change_large' => $changeLarge,
                'quantity_change_small' => $changeSmall,
                'quantity_after_large' => $product->stock_large,
                'quantity_after_small' => $product->stock_small,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
            ]);
        });
        
        return back()->with('success', 'Stok berhasil disesuaikan');
    }
    
    // Conversion page for Logistik
    public function conversionPage()
    {
        $products = Product::where('status', 'active')
            ->where('stock_large', '>', 0)
            ->get();
            
        return view('products.conversion', compact('products'));
    }
    
    public function executeConversion(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_large' => 'required|integer|min:1',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock_large < $request->quantity_large) {
            return back()->with('error', 'Stok ' . $product->large_unit . ' tidak mencukupi');
        }
        
        DB::transaction(function () use ($request, $product) {
            $beforeLarge = $product->stock_large;
            $beforeSmall = $product->stock_small;
            
            $convertedSmall = $request->quantity_large * $product->conversion_factor;
            
            $product->stock_large -= $request->quantity_large;
            $product->stock_small += $convertedSmall;
            $product->save();
            
            // Record conversion
            StockCard::create([
                'product_id' => $product->id,
                'transaction_date' => now(),
                'type' => 'conversion',
                'reference_type' => 'Unit Conversion',
                'quantity_before_large' => $beforeLarge,
                'quantity_before_small' => $beforeSmall,
                'quantity_change_large' => -$request->quantity_large,
                'quantity_change_small' => $convertedSmall,
                'quantity_after_large' => $product->stock_large,
                'quantity_after_small' => $product->stock_small,
                'notes' => "Konversi {$request->quantity_large} {$product->large_unit} → {$convertedSmall} {$product->small_unit}",
                'user_id' => auth()->id(),
            ]);
        });
        
        return back()->with('success', 'Konversi unit berhasil dilakukan');
    }
    
    // Stock opname
    public function stockOpname()
    {
        $products = Product::where('status', 'active')->get();
        return view('products.stock-opname', compact('products'));
    }
    
    public function saveStockOpname(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.actual_large' => 'required|integer|min:0',
            'products.*.actual_small' => 'required|integer|min:0',
        ]);
        
        DB::transaction(function () use ($request) {
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                
                if ($product->stock_large != $item['actual_large'] || $product->stock_small != $item['actual_small']) {
                    $beforeLarge = $product->stock_large;
                    $beforeSmall = $product->stock_small;
                    
                    $changeLarge = $item['actual_large'] - $product->stock_large;
                    $changeSmall = $item['actual_small'] - $product->stock_small;
                    
                    $product->stock_large = $item['actual_large'];
                    $product->stock_small = $item['actual_small'];
                    $product->save();
                    
                    // Record adjustment
                    StockCard::create([
                        'product_id' => $product->id,
                        'transaction_date' => now(),
                        'type' => 'adjustment',
                        'reference_type' => 'Stock Opname',
                        'quantity_before_large' => $beforeLarge,
                        'quantity_before_small' => $beforeSmall,
                        'quantity_change_large' => $changeLarge,
                        'quantity_change_small' => $changeSmall,
                        'quantity_after_large' => $product->stock_large,
                        'quantity_after_small' => $product->stock_small,
                        'notes' => 'Penyesuaian stok opname',
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        });
        
        return back()->with('success', 'Stock opname berhasil disimpan');
    }
    
    // API Methods
    public function apiSearch(Request $request)
    {
        $query = Product::where('status', 'active');
        
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($query) use ($q) {
                $query->where('code', 'like', "%{$q}%")
                      ->orWhere('name', 'like', "%{$q}%");
            });
        }
        
        $products = $query->limit(10)->get();
        
        return response()->json($products);
    }
    
    public function getPrice(Request $request, Product $product)
    {
        $customerType = $request->get('customer_type', 'non_member');
        $price = $product->getPriceByCustomerType($customerType);
        
        return response()->json([
            'price' => $price,
            'product' => $product
        ]);
    }
}