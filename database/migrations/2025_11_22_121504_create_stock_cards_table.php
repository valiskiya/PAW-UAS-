<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->date('transaction_date');
            $table->enum('type', ['in', 'out', 'adjustment', 'conversion']); // Tipe pergerakan stok
            $table->string('reference_type')->nullable(); // PO, Transaction, Manual
            $table->unsignedBigInteger('reference_id')->nullable();
            
            $table->integer('quantity_before_large')->default(0);
            $table->integer('quantity_before_small')->default(0);
            
            $table->integer('quantity_change_large')->default(0);
            $table->integer('quantity_change_small')->default(0);
            
            $table->integer('quantity_after_large')->default(0);
            $table->integer('quantity_after_small')->default(0);
            
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_cards');
    }
};