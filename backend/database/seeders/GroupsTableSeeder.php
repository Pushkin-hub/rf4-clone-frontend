<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('groups')->insert([
            [
                'group_name' => '10-А',
                'description' => '10 класс, группа А'
            ],
            [
                'group_name' => '10-Б',
                'description' => '10 класс, группа Б'
            ],
            [
                'group_name' => '11-А',
                'description' => '11 класс, группа А'
            ],
            [
                'group_name' => '1-Б',
                'description' => '11 класс, группа Б'
            ],
        ]);
    }
}
