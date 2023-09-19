<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            /* ------------------------------ Dashboard ----------------------------- */
            [
                'name' => 'dashboard-view',
                'display_name' => 'Dashboard View',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            /* ------------------------------ Role Permissions ----------------------------- */
            [
                'name' => 'roles-list',
                'display_name' => 'User List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'roles-create',
                'display_name' => 'User Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'roles-edit',
                'display_name' => 'User Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'roles-delete',
                'display_name' => 'User Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            /* ------------------------------  Permissions ----------------------------- */
            [
                'name' => 'permissions-list',
                'display_name' => 'User List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'permissions-edit',
                'display_name' => 'User Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            /* ------------------------------ Homes Permissions ----------------------------- */
            [
                'name' => 'homes-list',
                'display_name' => 'Homes List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'homes-create',
                'display_name' => 'Home Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'homes-edit',
                'display_name' => 'Homes Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'homes-delete',
                'display_name' => 'Homes Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            /* ------------------------------ Group Permissions ----------------------------- */
            [
                'name' => 'groups-list',
                'display_name' => 'Group List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'groups-create',
                'display_name' => 'Group Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'groups-edit',
                'display_name' => 'Group Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'groups-delete',
                'display_name' => 'Group Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            /* ------------------------------ User Permissions ----------------------------- */
            [
                'name' => 'users-list',
                'display_name' => 'User List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'users-create',
                'display_name' => 'User Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'users-edit',
                'display_name' => 'User Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'users-delete',
                'display_name' => 'User Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            /* ------------------------------ Regions Permissions ----------------------------- */
            [
                'name' => 'regions-list',
                'display_name' => 'regions List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'regions-create',
                'display_name' => 'regions Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'regions-edit',
                'display_name' => 'regions Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'regions-delete',
                'display_name' => 'regions Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            /* ------------------------------ Themes Permissions ----------------------------- */
            [
                'name' => 'themes-list',
                'display_name' => 'themes List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'themes-create',
                'display_name' => 'themes Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'themes-edit',
                'display_name' => 'themes Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'themes-delete',
                'display_name' => 'themes Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            /* ------------------------------ Page Permissions ----------------------------- */
            [
                'name' => 'page-list',
                'display_name' => 'page List',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'page-create',
                'display_name' => 'page Create',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'page-edit',
                'display_name' => 'page Edit',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'page-delete',
                'display_name' => 'themes Delete',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
