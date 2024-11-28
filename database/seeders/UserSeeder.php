<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'студент',
                'email' => 'test@mail.ru',
                'role' => 1,
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'методист',
                'email' => 'test2@mail.ru',
                'role' => 2,
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Отдел образования',
                'email' => 'test3@mail.ru',
                'role' => 3,
                'password' => Hash::make('12345678'),
            ],
        ];

        DB::table('users')->insert($data);
    }
}
