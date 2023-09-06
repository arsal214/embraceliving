<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Mbs;
use Illuminate\Http\Request;

class MbsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexMbs()
    {
        //
     return view('admin/Mbs/ManageMbs&Faqs');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createMbs()
    {
        //
     return view('admin/Mbs/CreateMbs');
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
     * @param  \App\Models\Mbs  $mbs
     * @return \Illuminate\Http\Response
     */
    public function show(Mbs $mbs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mbs  $mbs
     * @return \Illuminate\Http\Response
     */
    public function editMbs(Mbs $mbs)
    {
        //
     return view('admin/Mbs/EditMbs');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mbs  $mbs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mbs $mbs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mbs  $mbs
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mbs $mbs)
    {
        //
    }
}
