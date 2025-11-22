<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            
            $table->integer('total_shifts')->default(0);
            $table->integer('total_present')->default(0);
            $table->integer('total_leave')->default(0);
            $table->integer('total_sick')->default(0);
            $table->integer('total_alpha')->default(0);
            
            $table->integer('salary_grade');
            $table->decimal('base_salary_per_shift', 15, 2);
            $table->decimal('total_salary', 15, 2);
            
            $table->date('payment_date')->nullable();
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Unique: satu karyawan hanya bisa punya 1 record gaji per bulan
            $table->unique(['employee_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};