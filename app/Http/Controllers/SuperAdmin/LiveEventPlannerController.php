<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\LiveEventPlanner;
use Illuminate\Http\Request;

class LiveEventPlannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexLep_23rd()
    {
        //
        return view('admin/LiveEventPlanner/LEP-23rdJan');

    }
    public function indexLep_26th()
    {
        //
        return view('admin/LiveEventPlanner/LEP-26thDec');

    }
    public function indexLep_2nd()
    {
        //
        return view('admin/LiveEventPlanner/LEP-2ndJan');

    }
    public function indexLep_9th()
    {
        //
        return view('admin/LiveEventPlanner/LEP-9thJan');

    }
    public function indexLep_16th()
    {
        //
        return view('admin/LiveEventPlanner/LEP-16thJan');

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
     * @param  \App\Models\LiveEventPlanner  $liveEventPlanner
     * @return \Illuminate\Http\Response
     */
    public function show(LiveEventPlanner $liveEventPlanner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LiveEventPlanner  $liveEventPlanner
     * @return \Illuminate\Http\Response
     */
    public function edit(LiveEventPlanner $liveEventPlanner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LiveEventPlanner  $liveEventPlanner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LiveEventPlanner $liveEventPlanner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LiveEventPlanner  $liveEventPlanner
     * @return \Illuminate\Http\Response
     */
    public function destroy(LiveEventPlanner $liveEventPlanner)
    {
        //
    }
}
