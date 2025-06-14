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
            if (Schema::hasColumn('tb_transactions', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('tb_transactions', 'payment_status')) {
                $table->dropColumn('payment_status');
            }

            if (Schema::hasColumn('tb_transactions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('tb_transactions', 'payment_reference')) {
                $table->dropColumn('payment_reference');
            }
            if (Schema::hasColumn('tb_transactions', 'deposit_amount')) {
                $table->dropColumn('deposit_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_transactions', 'status')) {
                $table->string('status', 20)->nullable();
            }

            if (!Schema::hasColumn('tb_transactions', 'payment_status')) {
                $table->string('payment_status', 20)->default('unpaid');
            }

            if (!Schema::hasColumn('tb_transactions', 'payment_method')) {
                $table->string('payment_method', 50)->nullable();
            }

            if (!Schema::hasColumn('tb_transactions', 'payment_reference')) {
                $table->string('payment_reference', 50)->nullable();
            }

            if (!Schema::hasColumn('tb_transactions', 'deposit_amount')) {
                $table->string('deposit_amount', 50)->nullable();
            }
        });
    }
};
