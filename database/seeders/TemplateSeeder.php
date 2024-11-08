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
                'origin_name' => 'Справка для соц. стипендии',
                'name' => 'iahiucf12iu1fi2.docx',
                'path' => 'templates/iahiucf12iu1fi2.docx'
            ],
            [
                'origin_name' => 'Справка для представления по месту требования',
                'name' => 'dqwd12e1dq12.docx',
                'path' => 'templates/dqwd12e1dq12.docx'
            ],
        ];

        DB::table('document_templates')->insert($data);
    }
}
