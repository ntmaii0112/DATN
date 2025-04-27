<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('tb_users');
            $table->foreignId('reported_user_id')->nullable()->constrained('tb_users');
            $table->foreignId('item_id')->nullable()->constrained('tb_items');
            $table->foreignId('transaction_id')->nullable()->constrained('tb_transactions');
            $table->text('reason');
            $table->string('status', 20)->default('pending');
            
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_reports');
    }
};
