<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'over_name' => '山田',
                'under_name' => '太郎',
                'over_name_kana' => 'ヤマダ',
                'under_name_kana' => 'タロウ',
                'mail_address' => 'yamada.taro@example.com',
                'sex' => 1, //男性
                'birth_day' => '1990-01-01',
                'role' => 1, //管理者
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'over_name' => '佐藤',
                'under_name' => '花子',
                'over_name_kana' => 'サトウ',
                'under_name_kana' => 'ハナコ',
                'mail_address' => 'sato.hanako@example.com',
                'sex' => 2, //女性
                'birth_day' => '1992-05-15',
                'role' => 2, //一般ユーザー
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}