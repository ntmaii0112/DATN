<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tb_users');
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('tb_categories');
            $table->string('item_condition', 20);
            $table->string('status', 20);

            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_items');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];


};
