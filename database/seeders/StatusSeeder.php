<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'status' => 'На проверке у методиста',
            ],
            [
                'status' => 'Отправлен в отдел',
            ],
            [
                'status' => 'Готов к выдаче',
            ],
            [
                'status' => 'На доработке'
            ],
            [
                'status' => 'Отказ'
            ],

        ];
        DB::table('statuses')->insert($data);
    }
}
