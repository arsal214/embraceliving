<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('regions')->insert([
            [
                'name' => 'MD1',
                'status' => 'Active',
            ],
            [
                'name' => 'MD2',
                'status' => 'Active',
            ],
        ]);
    }
}
