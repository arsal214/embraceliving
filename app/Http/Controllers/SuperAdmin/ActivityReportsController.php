<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\ActivityReports;
use Illuminate\Http\Request;

class ActivityReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCategories()
    {
        //
     return view('admin/ActivityReports/Categories/Categories');
    }
    public function indexActivities()
    {
        //
     return view('admin/ActivityReports/Activities/Activities');
    }
    public function indexGeneratereport()
    {
        //
     return view('admin/ActivityReports/GenerateReport/GenerateReport');
    }
    public function indexRecordactivity()
    {
        //
     return view('admin/ActivityReports/RecordActivity/RecordActivity');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCategories()
    {
        //
     return view('admin/ActivityReports/Categories/CreateCategories');
    }
    public function createActivities()
    {
        //
     return view('admin/ActivityReports/Activities/CreateActivities');
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
     * @param  \App\Models\ActivityReports  $activityReports
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityReports $activityReports)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActivityReports  $activityReports
     * @return \Illuminate\Http\Response
     */
    public function editCategories(ActivityReports $activityReports)
    {
        //
     return view('admin/ActivityReports/Categories/EditCategories');
    }
    public function editActivities(ActivityReports $activityReports)
    {
        //
     return view('admin/ActivityReports/Activities/EditActivities');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ActivityReports  $activityReports
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActivityReports $activityReports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActivityReports  $activityReports
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivityReports $activityReports)
    {
        //
    }
}
