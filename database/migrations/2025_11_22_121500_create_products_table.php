<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            
            // Unit conversion
            $table->string('large_unit')->default('Karton'); // Unit besar
            $table->string('small_unit')->default('Pcs'); // Unit kecil
            $table->integer('conversion_factor')->default(1); // 1 karton = X pcs
            
            // Stock
            $table->integer('stock_large')->default(0); // Stok dalam karton
            $table->integer('stock_small')->default(0); // Stok dalam pcs
            $table->integer('min_stock')->default(10); // Minimum stok warning
            
            // Pricing
            $table->decimal('purchase_price', 15, 2)->default(0); // Harga beli per unit kecil
            $table->decimal('selling_price_retail', 15, 2)->default(0); // Harga jual eceran
            $table->decimal('selling_price_member', 15, 2)->default(0); // Harga jual member
            $table->decimal('selling_price_wholesale_low', 15, 2)->default(0); // Grosir rendah
            $table->decimal('selling_price_wholesale_high', 15, 2)->default(0); // Grosir tinggi
            
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};