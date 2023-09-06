<?php

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Configuration;
use Illuminate\Support\Facades\DB;

/**
 * Get listing of a resource.
 *
 * @return array
 */

function adminAllPermissions()
{
    $permissions = [];
    foreach (Permission::where('guard_name', 'web')->get() as $permission) {
        $title = explode('-', $permission->name);
        $permissions[$title[0]][] = array('id' => $permission->id, 'name' => $title[1]);
    }
    return $permissions;
}

function rolePermissions($id)
{
    return DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
        ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
        ->toArray();
}
