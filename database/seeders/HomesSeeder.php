<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('homes')->insert([
            [
                'region_id' => 1,
                'title' => 'Angusfield House',
                'code' => 'P01',
                'identifier' => 'P01',
            ],
            [
                'region_id' => 1,
                'title' => 'Ashbourne Court',
                'code' => 'P02',
                'identifier' => 'P02',
            ],
        ]);

    }
}
