<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

/**
 * Class RegionController
 * @package App\Http\Controllers
 */
class RegionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:regions-list|regions-create|regions-edit|regions-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:regions-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:regions-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:regions-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->type == "GroupAdmin")
        {
            $regions = Region::where('group_id',auth()->user()->group->id)->paginate();
        } else
        {
            $regions = Region::paginate();
        }
        return view('admin.region.index', compact('regions'))
            ->with('i', (request()->input('page', 1) - 1) * $regions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $region = new Region();
        return view('admin.region.create', compact('region'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'unique:regions,name',
                'status' => 'required',
            ]);
            if(auth()->user()->type =='GroupAdmin')
            {
                $request['group_id'] = auth()->user()->group->id;
            }
            $region = Region::create($request->all());
            return redirect()->route('regions.index')
                ->with('success', 'Region created successfully.');

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
        $region = Region::find($id);

        return view('admin.region.show', compact('region'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $region = Region::find($id);

        return view('admin.region.edit', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Region $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        try {
        $request->validate([
            'name' => 'required|unique:regions,name,' . $region->id,
            'status' => 'required',
        ]);
        if(auth()->user()->type == 'GroupAdmin')
        {
            $request['group_id'] = auth()->user()->group->id;
        }
        $region->update($request->all());
        return redirect()->route('regions.index')
            ->with('success', 'Region updated successfully');
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
        $region = Region::find($id)->delete();
        return redirect()->route('regions.index')
            ->with('success', 'Region deleted successfully');
    }
}
