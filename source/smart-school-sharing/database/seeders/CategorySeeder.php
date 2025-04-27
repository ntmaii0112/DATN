<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset AUTO_INCREMENT
        DB::statement('ALTER TABLE tb_categories AUTO_INCREMENT = 1');

        // Xoá dữ liệu cũ (nếu cần)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // truncate các bảng
        DB::table('tb_categories')->truncate();
        DB::table('tb_items')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('tb_categories')->insert([
            ['name' => 'Books', 'description' => 'Various books for study and leisure', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'Stationery', 'description' => 'Pens, pencils, rulers and other writing tools', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'Gadgets', 'description' => 'Useful electronic gadgets for students', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'Backpacks', 'description' => 'Backpacks suitable for school and travel', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'Art Supplies', 'description' => 'Paints, brushes, and other art materials', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'created_by' => 1, 'updated_by' => 1],
            ['name' => 'Notebooks', 'description' => 'Notebooks and journals of all types', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'created_by' => 1, 'updated_by' => 1],
        ]);
    }
}
