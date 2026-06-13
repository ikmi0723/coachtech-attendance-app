<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => '管理者ユーザー',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => '西 玲奈',
            'email' => 'user1@example.com',
            'role' => 'user',
            'email_verified_at' => Carbon::now(), 
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => '山田 太郎',
            'email' => 'user2@example.com',
            'role' => 'user',
            'email_verified_at' => Carbon::now(), 
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => '増田 一世',
            'email' => 'user3@example.com',
            'role' => 'user',
            'email_verified_at' => Carbon::now(), 
            'password' => Hash::make('password123'),
        ]);
    }
}
