<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('giver_id')->constrained('tb_users');
            $table->foreignId('receiver_id')->constrained('tb_users');
            $table->foreignId('item_id')->constrained('tb_items');

            $table->string('status', 20);

            // ➕ Thông tin mở rộng như yêu cầu
            $table->string('borrower_name')->nullable();
            $table->string('contact_info')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('purpose')->nullable();
            $table->text('message')->nullable();
            $table->enum('request_status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');

            $table->timestamps();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_transactions');
    }
};
