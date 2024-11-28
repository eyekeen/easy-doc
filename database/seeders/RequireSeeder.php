<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataArray = [
            [
                'key' => 'sname',
                'value' => 'Ваше ФИО',
                'template_id' => 1,
                'data_for' => 3
            ],
            [
                'key' => 'bdate',
                'value' => 'Дата рождения',
                'template_id' => 1,
                'data_for' => 1
            ],
            [
                'key' => 'course',
                'value' => 'Курс',
                'template_id' => 1,
                'data_for' => 1
            ],
            [
                'key' => 'oform',
                'value' => 'Форма обучения',
                'template_id' => 1,
                'data_for' => 1
            ],
            [
                'key' => 'ostart',
                'value' => 'Дата начала обучения',
                'template_id' => 1,
                'data_for' => 1
            ],
            [
                'key' => 'oend',
                'value' => 'Дата окончания обучения',
                'template_id' => 1,
                'data_for' => 1
            ],
            [
                'key' => 'name',
                'value' => 'Ваше ФИО',
                'template_id' => 2,
                'data_for' => 3
            ],
            [
                'key' => 'course',
                'value' => 'Курс',
                'template_id' => 2,
                'data_for' => 1
            ],
            [
                'key' => 'why',
                'value' => 'Основание выдачи справки',
                'template_id' => 1,
                'data_for' => 3
            ],
            [
                'key' => 'aday',
                'value' => 'День',
                'template_id' => 1,
                'data_for' => 3
            ],
            [
                'key' => 'amonth',
                'value' => 'Месяц',
                'template_id' => 1,
                'data_for' => 3
            ],
            [
                'key' => 'ayear',
                'value' => 'Год',
                'template_id' => 1,
                'data_for' => 3
            ],
            [
                'key' => 'number',
                'value' => 'Номер справки',
                'template_id' => 1,
                'data_for' => 3
            ],
        ];

        DB::table('document_requireds')->insert($dataArray);

    }
}
