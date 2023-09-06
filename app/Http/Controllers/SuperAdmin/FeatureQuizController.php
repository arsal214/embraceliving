<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\FeatureQuiz;
use Illuminate\Http\Request;

class FeatureQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexFq_23rd()
    {
        //
        return view('admin/FeatureQuiz/FQ-23rdJan');
    }
    public function indexFq_30th()
    {
        //
        return view('admin/FeatureQuiz/FQ-30thJan');
    }
    public function indexFq_()
    {
        //
        return view('admin/FeatureQuiz/FQ-');
    }
    public function indexFq_9th()
    {
        //
        return view('admin/FeatureQuiz/FQ-9thJan');
    }
    public function indexFq_16th()
    {
        //
        return view('admin/FeatureQuiz/FQ-16thJan');
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
     * @param  \App\Models\FeatureQuiz  $featureQuiz
     * @return \Illuminate\Http\Response
     */
    public function show(FeatureQuiz $featureQuiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeatureQuiz  $featureQuiz
     * @return \Illuminate\Http\Response
     */
    public function edit(FeatureQuiz $featureQuiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeatureQuiz  $featureQuiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeatureQuiz $featureQuiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeatureQuiz  $featureQuiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeatureQuiz $featureQuiz)
    {
        //
    }
}
