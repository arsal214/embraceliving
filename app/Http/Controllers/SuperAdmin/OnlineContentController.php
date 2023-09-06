<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\OnlineContent;
use Illuminate\Http\Request;

class OnlineContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOc_23rd()
    {
        //
    return view('admin/OnlineContent/OC-23rdJan');

    }
    public function indexOc_()
    {
        //
    return view('admin/OnlineContent/OC-');

    }
    public function indexOc_2nd()
    {
        //
    return view('admin/OnlineContent/OC-2ndJan');

    }
    public function indexOc_9th()
    {
        //
    return view('admin/OnlineContent/OC-9thJan');

    }
    public function indexOc_16th()
    {
        //
    return view('admin/OnlineContent/OC-16thJan');

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
     * @param  \App\Models\OnlineContent  $onlineContent
     * @return \Illuminate\Http\Response
     */
    public function show(OnlineContent $onlineContent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OnlineContent  $onlineContent
     * @return \Illuminate\Http\Response
     */
    public function edit(OnlineContent $onlineContent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OnlineContent  $onlineContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OnlineContent $onlineContent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OnlineContent  $onlineContent
     * @return \Illuminate\Http\Response
     */
    public function destroy(OnlineContent $onlineContent)
    {
        //
    }
}
