@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('manajer.suppliers.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Supplier</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('manajer.suppliers.update', $supplier->id) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Supplier -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Kode Supplier <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $supplier->code) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror" 
                           required>
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nama Supplier -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Nama Supplier <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Contact Person -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Contact Person
                    </label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Telepon -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror" 
                           required>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            
            <!-- Alamat -->
            <div class="mt-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea name="address" rows="4" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror" 
                          required>{{ old('address', $supplier->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('manajer.suppliers.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection