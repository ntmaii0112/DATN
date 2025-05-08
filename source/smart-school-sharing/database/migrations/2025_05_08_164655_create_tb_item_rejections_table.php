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
        Schema::create('tb_item_rejections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->text('reason');
            $table->timestamps();

            // Thêm index trước khi tạo foreign key
            $table->index('item_id');
            $table->index('rejected_by');

            $table->foreign('item_id')
                ->references('id')
                ->on('tb_items')
                ->onDelete('cascade');

            // Đảm bảo bảng tb_users tồn tại và có cột id kiểu unsignedBigInteger
            $table->foreign('rejected_by')
                ->references('id')
                ->on('tb_users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tb_item_rejections', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['rejected_by']);
        });

        Schema::dropIfExists('tb_item_rejections');
    }
};
