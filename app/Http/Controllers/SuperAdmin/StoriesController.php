<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Stories;
use Illuminate\Http\Request;

class StoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexStories()
    {
        //
    return view('admin/Stories/Stories');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\Stories  $stories
     * @return \Illuminate\Http\Response
     */
    public function show(Stories $stories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stories  $stories
     * @return \Illuminate\Http\Response
     */
    public function editStories(Stories $stories)
    {
        //
     return view('admin/Stories/Edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stories  $stories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stories $stories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stories  $stories
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stories $stories)
    {
        //
    }
}
