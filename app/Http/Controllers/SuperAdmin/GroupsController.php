<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexGroups()
    {
        //
      return view('admin/Group/ManageGroups');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createGroups()
    {
        //
    return view('admin/Group/AddGroup');

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
     * @param  \App\Models\Group  $groups
     * @return \Illuminate\Http\Response
     */
    public function show(Group $groups)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $groups
     * @return \Illuminate\Http\Response
     */
    public function editGroups(Group $groups)
    {
        //
    return view('admin/Group/EditGroup');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $groups
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $groups)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $groups
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $groups)
    {
        //
    }
}
