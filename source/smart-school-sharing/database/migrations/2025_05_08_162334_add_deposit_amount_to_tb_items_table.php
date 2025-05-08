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
        Schema::table('tb_items', function (Blueprint $table) {
            Schema::table('tb_items', function (Blueprint $table) {
                $table->unsignedInteger('deposit_amount')->default(0)->after('item_condition');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_items', function (Blueprint $table) {
            //
        });
    }
};
