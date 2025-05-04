<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Tạo 50 tài khoản
        for ($i = 0; $i < 50; $i++) {
            DB::table('tb_users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Mật khẩu mặc định là 'password'
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
                'role' => $faker->randomElement(['admin', 'user']), // Vai trò ngẫu nhiên (admin hoặc user)
                'status' => $faker->randomElement([1, 0]), // Trạng thái hoạt động hoặc bị khóa
            ]);
        }
    }
}
