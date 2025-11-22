<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $customers = $query->orderBy('name')->paginate(20);
        
        return view('customers.index', compact('customers'));
    }
    
    public function create()
    {
        return view('customers.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:customers,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:non_member,member,wholesale_low,wholesale_high',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);
        
        $data = $request->all();
        
        // Set discount and free shipping based on type
        if ($request->type === 'wholesale_low') {
            $data['discount_percentage'] = 5;
            $data['free_shipping'] = false;
        } elseif ($request->type === 'wholesale_high') {
            $data['discount_percentage'] = 10;
            $data['free_shipping'] = true;
        } else {
            $data['discount_percentage'] = 0;
            $data['free_shipping'] = false;
        }
        
        if (in_array($request->type, ['member', 'wholesale_low', 'wholesale_high'])) {
            $data['member_since'] = now();
        }
        
        Customer::create($data);
        
        return redirect()->route('manajer.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }
    
    public function show(Customer $customer)
    {
        $transactions = $customer->transactions()
            ->with('cashier')
            ->latest()
            ->take(10)
            ->get();
            
        return view('customers.show', compact('customer', 'transactions'));
    }
    
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }
    
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'code' => 'required|unique:customers,code,' . $customer->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:non_member,member,wholesale_low,wholesale_high',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        $data = $request->all();
        
        // Update discount and free shipping based on type
        if ($request->type === 'wholesale_low') {
            $data['discount_percentage'] = 5;
            $data['free_shipping'] = false;
        } elseif ($request->type === 'wholesale_high') {
            $data['discount_percentage'] = 10;
            $data['free_shipping'] = true;
        } else {
            $data['discount_percentage'] = 0;
            $data['free_shipping'] = false;
        }
        
        $customer->update($data);
        
        return redirect()->route('manajer.customers.index')
            ->with('success', 'Pelanggan berhasil diupdate');
    }
    
    public function destroy(Customer $customer)
    {
        if ($customer->transactions()->count() > 0) {
            return back()->with('error', 'Pelanggan tidak dapat dihapus karena memiliki riwayat transaksi');
        }
        
        $customer->delete();
        
        return redirect()->route('manajer.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }
    
    public function apiSearch(Request $request)
    {
        $query = Customer::where('status', 'active');
        
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($query) use ($q) {
                $query->where('code', 'like', "%{$q}%")
                      ->orWhere('name', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%");
            });
        }
        
        $customers = $query->limit(10)->get();
        
        return response()->json($customers);
    }
}