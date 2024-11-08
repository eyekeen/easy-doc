<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'origin_name' => 'Cправка о стипендии',
                'name' => 'iahiucf12iu1fi2.docx',
                'path' => 'templates/iahiucf12iu1fi2.docx'
            ],
            [
                'origin_name' => 'Cправка об обучении',
                'name' => 'dqwd12e1dq12.docx',
                'path' => 'templates/dqwd12e1dq12.docx'
            ],
        ];

        DB::table('document_templates')->insert($data);
    }
}
