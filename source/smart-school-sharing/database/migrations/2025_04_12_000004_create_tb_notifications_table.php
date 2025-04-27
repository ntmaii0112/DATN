<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tb_users');
            $table->text('content');
            $table->boolean('is_read')->default(0);
            
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_notifications');
    }
};
