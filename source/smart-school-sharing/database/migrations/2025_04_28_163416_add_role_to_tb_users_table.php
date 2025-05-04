<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tb_users', function (Blueprint $table) {
            $table->string('role', 50)->default('user')->after('updated_at');
        });
    }

    public function down()
    {
        Schema::table('tb_users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
