<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Whatson;
use Illuminate\Http\Request;

class WhatsonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWhatson()
    {
        //
     return view('admin/Whatson/ManageWhatson');
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
     * @param  \App\Models\Whatson  $whatson
     * @return \Illuminate\Http\Response
     */
    public function show(Whatson $whatson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Whatson  $whatson
     * @return \Illuminate\Http\Response
     */
    public function edit(Whatson $whatson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Whatson  $whatson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Whatson $whatson)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Whatson  $whatson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Whatson $whatson)
    {
        //
    }
}
