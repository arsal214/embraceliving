<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('groups')->insert([
            [
              'name' => 'Four Season',
              'headline' => 'Four Season Group',
              'description' => 'Four Season Group',
            ],
            [
              'name' => 'BrighterKind',
              'headline' => 'BrighterKind Group',
              'description' => 'BrighterKind Group',
            ],
        ]);
    }
}
