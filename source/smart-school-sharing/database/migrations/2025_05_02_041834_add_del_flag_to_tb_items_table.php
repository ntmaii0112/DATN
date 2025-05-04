<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tb_items', function (Blueprint $table) {
            $table->boolean('del_flag')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('tb_items', function (Blueprint $table) {
            $table->dropColumn('del_flag');
        });
    }
};
