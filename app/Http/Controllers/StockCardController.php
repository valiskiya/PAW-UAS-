<?php

namespace App\Http\Controllers;

use App\Models\StockCard;
use App\Models\Product;
use Illuminate\Http\Request;

class StockCardController extends Controller
{
    public function index(Request $request)
    {
        $query = StockCard::with(['product', 'user']);
        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }
        
        $stockCards = $query->latest('transaction_date')
            ->latest('created_at')
            ->paginate(50);
            
        $products = Product::orderBy('name')->get();
        
        return view('stock-cards.index', compact('stockCards', 'products'));
    }
    
    public function byProduct(Product $product)
    {
        $stockCards = StockCard::where('product_id', $product->id)
            ->with('user')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            
        return view('stock-cards.by-product', compact('product', 'stockCards'));
    }
}