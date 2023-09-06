<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Models\Region;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:homes-list|homes-create|homes-edit|homes-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:homes-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:homes-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:homes-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->type == "GroupAdmin") {
            $user = auth()->user();
            $group = $user->group;
            $groupHomes = $group->homes; // Homes assigned to the group
            $userHomes = Home::where('group_id', $group->id)->get(); // Homes created by the user
            $homes = $groupHomes->merge($userHomes);
        } else {
            $homes = Home::all();
        }


        return view('admin.home.index', compact('homes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $home = new Home();
        $regions = Region::where('status', 'Active')->pluck('name', 'id');
        return view('admin.home.create', compact('home', 'regions'));
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
                'region_id' => 'required',
                'title' => 'required|unique:homes,title',
                'code' => 'required',
                'identifier' => 'required',
                'status' => 'required',
            ]);
            if (auth()->user()->type = 'GroupAdmin') {
                $request['group_id'] = auth()->user()->group->id;
            }
            Home::create($request->all());
            return redirect()->route('homes.index')
                ->with('success', 'Home created successfully.');
        } catch (\Throwable $th) {
            return back()->withErrors(['msg' => $th->getMessage()]);
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
        $home = Home::find($id);
        return view('admin.home.show', compact('home'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $home = Home::find($id);
        $regions = Region::where('status', 'Active')->pluck('name', 'id');
        return view('admin.home.edit', compact('home', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Home $home
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Home $home)
    {
        try {
            $request->validate([
                'region_id' => 'required',
                'title' => 'required|unique:homes,title,' . $home->id,
                'code' => 'required',
                'identifier' => 'required',
                'status' => 'required',
            ]);

            if (auth()->user()->type = 'GroupAdmin') {
                $request['group_id'] = auth()->user()->group->id;
            }
            $home->update($request->all());
            return redirect()->route('homes.index')
                ->with('success', 'Home updated successfully');
        } catch (\Throwable $th) {
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
        $home = Home::find($id)->delete();
        return redirect()->route('homes.index')
            ->with('success', 'Home deleted successfully');
    }
}
