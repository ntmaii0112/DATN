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

        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        DB::table('tb_categories')->truncate();
        DB::table('tb_items')->truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();
        $adminId = 1; // Assuming admin user has ID 1

        DB::table('tb_categories')->insert([
            [
                'name' => 'Books',
                'description' => 'Various books for study and leisure',
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
                'icon' => '📚'
            ],
            [
                'name' => 'Stationery',
                'description' => 'Pens, pencils, rulers and other writing tools',
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
                'icon' => '✏️'
            ],
            [
                'name' => 'Gadgets',
                'description' => 'Useful electronic gadgets for students',
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
                'icon' => '💻'
            ],
            [
                'name' => 'Backpacks',
                'description' => 'Backpacks suitable for school and travel',
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
                'icon' => '🎒'
            ],
            [
                'name' => 'Art Supplies',
                'description' => 'Paints, brushes, and other art materials',
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
                'icon' => '🎨'
            ],
            [
                'name' => 'Notebooks',
                'description' => 'Notebooks and journals of all types',
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
                'icon' => '📝'
            ],
            [
                'name' => 'Laptop',
                'description' => 'Laptop',
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
                'icon' => '💻'
            ],
        ]);
    }
}
