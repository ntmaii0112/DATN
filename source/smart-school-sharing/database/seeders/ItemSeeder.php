<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [];
        $conditions = ['new', 'used'];
        $statuses = ['available', 'unavailable'];
        $names = ['Sách', 'Bút', 'Cặp', 'Laptop', 'Điện thoại', 'Bàn học', 'Ghế', 'Áo khoác', 'Đèn bàn', 'Giày thể thao'];
        $adjectives = ['cũ', 'mới', 'đẹp', 'hơi trầy', 'như mới', 'đã qua sử dụng', 'tốt'];
        $descriptions = [
            'Dùng tốt, không lỗi',
            'Có vài vết trầy nhỏ',
            'Thích hợp cho học sinh',
            'Mới dùng vài lần',
            'Đã được vệ sinh sạch sẽ',
            'Chưa qua sửa chữa'
        ];

        for ($i = 0; $i < 50; $i++) {
            $items[] = [
                'user_id' => rand(1, 2),
                'name' => $names[array_rand($names)] . ' ' . $adjectives[array_rand($adjectives)],
                'description' => $descriptions[array_rand($descriptions)],
                'category_id' => rand(1, 6),
                'item_condition' => $conditions[array_rand($conditions)],
                'status' => $statuses[array_rand($statuses)],
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'created_by' => 1,
                'updated_at' => Carbon::now(),
                'updated_by' => 1,
            ];
        }

        DB::table('tb_items')->insert($items);
    }
}
