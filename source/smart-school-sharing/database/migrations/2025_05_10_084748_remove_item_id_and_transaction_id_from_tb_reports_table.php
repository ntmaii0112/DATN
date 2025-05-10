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
        Schema::table('tb_reports', function (Blueprint $table) {
            Schema::table('tb_reports', function (Blueprint $table) {
                $table->dropForeign(['item_id']);
                $table->dropForeign(['transaction_id']);

                // Sau đó mới xóa cột
                $table->dropColumn(['item_id', 'transaction_id']);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();

            // Thêm lại foreign key (giả sử các bảng liên quan là `items` và `transactions`)
            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
        });
    }
};
