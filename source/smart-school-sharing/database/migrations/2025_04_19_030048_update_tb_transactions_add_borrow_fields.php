<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_transactions', function (Blueprint $table) {
        });
    }

    public function down(): void
    {
        Schema::table('tb_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'borrower_name',
                'contact_info',
                'start_date',
                'end_date',
                'purpose',
                'message',
                'request_status',
            ]);
        });
    }
};
