<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['category_name' => 'Математика'],
            ['category_name' => 'История'],
            ['category_name' => 'География'],
            ['category_name' => 'Биология'],
            ['category_name' => 'Литература'],
            ['category_name' => 'Физика'],
            ['category_name' => 'Химия'],
            ['category_name' => 'Информатика'],
        ]);
    }
}
