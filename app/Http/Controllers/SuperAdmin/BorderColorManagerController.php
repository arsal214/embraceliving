<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\BorderColorManager;
use Illuminate\Http\Request;

class BorderColorManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBorder()
    {
        //
     return view('admin/BorderColorManager/ManageBorders');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createBorder()
    {
        //
     return view('admin/BorderColorManager/CreateBorders');
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
     * @param  \App\Models\BorderColorManager  $borderColorManager
     * @return \Illuminate\Http\Response
     */
    public function show(BorderColorManager $borderColorManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BorderColorManager  $borderColorManager
     * @return \Illuminate\Http\Response
     */
    public function editBorder(BorderColorManager $borderColorManager)
    {
        //
     return view('admin/BorderColorManager/EditBorders');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BorderColorManager  $borderColorManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BorderColorManager $borderColorManager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BorderColorManager  $borderColorManager
     * @return \Illuminate\Http\Response
     */
    public function destroy(BorderColorManager $borderColorManager)
    {
        //
    }
}
