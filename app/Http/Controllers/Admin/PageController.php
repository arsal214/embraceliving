<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Page;
use App\Models\Theme;
use Illuminate\Http\Request;

/**
 * Class PageController
 * @package App\Http\Controllers
 */
class PageController extends Controller
{
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
            $pages = Page::where(function ($query) use ($group) {
                $query->where('group_id', null)
                    ->orWhere('group_id', $group->id);
            })->get();
        } else {
            $pages = Page::with(['group','theme'])->get();
        }
        return view('admin.page.index', compact('pages'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = new Page();
        $groups = Group::where('status','Active')->pluck('name','id');
        if (auth()->user()->type == 'GroupAdmin')
        {
            $themes = Theme::where('status', 'Active')
                ->where(function($query) {
                    $query->where('group_id', auth()->user()->group?->id)
                        ->orWhereNull('group_id');
                })->pluck('name', 'id');
        }else{
            $themes = Theme::where('status','Active')->pluck('name','id');
        }
        return view('admin.page.create', compact('page','groups','themes'));
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
                'title' => 'required|unique:pages,title',
                'status' => 'required',
                'page_icon' => 'required|image|mimes:jpeg,png,gif,jpg'
            ]);
            $request['slug'] = strtolower(str_replace(' ', '-', $request->title));
            if (auth()->user()->type == 'GroupAdmin') {
                $request['group_id'] = auth()->user()->group->id;
            }
            $request['border_type'] = json_encode($request->border_type);
            $request['script'] = !empty($request->script) ? $request->script : null;
            $request['is_default'] = !empty($request->is_default) ? $request->default_page : "NO";
            $request['is_monitor'] = !empty($request->is_monitor) ? $request->is_monitor : "ON";
            $page = Page::create($request->all());
            return redirect()->route('pages.index')
                ->with('success', 'Page created successfully.');
        }
        catch (\Throwable $th) {
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
        $page = Page::find($id);

        return view('admin.page.show', compact('page'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::find($id);

        $groups = Group::where('status','Active')->pluck('name','id');
        if (auth()->user()->type == 'GroupAdmin')
        {
            $themes = Theme::where('status', 'Active')
                ->where(function($query) {
                    $query->where('group_id', auth()->user()->group?->id)
                        ->orWhereNull('group_id');
                })->pluck('name', 'id');
        }else{
            $themes = Theme::where('status','Active')->pluck('name','id');
        }
        return view('admin.page.edit', compact('page','groups','themes'));    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Page $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        try {

            $request->validate([
                'title' => 'required|unique:pages,title,' . $page->id,
                'status' => 'required',
            ]);
            $request['slug'] = strtolower(str_replace(' ', '-', $request->title));
            if (auth()->user()->type == 'GroupAdmin') {
                $request['group_id'] = auth()->user()->group?->id;
            }
            $request['border_type'] = json_encode($request->border_type);
            $request['script'] = !empty($request->script) ? $request->script : null;
            $request['is_default'] = !empty($request->is_default) ? $request->default_page : "NO";
            $request['is_monitor'] = !empty($request->is_monitor) ? $request->is_monitor : "ON";
            $page->update($request->all());
            return redirect()->route('pages.index')
                ->with('success', 'Page updated successfully');
        }
        catch (\Throwable $th) {
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
        $page = Page::find($id)->delete();
        return redirect()->route('pages.index')
            ->with('success', 'Page deleted successfully');
    }
}
