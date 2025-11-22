<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('employee_code')->unique();
            $table->string('full_name');
            $table->string('phone');
            $table->text('address');
            $table->date('hire_date');
            $table->enum('position', ['kasir', 'logistik', 'manajer', 'lainnya'])->default('kasir');
            $table->integer('salary_grade')->default(3); // Strata 3,4,5,6,7
            $table->decimal('base_salary_per_shift', 15, 2)->default(100000); // Base untuk strata 3
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};