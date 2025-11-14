<?php

namespace Database\Seeders;

use App\Models\QuestionOption;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
        CategoriesTableSeeder::class,
        QuestionsTableSeeder::class,
        QuestionOptionsTableSeeder::class,
        GroupsTableSeeder::class
       ]);
    }
}
