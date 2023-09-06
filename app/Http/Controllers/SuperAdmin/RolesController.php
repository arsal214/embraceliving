<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexUsers()
    {
        //
     return view('admin/Roles/users/users');

    }
    public function indexUserpermissions()
    {
        //
     return view('admin/Roles/users/PermissionsUsers');
    }
    public function indexRoles()
    {
        //
      return view('admin/Roles/Roles/Roles');

    }
    public function indexRolespermissions()
    {
        //
      return view('admin/Roles/Roles/PermissionsRoles');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createUsers()
    {
        //
     return view('admin/Roles/users/CreateUsers');

    }
    public function createRoles()
    {
        //
     return view('admin/Roles/Roles/CreateRoles');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show(Roles $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function editUsers(Roles $roles)
    {
        //
      return view('admin/Roles/users/EditUsers');

    }
    public function editRoles(Roles $roles)
    {
        //
      return view('admin/Roles/Roles/EditRoles');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Roles $roles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Roles $roles)
    {
        //
    }
}
