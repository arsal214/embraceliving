<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\PortalStories;
use Illuminate\Http\Request;

class PortalStoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexStories()
    {
        //
    return view('admin/PortalStories/Stories/Stories');

    }
    public function indexStorypages()
    {
        //
    return view('admin/PortalStories/StoryPages/StoryPages');

    }
    public function indexBackgrounds()
    {
    return view('admin/PortalStories/Backgrounds/Backgrounds');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStorypage()
    {
        //
     return view('admin/PortalStories/StoryPages/AddStoryPage');

    }
    public function createBackground()
    {
        //
     return view('admin/PortalStories/Backgrounds/CreateBackground');

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
     * @param  \App\Models\PortalStories  $portalStories
     * @return \Illuminate\Http\Response
     */
    public function show(PortalStories $portalStories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PortalStories  $portalStories
     * @return \Illuminate\Http\Response
     */
    public function editStories(PortalStories $portalStories)
    {
        //
    return view('admin/PortalStories/Stories/EditStories');

    }
    public function editStorypage(PortalStories $portalStories)
    {
        //
        return view('admin/PortalStories/StoryPages/EditStoryPage');

    }
    public function editBackground(PortalStories $portalStories)
    {
        //
        return view('admin/PortalStories/Backgrounds/EditBackground');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PortalStories  $portalStories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PortalStories $portalStories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PortalStories  $portalStories
     * @return \Illuminate\Http\Response
     */
    public function destroy(PortalStories $portalStories)
    {
        //
    }
}
