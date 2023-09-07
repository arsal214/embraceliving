<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Models\GroupHome;
use App\Models\Home;
use Illuminate\Http\Request;

/**
 * Class GroupController
 * @package App\Http\Controllers
 */
class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:groups-list|groups-create|groups-edit|groups-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:groups-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:groups-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:groups-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
        $groups = Group::paginate();
        return view('admin.group.index', compact('groups'))
            ->with('i', (request()->input('page', 1) - 1) * $groups->perPage());
        } catch (\Throwable $th) {
            return to_route('groups.index')->withErrors(['msg' => $th->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = new Group();
        $homes = Home::where('status', 'Active')->pluck('title', 'id');
        return view('admin.group.create', compact('group', 'homes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|unique:groups,name',
                'logo' => 'required|image|mimes:jpeg,png,gif,jpg',
                'favicon' => 'required|image|mimes:jpeg,png,gif,jpg',
                'status' => 'required',
                'homes' => 'required|array',
            ]);
            $group = Group::create($request->except('homes'));
            if($group)
            {
                foreach ($request->homes as $home)
                {
                   GroupHome::create([
                       'home_id' => $home,
                       'group_id' => $group->id,
                   ]);
                }
            }
            return redirect()->route('groups.index')
                ->with('success', 'Group created successfully.');
        } catch (\Throwable $th) {
            return to_route('groups.index')->withErrors(['msg' => $th->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::find($id);

        return view('admin.group.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::with('homes')->find($id);
        $homes = Home::where('status', 'Active')->pluck('title', 'id');
        return view('admin.group.edit', compact('group','homes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        try{
            $request->validate([
                'name' => 'required|unique:groups,name,' .$group->id,
                'status' => 'required',
                'homes' => 'required|array',
            ]);
            $group->update($request->except('homes'));
            if($group)
            {
                GroupHome::where('group_id',$group->id)->delete();
                foreach ($request->homes as $home)
                {
                    GroupHome::create(
                        [
                            'home_id' => $home,
                            'group_id' => $group->id,]
                    );
                }
            }

        return redirect()->route('groups.index')
            ->with('success', 'Group updated successfully');
        } catch (\Throwable $th) {
            return redirect()->route('groups.edit', ['group' => $group->id])->withErrors(['msg' => $th->getMessage()]);        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $group = Group::find($id)->delete();
        return redirect()->route('groups.index')
            ->with('success', 'Group deleted successfully');
    }
}
