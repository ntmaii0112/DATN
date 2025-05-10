<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_transactions', function (Blueprint $table) {
            $table->string('payment_method')->nullable(); // Thêm cột payment_method
            $table->string('payment_reference')->nullable(); // Thêm cột payment_reference
            $table->decimal('deposit_amount', 10, 2)->nullable(); // Thêm cột deposit_amount
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_reference');
            $table->dropColumn('deposit_amount');
        });
    }
};
