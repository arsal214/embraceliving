<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\FriendlyQuiz;
use Illuminate\Http\Request;

class FriendlyQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexFrq_23rd()
    {
        //
        return view('admin/FriendlyQuiz/FRQ-23rdJan');
    }
    public function indexFrq_30th()
    {
        //
        return view('admin/FriendlyQuiz/FRQ-30thJan');
    }
    public function indexFrq_()
    {
        //
        return view('admin/FriendlyQuiz/FRQ-');
    }
    public function indexFrq_9th()
    {
        //
        return view('admin/FriendlyQuiz/FRQ-9thJan');
    }
    public function indexFrq_16th()
    {
        //
        return view('admin/FriendlyQuiz/FRQ-16thJan');
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
     * @param  \App\Models\FriendlyQuiz  $friendlyQuiz
     * @return \Illuminate\Http\Response
     */
    public function show(FriendlyQuiz $friendlyQuiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FriendlyQuiz  $friendlyQuiz
     * @return \Illuminate\Http\Response
     */
    public function edit(FriendlyQuiz $friendlyQuiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FriendlyQuiz  $friendlyQuiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FriendlyQuiz $friendlyQuiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FriendlyQuiz  $friendlyQuiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(FriendlyQuiz $friendlyQuiz)
    {
        //
    }
}
