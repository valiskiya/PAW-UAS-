<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $suppliers = $query->orderBy('name')->paginate(20);
        
        return view('suppliers.index', compact('suppliers'));
    }
    
    public function create()
    {
        return view('suppliers.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:suppliers,code',
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'required|string',
        ]);
        
        Supplier::create($request->all());
        
        return redirect()->route('manajer.suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }
    
    public function show(Supplier $supplier)
    {
        $purchaseOrders = $supplier->purchaseOrders()
            ->with('product')
            ->latest()
            ->take(10)
            ->get();
            
        return view('suppliers.show', compact('supplier', 'purchaseOrders'));
    }
    
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }
    
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'code' => 'required|unique:suppliers,code,' . $supplier->id,
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        $supplier->update($request->all());
        
        return redirect()->route('manajer.suppliers.index')
            ->with('success', 'Supplier berhasil diupdate');
    }
    
    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchaseOrders()->count() > 0) {
            return back()->with('error', 'Supplier tidak dapat dihapus karena memiliki riwayat purchase order');
        }
        
        $supplier->delete();
        
        return redirect()->route('manajer.suppliers.index')
            ->with('success', 'Supplier berhasil dihapus');
    }
}