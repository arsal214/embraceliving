<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permissions-list|permissions-create|permissions-edit|permissions-delete', ['only' => ['index']]);
        $this->middleware('permission:permissions-edit', ['only' => ['update']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::where('guard_name','web')->get();
        return view('admin.permissions.index',compact('permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
