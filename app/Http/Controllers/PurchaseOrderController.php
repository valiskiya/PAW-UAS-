<?php
namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'product']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        $purchaseOrders = $query->latest('order_date')->paginate(20);
        $suppliers = Supplier::where('status', 'active')->get();
        
        return view('purchase-orders.index', compact('purchaseOrders', 'suppliers'));
    }
    
    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();
        
        return view('purchase-orders.create', compact('suppliers', 'products'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'order_date' => 'required|date',
            'quantity_large' => 'required|integer|min:0',
            'quantity_small' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        
        $product = Product::find($request->product_id);
        $totalSmall = ($request->quantity_large * $product->conversion_factor) + $request->quantity_small;
        $totalPrice = $totalSmall * $request->unit_price;
        
        PurchaseOrder::create([
            'po_number' => PurchaseOrder::generatePONumber(),
            'supplier_id' => $request->supplier_id,
            'product_id' => $request->product_id,
            'order_date' => $request->order_date,
            'quantity_large' => $request->quantity_large,
            'quantity_small' => $request->quantity_small,
            'unit_price' => $request->unit_price,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('manajer.purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dibuat');
    }
    
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'product', 'receivedBy']);
        
        return view('purchase-orders.show', compact('purchaseOrder'));
    }
    
    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'PO ini sudah di-receive atau dibatalkan');
        }
        
        DB::transaction(function () use ($purchaseOrder) {
            $product = $purchaseOrder->product;
            
            $beforeLarge = $product->stock_large;
            $beforeSmall = $product->stock_small;
            
            // Add stock
            $product->stock_large += $purchaseOrder->quantity_large;
            $product->stock_small += $purchaseOrder->quantity_small;
            
            // Handle overflow
            while ($product->stock_small >= $product->conversion_factor) {
                $product->stock_large += 1;
                $product->stock_small -= $product->conversion_factor;
            }
            
            $product->save();
            
            // Update PO
            $purchaseOrder->update([
                'status' => 'received',
                'received_date' => now(),
                'received_by' => auth()->id(),
            ]);
            
            // Record in stock card
            StockCard::create([
                'product_id' => $product->id,
                'transaction_date' => now(),
                'type' => 'in',
                'reference_type' => 'PurchaseOrder',
                'reference_id' => $purchaseOrder->id,
                'quantity_before_large' => $beforeLarge,
                'quantity_before_small' => $beforeSmall,
                'quantity_change_large' => $purchaseOrder->quantity_large,
                'quantity_change_small' => $purchaseOrder->quantity_small,
                'quantity_after_large' => $product->stock_large,
                'quantity_after_small' => $product->stock_small,
                'notes' => "PO: {$purchaseOrder->po_number}",
                'user_id' => auth()->id(),
            ]);
        });
        
        return back()->with('success', 'Barang berhasil diterima dan stok diupdate');
    }
    
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return back()->with('error', 'PO yang sudah diterima tidak dapat dihapus');
        }
        
        $purchaseOrder->update(['status' => 'cancelled']);
        
        return redirect()->route('manajer.purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dibatalkan');
    }
}