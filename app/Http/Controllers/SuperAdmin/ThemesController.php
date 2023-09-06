<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Themes;
use Illuminate\Http\Request;

class ThemesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexThemes()
    {
        //
     return view('admin/Themes/ManageThemes');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createThemes()
    {
        //
     return view('admin/Themes/CreateThemes');
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
     * @param  \App\Models\Themes  $themes
     * @return \Illuminate\Http\Response
     */
    public function show(Themes $themes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Themes  $themes
     * @return \Illuminate\Http\Response
     */
    public function editThemes(Themes $themes)
    {
        //
     return view('admin/Themes/EditThemes');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Themes  $themes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Themes $themes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Themes  $themes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Themes $themes)
    {
        //
    }
}
