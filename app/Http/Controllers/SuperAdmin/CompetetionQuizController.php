<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\CompetetionQuiz;
use Illuminate\Http\Request;

class CompetetionQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCq_23rd()
    {
        //
        return view('admin/CompetetionQuiz/CQ-23rdJan');
    }
    public function indexCq_30th()
    {
        //
        return view('admin/CompetetionQuiz/CQ-30thJan');
    }
    public function indexCq_()
    {
        //
        return view('admin/CompetetionQuiz/CQ-');
    }
    public function indexCq_9th()
    {
        //
        return view('admin/CompetetionQuiz/CQ-9thJan');
    }
    public function indexCq_16th()
    {
        //
        return view('admin/CompetetionQuiz/CQ-16thJan');
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
     * @param  \App\Models\CompetetionQuiz  $competetionQuiz
     * @return \Illuminate\Http\Response
     */
    public function show(CompetetionQuiz $competetionQuiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompetetionQuiz  $competetionQuiz
     * @return \Illuminate\Http\Response
     */
    public function edit(CompetetionQuiz $competetionQuiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompetetionQuiz  $competetionQuiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompetetionQuiz $competetionQuiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompetetionQuiz  $competetionQuiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompetetionQuiz $competetionQuiz)
    {
        //
    }
}
