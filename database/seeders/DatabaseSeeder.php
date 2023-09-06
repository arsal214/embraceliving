<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(RegionSeeder::class);
        $this->call(HomesSeeder::class);
        $this->call(GroupsSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(SuperAdminSeeder::class);
    }
}
