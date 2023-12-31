<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Components;
use Illuminate\Http\Request;

class ComponentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexComponents()
    {
        //
     return view('admin/Components/Components');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createComponents()
    {
        //
     return view('admin/Components/CreateComponents');
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
     * @param  \App\Models\Components  $components
     * @return \Illuminate\Http\Response
     */
    public function show(Components $components)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Components  $components
     * @return \Illuminate\Http\Response
     */
    public function editComponents(Components $components)
    {
        //
     return view('admin/Components/EditComponents');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Components  $components
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Components $components)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Components  $components
     * @return \Illuminate\Http\Response
     */
    public function destroy(Components $components)
    {
        //
    }
}
