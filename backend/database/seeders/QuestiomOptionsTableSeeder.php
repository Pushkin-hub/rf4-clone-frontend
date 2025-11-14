<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestiomOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('question_options')->insert([
            //Вопрос 1
            [
                'question_id' => 1,
                'option_text' => '3.14',
                'is_correct' => true
            ],
            [
                'question_id' => 1,
                'option_text' => '3.16',
                'is_correct' => false
            ],
            [
                'question_id' => 1,
                'option_text' => '3.12',
                'is_correct' => false
            ],
            [
                'question_id' => 1,
                'option_text' => '3.18',
                'is_correct' => false
            ],
            //Вопрос 2
            [
                'question_id' => 2,
                'option_text' => '1937',
                'is_correct' => false
            ],
            [
                'question_id' => 2,
                'option_text' => '1939',
                'is_correct' => true
            ],
            [
                'question_id' => 2,
                'option_text' => '1941',
                'is_correct' => false
            ],
            [
                'question_id' => 2,
                'option_text' => '1945',
                'is_correct' => false
            ],
            //Вопрос 3
            [
                'question_id' => 3,
                'option_text' => 'Амазонка',
                'is_correct' => true
            ],
            [
                'question_id' => 3,
                'option_text' => 'Нил',
                'is_correct' => false
            ],
            [
                'question_id' => 3,
                'option_text' => 'Янцзы',
                'is_correct' => false
            ],
            [
                'question_id' => 3,
                'option_text' => 'Миссисипи',
                'is_correct' => false
            ],
            //Вопрос 4
            [
                'question_id' => 4,
                'option_text' => '23',
                'is_correct' => false
            ],
            [
                'question_id' => 4,
                'option_text' => '46',
                'is_correct' => true
            ],
            [
                'question_id' => 4,
                'option_text' => '48',
                'is_correct' => false
            ],
            [
                'question_id' => 4,
                'option_text' => '52',
                'is_correct' => false
            ],
            //Вопрос 5
            [
                'question_id' => 5,
                'option_text' => 'Лев Толстой',
                'is_correct' => false
            ],
            [
                'question_id' => 5,
                'option_text' => 'Федор Достоевский',
                'is_correct' => true
            ],
            [
                'question_id' => 5,
                'option_text' => 'Антон Чехов',
                'is_correct' => false
            ],
            [
                'question_id' => 5,
                'option_text' => 'Иван Тургенев',
                'is_correct' => false
            ],
            //Вопрос 6
            [
                'question_id' => 6,
                'option_text' => '300 00 км/с',
                'is_correct' => true
            ],
            [
                'question_id' => 6,
                'option_text' => '150 00 км/с',
                'is_correct' => false
            ],
            [
                'question_id' => 6,
                'option_text' => '450 00 км/с',
                'is_correct' => false
            ],
            [
                'question_id' => 6,
                'option_text' => '600 00 км/с',
                'is_correct' => false
            ],
            //Вопрос 7
            [
                'question_id' => 7,
                'option_text' => 'Серебро',
                'is_correct' => false
            ],
            [
                'question_id' => 7,
                'option_text' => 'Железо',
                'is_correct' => false
            ],
            [
                'question_id' => 7,
                'option_text' => 'Алюминий',
                'is_correct' => false
            ],
            [
                'question_id' => 7,
                'option_text' => 'Золото',
                'is_correct' => true
            ],
            //Вопрос 8
            [
                'question_id' => 8,
                'option_text' => 'Гипперссылка',
                'is_correct' => true
            ],
            [
                'question_id' => 8,
                'option_text' => 'Таблица',
                'is_correct' => false
            ],
            [
                'question_id' => 8,
                'option_text' => 'Список',
                'is_correct' => false
            ],
            [
                'question_id' => 8,
                'option_text' => 'Цитата',
                'is_correct' => false
            ],
            //Вопрос 9
            [
                'question_id' => 9,
                'option_text' => '11',
                'is_correct' => false
            ],
            [
                'question_id' => 9,
                'option_text' => '12',
                'is_correct' => true
            ],
            [
                'question_id' => 9,
                'option_text' => '13',
                'is_correct' => false
            ],
            [
                'question_id' => 9,
                'option_text' => '14',
                'is_correct' => false
            ],
            //Вопрос 10
            [
                'question_id' => 10,
                'option_text' => 'Канада',
                'is_correct' => false
            ],
            [
                'question_id' => 10,
                'option_text' => 'США',
                'is_correct' => false
            ],
            [
                'question_id' => 10,
                'option_text' => 'Россия',
                'is_correct' => true
            ],
            [
                'question_id' => 10,
                'option_text' => 'Китай',
                'is_correct' => false
            ],
        ]);
    }
}
