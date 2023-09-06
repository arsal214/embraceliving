<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
        'first_name'=> 'Super',
        'last_name' => 'admin',
        'email'     => 'superadmin@gmail.com',
        'username'     => 'superadmin',
        'password'  => bcrypt('password'),
        'type'      => 'admin'
    ]);

    $role = Role::create(['name' => 'Super admin', 'guard_name' => 'web']);
    $permissions = Permission::where('guard_name','web')->get();
    $role->syncPermissions($permissions);
    $user->assignRole($role);
    }
}
