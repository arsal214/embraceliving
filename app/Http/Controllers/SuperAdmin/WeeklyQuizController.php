<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\WeeklyQuiz;
use Illuminate\Http\Request;

class WeeklyQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWq_23rd()
    {
        //
        return view('admin/WeeklyQuiz/WQ-23rdJan');
    }
    public function indexWq_30th()
    {
        //
        return view('admin/WeeklyQuiz/WQ-30thJan');
    }
    public function indexWq_()
    {
        //
        return view('admin/WeeklyQuiz/WQ-');
    }
    public function indexWq_9th()
    {
        //
        return view('admin/WeeklyQuiz/WQ-9thJan');
    }
    public function indexWq_16th()
    {
        //
        return view('admin/WeeklyQuiz/WQ-16thJan');
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
     * @param  \App\Models\WeeklyQuiz  $weeklyQuiz
     * @return \Illuminate\Http\Response
     */
    public function show(WeeklyQuiz $weeklyQuiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WeeklyQuiz  $weeklyQuiz
     * @return \Illuminate\Http\Response
     */
    public function edit(WeeklyQuiz $weeklyQuiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WeeklyQuiz  $weeklyQuiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WeeklyQuiz $weeklyQuiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WeeklyQuiz  $weeklyQuiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(WeeklyQuiz $weeklyQuiz)
    {
        //
    }
}
