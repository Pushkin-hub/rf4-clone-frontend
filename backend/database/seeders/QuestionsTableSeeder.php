<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('questions')->insert([
            [
                'question_text' => 'Чему равно число n, с точностью до 2 знаков после запятой?',
                'category_id' => 1
            ],
             [
                'question_text' => 'В каком году началась вторая мировая война?',
                'category_id' => 2
            ],
             [
                'question_text' => 'Какая река является самой длинной в мире?',
                'category_id' => 3
            ],
             [
                'question_text' => 'Сколько хромосомов у здорового человека?',
                'category_id' => 4
            ],
             [
                'question_text' => 'Кто написал роман "Преступление и наказание"?',
                'category_id' => 5
            ],
            [
                'question_text' => 'Какова скорость света в вакууме?',
                'category_id' => 6
            ],
             [
                'question_text' => 'Какой химический элемент обозначается символом Au?',
                'category_id' => 7
            ], 
            [
                'question_text' => 'Что означает дискриптор <a> в HTML?',
                'category_id' => 8
            ],
            [
                'question_text' => 'Чему равен корень из 144?',
                'category_id' => 1
            ],
            [
                'question_text' => 'Какая страна имеет наибольшую площадь территории?',
                'category_id' => 3
            ]
        ]);
    }
}
