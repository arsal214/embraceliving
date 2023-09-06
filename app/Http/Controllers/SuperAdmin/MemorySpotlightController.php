<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\MemorySpotlight;
use Illuminate\Http\Request;

class MemorySpotlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCategories()
    {
        //
    return view('admin/MemorySpotlight/Categories');

    }
    public function indexArticles()
    {
        //
    return view('admin/MemorySpotlight/Articles');

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
     * @param  \App\Models\MemorySpotlight  $memorySpotlight
     * @return \Illuminate\Http\Response
     */
    public function show(MemorySpotlight $memorySpotlight)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MemorySpotlight  $memorySpotlight
     * @return \Illuminate\Http\Response
     */
    public function edit(MemorySpotlight $memorySpotlight)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MemorySpotlight  $memorySpotlight
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MemorySpotlight $memorySpotlight)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MemorySpotlight  $memorySpotlight
     * @return \Illuminate\Http\Response
     */
    public function destroy(MemorySpotlight $memorySpotlight)
    {
        //
    }
}
