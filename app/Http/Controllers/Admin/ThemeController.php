<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupTheme;
use App\Models\Theme;
use Illuminate\Http\Request;

/**
 * Class ThemeController
 * @package App\Http\Controllers
 */
class ThemeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:themes-list|themes-create|themes-edit|themes-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:themes-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:themes-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:themes-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (auth()->user()->type == 'GroupAdmin') {
                $group = Group::find(auth()->user()->group?->id);
                $themes= $group->themes;
//                dd($themes);
//                $themes = Theme::with('groups')->where('group_id', auth()->user()->group?->id)->orWhereNull('group_id')->get();
            }else{
                $themes = Theme::with('groups')->get();
            }
//            dd($themes);
            return view('admin.theme.index', compact('themes'));
        } catch (\Throwable $th) {
            return to_route('themes.index')->withErrors(['msg' => $th->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $theme = new Theme();
        return view('admin.theme.create', compact('theme'));
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
                'name' => 'required|unique:themes,name',
                'status' => 'required',
            ]);
            if (auth()->user()->type == 'GroupAdmin') {
                $group = auth()->user()->group;
                if ($group) {
                    $existingTheme = $group->themes()->where('themes.status', 'Active')->first();
                    if ($existingTheme) {
                        GroupTheme::where('group_id', $group->id)->update(['status' => 'InActive']);
                    }
                    // Assign the new theme to the group
                    $themeData = $request->all();
                    $themeData['group_id'] = $group->id;
                    $theme = Theme::create($themeData);
                    $createdBy = auth()->user()->type == 'GroupAdmin' ? 'GroupAdmin' : 'Admin';
                    $data = [
                        'group_id' => $group->id,
                        'theme_id' => $theme->id,
                        'created_by' => $createdBy,
                    ];
                    GroupTheme::create($data);
                }
            }else
            {
                $theme = Theme::create($request->all());
                $groups = Group::all();
                $createdBy = auth()->user()->type == 'GroupAdmin' ? 'GroupAdmin' : 'Admin';
                $groupData = $groups->mapWithKeys(function ($group) use ($createdBy) {
                    return [$group->id => ['created_by' => $createdBy]];
                });
                $theme->groups()->attach($groupData);
            }

            return redirect()->route('themes.index')
                ->with('success', 'Theme created successfully.');
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
        $theme = Theme::find($id);

        return view('admin.theme.show', compact('theme'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $theme = Theme::find($id);

        return view('admin.theme.edit', compact('theme'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Theme $theme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Theme $theme)
    {

        try {
            $request->validate([
                'name' => 'required|unique:themes,name,' . $theme->id,
                'status' => 'required',
            ]);
            if (auth()->user()->type == 'GroupAdmin') {
                $request['group_id'] = auth()->user()->group?->id;
            }
            $theme->update($request->all());
            if ($request->status == 'InActive') {
                $theme->groups()->detach();
            }
            return redirect()->route('themes.index')
                ->with('success', 'Theme updated successfully');
        } catch (\Throwable $th) {
            return redirect()->route('themes.edit', ['theme' => $theme->id])->withErrors(['msg' => $th->getMessage()]);
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $theme = Theme::find($id)->delete();

        return redirect()->route('themes.index')
            ->with('success', 'Theme deleted successfully');
    }
}
