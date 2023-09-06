<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\LiveStreaming;
use Illuminate\Http\Request;

class LiveStreamingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexChannels()
    {
        //
     return view('admin/LiveStreaming/Channels');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createChannels()
    {
        //
     return view('admin/LiveStreaming/CreateChannels');
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
     * @param  \App\Models\LiveStreaming  $liveStreaming
     * @return \Illuminate\Http\Response
     */
    public function show(LiveStreaming $liveStreaming)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LiveStreaming  $liveStreaming
     * @return \Illuminate\Http\Response
     */
    public function editChannels(LiveStreaming $liveStreaming)
    {
        //
     return view('admin/LiveStreaming/EditChannels');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LiveStreaming  $liveStreaming
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LiveStreaming $liveStreaming)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LiveStreaming  $liveStreaming
     * @return \Illuminate\Http\Response
     */
    public function destroy(LiveStreaming $liveStreaming)
    {
        //
    }
}
