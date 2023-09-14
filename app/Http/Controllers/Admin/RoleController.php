<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles-list|roles-create|roles-edit|roles-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:roles-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::paginate();

        return view('admin.role.index', compact('roles'))
            ->with('i', (request()->input('page', 1) - 1) * $roles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = new Role();
        return view('admin.role.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $this->validate($request, [
                'role'       => 'required|unique:roles,name',
                'permissions'=> 'required'
            ]);
            $role = Role::create(['name' => $request->input('role'),
                'guard_name' => 'web'
            ]);
            $role->syncPermissions($request->input('permissions'));

            return redirect()->route('roles.index')
                ->with('success', 'Role created successfully.');
        }catch (\Throwable $th) {
            return back()->withErrors(['msg' => $th->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);

        return view('admin.role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $role = Role::find($id);
        return view('admin.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        try {
            $this->validate($request, [
                'role'        => 'required|unique:roles,name' .$role->id,
                'permissions' => 'required'
            ]);

            $role->name = $request->input('role');
            $role->guard_name = 'web';
            $role->save();
            $role->syncPermissions($request->input('permissions'));;

            return redirect()->route('roles.index')
                ->with('success', 'Role updated successfully');
        }catch (\Throwable $th) {
            return back()->withErrors(['msg' => $th->getMessage()]);
        }


    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {

        $roles =  DB::table('model_has_roles')->where('role_id', $id)->get();

        if(count($roles) > 0 )
        {
            return redirect()->route('roles.shop.index')
                ->withErrors(['msg' =>'Role Cannot be deleted Because role already attach to user']);
        }else
        {
            Role::find($id)->delete();
            return redirect()->route('roles.shop.index')
                ->with('success', 'Role deleted successfully');
        }
    }
}
