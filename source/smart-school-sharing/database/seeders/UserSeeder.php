<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Nguyễn Thanh Mai',
            'email' => 'maint@gmail.com',
            'email_verified_at' => null,
            'password' => '$2y$12$wAo7r67Jgp/unSgt33e.tuZ6DotZI34blGCgGStpyEEIit6Eqb50a', // đã mã hóa
            'phone' => '+84123456789',
            'address' => 'Xã Xuân Hồng, Huyện Xuân Trường, Tỉnh Nam Định',
            'remember_token' => null,
            'created_at' => '2025-05-01 08:09:04',
            'updated_at' => '2025-05-01 08:09:04',
            'role' => 'admin',
            'status' => 1,
        ]);
    }
}
